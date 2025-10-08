<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TimeEntry;
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
        $filters = $request->only(['task_id', 'start_date', 'end_date']);

        // Set default date range if not provided
        if (empty($filters['start_date'])) {
            $filters['start_date'] = Carbon::now()->startOfWeek()->format('Y-m-d');
        }
        if (empty($filters['end_date'])) {
            $filters['end_date'] = Carbon::now()->endOfWeek()->format('Y-m-d');
        }

        $timeEntries = $this->timeTrackingService->getTimeEntries($filters, $user, 20);

        // Calculate totals
        $totalHours = $timeEntries->sum(function ($entry) {
            return $entry->duration_hours;
        });

        // Get available tasks for time entry
        $availableTasks = [];
        if ($user->isAdmin()) {
            $availableTasks = Task::with('project')->get();
        } elseif ($user->isManager()) {
            $availableTasks = Task::whereHas('project', function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            })->with('project')->get();
        } else {
            $availableTasks = Task::where('assigned_to', $user->id)
                ->with('project')
                ->get();
        }

        return view('timesheet.index', compact(
            'timeEntries',
            'totalHours',
            'availableTasks',
            'filters'
        ));
    }

    public function create(Request $request)
    {
        $taskId = $request->input('task_id');
        $user = $request->user();

        // Get available tasks
        $availableTasks = [];
        if ($user->isAdmin()) {
            $availableTasks = Task::with('project')->get();
        } elseif ($user->isManager()) {
            $availableTasks = Task::whereHas('project', function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            })->with('project')->get();
        } else {
            $availableTasks = Task::where('assigned_to', $user->id)
                ->where('status', '!=', 'completed')
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
            $availableTasks = Task::with('project')->get();
        } elseif ($user->isManager()) {
            $availableTasks = Task::whereHas('project', function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            })->with('project')->get();
        } else {
            $availableTasks = Task::where('assigned_to', $user->id)
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

    public function destroy(TimeEntry $timeEntry)
    {
        $this->authorize('delete', $timeEntry);

        $this->timeTrackingService->deleteTimeEntry($timeEntry);

        return redirect()->route('timesheet.index')
            ->with('success', __('Time entry deleted successfully'));
    }
}
