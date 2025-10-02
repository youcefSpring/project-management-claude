<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function projects(Request $request): JsonResponse
    {
        try {
            $this->authorize('viewReports', auth()->user());

            $filters = $request->only(['start_date', 'end_date', 'project_id', 'format']);
            $data = $this->reportService->getProjectsReport($filters, $request->user());

            return response()->json([
                'data' => $data['projects'],
                'summary' => $data['summary']
            ]);

        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès refusé'
                ], 403);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du rapport'
            ], 500);
        }
    }

    public function users(Request $request): JsonResponse
    {
        try {
            $this->authorize('viewReports', auth()->user());

            $filters = $request->only(['start_date', 'end_date', 'user_id', 'project_id']);
            $data = $this->reportService->getUsersReport($filters, $request->user());

            return response()->json([
                'data' => $data['users'],
                'total_hours' => $data['total_hours']
            ]);

        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès refusé'
                ], 403);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du rapport'
            ], 500);
        }
    }

    public function timeTracking(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['user_id', 'project_id', 'start_date', 'end_date']);
            $data = $this->reportService->getTimeTrackingReport($filters, $request->user());

            return response()->json([
                'data' => $data['entries'],
                'daily_totals' => $data['daily_totals'],
                'weekly_totals' => $data['weekly_totals']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du rapport'
            ], 500);
        }
    }

    public function export(Request $request): JsonResponse
    {
        try {
            $this->authorize('viewReports', auth()->user());

            $request->validate([
                'type' => 'required|in:project,user,time',
                'format' => 'required|in:pdf,excel',
                'filters' => 'nullable|array'
            ]);

            $downloadUrl = $this->reportService->exportReport(
                $request->input('type'),
                $request->input('format'),
                $request->input('filters', []),
                $request->user()
            );

            return response()->json([
                'success' => true,
                'download_url' => $downloadUrl
            ]);

        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès refusé'
                ], 403);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'export du rapport'
            ], 500);
        }
    }
}