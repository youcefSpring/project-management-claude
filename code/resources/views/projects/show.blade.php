@extends('layouts.sidebar')

@section('title', $project->title)
@section('page-title', $project->title)

@section('content')
<div class="row">
    <!-- Project Header -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <h1 class="mb-0 me-3">{{ $project->title }}</h1>
                            <span class="badge status-{{ $project->status }} status-badge">
                                @switch($project->status)
                                    @case('planning') {{ __('app.projects.planning') }} @break
                                    @case('active') {{ __('app.projects.active') }} @break
                                    @case('on_hold') {{ __('app.projects.on_hold') }} @break
                                    @case('completed') {{ __('app.projects.completed') }} @break
                                    @case('cancelled') {{ __('app.projects.cancelled') }} @break
                                    @default {{ ucfirst($project->status) }}
                                @endswitch
                            </span>
                        </div>
                        <p class="text-muted mb-3">{{ $project->description }}</p>

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <i class="bi bi-person-fill me-2 text-primary"></i>
                                <strong>{{ __('app.projects.manager') }}:</strong> {{ $project->manager->name }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="bi bi-calendar me-2 text-primary"></i>
                                <strong>{{ __('app.projects.start_date') }}:</strong> {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : __('app.not_available') }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="bi bi-calendar-check me-2 text-primary"></i>
                                <strong>{{ __('app.projects.end_date') }}:</strong> {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M d, Y') : __('app.not_available') }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="bi bi-clock me-2 text-primary"></i>
                                <strong>{{ __('app.projects.total_hours') }}:</strong> {{ $stats['total_hours'] }}h
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 text-center">
                        <!-- Progress Chart -->
                        <div class="position-relative d-inline-block mb-3">
                            <canvas id="progressChart" width="150" height="150"></canvas>
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <h3 class="mb-0">{{ $stats['progress_percentage'] }}%</h3>
                                <small class="text-muted">{{ __('app.projects.complete') }}</small>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col-6">
                                <div class="fw-bold text-primary fs-4">{{ $stats['total_tasks'] }}</div>
                                <small class="text-muted">{{ __('app.projects.total_tasks') }}</small>
                            </div>
                            <div class="col-6">
                                <div class="fw-bold text-success fs-4">{{ $stats['completed_tasks'] }}</div>
                                <small class="text-muted">{{ __('app.projects.completed_tasks') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                <div class="mt-3 pt-3 border-top">
                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary me-2">
                        <i class="bi bi-pencil me-2"></i>{{ __('app.projects.edit_project') }}
                    </a>
                    <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}" class="btn btn-outline-success me-2">
                        <i class="bi bi-plus-circle me-2"></i>{{ __('app.projects.add_task') }}
                    </a>
                    <button class="btn btn-outline-warning" onclick="changeProjectStatus()">
                        <i class="bi bi-arrow-repeat me-2"></i>{{ __('app.projects.change_status') }}
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tasks Section -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-check2-square me-2"></i>
                    {{ __('app.projects.project_tasks') }}
                </h5>
                <div>
                    <button class="btn btn-sm btn-outline-secondary me-2" onclick="refreshTasks()">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>{{ __('app.projects.add_task') }}
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <!-- Task Filters -->
                <form method="GET" action="{{ route('projects.show', $project) }}" class="row mb-3">
                    <div class="col-md-4">
                        <select class="form-select form-select-sm" name="status" onchange="this.form.submit()">
                            <option value="">{{ __('app.projects.all_tasks') }}</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('app.tasks.pending') }}</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('app.tasks.in_progress') }}</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('app.tasks.completed') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control form-control-sm" name="search"
                               value="{{ request('search') }}" placeholder="{{ __('app.projects.search_tasks') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Tasks List -->
                <div id="tasks-container">
                    @if($project->tasks->count() > 0)
                        @foreach($project->tasks as $task)
                            <div class="card border-start border-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'warning' : 'secondary') }} border-3 mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-1">
                                                <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none">{{ $task->title }}</a>
                                            </h6>
                                            <p class="text-muted small mb-2">{{ $task->description ?: '' }}</p>
                                            <div class="d-flex align-items-center flex-wrap gap-2">
                                                <span class="badge status-{{ $task->status }} status-badge">
                                                    @switch($task->status)
                                                        @case('pending') {{ __('app.tasks.pending') }} @break
                                                        @case('in_progress') {{ __('app.tasks.in_progress') }} @break
                                                        @case('completed') {{ __('app.tasks.completed') }} @break
                                                        @case('cancelled') {{ __('app.tasks.cancelled') }} @break
                                                        @default {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                    @endswitch
                                                </span>
                                                @if($task->assignedUser)
                                                    <span class="badge bg-light text-dark">
                                                        <i class="bi bi-person me-1"></i>{{ $task->assignedUser->name }}
                                                    </span>
                                                @endif
                                                @if($task->due_date)
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-calendar me-1"></i>{{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('tasks.show', $task) }}">
                                                    <i class="bi bi-eye me-2"></i>{{ __('app.view') }}
                                                </a></li>
                                                @can('update', $task)
                                                <li><a class="dropdown-item" href="{{ route('tasks.edit', $task) }}">
                                                    <i class="bi bi-pencil me-2"></i>{{ __('app.edit') }}
                                                </a></li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-check2-square fs-2"></i>
                            <p class="mt-2">{{ __('app.projects.no_tasks_found') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Team Members -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>
                    {{ __('app.projects.team_members') }}
                </h5>
            </div>
            <div class="card-body">
                <div id="team-members">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                             style="width: 32px; height: 32px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <div class="fw-bold">{{ $project->manager->name }}</div>
                            <small class="text-muted">{{ __('app.projects.project_manager') }}</small>
                        </div>
                    </div>
                    @foreach($allTasks->where('assigned_to', '!=', null)->unique('assigned_to') as $task)
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                             style="width: 32px; height: 32px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <div class="fw-bold">{{ $task->assignedUser->name ?? __('app.unassigned') }}</div>
                            <small class="text-muted">{{ __('app.projects.team_member') }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-activity me-2"></i>
                    {{ __('app.projects.recent_activity') }}
                </h5>
            </div>
            <div class="card-body">
                <div id="project-activity">
                    <!-- Activity will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    initProgressChart();
    loadProjectActivity();
});

function initProgressChart() {
    const ctx = document.getElementById('progressChart').getContext('2d');
    const progress = {{ $stats['progress_percentage'] }};

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [progress, 100 - progress],
                backgroundColor: ['#198754', '#e9ecef'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                }
            },
            responsive: false,
            maintainAspectRatio: false
        }
    });
}


