<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['project_id', 'assigned_to', 'status', 'due_date', 'search']);

            // Clean up empty values
            $filters = array_filter($filters, function($value) {
                return $value !== null && $value !== '';
            });

            $tasks = $this->taskService->getAccessibleTasks($request->user(), $filters);

            // Load relationships for API response
            $tasks->load(['project', 'assignedUser']);

            return response()->json([
                'success' => true,
                'data' => $tasks->values(), // Convert to array with numeric keys
                'count' => $tasks->count(),
                'message' => 'Tasks retrieved successfully',
            ]);

        } catch (\Exception $e) {
            \Log::error('API Task index error: ' . $e->getMessage(), [
                'user_id' => $request->user()?->id,
                'filters' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving tasks',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error',
            ], 500);
        }
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $task = $this->taskService->createTask($request->validated(), $request->user());

            return response()->json([
                'success' => true,
                'data' => $task->load(['project', 'assignedUser']),
                'message' => 'Tâche créée avec succès',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la tâche',
            ], 500);
        }
    }

    public function show(Task $task): JsonResponse
    {
        try {
            $this->authorize('view', $task);

            $task->load([
                'project.manager',
                'assignedUser',
                'notes.user',
                'timeEntries.user',
            ]);

            return response()->json([
                'data' => $task,
                'notes' => $task->notes,
                'time_entries' => $task->timeEntries,
            ]);

        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès refusé',
                ], 403);
            }

            return response()->json([
                'success' => false,
                'message' => 'Tâche non trouvée',
            ], 404);
        }
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        try {
            $this->authorize('update', $task);

            $updatedTask = $this->taskService->updateTask($task, $request->validated(), $request->user());

            return response()->json([
                'success' => true,
                'data' => $updatedTask->load(['project', 'assignedUser']),
                'message' => 'Tâche mise à jour avec succès',
            ]);

        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès refusé',
                ], 403);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la tâche',
            ], 500);
        }
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task): JsonResponse
    {
        try {
            $this->authorize('update', $task);

            $updatedTask = $this->taskService->updateTaskStatus(
                $task,
                $request->validated()['status'],
                $request->user()
            );

            return response()->json([
                'success' => true,
                'data' => $updatedTask->load(['project', 'assignedUser']),
                'message' => 'Statut mis à jour avec succès',
            ]);

        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès refusé',
                ], 403);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut',
            ], 500);
        }
    }

    public function destroy(Task $task): JsonResponse
    {
        try {
            $this->authorize('delete', $task);

            $this->taskService->deleteTask($task, auth()->user());

            return response()->json([
                'success' => true,
                'message' => 'Tâche supprimée avec succès',
            ]);

        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès refusé',
                ], 403);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la tâche',
            ], 500);
        }
    }
}
