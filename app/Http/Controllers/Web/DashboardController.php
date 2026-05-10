<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total_projects' => $user->accessibleProjects()->count(),
            'active_projects' => $user->accessibleProjects()->where('status', 'active')->count(),
            'total_tasks' => $user->accessibleTasks()->count(),
            'completed_tasks' => $user->accessibleTasks()->where('status', 'completed')->count(),
            'pending_tasks' => $user->accessibleTasks()->whereIn('status', ['pending', 'in_progress'])->count(),
            'overdue_tasks' => $user->accessibleTasks()->where('due_date', '<', now())->count(),
            'total_time_today' => $user->timeEntries()->whereDate('start_time', today())->sum('duration_hours'),
            'total_time_this_week' => $user->timeEntries()->whereBetween('start_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('duration_hours'),
            'total_time_this_month' => $user->timeEntries()->whereBetween('start_time', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('duration_hours'),
        ];

        $myTasks = $user->tasks()
            ->with(['project'])
            ->orderBy('due_date', 'asc')
            ->take(6)
            ->get();

        $recentActivity = $user->tasks()
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->merge($user->timeEntries()->with('task.project')->orderBy('created_at', 'desc')->limit(5)->get())
            ->sortByDesc('updated_at')
            ->take(10);

        $notifications = $user->notifications()->limit(10)->get();

        return view('dashboard.index', compact('stats', 'recentActivity', 'notifications', 'myTasks'));
    }
}