function loadProjectActivity() {
    const container = document.getElementById('project-activity');

    // Show loader
    container.innerHTML = `
        <div class="d-flex justify-content-center align-items-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span class="ms-2 text-muted small">{{ __('app.projects.loading_activity') }}</span>
        </div>
    `;

    // Load project activity (using static data for now, but structure is ready for API)
    setTimeout(() => {
        container.innerHTML = `
            <div class="small">
                <div class="d-flex mb-3">
                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                         style="width: 24px; height: 24px;">
                        <i class="bi bi-check" style="font-size: 0.7rem;"></i>
                    </div>
                    <div>
                        <div>{{ __('app.projects.task_completed') }}</div>
                        <small class="text-muted">{{ __('app.projects.hours_ago', ['hours' => 2]) }}</small>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                         style="width: 24px; height: 24px;">
                        <i class="bi bi-plus" style="font-size: 0.7rem;"></i>
                    </div>
                    <div>
                        <div>{{ __('app.projects.new_task_added') }}</div>
                        <small class="text-muted">{{ __('app.projects.hours_ago', ['hours' => 5]) }}</small>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                         style="width: 24px; height: 24px;">
                        <i class="bi bi-clock" style="font-size: 0.7rem;"></i>
                    </div>
                    <div>
                        <div>{{ __('app.projects.time_logged') }}</div>
                        <small class="text-muted">{{ __('app.projects.day_ago') }}</small>
                    </div>
                </div>
            </div>
        `;
    }, 600);
}
</script>
@endpush