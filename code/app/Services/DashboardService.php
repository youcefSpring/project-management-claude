<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    /**
     * Get dashboard statistics for a specific user
     */
    public function getStats($user = null)
    {
        if (! $user) {
            $user = Auth::user();
        }

        if (! $user) {
            return null;
        }

        $stats = [
            'total_projects' => $this->getTotalProjects($user),
            'active_projects' => $this->getActiveProjects($user),
            'total_tasks' => $this->getTotalTasks($user),
            'completed_tasks' => $this->getCompletedTasks($user),
            'pending_tasks' => $this->getPendingTasks($user),
            'overdue_tasks' => $this->getOverdueTasks($user),
            'total_time_today' => $this->getTotalTimeToday($user),
            'total_time_this_week' => $this->getTotalTimeThisWeek($user),
            'total_time_this_month' => $this->getTotalTimeThisMonth($user),
        ];

        return $stats;
    }

    /**
     * Get dashboard statistics for the authenticated user
     */
    public function getDashboardStats()
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        $stats = [
            'total_projects' => $this->getTotalProjects($user),
            'active_projects' => $this->getActiveProjects($user),
            'total_tasks' => $this->getTotalTasks($user),
            'completed_tasks' => $this->getCompletedTasks($user),
            'pending_tasks' => $this->getPendingTasks($user),
            'overdue_tasks' => $this->getOverdueTasks($user),
            'total_time_today' => $this->getTotalTimeToday($user),
            'total_time_this_week' => $this->getTotalTimeThisWeek($user),
            'total_time_this_month' => $this->getTotalTimeThisMonth($user),
        ];

        return $stats;
    }

    /**
     * Get notifications for a specific user
     */
    public function getNotifications($user = null, $limit = 10)
    {
        if (! $user) {
            $user = Auth::user();
        }

        if (! $user) {
            return collect();
        }

        $notificationService = app(NotificationService::class);

        return $notificationService->getUserNotifications($user, $limit);
    }

    /**
     * Get recent activity for dashboard
     */
    public function getRecentActivity($user = null, $limit = 10)
    {
        if (! $user) {
            $user = Auth::user();
        }

        if (! $user) {
            return collect();
        }

        // Get recent tasks, time entries, and project updates
        $recentTasks = Task::where('assigned_to', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();

        $recentTimeEntries = TimeEntry::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->with(['task.project'])
            ->get();

        // Combine and sort by date
        $activities = collect();

        foreach ($recentTasks as $task) {
            $activities->push([
                'type' => 'task_update',
                'description' => "Updated task: {$task->title}",
                'date' => $task->updated_at,
                'model' => $task,
            ]);
        }

        foreach ($recentTimeEntries as $entry) {
            $taskTitle = $entry->task ? $entry->task->title : 'Unknown Task';
            $activities->push([
                'type' => 'time_entry',
                'description' => "Logged {$entry->duration} hours on: {$taskTitle}",
                'date' => $entry->created_at,
                'model' => $entry,
            ]);
        }

        return $activities->sortByDesc('date')->take($limit);
    }

    /**
     * Get upcoming deadlines
     */
    public function getUpcomingDeadlines($days = 7)
    {
        $user = Auth::user();

        if (! $user) {
            return collect();
        }

        $endDate = Carbon::now()->addDays($days);

        return Task::where('assigned_to', $user->id)
            ->where('status', '!=', 'completed')
            ->where('due_date', '>=', Carbon::now())
            ->where('due_date', '<=', $endDate)
            ->orderBy('due_date', 'asc')
            ->with(['project'])
            ->get();
    }

    /**
     * Get productivity metrics
     */
    public function getProductivityMetrics()
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        return [
            'tasks_completed_this_week' => Task::where('assigned_to', $user->id)
                ->where('status', 'completed')
                ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                ->count(),

            'time_logged_this_week' => TimeEntry::where('user_id', $user->id)
                ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
                ->sum('duration_hours'),

            'average_task_completion_time' => $this->getAverageTaskCompletionTime($user),
            'efficiency_score' => $this->calculateEfficiencyScore($user),
        ];
    }

    private function getTotalProjects($user)
    {
        if ($user->role === 'admin') {
            return Project::count();
        }

        return Project::where('manager_id', $user->id)
            ->orWhereHas('tasks', function ($query) use ($user) {
                $query->where('assigned_to', $user->id);
            })
            ->distinct()
            ->count();
    }

    private function getActiveProjects($user)
    {
        if ($user->role === 'admin') {
            return Project::where('status', 'active')->count();
        }

        return Project::where('status', 'active')
            ->where(function ($query) use ($user) {
                $query->where('manager_id', $user->id)
                    ->orWhereHas('tasks', function ($subQuery) use ($user) {
                        $subQuery->where('assigned_to', $user->id);
                    });
            })
            ->distinct()
            ->count();
    }

    private function getTotalTasks($user)
    {
        if ($user->role === 'admin') {
            return Task::count();
        }

        return Task::where('assigned_to', $user->id)->count();
    }

    private function getCompletedTasks($user)
    {
        if ($user->role === 'admin') {
            return Task::where('status', 'completed')->count();
        }

        return Task::where('assigned_to', $user->id)
            ->where('status', 'completed')
            ->count();
    }

    private function getPendingTasks($user)
    {
        if ($user->role === 'admin') {
            return Task::whereIn('status', ['pending', 'in_progress'])->count();
        }

        return Task::where('assigned_to', $user->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();
    }

    private function getOverdueTasks($user)
    {
        if ($user->role === 'admin') {
            return Task::where('due_date', '<', Carbon::now())
                ->where('status', '!=', 'completed')
                ->count();
        }

        return Task::where('assigned_to', $user->id)
            ->where('due_date', '<', Carbon::now())
            ->where('status', '!=', 'completed')
            ->count();
    }

    private function getTotalTimeToday($user)
    {
        return TimeEntry::where('user_id', $user->id)
            ->whereDate('start_time', Carbon::today())
            ->sum('duration_hours');
    }

    private function getTotalTimeThisWeek($user)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        return TimeEntry::where('user_id', $user->id)
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->sum('duration_hours');
    }

    private function getTotalTimeThisMonth($user)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return TimeEntry::where('user_id', $user->id)
            ->whereBetween('start_time', [$startOfMonth, $endOfMonth])
            ->sum('duration_hours');
    }

    private function getAverageTaskCompletionTime($user)
    {
        $completedTasks = Task::where('assigned_to', $user->id)
            ->where('status', 'completed')
            ->whereNotNull('created_at')
            ->whereNotNull('updated_at')
            ->get();

        if ($completedTasks->isEmpty()) {
            return 0;
        }

        $totalDays = $completedTasks->sum(function ($task) {
            return $task->created_at->diffInDays($task->updated_at);
        });

        return round($totalDays / $completedTasks->count(), 2);
    }

    private function calculateEfficiencyScore($user)
    {
        // Simple efficiency calculation based on completed tasks vs total time
        $completedTasks = $this->getCompletedTasks($user);
        $totalTime = $this->getTotalTimeThisMonth($user);

        if ($totalTime == 0) {
            return 0;
        }

        // Basic efficiency: tasks completed per hour
        return round(($completedTasks / $totalTime) * 100, 2);
    }
}
