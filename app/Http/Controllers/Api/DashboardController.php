<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function stats(): JsonResponse
    {
        try {
            $stats = $this->dashboardService->getStats(auth()->user());

            return response()->json($stats);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
            ], 500);
        }
    }

    public function recentActivity(): JsonResponse
    {
        try {
            $activities = $this->dashboardService->getRecentActivity(auth()->user());

            return response()->json([
                'data' => $activities,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'activité récente',
            ], 500);
        }
    }

    public function notifications(): JsonResponse
    {
        try {
            $notifications = $this->dashboardService->getNotifications(auth()->user());

            return response()->json([
                'data' => $notifications['notifications'],
                'unread_count' => $notifications['unread_count'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des notifications',
            ], 500);
        }
    }
}
