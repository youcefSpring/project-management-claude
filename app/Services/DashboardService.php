<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class DashboardService
{
    public function getStats($user = null)
    {
        $user = $user ?? Auth::user();

        if (! $user) {
            return null;
        }

        return [
            'total_projects' => $user->accessibleProjects()->count(),
            'active_projects' => $user->accessibleProjects()->where('status', 'active')->count(),
            'total_tasks' => $user->accessibleTasks()->count(),
            'completed_tasks' => $user->accessibleTasks()->where('status', 'completed')->count(),
            'pending_tasks' => $user->accessibleTasks()->whereIn('status', ['pending', 'in_progress'])->count(),
            'overdue_tasks' => $user->accessibleTasks()->where('due_date', '<', now())->count(),
            'total_time_today' => $user->timeEntries()->whereDate('start_time', today())->sum('duration_hours'),
            'total_time_this_week' => $user->timeEntriesThisWeek()->sum('duration_hours'),
            'total_time_this_month' => $user->timeEntriesThisMonth()->sum('duration_hours'),
        ];
    }

    public function getRecentActivity($user = null, $limit = 10)
    {
        $user = $user ?? Auth::user();

        if (! $user) {
            return collect();
        }

        $tasks = $user->tasks()->orderBy('updated_at', 'desc')->limit($limit)->get();
        $entries = $user->timeEntries()->with('task.project')->orderBy('created_at', 'desc')->limit($limit)->get();

        return $tasks->map(fn ($t) => (object) ['type' => 'task', 'model' => $t, 'date' => $t->updated_at])
            ->merge($entries->map(fn ($e) => (object) ['type' => 'time_entry', 'model' => e, 'date' => $e->created_at]))
            ->sortByDesc('date')->take($limit);
    }
}
