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

    public function show(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        // Get filters from request
        $filters = $request->only(['status', 'search']);

        // Load project with relationships
        $project->load([
            'manager',
            'tasks' => function ($query) use ($filters) {
                if (!empty($filters['status'])) {
                    $query->where('status', $filters['status']);
                }
                if (!empty($filters['search'])) {
                    $query->where(function ($q) use ($filters) {
                        $q->where('title', 'like', '%' . $filters['search'] . '%')
                          ->orWhere('description', 'like', '%' . $filters['search'] . '%');
                    });
                }
                return $query->with(['assignedUser', 'timeEntries']);
            },
            'tasks.assignedUser',
            'tasks.timeEntries',
        ]);

        // Calculate stats based on all tasks (not filtered)
        $allTasks = $project->tasks()->with(['timeEntries'])->get();
        $stats = [
            'total_tasks' => $allTasks->count(),
            'completed_tasks' => $allTasks->where('status', 'completed')->count(),
            'total_hours' => $allTasks->sum(function ($task) {
                return $task->timeEntries->sum('duration_hours');
            }),
            'progress_percentage' => $allTasks->count() > 0
                ? round(($allTasks->where('status', 'completed')->count() / $allTasks->count()) * 100)
                : 0,
        ];

        // Get available users for task assignment
        $availableUsers = User::where('role', 'member')->get();

        // Get all tasks for team members display (unfiltered)
        $allTasks = $project->tasks()->with(['assignedUser'])->get();

        return view('projects.show', compact('project', 'stats', 'availableUsers', 'allTasks'));
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
