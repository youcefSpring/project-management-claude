<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Generate project summary report
     */
    public function generateProjectReport(int $projectId, User $user, array $filters = []): array
    {
        $project = Project::findOrFail($projectId);

        // Check permissions
        if (!$project->canBeViewedBy($user)) {
            throw new \UnauthorizedHttpException('You are not authorized to view this project report.');
        }

        $startDate = isset($filters['start_date']) ? Carbon::parse($filters['start_date']) : $project->start_date;
        $endDate = isset($filters['end_date']) ? Carbon::parse($filters['end_date']) : Carbon::now();

        return [
            'project' => $project,
            'summary' => $this->getProjectSummary($project),
            'tasks_breakdown' => $this->getTasksBreakdown($project),
            'time_tracking' => $this->getProjectTimeTracking($project, $startDate, $endDate),
            'team_performance' => $this->getTeamPerformance($project, $startDate, $endDate),
            'timeline' => $this->getProjectTimeline($project),
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ];
    }

    /**
     * Generate user time report
     */
    public function generateUserTimeReport(int $userId, User $requestingUser, array $filters = []): array
    {
        $user = User::findOrFail($userId);

        // Check permissions
        if (!$this->canViewUserReport($requestingUser, $user)) {
            throw new \UnauthorizedHttpException('You are not authorized to view this user report.');
        }

        $startDate = isset($filters['start_date']) ? Carbon::parse($filters['start_date']) : Carbon::now()->startOfMonth();
        $endDate = isset($filters['end_date']) ? Carbon::parse($filters['end_date']) : Carbon::now()->endOfMonth();

        $timeEntries = TimeEntry::forUser($user->id)
                               ->dateRange($startDate, $endDate)
                               ->with(['task.project'])
                               ->get();

        return [
            'user' => $user,
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => $this->getUserTimeSummary($timeEntries, $startDate, $endDate),
            'daily_breakdown' => $this->getDailyTimeBreakdown($timeEntries, $startDate, $endDate),
            'project_breakdown' => $this->getProjectTimeBreakdown($timeEntries),
            'task_breakdown' => $this->getTaskTimeBreakdown($timeEntries),
            'productivity_metrics' => $this->getProductivityMetrics($user, $timeEntries),
        ];
    }

    /**
     * Generate team performance report
     */
    public function generateTeamReport(User $user, array $filters = []): array
    {
        if (!$user->isManager() && !$user->isAdmin()) {
            throw new \UnauthorizedHttpException('You are not authorized to view team reports.');
        }

        $startDate = isset($filters['start_date']) ? Carbon::parse($filters['start_date']) : Carbon::now()->startOfMonth();
        $endDate = isset($filters['end_date']) ? Carbon::parse($filters['end_date']) : Carbon::now()->endOfMonth();

        // Get projects based on user role
        $projects = $user->isAdmin() ? Project::all() : $user->managedProjects;

        return [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'overview' => $this->getTeamOverview($projects, $startDate, $endDate),
            'project_summaries' => $this->getMultipleProjectSummaries($projects),
            'team_members' => $this->getTeamMembersPerformance($projects, $startDate, $endDate),
            'time_distribution' => $this->getTimeDistribution($projects, $startDate, $endDate),
            'completion_trends' => $this->getCompletionTrends($projects, $startDate, $endDate),
        ];
    }

    /**
     * Generate overdue tasks report
     */
    public function generateOverdueTasksReport(User $user): array
    {
        $query = Task::overdue()->with(['project', 'assignedUser']);

        // Apply user-based filtering
        if ($user->isMember()) {
            $query->where('assigned_to', $user->id);
        } elseif ($user->isManager()) {
            $query->whereHas('project', function($q) use ($user) {
                $q->where('manager_id', $user->id);
            });
        }

        $overdueTasks = $query->get();

        return [
            'tasks' => $overdueTasks,
            'summary' => [
                'total_overdue' => $overdueTasks->count(),
                'by_project' => $overdueTasks->groupBy('project_id')->map(function($tasks) {
                    return [
                        'project' => $tasks->first()->project,
                        'count' => $tasks->count(),
                        'tasks' => $tasks,
                    ];
                }),
                'by_user' => $overdueTasks->groupBy('assigned_to')->map(function($tasks) {
                    return [
                        'user' => $tasks->first()->assignedUser,
                        'count' => $tasks->count(),
                        'tasks' => $tasks,
                    ];
                }),
                'average_days_overdue' => $this->getAverageDaysOverdue($overdueTasks),
            ],
        ];
    }

    /**
     * Generate project comparison report
     */
    public function generateProjectComparisonReport(array $projectIds, User $user): array
    {
        $projects = Project::whereIn('id', $projectIds)
                          ->accessibleBy($user)
                          ->with(['tasks', 'manager'])
                          ->get();

        return [
            'projects' => $projects->map(function($project) {
                return [
                    'project' => $project,
                    'summary' => $this->getProjectSummary($project),
                    'metrics' => $this->getProjectMetrics($project),
                ];
            }),
            'comparison' => $this->compareProjects($projects),
        ];
    }

    /**
     * Export report data
     */
    public function exportReport(array $reportData, string $format = 'pdf'): string
    {
        switch ($format) {
            case 'pdf':
                return $this->exportToPdf($reportData);
            case 'excel':
                return $this->exportToExcel($reportData);
            case 'csv':
                return $this->exportToCsv($reportData);
            default:
                throw new \InvalidArgumentException("Unsupported export format: {$format}");
        }
    }

    /**
     * Get project summary
     */
    private function getProjectSummary(Project $project): array
    {
        $tasks = $project->tasks;

        return [
            'total_tasks' => $tasks->count(),
            'completed_tasks' => $tasks->where('status', Task::STATUS_DONE)->count(),
            'in_progress_tasks' => $tasks->where('status', Task::STATUS_IN_PROGRESS)->count(),
            'pending_tasks' => $tasks->where('status', Task::STATUS_TODO)->count(),
            'overdue_tasks' => $tasks->filter(fn($task) => $task->isOverdue())->count(),
            'completion_percentage' => $project->completion_percentage,
            'total_hours' => $project->total_hours,
            'team_size' => $project->teamMembers()->count(),
            'progress_status' => $this->getProgressStatus($project),
        ];
    }

    /**
     * Get tasks breakdown
     */
    private function getTasksBreakdown(Project $project): array
    {
        $tasks = $project->tasks;

        return [
            'by_status' => [
                Task::STATUS_TODO => $tasks->where('status', Task::STATUS_TODO)->count(),
                Task::STATUS_IN_PROGRESS => $tasks->where('status', Task::STATUS_IN_PROGRESS)->count(),
                Task::STATUS_DONE => $tasks->where('status', Task::STATUS_DONE)->count(),
            ],
            'by_assignee' => $tasks->groupBy('assigned_to')->map(function($userTasks) {
                $user = $userTasks->first()->assignedUser;
                return [
                    'user' => $user,
                    'total' => $userTasks->count(),
                    'completed' => $userTasks->where('status', Task::STATUS_DONE)->count(),
                    'completion_rate' => $userTasks->count() > 0 ?
                        round(($userTasks->where('status', Task::STATUS_DONE)->count() / $userTasks->count()) * 100, 2) : 0,
                ];
            }),
            'priority_distribution' => $this->getTaskPriorityDistribution($tasks),
        ];
    }

    /**
     * Get project time tracking
     */
    private function getProjectTimeTracking(Project $project, Carbon $startDate, Carbon $endDate): array
    {
        $timeEntries = TimeEntry::forProject($project->id)
                               ->dateRange($startDate, $endDate)
                               ->with(['user', 'task'])
                               ->get();

        return [
            'total_hours' => $timeEntries->sum('duration'),
            'total_entries' => $timeEntries->count(),
            'average_entry_duration' => $timeEntries->avg('duration'),
            'by_user' => $timeEntries->groupBy('user_id')->map(function($entries) {
                return [
                    'user' => $entries->first()->user,
                    'total_hours' => $entries->sum('duration'),
                    'entry_count' => $entries->count(),
                ];
            }),
            'daily_hours' => $this->getDailyHours($timeEntries, $startDate, $endDate),
        ];
    }

    /**
     * Get team performance
     */
    private function getTeamPerformance(Project $project, Carbon $startDate, Carbon $endDate): array
    {
        $teamMembers = $project->teamMembers();

        return $teamMembers->map(function($member) use ($project, $startDate, $endDate) {
            $userTasks = $project->tasks()->where('assigned_to', $member->id)->get();
            $userTimeEntries = TimeEntry::forUser($member->id)
                                       ->forProject($project->id)
                                       ->dateRange($startDate, $endDate)
                                       ->get();

            return [
                'user' => $member,
                'tasks_assigned' => $userTasks->count(),
                'tasks_completed' => $userTasks->where('status', Task::STATUS_DONE)->count(),
                'completion_rate' => $userTasks->count() > 0 ?
                    round(($userTasks->where('status', Task::STATUS_DONE)->count() / $userTasks->count()) * 100, 2) : 0,
                'total_hours' => $userTimeEntries->sum('duration'),
                'average_hours_per_task' => $userTasks->count() > 0 ?
                    round($userTimeEntries->sum('duration') / $userTasks->count(), 2) : 0,
                'productivity_score' => $this->calculateProductivityScore($member, $userTasks, $userTimeEntries),
            ];
        })->toArray();
    }

    /**
     * Get project timeline
     */
    private function getProjectTimeline(Project $project): array
    {
        $events = [];

        // Project milestones
        $events[] = [
            'date' => $project->start_date,
            'type' => 'project_start',
            'title' => 'Project Started',
            'description' => "Project '{$project->title}' was started",
        ];

        if ($project->isCompleted()) {
            $events[] = [
                'date' => $project->updated_at,
                'type' => 'project_complete',
                'title' => 'Project Completed',
                'description' => "Project '{$project->title}' was completed",
            ];
        }

        // Task completions
        $completedTasks = $project->tasks()->where('status', Task::STATUS_DONE)->get();
        foreach ($completedTasks as $task) {
            $events[] = [
                'date' => $task->updated_at,
                'type' => 'task_complete',
                'title' => 'Task Completed',
                'description' => "Task '{$task->title}' was completed",
                'task' => $task,
            ];
        }

        // Sort by date
        usort($events, function($a, $b) {
            return $a['date']->timestamp - $b['date']->timestamp;
        });

        return $events;
    }

    /**
     * Check if user can view another user's report
     */
    private function canViewUserReport(User $requestingUser, User $targetUser): bool
    {
        // Admin can view any user's report
        if ($requestingUser->isAdmin()) {
            return true;
        }

        // Manager can view reports of team members
        if ($requestingUser->isManager()) {
            return $targetUser->assignedTasks()->whereHas('project', function($query) use ($requestingUser) {
                $query->where('manager_id', $requestingUser->id);
            })->exists();
        }

        // Users can view their own reports
        return $requestingUser->id === $targetUser->id;
    }

    /**
     * Get user time summary
     */
    private function getUserTimeSummary(Collection $timeEntries, Carbon $startDate, Carbon $endDate): array
    {
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $workingDays = $this->getWorkingDays($startDate, $endDate);

        return [
            'total_hours' => $timeEntries->sum('duration'),
            'total_entries' => $timeEntries->count(),
            'average_daily_hours' => $workingDays > 0 ? round($timeEntries->sum('duration') / $workingDays, 2) : 0,
            'average_entry_duration' => $timeEntries->count() > 0 ? round($timeEntries->avg('duration'), 2) : 0,
            'working_days' => $workingDays,
            'days_with_entries' => $timeEntries->pluck('start_time')->map(fn($date) => $date->format('Y-m-d'))->unique()->count(),
        ];
    }

    /**
     * Export to PDF
     */
    private function exportToPdf(array $data): string
    {
        // This would integrate with a PDF library like DOMPDF or TCPDF
        // For now, return a placeholder
        $filename = 'report_' . time() . '.pdf';
        // Implementation would go here
        return $filename;
    }

    /**
     * Export to Excel
     */
    private function exportToExcel(array $data): string
    {
        // This would integrate with a library like PhpSpreadsheet
        // For now, return a placeholder
        $filename = 'report_' . time() . '.xlsx';
        // Implementation would go here
        return $filename;
    }

    /**
     * Export to CSV
     */
    private function exportToCsv(array $data): string
    {
        $filename = 'report_' . time() . '.csv';
        $filepath = storage_path('app/exports/' . $filename);

        // Ensure directory exists
        if (!file_exists(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        $file = fopen($filepath, 'w');

        // Add headers (this is a simplified example)
        fputcsv($file, ['Date', 'Project', 'Task', 'User', 'Hours', 'Status']);

        // Add data (this would be customized based on report type)
        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        fclose($file);

        return $filename;
    }

    /**
     * Get daily time breakdown
     */
    private function getDailyTimeBreakdown(Collection $timeEntries, Carbon $startDate, Carbon $endDate): array
    {
        $breakdown = [];
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $dayEntries = $timeEntries->filter(function($entry) use ($current) {
                return $entry->start_time->format('Y-m-d') === $current->format('Y-m-d');
            });

            $breakdown[] = [
                'date' => $current->copy(),
                'total_hours' => $dayEntries->sum('duration'),
                'entry_count' => $dayEntries->count(),
                'is_weekend' => $current->isWeekend(),
            ];

            $current->addDay();
        }

        return $breakdown;
    }

    /**
     * Get working days between dates
     */
    private function getWorkingDays(Carbon $startDate, Carbon $endDate): int
    {
        $workingDays = 0;
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            if (!$current->isWeekend()) {
                $workingDays++;
            }
            $current->addDay();
        }

        return $workingDays;
    }

    /**
     * Calculate productivity score
     */
    private function calculateProductivityScore(User $user, Collection $tasks, Collection $timeEntries): float
    {
        if ($tasks->isEmpty()) {
            return 0;
        }

        $completionRate = $tasks->where('status', Task::STATUS_DONE)->count() / $tasks->count();
        $averageHoursPerTask = $tasks->count() > 0 ? $timeEntries->sum('duration') / $tasks->count() : 0;

        // Simple scoring algorithm (can be enhanced)
        $score = ($completionRate * 70) + (min($averageHoursPerTask, 8) / 8 * 30);

        return round($score, 2);
    }

    /**
     * Get progress status
     */
    private function getProgressStatus(Project $project): string
    {
        $completion = $project->completion_percentage;

        if ($completion >= 100) {
            return 'completed';
        } elseif ($completion >= 75) {
            return 'on_track';
        } elseif ($completion >= 50) {
            return 'progressing';
        } elseif ($completion >= 25) {
            return 'behind';
        } else {
            return 'not_started';
        }
    }

    /**
     * Get average days overdue
     */
    private function getAverageDaysOverdue(Collection $tasks): float
    {
        if ($tasks->isEmpty()) {
            return 0;
        }

        $totalDaysOverdue = $tasks->sum(function($task) {
            return $task->due_date ? Carbon::today()->diffInDays($task->due_date) : 0;
        });

        return round($totalDaysOverdue / $tasks->count(), 2);
    }

    /**
     * Get task priority distribution (placeholder - would need priority field)
     */
    private function getTaskPriorityDistribution(Collection $tasks): array
    {
        // This would require a priority field on tasks
        // For now, return a placeholder
        return [
            'high' => 0,
            'medium' => 0,
            'low' => 0,
        ];
    }

    /**
     * Get daily hours
     */
    private function getDailyHours(Collection $timeEntries, Carbon $startDate, Carbon $endDate): array
    {
        return $timeEntries->groupBy(function($entry) {
            return $entry->start_time->format('Y-m-d');
        })->map(function($dayEntries) {
            return $dayEntries->sum('duration');
        })->toArray();
    }

    /**
     * Get project time breakdown
     */
    private function getProjectTimeBreakdown(Collection $timeEntries): array
    {
        return $timeEntries->groupBy('task.project_id')->map(function($entries) {
            return [
                'project' => $entries->first()->task->project,
                'total_hours' => $entries->sum('duration'),
                'entry_count' => $entries->count(),
            ];
        })->values()->toArray();
    }

    /**
     * Get task time breakdown
     */
    private function getTaskTimeBreakdown(Collection $timeEntries): array
    {
        return $timeEntries->groupBy('task_id')->map(function($entries) {
            return [
                'task' => $entries->first()->task,
                'total_hours' => $entries->sum('duration'),
                'entry_count' => $entries->count(),
            ];
        })->values()->toArray();
    }

    /**
     * Get productivity metrics
     */
    private function getProductivityMetrics(User $user, Collection $timeEntries): array
    {
        return [
            'efficiency_score' => $this->calculateEfficiencyScore($timeEntries),
            'consistency_score' => $this->calculateConsistencyScore($timeEntries),
            'focus_score' => $this->calculateFocusScore($timeEntries),
        ];
    }

    /**
     * Calculate efficiency score (placeholder)
     */
    private function calculateEfficiencyScore(Collection $timeEntries): float
    {
        // This would be based on actual vs estimated time
        return 75.0; // Placeholder
    }

    /**
     * Calculate consistency score (placeholder)
     */
    private function calculateConsistencyScore(Collection $timeEntries): float
    {
        // This would be based on regular work patterns
        return 80.0; // Placeholder
    }

    /**
     * Calculate focus score (placeholder)
     */
    private function calculateFocusScore(Collection $timeEntries): float
    {
        // This would be based on time entry patterns
        return 70.0; // Placeholder
    }

    /**
     * Get team overview
     */
    private function getTeamOverview(Collection $projects, Carbon $startDate, Carbon $endDate): array
    {
        return [
            'total_projects' => $projects->count(),
            'active_projects' => $projects->where('status', Project::STATUS_IN_PROGRESS)->count(),
            'completed_projects' => $projects->where('status', Project::STATUS_COMPLETED)->count(),
            'total_tasks' => $projects->sum(fn($p) => $p->tasks->count()),
            'completed_tasks' => $projects->sum(fn($p) => $p->completedTasks->count()),
            'team_size' => $projects->flatMap(fn($p) => $p->teamMembers())->unique('id')->count(),
        ];
    }

    /**
     * Get multiple project summaries
     */
    private function getMultipleProjectSummaries(Collection $projects): array
    {
        return $projects->map(function($project) {
            return [
                'project' => $project,
                'summary' => $this->getProjectSummary($project),
            ];
        })->toArray();
    }

    /**
     * Get team members performance
     */
    private function getTeamMembersPerformance(Collection $projects, Carbon $startDate, Carbon $endDate): array
    {
        $allMembers = $projects->flatMap(fn($p) => $p->teamMembers())->unique('id');

        return $allMembers->map(function($member) use ($projects, $startDate, $endDate) {
            $memberTasks = Task::where('assigned_to', $member->id)
                              ->whereIn('project_id', $projects->pluck('id'))
                              ->get();

            $memberTimeEntries = TimeEntry::forUser($member->id)
                                         ->dateRange($startDate, $endDate)
                                         ->get();

            return [
                'user' => $member,
                'tasks_count' => $memberTasks->count(),
                'completed_tasks' => $memberTasks->where('status', Task::STATUS_DONE)->count(),
                'total_hours' => $memberTimeEntries->sum('duration'),
                'productivity_score' => $this->calculateProductivityScore($member, $memberTasks, $memberTimeEntries),
            ];
        })->toArray();
    }

    /**
     * Get time distribution
     */
    private function getTimeDistribution(Collection $projects, Carbon $startDate, Carbon $endDate): array
    {
        $timeEntries = TimeEntry::whereHas('task', function($query) use ($projects) {
            $query->whereIn('project_id', $projects->pluck('id'));
        })->dateRange($startDate, $endDate)->get();

        return [
            'by_project' => $this->getProjectTimeBreakdown($timeEntries),
            'by_user' => $timeEntries->groupBy('user_id')->map(function($entries) {
                return [
                    'user' => $entries->first()->user,
                    'total_hours' => $entries->sum('duration'),
                ];
            })->values()->toArray(),
        ];
    }

    /**
     * Get completion trends
     */
    private function getCompletionTrends(Collection $projects, Carbon $startDate, Carbon $endDate): array
    {
        // This would analyze completion trends over time
        // For now, return a placeholder
        return [
            'weekly_completions' => [],
            'trend_direction' => 'stable',
            'completion_velocity' => 0,
        ];
    }

    /**
     * Get project metrics
     */
    private function getProjectMetrics(Project $project): array
    {
        return [
            'planned_duration' => $project->start_date->diffInDays($project->end_date),
            'actual_duration' => $project->start_date->diffInDays(Carbon::now()),
            'budget_utilization' => 0, // Would need budget tracking
            'resource_utilization' => $project->teamMembers()->count(),
            'risk_score' => $this->calculateRiskScore($project),
        ];
    }

    /**
     * Compare projects
     */
    private function compareProjects(Collection $projects): array
    {
        return [
            'best_performer' => $projects->sortByDesc('completion_percentage')->first(),
            'most_delayed' => $projects->filter(fn($p) => $p->end_date->isPast())->sortBy('end_date')->first(),
            'average_completion' => $projects->avg('completion_percentage'),
            'total_hours' => $projects->sum('total_hours'),
        ];
    }

    /**
     * Calculate risk score
     */
    private function calculateRiskScore(Project $project): float
    {
        $score = 0;

        // Behind schedule
        if ($project->end_date->isPast() && !$project->isCompleted()) {
            $score += 40;
        }

        // Low completion rate
        if ($project->completion_percentage < 50) {
            $score += 30;
        }

        // Overdue tasks
        $overdueTasks = $project->overdueTasks()->count();
        if ($overdueTasks > 0) {
            $score += min($overdueTasks * 5, 30);
        }

        return min($score, 100);
    }
}