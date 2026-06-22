<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use App\Services\ReportService;
use Carbon\Carbon;
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
        // The index view's 4th card reads 'active_users'; the service exposes 'total_users'.
        $overview['active_users'] = $overview['active_users'] ?? ($overview['total_users'] ?? 0);

        return view('reports.index', compact('projects', 'users', 'overview'));
    }

    /** Date range from filters, defaulting to the current month. */
    private function range(array $filters): array
    {
        $start = ! empty($filters['start_date']) ? Carbon::parse($filters['start_date'])->startOfDay() : Carbon::now()->startOfMonth();
        $end = ! empty($filters['end_date']) ? Carbon::parse($filters['end_date'])->endOfDay() : Carbon::now()->endOfMonth();

        return [$start, $end];
    }

    /** Projects this user may report on. */
    private function scopedProjects(User $user)
    {
        return $user->isAdmin() ? Project::query() : Project::where('manager_id', $user->id);
    }

    public function projects(Request $request)
    {
        $this->authorize('viewReports', auth()->user());

        $filters = $request->only(['start_date', 'end_date', 'project_id']);
        $user = $request->user();

        $query = $this->scopedProjects($user)->with(['manager:id,name', 'tasks.timeEntries']);
        if (! empty($filters['project_id'])) {
            $query->where('id', $filters['project_id']);
        }
        $projects = $query->get();

        $totalTasks = $projects->sum(fn ($p) => $p->tasks->count());
        $totalHours = $projects->sum(fn ($p) => $p->tasks->flatMap->timeEntries->sum('duration_hours'));
        $avgCompletion = $projects->isEmpty() ? 0 : round($projects->map(function ($p) {
            $t = $p->tasks->count();

            return $t ? ($p->tasks->where('status', 'completed')->count() / $t) * 100 : 0;
        })->avg(), 1);

        $data = [
            'projects' => $projects,
            'total_tasks' => $totalTasks,
            'total_hours' => $totalHours,
            'avg_completion' => $avgCompletion,
        ];

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('reports.projects', compact('data', 'filters'));
    }

    public function users(Request $request)
    {
        $this->authorize('viewReports', auth()->user());

        $filters = $request->only(['start_date', 'end_date', 'user_id', 'project_id']);
        $user = $request->user();
        [$start, $end] = $this->range($filters);

        $users = $user->isAdmin()
            ? User::all()
            : User::whereHas('assignedTasks.project', fn ($q) => $q->where('manager_id', $user->id))->get();

        $rows = $users->map(function (User $u) use ($start, $end) {
            $tasks = Task::where('assigned_to', $u->id)->get();
            $hours = TimeEntry::where('user_id', $u->id)
                ->whereBetween('start_time', [$start, $end])->sum('duration_hours');

            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'role' => $u->role,
                'total_tasks' => $tasks->count(),
                'completed_tasks' => $tasks->where('status', 'completed')->count(),
                'total_hours' => (float) $hours,
            ];
        })->values();

        $data = [
            'users' => $rows,
            'projects' => $this->scopedProjects($user)->get(['id', 'title']),
        ];

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('reports.users', compact('data', 'filters'));
    }

    public function timeTracking(Request $request)
    {
        $filters = $request->only(['user_id', 'project_id', 'start_date', 'end_date']);
        $user = $request->user();
        [$start, $end] = $this->range($filters);
        $isPrivileged = $user->isAdmin() || $user->isManager();

        // Non-privileged users only ever see their own entries.
        if (! $isPrivileged) {
            $filters['user_id'] = $user->id;
        }

        $query = TimeEntry::with(['user:id,name', 'task.project'])
            ->whereBetween('start_time', [$start, $end]);

        if ($user->isManager() && ! $user->isAdmin()) {
            $query->whereHas('task.project', fn ($q) => $q->where('manager_id', $user->id));
        }
        if (! empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }
        if (! empty($filters['project_id'])) {
            $query->whereHas('task.project', fn ($q) => $q->where('id', $filters['project_id']));
        }

        $entries = $query->orderByDesc('start_time')->get();

        $totalHours = (float) $entries->sum('duration_hours');
        $days = max(1, $start->diffInDays($end) + 1);

        $usersSummary = $entries->groupBy('user_id')->map(function ($group) {
            $activeDays = $group->pluck('start_time')
                ->map(fn ($d) => Carbon::parse($d)->format('Y-m-d'))->unique()->count() ?: 1;
            $hours = (float) $group->sum('duration_hours');

            return [
                'name' => optional($group->first()->user)->name ?? '—',
                'total_hours' => $hours,
                'entries_count' => $group->count(),
                'avg_daily_hours' => round($hours / $activeDays, 1),
                'projects_count' => $group->pluck('task.project.id')->filter()->unique()->count(),
            ];
        })->values();

        $data = [
            'time_entries' => $entries,
            'users_summary' => $usersSummary,
            'total_hours' => $totalHours,
            'billable_hours' => $totalHours,
            'avg_daily_hours' => round($totalHours / $days, 1),
            'users' => $isPrivileged
                ? User::where('organization_id', $user->organization_id)->get(['id', 'name'])
                : collect([$user]),
            'projects' => $this->scopedProjects($user)->get(['id', 'title']),
        ];

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('reports.time-tracking', compact('data', 'filters'));
    }

    public function export(Request $request)
    {
        $this->authorize('viewReports', auth()->user());

        $user = $request->user();
        // Per-page buttons send ?export=<fmt>; the modal sends ?format=<fmt>.
        $format = strtolower($request->get('format', $request->get('export', 'csv')));
        $type = $request->get('type', 'overview');
        $filters = [
            'start_date' => $request->get('start_date', $request->get('date_from')),
            'end_date' => $request->get('end_date', $request->get('date_to')),
            'project_id' => $request->get('project_id'),
            'user_id' => $request->get('user_id'),
        ];

        $table = $this->buildExportTable($type, $user, $filters);
        $base = 'report_'.$type.'_'.now()->format('Y-m-d_His');

        return match ($format) {
            'pdf' => $this->exportPdf($table, $base),
            'excel', 'xls' => $this->exportExcel($table, $base),
            default => $this->exportCsv($table, $base),
        };
    }

    /** Build {title, headers, rows[]} for the requested report type. */
    private function buildExportTable(string $type, User $user, array $filters): array
    {
        [$start, $end] = $this->range($filters);

        if ($type === 'projects' || $type === 'tasks') {
            $projects = $this->scopedProjects($user)->with(['manager:id,name', 'tasks.timeEntries'])->get();

            return [
                'title' => __('app.reports.export_title.projects'),
                'headers' => [
                    __('app.reports.col.project'), __('app.reports.col.manager'), __('app.reports.col.status'),
                    __('app.reports.col.tasks'), __('app.reports.col.completed'), __('app.reports.col.completion'),
                    __('app.reports.col.hours'), __('app.reports.col.team'),
                ],
                'rows' => $projects->map(function ($p) {
                    $total = $p->tasks->count();
                    $done = $p->tasks->where('status', 'completed')->count();

                    return [
                        $p->title,
                        optional($p->manager)->name ?? '-',
                        ucfirst(str_replace('_', ' ', $p->status)),
                        $total,
                        $done,
                        ($total ? round($done / $total * 100, 1) : 0).'%',
                        round($p->tasks->flatMap->timeEntries->sum('duration_hours'), 1).'h',
                        $p->tasks->pluck('assigned_to')->filter()->unique()->count(),
                    ];
                })->all(),
            ];
        }

        if ($type === 'users') {
            $users = $user->isAdmin()
                ? User::all()
                : User::whereHas('assignedTasks.project', fn ($q) => $q->where('manager_id', $user->id))->get();

            return [
                'title' => __('app.reports.export_title.users'),
                'headers' => [
                    __('app.reports.col.name'), __('app.reports.col.email'), __('app.reports.col.role'),
                    __('app.reports.col.tasks'), __('app.reports.col.completed'), __('app.reports.col.hours'),
                ],
                'rows' => $users->map(function (User $u) use ($start, $end) {
                    $tasks = Task::where('assigned_to', $u->id)->get();

                    return [
                        $u->name,
                        $u->email,
                        ucfirst($u->role),
                        $tasks->count(),
                        $tasks->where('status', 'completed')->count(),
                        round(TimeEntry::where('user_id', $u->id)->whereBetween('start_time', [$start, $end])->sum('duration_hours'), 1).'h',
                    ];
                })->all(),
            ];
        }

        if ($type === 'time') {
            $query = TimeEntry::with(['user:id,name', 'task.project'])->whereBetween('start_time', [$start, $end]);
            if ($user->isManager() && ! $user->isAdmin()) {
                $query->whereHas('task.project', fn ($q) => $q->where('manager_id', $user->id));
            } elseif (! $user->isAdmin() && ! $user->isManager()) {
                $query->where('user_id', $user->id);
            }

            return [
                'title' => __('app.reports.export_title.time'),
                'headers' => [
                    __('app.reports.col.date'), __('app.reports.col.user'), __('app.reports.col.project'),
                    __('app.reports.col.task'), __('app.reports.col.hours'), __('app.reports.col.description'),
                ],
                'rows' => $query->orderByDesc('start_time')->get()->map(fn ($e) => [
                    optional($e->start_time)->format('Y-m-d') ?? '-',
                    optional($e->user)->name ?? '-',
                    optional(optional($e->task)->project)->title ?? '-',
                    optional($e->task)->title ?? '-',
                    round($e->duration_hours, 1).'h',
                    $e->comment ?? '',
                ])->all(),
            ];
        }

        // overview (default)
        $overview = $this->reportService->generateOverview($user);

        return [
            'title' => __('app.reports.export_title.overview'),
            'headers' => [__('app.reports.col.metric'), __('app.reports.col.value')],
            'rows' => collect($overview)->map(fn ($v, $k) => [ucfirst(str_replace('_', ' ', $k)), $v])->values()->all(),
        ];
    }

    private function exportCsv(array $table, string $base)
    {
        return response()->streamDownload(function () use ($table) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $table['headers']);
            foreach ($table['rows'] as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $base.'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Excel via an HTML table (.xls) — opens natively in Excel/LibreOffice, no extra dependency. */
    private function exportExcel(array $table, string $base)
    {
        $html = '<table border="1"><thead><tr>';
        foreach ($table['headers'] as $h) {
            $html .= '<th>'.e($h).'</th>';
        }
        $html .= '</tr></thead><tbody>';
        foreach ($table['rows'] as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>'.e($cell).'</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$base.'.xls"',
        ]);
    }

    private function exportPdf(array $table, string $base)
    {
        $dir = app()->getLocale() === 'ar' ? 'rtl' : 'ltr';
        $align = $dir === 'rtl' ? 'right' : 'left';
        $html = '<html dir="'.$dir.'"><head><meta charset="utf-8"><style>'
            .'body{font-family:DejaVu Sans, sans-serif;font-size:11px;color:#222;direction:'.$dir.';}'
            .'h2{color:#4f46e5;margin:0 0 4px;} .meta{color:#666;font-size:10px;margin-bottom:12px;}'
            .'table{width:100%;border-collapse:collapse;} th{background:#4f46e5;color:#fff;text-align:'.$align.';padding:6px;}'
            .'td{padding:6px;border-bottom:1px solid #e5e7eb;text-align:'.$align.';} tr:nth-child(even) td{background:#f8f9fb;}'
            .'</style></head><body>';
        $html .= '<h2>'.e($table['title']).'</h2>';
        $html .= '<div class="meta">'.e(config('app.name')).' — '.now()->format('Y-m-d H:i').'</div>';
        $html .= '<table><thead><tr>';
        foreach ($table['headers'] as $h) {
            $html .= '<th>'.e($h).'</th>';
        }
        $html .= '</tr></thead><tbody>';
        foreach ($table['rows'] as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>'.e($cell).'</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table></body></html>';

        $dompdf = new \Dompdf\Dompdf(['isRemoteEnabled' => false]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$base.'.pdf"',
        ]);
    }
}
