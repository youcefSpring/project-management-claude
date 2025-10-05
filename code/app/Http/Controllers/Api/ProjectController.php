<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['status', 'manager_id', 'search']);
            $projects = $this->projectService->getProjects($filters, $request->user());

            return response()->json([
                'data' => $projects->items(),
                'meta' => [
                    'current_page' => $projects->currentPage(),
                    'last_page' => $projects->lastPage(),
                    'per_page' => $projects->perPage(),
                    'total' => $projects->total(),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des projets',
            ], 500);
        }
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        try {
            $project = $this->projectService->createProject($request->validated(), $request->user());

            return response()->json([
                'success' => true,
                'data' => $project,
                'message' => 'Projet créé avec succès',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du projet',
            ], 500);
        }
    }

    public function show(Project $project): JsonResponse
    {
        try {
            $this->authorize('view', $project);

            $project->load(['manager', 'tasks.assignedUser', 'tasks.timeEntries']);

            $stats = [
                'total_tasks' => $project->tasks->count(),
                'completed_tasks' => $project->tasks->where('status', 'fait')->count(),
                'total_hours' => $project->total_hours,
                'progress_percentage' => $project->getProgressPercentage(),
            ];

            return response()->json([
                'data' => $project,
                'tasks' => $project->tasks,
                'stats' => $stats,
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
                'message' => 'Projet non trouvé',
            ], 404);
        }
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        try {
            $this->authorize('update', $project);

            $updatedProject = $this->projectService->updateProject($project, $request->validated(), $request->user());

            return response()->json([
                'success' => true,
                'data' => $updatedProject,
                'message' => 'Projet mis à jour avec succès',
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
                'message' => 'Erreur lors de la mise à jour du projet',
            ], 500);
        }
    }

    public function destroy(Project $project): JsonResponse
    {
        try {
            $this->authorize('delete', $project);

            $this->projectService->deleteProject($project, auth()->user());

            return response()->json([
                'success' => true,
                'message' => 'Projet supprimé avec succès',
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
                'message' => 'Erreur lors de la suppression du projet',
            ], 500);
        }
    }
}
