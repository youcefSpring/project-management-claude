<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $filters = $request->only(['project_id', 'status', 'assigned_to']);

        // Get accessible tasks for the user
        $tasks = $this->taskService->getAccessibleTasks($user, $filters);

        // Get data for filters
        $projects = [];
        $users = [];

        if ($user->isAdmin()) {
            $projects = Project::all();
            $users = User::where('role', 'member')->get();
        } elseif ($user->isManager()) {
            $projects = Project::where('manager_id', $user->id)->get();
            $users = User::where('role', 'member')->get();
        } else {
            $projects = Project::whereHas('tasks', function ($query) use ($user) {
                $query->where('assigned_to', $user->id);
            })->get();
        }

        return view('tasks.index', compact('tasks', 'projects', 'users'));
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load([
            'project.manager',
            'assignedUser',
            'notes.user',
            'timeEntries.user',
        ]);

        return view('tasks.show', compact('task'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Task::class);

        $projectId = $request->input('project_id');
        $projects = [];
        $users = User::where('role', 'member')->get();

        if (auth()->user()->isAdmin()) {
            $projects = Project::all();
        } elseif (auth()->user()->isManager()) {
            $projects = Project::where('manager_id', auth()->id())->get();
        }

        $selectedProject = $projectId ? Project::find($projectId) : null;

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
        $users = User::where('role', 'member')->get();

        $user = auth()->user();
        if ($user->isAdmin()) {
            $projects = Project::all();
        } elseif ($user->isManager()) {
            $projects = Project::where('manager_id', $user->id)->get();
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
}
