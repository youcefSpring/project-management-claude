<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    /**
     * User dashboard stats. Collapsed into 3 aggregate queries and cached 5 min.
     * Eventually-consistent within the TTL — acceptable for dashboard counters.
     */
    public function getStats($user = null)
    {
        $user = $user ?? Auth::user();

        if (! $user) {
            return null;
        }

        return Cache::remember("dash:stats:{$user->id}", 300, function () use ($user) {
            $now = now();

            // One query for all task counts via conditional aggregation.
            $tasks = $user->accessibleTasks()
                ->selectRaw('COUNT(*) as total')
                ->selectRaw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed")
                ->selectRaw("SUM(CASE WHEN status IN ('pending','in_progress') THEN 1 ELSE 0 END) as pending")
                ->selectRaw('SUM(CASE WHEN due_date < ? THEN 1 ELSE 0 END) as overdue', [$now])
                ->first();

            // Project counts. accessibleProjects() may be a belongsToMany (non-admins),
            // whose pivot columns break a single conditional-aggregate under only_full_group_by,
            // so use plain counts on cloned queries instead.
            $projectsQuery = $user->accessibleProjects();
            $projects = (object) [
                'total' => (clone $projectsQuery)->count(),
                'active' => (clone $projectsQuery)->where('status', 'active')->count(),
            ];

            // One query for all time buckets.
            $time = $user->timeEntries()
                ->selectRaw('SUM(CASE WHEN start_time >= ? THEN duration_hours ELSE 0 END) as today', [$now->copy()->startOfDay()])
                ->selectRaw('SUM(CASE WHEN start_time BETWEEN ? AND ? THEN duration_hours ELSE 0 END) as week', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()])
                ->selectRaw('SUM(CASE WHEN start_time BETWEEN ? AND ? THEN duration_hours ELSE 0 END) as month', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
                ->first();

            return [
                'total_projects' => (int) ($projects->total ?? 0),
                'active_projects' => (int) ($projects->active ?? 0),
                'total_tasks' => (int) ($tasks->total ?? 0),
                'completed_tasks' => (int) ($tasks->completed ?? 0),
                'pending_tasks' => (int) ($tasks->pending ?? 0),
                'overdue_tasks' => (int) ($tasks->overdue ?? 0),
                'total_time_today' => (float) ($time->today ?? 0),
                'total_time_this_week' => (float) ($time->week ?? 0),
                'total_time_this_month' => (float) ($time->month ?? 0),
            ];
        });
    }

    /**
     * Recent activity normalised into a consistent shape for blade + API.
     * Each item: ['type','description','date','url'].
     */
    public function getRecentActivity($user = null, $limit = 10)
    {
        $user = $user ?? Auth::user();

        if (! $user) {
            return collect();
        }

        $tasks = $user->tasks()
            ->with('project')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn ($t) => [
                'type' => 'task_update',
                'description' => __('app.tasks.title').': '.$t->title,
                'date' => $t->updated_at,
                'url' => route('tasks.show', $t),
            ]);

        $entries = $user->timeEntries()
            ->with('task.project')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn ($e) => [
                'type' => 'time_entry',
                'description' => __('app.time.log_time').': '.number_format((float) $e->duration_hours, 1).'h'
                    .($e->task ? ' — '.$e->task->title : ''),
                'date' => $e->created_at,
                'url' => $e->task ? route('tasks.show', $e->task) : route('timesheet.index'),
            ]);

        return $tasks->merge($entries)
            ->sortByDesc('date')
            ->take($limit)
            ->values();
    }

    /**
     * Latest notifications + unread count for the user.
     */
    public function getNotifications($user = null, $limit = 10)
    {
        $user = $user ?? Auth::user();

        if (! $user) {
            return ['notifications' => collect(), 'unread_count' => 0];
        }

        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        $unread = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return [
            'notifications' => $notifications,
            'unread_count' => $unread,
        ];
    }
}
