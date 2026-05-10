<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // Get all dashboard data at once
        $stats = $this->dashboardService->getStats($user);
        $recentActivity = $this->dashboardService->getRecentActivity($user);
        $notifications = $this->dashboardService->getNotifications($user);

        // Get user's tasks for the "My Tasks" section
        $myTasks = \App\Models\Task::where('assigned_to', $user->id)
            ->with(['project'])
            ->orderBy('due_date', 'asc')
            ->take(6)
            ->get();

        return view('dashboard.index', compact('stats', 'recentActivity', 'notifications', 'myTasks'));
    }
}
