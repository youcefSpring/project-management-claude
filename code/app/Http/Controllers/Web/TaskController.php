<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use App\Services\TaskNoteService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskService $taskService;
    protected TaskNoteService $taskNoteService;

    public function __construct(TaskService $taskService, TaskNoteService $taskNoteService)
    {
        $this->taskService = $taskService;
        $this->taskNoteService = $taskNoteService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $filters = $request->only(['project_id', 'status', 'assigned_to', 'priority', 'search']);

        // Get accessible tasks for the user
        $tasks = $this->taskService->getAccessibleTasks($user, $filters);

        // Get data for filters
        $projects = [];
        $users = [];

        if ($user->isAdmin()) {
            // Admin sees all projects and users from their organization
            $projects = Project::where('organization_id', $user->organization_id)->get();
            $users = User::where('organization_id', $user->organization_id)
                        ->whereIn('role', ['member', 'manager', 'developer', 'designer', 'tester'])
                        ->get();
        } elseif ($user->isManager()) {
            // Manager sees projects they manage and users who can work on tasks in their organization
            $projects = Project::where('manager_id', $user->id)
                              ->where('organization_id', $user->organization_id)
                              ->get();
            $users = User::where('organization_id', $user->organization_id)
                        ->whereIn('role', ['member', 'developer', 'designer', 'tester'])
                        ->get();
        } else {
            // Members see only projects they're assigned to
            $projects = Project::whereHas('tasks', function ($query) use ($user) {
                $query->where('assigned_to', $user->id);
            })->where('organization_id', $user->organization_id)->get();
        }

        return view('tasks.index', compact('tasks', 'projects', 'users'));
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load([
            'project.manager',
            'assignedUser',
            'notes' => function($query) {
                $query->with('user')
                      ->orderBy('created_at', 'asc');

                // Filter internal notes for non-managers/admins
                $user = auth()->user();
                if (!$user->isAdmin() && !$user->isManager()) {
                    $query->where('is_internal', false);
                }
            },
            'timeEntries.user',
        ]);

        return view('tasks.show', compact('task'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Task::class);

        $projectId = $request->input('project_id');
        $projects = [];
        $users = User::where('organization_id', auth()->user()->organization_id)
                    ->whereIn('role', ['member', 'developer', 'designer', 'tester'])
                    ->get();

        if (auth()->user()->isAdmin()) {
            $projects = Project::where('organization_id', auth()->user()->organization_id)->get();
        } elseif (auth()->user()->isManager()) {
            $projects = Project::where('manager_id', auth()->id())
                              ->where('organization_id', auth()->user()->organization_id)
                              ->get();
        }

        $selectedProject = $projectId ? Project::where('id', $projectId)
                                               ->where('organization_id', auth()->user()->organization_id)
                                               ->first() : null;

        return view('tasks.create', compact('projects', 'users', 'selectedProject'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Task::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $task = $this->taskService->create($request->all(), $request->user());

        return redirect()->route('tasks.show', $task)
            ->with('success', __('Task created successfully.'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $projects = [];
        $users = User::where('organization_id', auth()->user()->organization_id)
                    ->whereIn('role', ['member', 'developer', 'designer', 'tester'])
                    ->get();

        $user = auth()->user();
        if ($user->isAdmin()) {
            $projects = Project::where('organization_id', $user->organization_id)->get();
        } elseif ($user->isManager()) {
            $projects = Project::where('manager_id', $user->id)
                              ->where('organization_id', $user->organization_id)
                              ->get();
        }

        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $this->taskService->update($task, $request->all(), $request->user());

        return redirect()->route('tasks.show', $task)
            ->with('success', __('Task updated successfully.'));
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $this->taskService->delete($task, auth()->user());

        return redirect()->route('tasks.index')
            ->with('success', __('Task deleted successfully.'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $oldStatus = $task->status;
        $newStatus = $request->status;

        $task->update([
            'status' => $newStatus,
            'updated_at' => now(),
        ]);

        // Create status change note using the service
        $this->taskNoteService->createStatusChangeNote(
            $task->id,
            $oldStatus,
            $newStatus,
            $request->input('comment')
        );

        return response()->json([
            'success' => true,
            'message' => __('app.tasks.status_updated_successfully'),
            'status' => $task->status,
        ]);
    }
}
