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

        // Get overview data for dashboard cards
        $overview = $this->reportService->generateOverview($user);

        return view('reports.index', compact('projects', 'users', 'overview'));
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
        if (! empty($filters['user_id'])) {
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

    public function export(Request $request)
    {
        $this->authorize('viewReports', auth()->user());

        $user = $request->user();
        $format = $request->get('format', 'csv');
        $type = $request->get('type', 'overview');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $overview = $this->reportService->generateOverview($user);

        $data = [
            'report_type' => $type,
            'overview' => $overview,
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'user' => $user->name,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];

        $filename = 'report_'.now()->format('Y-m-d_His').'.'.$format;

        $content = 'Report Generated: '.now()->format('Y-m-d H:i:s')."\n";
        $content .= 'Report Type: '.$type."\n";
        $content .= 'User: '.$user->name."\n";
        if ($dateFrom && $dateTo) {
            $content .= 'Date Range: '.$dateFrom.' to '.$dateTo."\n";
        }
        $content .= "\nOverview\n";
        $content .= "--------\n";
        foreach ($overview as $key => $value) {
            $content .= $key.': '.$value."\n";
        }

        $mimeType = match ($format) {
            'pdf' => 'application/pdf',
            'excel' => 'application/vnd.ms-excel',
            default => 'text/csv',
        };

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
