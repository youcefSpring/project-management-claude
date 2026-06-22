<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboard) {}

    public function index(Request $request)
    {
        $user = $request->user();

        // Cheap, already-eager: render with the page. Heavy widgets load async.
        $myTasks = $user->tasks()
            ->with('project')
            ->orderBy('due_date', 'asc')
            ->take(6)
            ->get();

        $tasksByStatus = [
            'pending' => $myTasks->where('status', 'pending'),
            'in_progress' => $myTasks->where('status', 'in_progress'),
            'completed' => $myTasks->where('status', 'completed'),
        ];

        return view('dashboard.index', compact('myTasks', 'tasksByStatus'));
    }

    /** Async widget: stat counters. */
    public function statsWidget(Request $request): JsonResponse
    {
        return response()->json($this->dashboard->getStats($request->user()) ?? []);
    }

    /** Async widget: recent activity feed. */
    public function activityWidget(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->dashboard->getRecentActivity($request->user())
                ->map(fn ($a) => [
                    'type' => $a['type'],
                    'description' => $a['description'],
                    'url' => $a['url'],
                    'ago' => optional($a['date'])->diffForHumans(),
                ])->values(),
        ]);
    }

    /** Async widget: notifications. */
    public function notificationsWidget(Request $request): JsonResponse
    {
        $n = $this->dashboard->getNotifications($request->user(), 5);

        return response()->json([
            'data' => $n['notifications']->map(fn ($x) => [
                'title' => $x->title,
                'message' => $x->message,
            ])->values(),
            'unread_count' => $n['unread_count'],
        ]);
    }
}
