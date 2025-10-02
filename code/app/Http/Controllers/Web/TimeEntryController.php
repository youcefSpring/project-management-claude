<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use App\Models\Task;
use App\Services\TimeTrackingService;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        if (!$filters['start_date']) {
            $filters['start_date'] = Carbon::now()->startOfWeek()->format('Y-m-d');
        }
        if (!$filters['end_date']) {
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
                ->where('status', '!=', 'fait')
                ->with('project')
                ->get();
        }

        $selectedTask = $taskId ? Task::find($taskId) : null;

        return view('timesheet.create', compact('availableTasks', 'selectedTask'));
    }

    public function edit(TimeEntry $timeEntry)
    {
        $this->authorize('update', $timeEntry);

        return view('timesheet.edit', compact('timeEntry'));
    }
}