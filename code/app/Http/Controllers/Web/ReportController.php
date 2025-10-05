<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewReports', auth()->user());

        $user = $request->user();

        // Get data for filter dropdowns
        $projects = [];
        $users = [];

        if ($user->isAdmin()) {
            $projects = Project::all();
            $users = User::all();
        } elseif ($user->isManager()) {
            $projects = Project::where('manager_id', $user->id)->get();
            $users = User::whereHas('assignedTasks.project', function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            })->get();
        }

        return view('reports.index', compact('projects', 'users'));
    }

    public function projects(Request $request)
    {
        $this->authorize('viewReports', auth()->user());

        $filters = $request->only(['start_date', 'end_date', 'project_id']);
        $data = $this->reportService->generateTeamReport($request->user(), $filters);

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('reports.projects', compact('data', 'filters'));
    }

    public function users(Request $request)
    {
        $this->authorize('viewReports', auth()->user());

        $filters = $request->only(['start_date', 'end_date', 'user_id', 'project_id']);
        $data = $this->reportService->getUsersReport($request->user(), $filters);

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('reports.users', compact('data', 'filters'));
    }

    public function timeTracking(Request $request)
    {
        $filters = $request->only(['user_id', 'project_id', 'start_date', 'end_date']);

        // If no user_id specified and user is not admin/manager, show their own data
        if (empty($filters['user_id']) && ! ($request->user()->isAdmin() || $request->user()->isManager())) {
            $filters['user_id'] = $request->user()->id;
        }

        // For time tracking report, use the user time report if user_id is specified
        if (!empty($filters['user_id'])) {
            $data = $this->reportService->generateUserTimeReport($filters['user_id'], $request->user(), $filters);
        } else {
            // Generate team report for admins/managers
            $data = $this->reportService->generateTeamReport($request->user(), $filters);
        }

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('reports.time-tracking', compact('data', 'filters'));
    }
}
