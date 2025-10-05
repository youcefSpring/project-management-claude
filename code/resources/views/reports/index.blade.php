@extends('layouts.app')

@section('title', __('Reports'))
@section('page-title', __('Reports & Analytics'))

@section('content')
<div class="row">
    <!-- Overview Cards -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">{{ __('Total Projects') }}</h6>
                                <h3 class="mb-0">{{ $overview['total_projects'] ?? 0 }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-folder fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">{{ __('Total Tasks') }}</h6>
                                <h3 class="mb-0">{{ $overview['total_tasks'] ?? 0 }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check2-square fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">{{ __('Total Hours') }}</h6>
                                <h3 class="mb-0">{{ number_format($overview['total_hours'] ?? 0, 1) }}h</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-clock fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">{{ __('Active Users') }}</h6>
                                <h3 class="mb-0">{{ $overview['active_users'] ?? 0 }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-people fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Links -->
    <div class="col-12">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-folder-fill text-primary fs-1 mb-3"></i>
                        <h5 class="card-title">{{ __('Project Reports') }}</h5>
                        <p class="card-text">{{ __('Detailed project performance, progress tracking, and resource allocation analysis.') }}</p>
                        <a href="{{ route('reports.projects') }}" class="btn btn-primary">
                            {{ __('View Project Reports') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill text-success fs-1 mb-3"></i>
                        <h5 class="card-title">{{ __('User Reports') }}</h5>
                        <p class="card-text">{{ __('User productivity, time allocation, and performance metrics across all projects.') }}</p>
                        <a href="{{ route('reports.users') }}" class="btn btn-success">
                            {{ __('View User Reports') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-clock-fill text-warning fs-1 mb-3"></i>
                        <h5 class="card-title">{{ __('Time Tracking Reports') }}</h5>
                        <p class="card-text">{{ __('Comprehensive time tracking analysis, billable hours, and efficiency reports.') }}</p>
                        <a href="{{ route('reports.time-tracking') }}" class="btn btn-warning">
                            {{ __('View Time Reports') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Reports Section -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Quick Reports') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>{{ __('Recent Activity') }}</h6>
                        <div class="list-group list-group-flush">
                            @if(isset($recent_activity) && count($recent_activity) > 0)
                                @foreach($recent_activity as $activity)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $activity['title'] ?? 'Activity' }}</h6>
                                                <p class="mb-1 small">{{ $activity['description'] ?? '' }}</p>
                                                <small class="text-muted">{{ $activity['date'] ?? '' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="list-group-item">
                                    <p class="text-muted mb-0">{{ __('No recent activity found.') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6>{{ __('Project Status Overview') }}</h6>
                        @if(isset($project_status) && count($project_status) > 0)
                            @foreach($project_status as $status => $count)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-{{ $status === 'active' ? 'success' : ($status === 'completed' ? 'primary' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                    <span>{{ $count }} {{ __('projects') }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">{{ __('No project data available.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection