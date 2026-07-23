<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class TaskService
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function create(array $data, User $creator): Task
    {
        $project = Project::findOrFail($data['project_id']);

        if (! $creator->isAdmin() && (! $creator->isManager() || $project->manager_id !== $creator->id)) {
            throw new \UnauthorizedHttpException('Not authorized to create tasks for this project.');
        }

        $task = Task::create([
            'project_id' => $data['project_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => \App\Models\TaskStatus::defaultSlugFor($project->organization_id),
            'due_date' => ! empty($data['due_date']) ? Carbon::parse($data['due_date']) : null,
            'assigned_to' => $data['assigned_to'] ?? null,
        ]);

        if ($task->assigned_to) {
            $this->notificationService->notifyTaskAssigned($task);
        }

        return $task;
    }

    public function update(Task $task, array $data, User $user): Task
    {
        if (! canEditTask($user, $task)) {
            throw new \UnauthorizedHttpException('Not authorized to edit this task.');
        }

        $updateData = array_filter($data, fn ($v) => $v !== null);

        if (isset($updateData['due_date'])) {
            $updateData['due_date'] = Carbon::parse($updateData['due_date']);
        }

        $oldAssignee = $task->assigned_to;
        $task->update($updateData);

        $newAssignee = $data['assigned_to'] ?? $oldAssignee;

        if ($oldAssignee !== $newAssignee) {
            if ($newAssignee) {
                $this->notificationService->notifyTaskAssigned($task);
            }
        }

        if (isset($data['status']) && $data['status'] !== $task->getOriginal('status')) {
            $this->notificationService->notifyTaskStatusChanged($task);
        }

        return $task->fresh();
    }

    public function delete(Task $task, User $user): bool
    {
        if (! $user->isAdmin() && (! $user->isManager() || $task->project->manager_id !== $user->id)) {
            throw new \UnauthorizedHttpException('Not authorized to delete this task.');
        }

        if ($task->timeEntries()->exists()) {
            throw ValidationException::withMessages(['task' => ['Cannot delete task with time entries.']]);
        }

        return $task->delete();
    }

    public function getAccessibleTasks(User $user, array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = Task::accessibleBy($user);

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
            $query->where(fn ($q) => $q->where('title', 'like', '%'.$filters['search'].'%')
                ->orWhere('description', 'like', '%'.$filters['search'].'%'));
        }

        return $query->with(['project', 'assignedUser'])
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}

function canEditTask(User $user, Task $task): bool
{
    if ($user->isAdmin()) {
        return true;
    }
    if ($user->isManager() && $task->project->manager_id === $user->id) {
        return true;
    }
    if ($user->isMember() && $task->assigned_to === $user->id) {
        return true;
    }

    return false;
}
