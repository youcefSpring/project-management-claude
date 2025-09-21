<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $filters = $request->only(['status', 'search']);

        // Basic filtering for the view - Ajax will handle advanced filtering
        $projects = $this->projectService->getProjects($filters, $user, 10);

        // Get managers for filter dropdown (admin/manager only)
        $managers = [];
        if ($user->isAdmin() || $user->isManager()) {
            $managers = User::where('role', 'manager')->get();
        }

        return view('projects.index', compact('projects', 'managers'));
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        $project->load([
            'manager',
            'tasks.assignedUser',
            'tasks.timeEntries'
        ]);

        $stats = [
            'total_tasks' => $project->tasks->count(),
            'completed_tasks' => $project->tasks->where('status', 'fait')->count(),
            'total_hours' => $project->total_hours,
            'progress_percentage' => $project->getProgressPercentage(),
        ];

        // Get available users for task assignment
        $availableUsers = User::where('role', 'member')->get();

        return view('projects.show', compact('project', 'stats', 'availableUsers'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);

        $managers = User::where('role', 'manager')->get();

        return view('projects.create', compact('managers'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $managers = User::where('role', 'manager')->get();

        return view('projects.edit', compact('project', 'managers'));
    }
}