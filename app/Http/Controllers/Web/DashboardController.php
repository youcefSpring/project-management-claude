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
        $stats = $this->dashboardService->getStats($user);
        $recentActivity = $this->dashboardService->getRecentActivity($user);
        $notifications = $this->dashboardService->getNotifications($user);

        return view('dashboard.index', compact('stats', 'recentActivity', 'notifications'));
    }
}