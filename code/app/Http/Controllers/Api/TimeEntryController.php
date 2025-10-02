<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTimeEntryRequest;
use App\Http\Requests\UpdateTimeEntryRequest;
use App\Models\TimeEntry;
use App\Services\TimeTrackingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TimeEntryController extends Controller
{
    protected TimeTrackingService $timeTrackingService;

    public function __construct(TimeTrackingService $timeTrackingService)
    {
        $this->timeTrackingService = $timeTrackingService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['task_id', 'user_id', 'start_date', 'end_date']);
            $timeEntries = $this->timeTrackingService->getTimeEntries($filters, $request->user());

            $totalHours = $timeEntries->sum(function ($entry) {
                return $entry->duration_hours;
            });

            return response()->json([
                'data' => $timeEntries->items(),
                'total_hours' => round($totalHours, 2),
                'meta' => [
                    'current_page' => $timeEntries->currentPage(),
                    'last_page' => $timeEntries->lastPage(),
                    'per_page' => $timeEntries->perPage(),
                    'total' => $timeEntries->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des entrées de temps'
            ], 500);
        }
    }

    public function store(StoreTimeEntryRequest $request): JsonResponse
    {
        try {
            $timeEntry = $this->timeTrackingService->createTimeEntry($request->validated(), $request->user());

            $taskTotalHours = $timeEntry->task->timeEntries()->sum('duration_hours');

            return response()->json([
                'success' => true,
                'data' => $timeEntry->load(['task.project', 'user']),
                'task_total_hours' => round($taskTotalHours, 2),
                'message' => 'Entrée de temps ajoutée avec succès'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout de l\'entrée de temps'
            ], 500);
        }
    }

    public function show(TimeEntry $timeEntry): JsonResponse
    {
        try {
            $this->authorize('view', $timeEntry);

            $timeEntry->load(['task.project', 'user']);

            return response()->json([
                'data' => $timeEntry
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
                'message' => 'Entrée de temps non trouvée'
            ], 404);
        }
    }

    public function update(UpdateTimeEntryRequest $request, TimeEntry $timeEntry): JsonResponse
    {
        try {
            $this->authorize('update', $timeEntry);

            $updatedTimeEntry = $this->timeTrackingService->updateTimeEntry(
                $timeEntry,
                $request->validated(),
                $request->user()
            );

            return response()->json([
                'success' => true,
                'data' => $updatedTimeEntry->load(['task.project', 'user']),
                'message' => 'Entrée de temps mise à jour avec succès'
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
                'message' => 'Erreur lors de la mise à jour de l\'entrée de temps'
            ], 500);
        }
    }

    public function destroy(TimeEntry $timeEntry): JsonResponse
    {
        try {
            $this->authorize('delete', $timeEntry);

            $this->timeTrackingService->deleteTimeEntry($timeEntry, auth()->user());

            return response()->json([
                'success' => true,
                'message' => 'Entrée de temps supprimée avec succès'
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
                'message' => 'Erreur lors de la suppression de l\'entrée de temps'
            ], 500);
        }
    }
}