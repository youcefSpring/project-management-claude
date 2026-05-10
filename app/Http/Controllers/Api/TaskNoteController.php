<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskNoteRequest;
use App\Http\Requests\UpdateTaskNoteRequest;
use App\Models\Task;
use App\Models\TaskNote;
use App\Services\TaskNoteService;
use Illuminate\Http\JsonResponse;

class TaskNoteController extends Controller
{
    protected TaskNoteService $taskNoteService;

    public function __construct(TaskNoteService $taskNoteService)
    {
        $this->taskNoteService = $taskNoteService;
    }

    public function index(Task $task): JsonResponse
    {
        try {
            $this->authorize('view', $task);

            $notes = $task->notes()
                ->with(['user'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'data' => $notes,
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
                'message' => 'Erreur lors de la récupération des notes',
            ], 500);
        }
    }

    public function store(StoreTaskNoteRequest $request, Task $task): JsonResponse
    {
        try {
            $this->authorize('view', $task);

            $note = $this->taskNoteService->createNote(
                array_merge($request->validated(), ['task_id' => $task->id]),
                $request->user()
            );

            return response()->json([
                'success' => true,
                'data' => $note->load(['user']),
                'message' => 'Note ajoutée avec succès',
            ], 201);

        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès refusé',
                ], 403);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout de la note',
            ], 500);
        }
    }

    public function show(Task $task, TaskNote $note): JsonResponse
    {
        try {
            $this->authorize('view', $task);

            if ($note->task_id !== $task->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Note non trouvée',
                ], 404);
            }

            $note->load(['user', 'task.project']);

            return response()->json([
                'data' => $note,
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
                'message' => 'Note non trouvée',
            ], 404);
        }
    }

    public function update(UpdateTaskNoteRequest $request, TaskNote $note): JsonResponse
    {
        try {
            $this->authorize('update', $note);

            $updatedNote = $this->taskNoteService->updateNote(
                $note,
                $request->validated(),
                $request->user()
            );

            return response()->json([
                'success' => true,
                'data' => $updatedNote->load(['user']),
                'message' => 'Note mise à jour avec succès',
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
                'message' => 'Erreur lors de la mise à jour de la note',
            ], 500);
        }
    }

    public function destroy(TaskNote $note): JsonResponse
    {
        try {
            $this->authorize('delete', $note);

            $this->taskNoteService->deleteNote($note, auth()->user());

            return response()->json([
                'success' => true,
                'message' => 'Note supprimée avec succès',
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
                'message' => 'Erreur lors de la suppression de la note',
            ], 500);
        }
    }
}
