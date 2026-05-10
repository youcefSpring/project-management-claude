@extends('layouts.sidebar')

@section('title', __('app.reports.time_summary'))
@section('page-title', __('app.reports.time_summary'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clock me-2"></i>
                    {{ __('app.reports.time_summary') }}
                </h5>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('reports.time-tracking') }}" class="row mb-4">
                    <div class="col-md-3">
                        <label for="user_id" class="form-label">{{ __('app.users.title') }}</label>
                        <select class="form-select" id="user_id" name="user_id">
                            <option value="">{{ __('app.reports.all_users') }}</option>
                            @if(isset($data['users']))
                                @foreach($data['users'] as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
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
                    <div class="col-md-2">
                        <label for="start_date" class="form-label">{{ __('app.projects.start_date') }}</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date" class="form-label">{{ __('app.projects.end_date') }}</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search me-1"></i>{{ __('app.filter') }}
                        </button>
                        <a href="{{ route('reports.time-tracking') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>{{ __('app.cancel') }}
                        </a>
                    </div>
                </form>

                <!-- Report Content -->
                @if(isset($data['time_entries']) && count($data['time_entries']) > 0)
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ __('app.time.total_time') }}</h5>
                                    <h3>{{ number_format($data['total_hours'] ?? 0, 1) }}h</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ __('Total Entries') }}</h5>
                                    <h3>{{ count($data['time_entries']) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ __('Avg Daily Hours') }}</h5>
                                    <h3>{{ number_format($data['avg_daily_hours'] ?? 0, 1) }}h</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ __('app.time.billable') }}</h5>
                                    <h3>{{ number_format($data['billable_hours'] ?? 0, 1) }}h</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Time Entries by User -->
                    @if(isset($data['users_summary']) && count($data['users_summary']) > 0)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('Hours by User') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>{{ __('app.users.title') }}</th>
                                                <th>{{ __('app.time.total_time') }}</th>
                                                <th>{{ __('Entries') }}</th>
                                                <th>{{ __('Avg Per Day') }}</th>
                                                <th>{{ __('app.nav.projects') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['users_summary'] as $userSummary)
                                                <tr>
                                                    <td>
                                                        <i class="bi bi-person-circle me-1"></i>
                                                        {{ $userSummary['name'] }}
                                                    </td>
                                                    <td>
                                                        <strong>{{ number_format($userSummary['total_hours'], 1) }}h</strong>
                                                    </td>
                                                    <td>{{ $userSummary['entries_count'] }}</td>
                                                    <td>{{ number_format($userSummary['avg_daily_hours'], 1) }}h</td>
                                                    <td>{{ $userSummary['projects_count'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Detailed Time Entries -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Detailed Time Entries') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('app.date') }}</th>
                                            <th>{{ __('app.users.title') }}</th>
                                            <th>{{ __('app.projects.title') }}</th>
                                            <th>{{ __('app.tasks.title') }}</th>
                                            <th>{{ __('app.time.duration') }}</th>
                                            <th>{{ __('app.description') }}</th>
                                            <th>{{ __('app.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['time_entries'] as $entry)
                                            <tr>
                                                <td>
                                                    @if($entry->start_time)
                                                        @php
                                                            $startTime = is_string($entry->start_time) ? \Carbon\Carbon::parse($entry->start_time) : $entry->start_time;
                                                        @endphp
                                                        {{ $startTime->format('M d, Y') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <i class="bi bi-person-circle me-1"></i>
                                                    {{ $entry->user->name }}
                                                </td>
                                                <td>
                                                    @if($entry->task && $entry->task->project)
                                                        <a href="{{ route('projects.show', $entry->task->project) }}" class="text-decoration-none">
                                                            {{ $entry->task->project->title }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($entry->task)
                                                        <a href="{{ route('tasks.show', $entry->task) }}" class="text-decoration-none">
                                                            {{ Str::limit($entry->task->title, 30) }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        {{ number_format($entry->duration_hours, 1) }}h
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($entry->comment || $entry->description)
                                                        {{ Str::limit($entry->comment ?? $entry->description, 50) }}
                                                    @else
                                                        <span class="text-muted">{{ __('app.description') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($entry->task)
                                                        <a href="{{ route('tasks.show', $entry->task) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
                        <i class="bi bi-clock-history text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">{{ __('app.time.no_entries') }}</h5>
                        <p class="text-muted">{{ __('Try adjusting your filters or start logging time on tasks.') }}</p>
                        <a href="{{ route('timesheet.index') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>{{ __('app.nav.timesheet') }}
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

    const url = `{{ route('reports.time-tracking') }}?${params.toString()}`;
    window.open(url, '_blank');
}
</script>
@endpush
@endsection
