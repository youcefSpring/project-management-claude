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
            $managers = User::where('organization_id', $user->organization_id)
                          ->where(function ($query) {
                              $query->where('role', 'manager')
                                    ->orWhereHas('userRoles', function ($q) {
                                        $q->where('role', 'manager')->where('is_active', true);
                                    });
                          })
                          ->get();
        }

        return view('projects.index', compact('projects', 'managers'));
    }

    public function show(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        // Get filters from request
        $filters = $request->only(['status', 'search']);

        // Load project with the (optionally filtered) task list shown in the page.
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
                return $query->with(['assignedUser', 'timeEntries'])->orderByDesc('updated_at');
            },
            'notes.user',
        ]);

        // Single unfiltered query reused for stats, team and activity (no N+1, no dup queries).
        $allTasks = $project->tasks()->with(['assignedUser', 'timeEntries'])->get();

        $completed = $allTasks->where('status', 'completed')->count();
        $stats = [
            'total_tasks' => $allTasks->count(),
            'completed_tasks' => $completed,
            'total_hours' => round($allTasks->sum(fn ($task) => $task->timeEntries->sum('duration_hours')), 1),
            'progress_percentage' => $allTasks->count() > 0
                ? (int) round(($completed / $allTasks->count()) * 100)
                : 0,
        ];

        // Team = manager + distinct task assignees.
        $teamMembers = $allTasks->pluck('assignedUser')->filter()->unique('id')->values();

        // Real recent activity from task updates (replaces the old fake/mock feed).
        $recentActivity = $allTasks->sortByDesc('updated_at')->take(5)->map(fn ($task) => [
            'title' => $task->title,
            'status' => $task->status,
            'ago' => optional($task->updated_at)->diffForHumans(),
            'url' => route('tasks.show', $task),
        ])->values();

        return view('projects.show', compact('project', 'stats', 'teamMembers', 'recentActivity'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);

        $managers = User::where('organization_id', auth()->user()->organization_id)
                       ->where(function ($query) {
                           $query->where('role', 'manager')
                                 ->orWhereHas('userRoles', function ($q) {
                                     $q->where('role', 'manager')->where('is_active', true);
                                 });
                       })
                       ->get();

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

        if ($request->expectsJson()) {
            return $this->ajaxSuccess(__('Project created successfully.'), route('projects.show', $project));
        }

        return redirect()->route('projects.show', $project)
            ->with('success', __('Project created successfully.'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $managers = User::where('organization_id', auth()->user()->organization_id)
                       ->where(function ($query) {
                           $query->where('role', 'manager')
                                 ->orWhereHas('userRoles', function ($q) {
                                     $q->where('role', 'manager')->where('is_active', true);
                                 });
                       })
                       ->get();

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

        if ($request->expectsJson()) {
            return $this->ajaxSuccess(__('Project updated successfully.'), route('projects.show', $project));
        }

        return redirect()->route('projects.show', $project)
            ->with('success', __('Project updated successfully.'));
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $this->projectService->delete($project, auth()->user());

        if (request()->expectsJson()) {
            return $this->ajaxSuccess(__('Project deleted successfully.'), route('projects.index'));
        }

        return redirect()->route('projects.index')
            ->with('success', __('Project deleted successfully.'));
    }
}
