@extends('layouts.sidebar')

@section('title', __('app.reports.project_summary'))
@section('page-title', __('app.reports.project_summary'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-folder me-2"></i>
                    {{ __('app.reports.project_summary') }}
                </h5>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('reports.projects') }}" class="row mb-4">
                    <div class="col-md-3">
                        <label for="project_id" class="form-label">{{ __('app.projects.title') }}</label>
                        <select class="form-select" id="project_id" name="project_id">
                            <option value="">{{ __('app.reports.all_projects') }}</option>
                            @if(isset($data['projects']))
                                @foreach($data['projects'] as $project)
                                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->title }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">{{ __('app.projects.start_date') }}</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">{{ __('app.projects.end_date') }}</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search me-1"></i>{{ __('app.filter') }}
                        </button>
                        <a href="{{ route('reports.projects') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>{{ __('app.cancel') }}
                        </a>
                    </div>
                </form>

                <!-- Report Content -->
                @if(isset($data['projects']) && count($data['projects']) > 0)
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ __('app.dashboard.total_projects') }}</h5>
                                    <h3>{{ count($data['projects']) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ __('app.tasks.title') }}</h5>
                                    <h3>{{ $data['total_tasks'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ __('app.time.total_time') }}</h5>
                                    <h3>{{ number_format($data['total_hours'] ?? 0, 1) }}h</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ __('Avg Completion') }}</h5>
                                    <h3>{{ number_format($data['avg_completion'] ?? 0, 1) }}%</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Projects Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('app.projects.title') }}</th>
                                    <th>{{ __('app.projects.manager') }}</th>
                                    <th>{{ __('app.status') }}</th>
                                    <th>{{ __('app.tasks.title') }}</th>
                                    <th>{{ __('Completion') }}</th>
                                    <th>{{ __('app.time.total_time') }}</th>
                                    <th>{{ __('Team Size') }}</th>
                                    <th>{{ __('app.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['projects'] as $project)
                                    <tr>
                                        <td>
                                            <strong>{{ $project->title }}</strong>
                                            @if($project->description)
                                                <br><small class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($project->manager)
                                                <i class="bi bi-person-circle me-1"></i>
                                                {{ $project->manager->name }}
                                            @else
                                                <span class="text-muted">{{ __('app.projects.manager') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $project->status === 'active' ? 'success' : ($project->status === 'completed' ? 'primary' : 'warning') }}">
                                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $totalTasks = $project->tasks->count();
                                                $completedTasks = $project->tasks->where('status', 'completed')->count();
                                            @endphp
                                            {{ $completedTasks }}/{{ $totalTasks }}
                                        </td>
                                        <td>
                                            @php
                                                $completion = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                                            @endphp
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $completion }}%" aria-valuenow="{{ $completion }}" aria-valuemin="0" aria-valuemax="100">
                                                    {{ number_format($completion, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $projectHours = $project->tasks->flatMap->timeEntries->sum('duration_hours');
                                            @endphp
                                            <i class="bi bi-clock me-1"></i>
                                            {{ number_format($projectHours, 1) }}h
                                        </td>
                                        <td>
                                            @php
                                                $teamSize = $project->tasks->pluck('assigned_to')->filter()->unique()->count();
                                            @endphp
                                            <i class="bi bi-people me-1"></i>
                                            {{ $teamSize }}
                                        </td>
                                        <td>
                                            <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i>{{ __('app.View all') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Export Options -->
                    <div class="mt-4">
                        <h6>{{ __('app.export') }}</h6>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-success" onclick="exportReport('csv')">
                                <i class="bi bi-file-earmark-spreadsheet me-1"></i>{{ __('app.reports.export_excel') }}
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="exportReport('pdf')">
                                <i class="bi bi-file-earmark-pdf me-1"></i>{{ __('app.reports.export_pdf') }}
                            </button>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-folder-x text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">{{ __('app.projects.no_projects') }}</h5>
                        <p class="text-muted">{{ __('Try adjusting your filters or create a new project.') }}</p>
                        <a href="{{ route('projects.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>{{ __('app.projects.create') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportReport(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);

    const url = `{{ route('reports.projects') }}?${params.toString()}`;
    window.open(url, '_blank');
}
</script>
@endpush
@endsection
