<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class TaskService
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Create a new task
     */
    public function create(array $data, User $creator): Task
    {
        // Validate business rules
        $this->validateTaskData($data);

        // Validate project access
        $project = Project::findOrFail($data['project_id']);
        if (! $this->canUserManageProjectTasks($creator, $project)) {
            throw new \UnauthorizedHttpException('You are not authorized to create tasks for this project.');
        }

        // Validate assignee
        if (! empty($data['assigned_to'])) {
            $assignee = User::find($data['assigned_to']);
            if (! $assignee || ! $assignee->isMember()) {
                throw ValidationException::withMessages([
                    'assigned_to' => ['The selected assignee is invalid.'],
                ]);
            }
        }

        // Validate due date against project dates
        if (! empty($data['due_date'])) {
            $this->validateTaskDueDate($data['due_date'], $project);
        }

        // Create task
        $task = Task::create([
            'project_id' => $data['project_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => Task::STATUS_PENDING,
            'due_date' => ! empty($data['due_date']) ? Carbon::parse($data['due_date']) : null,
            'assigned_to' => $data['assigned_to'] ?? null,
        ]);

        // Send notification to assignee
        if ($task->assigned_to) {
            $this->notificationService->notifyTaskAssigned($task);
        }

        // Log activity
        \Log::info('Task created', [
            'task_id' => $task->id,
            'project_id' => $task->project_id,
            'created_by' => $creator->id,
            'assigned_to' => $task->assigned_to,
        ]);

        return $task;
    }

    /**
     * Update task
     */
    public function update(Task $task, array $data, User $user): Task
    {
        // Check permissions
        if (! $this->canUserEditTask($user, $task)) {
            throw new \UnauthorizedHttpException('You are not authorized to edit this task.');
        }

        // Validate business rules for updates
        $this->validateTaskData($data, $task->id);

        // Special handling for status changes
        if (isset($data['status']) && $data['status'] !== $task->status) {
            $this->validateStatusTransition($task, $data['status'], $user);
        }

        // Validate due date if changed
        if (isset($data['due_date']) && $data['due_date'] !== $task->due_date) {
            $this->validateTaskDueDate($data['due_date'], $task->project);
        }

        // Track assignment changes
        $oldAssignee = $task->assigned_to;
        $newAssignee = $data['assigned_to'] ?? $task->assigned_to;

        // Update task
        $updateData = array_filter($data, function ($value) {
            return $value !== null;
        });

        if (isset($updateData['due_date'])) {
            $updateData['due_date'] = Carbon::parse($updateData['due_date']);
        }

        $task->update($updateData);

        // Handle assignment notifications
        if ($oldAssignee !== $newAssignee) {
            if ($newAssignee) {
                $this->notificationService->notifyTaskAssigned($task);
            }
            if ($oldAssignee) {
                $this->notificationService->notifyTaskUnassigned($task, $oldAssignee);
            }
        }

        // Handle status change notifications
        if (isset($data['status']) && $data['status'] !== $task->getOriginal('status')) {
            $this->notificationService->notifyTaskStatusChanged($task);
        }

        // Log activity
        \Log::info('Task updated', [
            'task_id' => $task->id,
            'updated_by' => $user->id,
            'changes' => $updateData,
        ]);

        return $task->fresh();
    }

    /**
     * Delete task
     */
    public function delete(Task $task, User $user): bool
    {
        // Check permissions
        if (! $this->canUserDeleteTask($user, $task)) {
            throw new \UnauthorizedHttpException('You are not authorized to delete this task.');
        }

        // Check if task can be deleted (no time entries)
        if ($task->timeEntries()->exists()) {
            throw ValidationException::withMessages([
                'task' => ['Task cannot be deleted because it has time entries.'],
            ]);
        }

        // Notify assignee if task is assigned
        if ($task->assigned_to) {
            $this->notificationService->notifyTaskDeleted($task);
        }

        // Log before deletion
        \Log::info('Task deleted', [
            'task_id' => $task->id,
            'task_title' => $task->title,
            'project_id' => $task->project_id,
            'deleted_by' => $user->id,
        ]);

        return $task->delete();
    }

    /**
     * Change task status
     */
    public function changeStatus(Task $task, string $newStatus, User $user): Task
    {
        // Validate transition
        $this->validateStatusTransition($task, $newStatus, $user);

        $oldStatus = $task->status;
        $task->update(['status' => $newStatus]);

        // Send notifications
        $this->notificationService->notifyTaskStatusChanged($task);

        // Log status change
        \Log::info('Task status changed', [
            'task_id' => $task->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $user->id,
        ]);

        return $task;
    }

    /**
     * Assign task to user
     */
    public function assignTask(Task $task, ?int $userId, User $assigner): Task
    {
        // Check permissions
        if (! $this->canUserManageProjectTasks($assigner, $task->project)) {
            throw new \UnauthorizedHttpException('You are not authorized to assign this task.');
        }

        // Validate assignee
        if ($userId) {
            $assignee = User::find($userId);
            if (! $assignee || ! $assignee->isMember()) {
                throw ValidationException::withMessages([
                    'assigned_to' => ['The selected assignee is invalid.'],
                ]);
            }
        }

        $oldAssignee = $task->assigned_to;
        $task->update(['assigned_to' => $userId]);

        // Handle notifications
        if ($userId && $userId !== $oldAssignee) {
            $this->notificationService->notifyTaskAssigned($task);
        }
        if ($oldAssignee && $oldAssignee !== $userId) {
            $this->notificationService->notifyTaskUnassigned($task, $oldAssignee);
        }

        return $task;
    }

    /**
     * Get tasks accessible by user
     */
    public function getAccessibleTasks(User $user, array $filters = []): Collection
    {
        $query = Task::accessibleBy($user);

        // Apply filters
        if (! empty($filters['project_id'])) {
            $query->where('project_id', $filters['project_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (! empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (! empty($filters['due_date'])) {
            $query->whereDate('due_date', $filters['due_date']);
        }

        if (! empty($filters['overdue'])) {
            $query->overdue();
        }

        return $query->with(['project', 'assignedUser'])
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get user's task dashboard data
     */
    public function getUserTaskDashboard(User $user): array
    {
        $tasks = Task::accessibleBy($user)->get();

        return [
            'total_tasks' => $tasks->count(),
            'todo_tasks' => $tasks->where('status', Task::STATUS_PENDING)->count(),
            'in_progress_tasks' => $tasks->where('status', Task::STATUS_IN_PROGRESS)->count(),
            'completed_tasks' => $tasks->where('status', Task::STATUS_DONE)->count(),
            'overdue_tasks' => $tasks->filter(fn ($task) => $task->isOverdue())->count(),
            'due_today' => $tasks->filter(fn ($task) => $task->due_date && $task->due_date->isToday() && ! $task->isDone()
            )->count(),
            'due_this_week' => $tasks->filter(fn ($task) => $task->due_date && $task->due_date->isBetween(now(), now()->endOfWeek()) && ! $task->isDone()
            )->count(),
            'recent_tasks' => $tasks->sortByDesc('created_at')->take(5),
        ];
    }

    /**
     * Get task statistics for project
     */
    public function getProjectTaskStats(Project $project): array
    {
        $tasks = $project->tasks;

        return [
            'total_tasks' => $tasks->count(),
            'completed_tasks' => $tasks->where('status', Task::STATUS_DONE)->count(),
            'in_progress_tasks' => $tasks->where('status', Task::STATUS_IN_PROGRESS)->count(),
            'todo_tasks' => $tasks->where('status', Task::STATUS_PENDING)->count(),
            'overdue_tasks' => $tasks->filter(fn ($task) => $task->isOverdue())->count(),
            'completion_percentage' => $project->completion_percentage,
            'average_completion_time' => $this->getAverageCompletionTime($tasks),
        ];
    }

    /**
     * Validate task data
     */
    private function validateTaskData(array $data, ?int $exceptId = null): void
    {
        // Check for duplicate titles within the same project
        if (isset($data['project_id'], $data['title'])) {
            $query = Task::where('project_id', $data['project_id'])
                ->where('title', $data['title']);

            if ($exceptId) {
                $query->where('id', '!=', $exceptId);
            }

            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'title' => ['A task with this title already exists in this project.'],
                ]);
            }
        }
    }

    /**
     * Validate task due date
     */
    private function validateTaskDueDate($dueDate, Project $project): void
    {
        $due = Carbon::parse($dueDate);

        if ($due->lt(Carbon::today())) {
            throw ValidationException::withMessages([
                'due_date' => ['Due date cannot be in the past.'],
            ]);
        }

        if ($due->gt($project->end_date)) {
            throw ValidationException::withMessages([
                'due_date' => ['Due date cannot be after project end date.'],
            ]);
        }
    }

    /**
     * Validate status transition
     */
    private function validateStatusTransition(Task $task, string $newStatus, User $user): void
    {
        if (! $task->canTransitionTo($newStatus)) {
            throw ValidationException::withMessages([
                'status' => ['Invalid status transition.'],
            ]);
        }

        // Business rule: only assigned user or manager can change status
        if ($user->isMember() && $task->assigned_to !== $user->id) {
            throw ValidationException::withMessages([
                'status' => ['You can only change the status of tasks assigned to you.'],
            ]);
        }

        // Business rule: task must be assigned to move to in_progress
        if ($newStatus === Task::STATUS_IN_PROGRESS && ! $task->assigned_to) {
            throw ValidationException::withMessages([
                'status' => ['Task must be assigned before it can be started.'],
            ]);
        }
    }

    /**
     * Check if user can edit task
     */
    private function canUserEditTask(User $user, Task $task): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isManager() && $task->project->manager_id === $user->id) {
            return true;
        }

        if ($user->isMember() && $task->assigned_to === $user->id) {
            return true; // Members can update their own task status
        }

        return false;
    }

    /**
     * Check if user can delete task
     */
    private function canUserDeleteTask(User $user, Task $task): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isManager() && $task->project->manager_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can manage project tasks
     */
    private function canUserManageProjectTasks(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isManager() && $project->manager_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Get average completion time for tasks
     */
    private function getAverageCompletionTime(Collection $tasks): ?float
    {
        $completedTasks = $tasks->where('status', Task::STATUS_DONE);

        if ($completedTasks->isEmpty()) {
            return null;
        }

        $totalDays = $completedTasks->sum(function ($task) {
            return $task->created_at->diffInDays($task->updated_at);
        });

        return round($totalDays / $completedTasks->count(), 2);
    }

    /**
     * Auto-assign tasks based on workload
     */
    public function autoAssignTask(Task $task, User $manager): Task
    {
        // Get available team members for the project
        $teamMembers = $task->project->teamMembers()->get();

        if ($teamMembers->isEmpty()) {
            return $task; // No members to assign to
        }

        // Find member with least assigned tasks
        $memberWithLeastTasks = $teamMembers->sortBy(function ($member) {
            return $member->assignedTasks()->whereIn('status', [
                Task::STATUS_PENDING,
                Task::STATUS_IN_PROGRESS,
            ])->count();
        })->first();

        return $this->assignTask($task, $memberWithLeastTasks->id, $manager);
    }

    /**
     * Bulk update task status
     */
    public function bulkUpdateStatus(array $taskIds, string $newStatus, User $user): int
    {
        $updatedCount = 0;

        foreach ($taskIds as $taskId) {
            try {
                $task = Task::findOrFail($taskId);
                $this->changeStatus($task, $newStatus, $user);
                $updatedCount++;
            } catch (\Exception $e) {
                \Log::warning('Failed to update task status', [
                    'task_id' => $taskId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $updatedCount;
    }
}
