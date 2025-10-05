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

        // Get accessible projects for the user
        $projects = $this->projectService->getAccessibleProjects($user, $filters);

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
            'tasks.timeEntries',
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

    public function store(Request $request)
    {
        $this->authorize('create', Project::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'manager_id' => 'required|exists:users,id',
        ]);

        $project = $this->projectService->create($request->all(), $request->user());

        return redirect()->route('projects.show', $project)
            ->with('success', __('Project created successfully.'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $managers = User::where('role', 'manager')->get();

        return view('projects.edit', compact('project', 'managers'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'manager_id' => 'required|exists:users,id',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
        ]);

        $this->projectService->update($project, $request->all(), $request->user());

        return redirect()->route('projects.show', $project)
            ->with('success', __('Project updated successfully.'));
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $this->projectService->delete($project, auth()->user());

        return redirect()->route('projects.index')
            ->with('success', __('Project deleted successfully.'));
    }
}
