<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use App\Services\TimeTrackingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimeEntryController extends Controller
{
    protected TimeTrackingService $timeTrackingService;

    public function __construct(TimeTrackingService $timeTrackingService)
    {
        $this->timeTrackingService = $timeTrackingService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $filters = $request->only(['task_id', 'start_date', 'end_date', 'show_all', 'project', 'user', 'search', 'date_from', 'date_to']);

        // Map JavaScript parameter names to service parameter names
        if (!empty($filters['date_from'])) {
            $filters['start_date'] = $filters['date_from'];
            unset($filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $filters['end_date'] = $filters['date_to'];
            unset($filters['date_to']);
        }
        if (!empty($filters['project'])) {
            $filters['project_id'] = $filters['project'];
            unset($filters['project']);
        }
        if (!empty($filters['user'])) {
            $filters['user_id'] = $filters['user'];
            unset($filters['user']);
        }

        // If show_all parameter is present, don't apply date filters
        if (!$request->has('show_all')) {
            // Set default date range if not provided (show last 30 days instead of just this week)
            if (empty($filters['start_date'])) {
                $filters['start_date'] = Carbon::now()->subDays(30)->format('Y-m-d');
            }
            if (empty($filters['end_date'])) {
                $filters['end_date'] = Carbon::now()->addDay()->format('Y-m-d'); // Include today + tomorrow to catch entries created today
            }
        } else {
            // Remove date filters to show all entries
            unset($filters['start_date'], $filters['end_date']);
        }

        $timeEntries = $this->timeTrackingService->getTimeEntries($filters, $user, 20);

        // Calculate totals
        $totalHours = $timeEntries->sum(function ($entry) {
            return $entry->duration_hours;
        });

        // Get available tasks for time entry
        $availableTasks = [];
        if ($user->isAdmin()) {
            $availableTasks = Task::whereHas('project', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })->with('project')->get();
        } elseif ($user->isManager()) {
            $availableTasks = Task::whereHas('project', function ($query) use ($user) {
                $query->where('manager_id', $user->id)
                      ->where('organization_id', $user->organization_id);
            })->with('project')->get();
        } else {
            $availableTasks = Task::where('assigned_to', $user->id)
                ->whereHas('project', function ($query) use ($user) {
                    $query->where('organization_id', $user->organization_id);
                })
                ->with('project')
                ->get();
        }

        // Get projects and users for filtering (admin and managers only)
        $projects = collect();
        $users = collect();

        if ($user->isAdmin()) {
            $projects = Project::where('organization_id', $user->organization_id)->get();
            $users = User::where('organization_id', $user->organization_id)->get();
        } elseif ($user->isManager()) {
            // Projects the user manages or is a member of
            $projects = Project::accessibleBy($user)->get();
            // Users from projects the manager has access to - simplified query
            $projectIds = $projects->pluck('id');
            $users = User::whereHas('timeEntries.task', function ($query) use ($projectIds) {
                $query->whereIn('project_id', $projectIds);
            })->distinct()->get();
        }

        // Calculate summary statistics based on accessible time entries
        $summary = [
            'total_hours' => $timeEntries->sum('duration_hours'),
            'approved_hours' => 0, // Placeholder - implement when approval system is added
            'pending_hours' => $timeEntries->sum('duration_hours'), // All logged hours are currently "pending"
            'rejected_hours' => 0, // Placeholder - implement when approval system is added
        ];

        return view('timesheet.index', compact(
            'timeEntries',
            'totalHours',
            'availableTasks',
            'projects',
            'users',
            'filters',
            'summary'
        ));
    }

    public function create(Request $request)
    {
        $taskId = $request->input('task_id');
        $user = $request->user();

        // Get available tasks
        $availableTasks = [];
        if ($user->isAdmin()) {
            $availableTasks = Task::whereHas('project', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })->with('project')->get();
        } elseif ($user->isManager()) {
            $availableTasks = Task::whereHas('project', function ($query) use ($user) {
                $query->where('manager_id', $user->id)
                      ->where('organization_id', $user->organization_id);
            })->with('project')->get();
        } else {
            $availableTasks = Task::where('assigned_to', $user->id)
                ->where('status', '!=', 'completed')
                ->whereHas('project', function ($query) use ($user) {
                    $query->where('organization_id', $user->organization_id);
                })
                ->with('project')
                ->get();
        }

        $selectedTask = $taskId ? Task::find($taskId) : null;

        return view('timesheet.create', compact('availableTasks', 'selectedTask'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'hours' => 'nullable|numeric|min:0.1|max:24',
            'description' => 'nullable|string|max:1000',
        ]);

        // Prepare data array for the service
        $data = [
            'task_id' => $request->input('task_id'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'comment' => $request->input('description'),
        ];

        // If hours are manually entered, calculate end time
        if ($request->filled('hours')) {
            $startTime = Carbon::parse($request->input('start_time'));
            $data['end_time'] = $startTime->copy()->addHours($request->input('hours'));
        }

        $timeEntry = $this->timeTrackingService->createTimeEntry($data, $request->user());

        return redirect()->route('timesheet.index')
            ->with('success', __('Time entry created successfully'));
    }

    public function show(TimeEntry $timeEntry)
    {
        $this->authorize('view', $timeEntry);

        return view('timesheet.show', compact('timeEntry'));
    }

    public function edit(TimeEntry $timeEntry)
    {
        $this->authorize('update', $timeEntry);

        $user = auth()->user();
        $availableTasks = [];

        if ($user->isAdmin()) {
            $availableTasks = Task::whereHas('project', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })->with('project')->get();
        } elseif ($user->isManager()) {
            $availableTasks = Task::whereHas('project', function ($query) use ($user) {
                $query->where('manager_id', $user->id)
                      ->where('organization_id', $user->organization_id);
            })->with('project')->get();
        } else {
            $availableTasks = Task::where('assigned_to', $user->id)
                ->whereHas('project', function ($query) use ($user) {
                    $query->where('organization_id', $user->organization_id);
                })
                ->with('project')
                ->get();
        }

        return view('timesheet.edit', compact('timeEntry', 'availableTasks'));
    }

    public function update(Request $request, TimeEntry $timeEntry)
    {
        $this->authorize('update', $timeEntry);

        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'hours' => 'nullable|numeric|min:0.1|max:24',
            'description' => 'nullable|string|max:1000',
        ]);

        // Prepare data array for the service
        $data = [
            'task_id' => $request->input('task_id'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'comment' => $request->input('description'),
        ];

        // If hours are manually entered, calculate end time
        if ($request->filled('hours')) {
            $startTime = Carbon::parse($request->input('start_time'));
            $data['end_time'] = $startTime->copy()->addHours($request->input('hours'));
        }

        $timeEntry = $this->timeTrackingService->update($timeEntry, $data, $request->user());

        return redirect()->route('timesheet.index')
            ->with('success', __('Time entry updated successfully'));
    }

    public function destroy(Request $request, TimeEntry $timeEntry)
    {
        $this->authorize('delete', $timeEntry);

        $this->timeTrackingService->delete($timeEntry, $request->user());

        return redirect()->route('timesheet.index')
            ->with('success', __('Time entry deleted successfully'));
    }
}
