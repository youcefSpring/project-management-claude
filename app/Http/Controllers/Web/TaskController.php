<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
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

        // Basic filtering for the view - Ajax will handle advanced filtering
        $tasks = $this->taskService->getTasks($filters, $user, 15);

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
            'timeEntries.user'
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

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $users = User::where('role', 'member')->get();

        return view('tasks.edit', compact('task', 'users'));
    }
}