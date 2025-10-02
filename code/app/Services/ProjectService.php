<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ProjectService
{
    /**
     * Create a new project
     */
    public function create(array $data, User $creator): Project
    {
        // Validate business rules
        $this->validateProjectData($data);

        // Ensure manager exists and has appropriate role
        $manager = User::find($data['manager_id']);
        if (!$manager || !$manager->isManager() && !$manager->isAdmin()) {
            throw ValidationException::withMessages([
                'manager_id' => ['The selected manager is invalid.'],
            ]);
        }

        // Validate dates
        $this->validateProjectDates($data['start_date'], $data['end_date']);

        // Create project
        $project = Project::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => Project::STATUS_IN_PROGRESS,
            'start_date' => Carbon::parse($data['start_date']),
            'end_date' => Carbon::parse($data['end_date']),
            'manager_id' => $data['manager_id'],
        ]);

        // Log activity
        \Log::info('Project created', [
            'project_id' => $project->id,
            'created_by' => $creator->id,
            'manager_id' => $project->manager_id,
        ]);

        return $project;
    }

    /**
     * Update project
     */
    public function update(Project $project, array $data, User $user): Project
    {
        // Check permissions
        if (!$this->canUserEditProject($user, $project)) {
            throw new \UnauthorizedHttpException('You are not authorized to edit this project.');
        }

        // Validate business rules for updates
        $this->validateProjectData($data, $project->id);

        // Special validation for status changes
        if (isset($data['status']) && $data['status'] !== $project->status) {
            $this->validateStatusTransition($project, $data['status'], $user);
        }

        // Validate dates if changed
        if (isset($data['start_date']) || isset($data['end_date'])) {
            $startDate = $data['start_date'] ?? $project->start_date;
            $endDate = $data['end_date'] ?? $project->end_date;
            $this->validateProjectDates($startDate, $endDate);
        }

        // Update project
        $updateData = array_filter($data, function($value) {
            return $value !== null;
        });

        if (isset($updateData['start_date'])) {
            $updateData['start_date'] = Carbon::parse($updateData['start_date']);
        }
        if (isset($updateData['end_date'])) {
            $updateData['end_date'] = Carbon::parse($updateData['end_date']);
        }

        $project->update($updateData);

        // Log activity
        \Log::info('Project updated', [
            'project_id' => $project->id,
            'updated_by' => $user->id,
            'changes' => $updateData,
        ]);

        return $project->fresh();
    }

    /**
     * Delete project
     */
    public function delete(Project $project, User $user): bool
    {
        // Check permissions
        if (!$user->isAdmin()) {
            throw new \UnauthorizedHttpException('Only administrators can delete projects.');
        }

        // Check if project can be deleted
        if (!$project->canBeDeleted()) {
            throw ValidationException::withMessages([
                'project' => ['Project cannot be deleted because it has time entries.'],
            ]);
        }

        // Log before deletion
        \Log::info('Project deleted', [
            'project_id' => $project->id,
            'project_title' => $project->title,
            'deleted_by' => $user->id,
        ]);

        return $project->delete();
    }

    /**
     * Get projects accessible by user
     */
    public function getAccessibleProjects(User $user, array $filters = []): Collection
    {
        $query = Project::accessibleBy($user);

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['manager_id'])) {
            $query->where('manager_id', $filters['manager_id']);
        }

        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('title', 'LIKE', "%{$filters['search']}%")
                  ->orWhere('description', 'LIKE', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['date_range'])) {
            $dateRange = $filters['date_range'];
            if (isset($dateRange['start'])) {
                $query->where('start_date', '>=', Carbon::parse($dateRange['start']));
            }
            if (isset($dateRange['end'])) {
                $query->where('end_date', '<=', Carbon::parse($dateRange['end']));
            }
        }

        return $query->with(['manager', 'tasks'])
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    /**
     * Get project statistics
     */
    public function getProjectStats(Project $project): array
    {
        return [
            'total_tasks' => $project->tasks()->count(),
            'completed_tasks' => $project->completedTasks()->count(),
            'pending_tasks' => $project->pendingTasks()->count(),
            'overdue_tasks' => $project->overdueTasks()->count(),
            'completion_percentage' => $project->completion_percentage,
            'total_hours' => $project->total_hours,
            'team_members_count' => $project->teamMembers()->count(),
            'days_remaining' => $this->getDaysRemaining($project),
            'is_on_track' => $this->isProjectOnTrack($project),
        ];
    }

    /**
     * Change project status
     */
    public function changeStatus(Project $project, string $newStatus, User $user): Project
    {
        $this->validateStatusTransition($project, $newStatus, $user);

        $oldStatus = $project->status;
        $project->update(['status' => $newStatus]);

        // Log status change
        \Log::info('Project status changed', [
            'project_id' => $project->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $user->id,
        ]);

        return $project;
    }

    /**
     * Get project dashboard data
     */
    public function getDashboardData(User $user): array
    {
        $projects = $this->getAccessibleProjects($user);

        return [
            'total_projects' => $projects->count(),
            'active_projects' => $projects->where('status', Project::STATUS_IN_PROGRESS)->count(),
            'completed_projects' => $projects->where('status', Project::STATUS_COMPLETED)->count(),
            'recent_projects' => $projects->take(5),
            'overdue_projects' => $this->getOverdueProjects($user),
            'completion_stats' => $this->getCompletionStats($projects),
        ];
    }

    /**
     * Validate project data
     */
    private function validateProjectData(array $data, ?int $exceptId = null): void
    {
        // Check for duplicate titles
        $query = Project::where('title', $data['title']);
        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'title' => ['A project with this title already exists.'],
            ]);
        }
    }

    /**
     * Validate project dates
     */
    private function validateProjectDates($startDate, $endDate): void
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        if ($end->lte($start)) {
            throw ValidationException::withMessages([
                'end_date' => ['End date must be after start date.'],
            ]);
        }

        if ($start->lt(Carbon::today())) {
            throw ValidationException::withMessages([
                'start_date' => ['Start date cannot be in the past.'],
            ]);
        }
    }

    /**
     * Validate status transition
     */
    private function validateStatusTransition(Project $project, string $newStatus, User $user): void
    {
        $validTransitions = [
            Project::STATUS_IN_PROGRESS => [Project::STATUS_COMPLETED, Project::STATUS_CANCELLED],
            Project::STATUS_CANCELLED => [Project::STATUS_IN_PROGRESS],
            Project::STATUS_COMPLETED => [], // Completed projects cannot be changed
        ];

        if (!in_array($newStatus, $validTransitions[$project->status] ?? [])) {
            throw ValidationException::withMessages([
                'status' => ['Invalid status transition.'],
            ]);
        }

        // Business rule: can only mark as completed if all tasks are done
        if ($newStatus === Project::STATUS_COMPLETED) {
            $pendingTasks = $project->pendingTasks()->count();
            if ($pendingTasks > 0) {
                throw ValidationException::withMessages([
                    'status' => ['Cannot complete project with pending tasks.'],
                ]);
            }
        }

        // Permission check for cancellation
        if ($newStatus === Project::STATUS_CANCELLED && !$user->isAdmin() && !$user->isManager()) {
            throw ValidationException::withMessages([
                'status' => ['Only managers and admins can cancel projects.'],
            ]);
        }
    }

    /**
     * Check if user can edit project
     */
    private function canUserEditProject(User $user, Project $project): bool
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
     * Get days remaining for project
     */
    private function getDaysRemaining(Project $project): int
    {
        return max(0, Carbon::today()->diffInDays($project->end_date, false));
    }

    /**
     * Check if project is on track
     */
    private function isProjectOnTrack(Project $project): bool
    {
        $totalDays = $project->start_date->diffInDays($project->end_date);
        $elapsedDays = $project->start_date->diffInDays(Carbon::today());
        $expectedProgress = $totalDays > 0 ? ($elapsedDays / $totalDays) * 100 : 0;

        return $project->completion_percentage >= $expectedProgress - 10; // 10% tolerance
    }

    /**
     * Get overdue projects
     */
    private function getOverdueProjects(User $user): Collection
    {
        return Project::accessibleBy($user)
                     ->where('end_date', '<', Carbon::today())
                     ->where('status', Project::STATUS_IN_PROGRESS)
                     ->with('manager')
                     ->get();
    }

    /**
     * Get completion statistics
     */
    private function getCompletionStats(Collection $projects): array
    {
        $totalProjects = $projects->count();

        if ($totalProjects === 0) {
            return ['average_completion' => 0, 'on_track_count' => 0];
        }

        $averageCompletion = $projects->avg('completion_percentage');
        $onTrackCount = $projects->filter(function($project) {
            return $this->isProjectOnTrack($project);
        })->count();

        return [
            'average_completion' => round($averageCompletion, 2),
            'on_track_count' => $onTrackCount,
            'on_track_percentage' => round(($onTrackCount / $totalProjects) * 100, 2),
        ];
    }

    /**
     * Archive completed projects
     */
    public function archiveCompletedProjects(int $daysOld = 90): int
    {
        $cutoffDate = Carbon::now()->subDays($daysOld);

        $projects = Project::where('status', Project::STATUS_COMPLETED)
                          ->where('updated_at', '<', $cutoffDate)
                          ->get();

        $archivedCount = 0;

        foreach ($projects as $project) {
            // Add an 'archived' status or move to archive table
            // For now, we'll just log the archival
            \Log::info('Project archived', ['project_id' => $project->id]);
            $archivedCount++;
        }

        return $archivedCount;
    }
}