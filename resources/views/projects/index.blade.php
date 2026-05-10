@extends('layouts.sidebar')

@section('title', __('app.projects.title'))
@section('page-title', __('app.projects.title'))

@section('content')
<div class="row">
    <!-- Header Actions -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">{{ __('app.projects.title') }}</h2>
                <p class="text-muted mb-0">{{ __('app.projects.manage_and_track') }}</p>
            </div>
            <div>
                @can('create', App\Models\Project::class)
                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    {{ __('app.projects.new_project') }}
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-muted">
                    <i class="bi bi-funnel me-2"></i>{{ __('app.projects.project_filters') }}
                </h6>
                <button type="button" id="toggleFilters" class="btn btn-sm btn-outline-secondary" title="{{ __('app.toggle_filters') }}">
                    <i class="bi bi-chevron-up" id="toggleFiltersIcon"></i>
                </button>
            </div>
            <div class="card-body p-3" id="filtersContent">
                <form method="GET" action="{{ route('projects.index') }}" class="row g-3 align-items-end">
                    <div class="{{ auth()->user()->isAdmin() ? 'col-md-3' : 'col-md-4' }}">
                        <label for="status" class="form-label small text-muted">{{ __('app.status') }}</label>
                        <select class="form-select form-select-sm" id="status" name="status">
                            <option value="">{{ __('app.projects.all_statuses') }}</option>
                            <option value="planning" {{ request('status') === 'planning' ? 'selected' : '' }}>
                                {{ __('app.projects.planning') }}
                            </option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                {{ __('app.projects.active') }}
                            </option>
                            <option value="on_hold" {{ request('status') === 'on_hold' ? 'selected' : '' }}>
                                {{ __('app.projects.on_hold') }}
                            </option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                {{ __('app.projects.completed') }}
                            </option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                {{ __('app.projects.cancelled') }}
                            </option>
                        </select>
                    </div>

                    @if(auth()->user()->isAdmin())
                    <div class="col-md-3">
                        <label for="manager_id" class="form-label small text-muted">{{ __('app.projects.manager') }}</label>
                        <select class="form-select form-select-sm" id="manager_id" name="manager_id">
                            <option value="">{{ __('app.all_managers') }}</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" {{ request('manager_id') == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="{{ auth()->user()->isAdmin() ? 'col-md-4' : 'col-md-6' }}">
                        <label for="search" class="form-label small text-muted">{{ __('app.search') }}</label>
                        <input type="text" class="form-control form-control-sm" id="search" name="search"
                               value="{{ request('search') }}" placeholder="{{ __('app.projects.search_placeholder') }}">
                    </div>

                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Projects List -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    {{ __('app.projects.title') }}
                    <span class="badge bg-secondary ms-2">{{ $projects->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($projects->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('app.projects.name') }}</th>
                                    <th>{{ __('app.projects.manager') }}</th>
                                    <th>{{ __('app.status') }}</th>
                                    <th>{{ __('app.projects.dates') }}</th>
                                    <th>{{ __('app.projects.progress') }}</th>
                                    <th>{{ __('app.tasks.title') }}</th>
                                    <th>{{ __('app.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $project)
                                    @php
                                        $tasksCount = $project->tasks_count ?? $project->tasks->count();
                                        $completedTasks = $project->completed_tasks_count ?? $project->tasks->where('status', 'completed')->count();
                                        $progress = $tasksCount > 0 ? round(($completedTasks / $tasksCount) * 100) : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div>
                                                <a href="{{ route('projects.show', $project) }}" class="fw-bold text-decoration-none">
                                                    {{ $project->title }}
                                                </a>
                                                @if($project->description)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($project->description, 80) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                     style="width: 32px; height: 32px;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <span>{{ $project->manager->name ?? __('app.no_manager') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $project->status === 'active' ? 'success' : ($project->status === 'completed' ? 'primary' : 'warning') }}">
                                                @switch($project->status)
                                                    @case('planning') {{ __('app.projects.planning') }} @break
                                                    @case('active') {{ __('app.projects.active') }} @break
                                                    @case('on_hold') {{ __('app.projects.on_hold') }} @break
                                                    @case('completed') {{ __('app.projects.completed') }} @break
                                                    @case('cancelled') {{ __('app.projects.cancelled') }} @break
                                                    @default {{ ucfirst($project->status) }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                @if($project->start_date && $project->end_date)
                                                    {{ \Carbon\Carbon::parse($project->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($project->end_date)->format('M d, Y') }}
                                                @else
                                                    {{ __('app.no_dates_set') }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <div style="min-width: 120px;">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <small class="text-muted">{{ $progress }}%</small>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-{{ $progress >= 75 ? 'success' : ($progress >= 50 ? 'warning' : 'danger') }}"
                                                         style="width: {{ $progress }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $completedTasks }}/{{ $tasksCount }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown dropstart">
                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 160px;">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('projects.show', $project) }}">
                                                            <i class="bi bi-eye me-2"></i>{{ __('app.projects.view') }}
                                                        </a>
                                                    </li>
                                                    @can('update', $project)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('projects.edit', $project) }}">
                                                                <i class="bi bi-pencil me-2"></i>{{ __('app.edit') }}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('tasks.create') }}?project_id={{ $project->id }}">
                                                            <i class="bi bi-plus-circle me-2"></i>{{ __('app.projects.add_task') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-folder-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('app.projects.no_projects') }}</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['status', 'manager_id', 'search']))
                                {{ __('app.try_adjusting_filters') }}
                                <a href="{{ route('projects.index') }}">{{ __('app.clear_filters') }}</a>
                            @else
                                {{ __('app.projects.get_started_message') }}
                            @endif
                        </p>
                        @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                            <a href="{{ route('projects.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                {{ __('app.projects.create') }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    setupFiltersToggle();
});

function setupFiltersToggle() {
    const toggleBtn = document.getElementById('toggleFilters');
    const filtersContent = document.getElementById('filtersContent');
    const toggleIcon = document.getElementById('toggleFiltersIcon');

    // Check localStorage for saved state (default: visible)
    const isHidden = localStorage.getItem('projectFiltersHidden') === 'true';

    if (isHidden) {
        filtersContent.style.display = 'none';
        toggleIcon.className = 'bi bi-chevron-down';
    } else {
        filtersContent.style.display = 'block';
        toggleIcon.className = 'bi bi-chevron-up';
    }

    toggleBtn.addEventListener('click', function() {
        const isCurrentlyVisible = filtersContent.style.display !== 'none';

        if (isCurrentlyVisible) {
            // Hide filters
            filtersContent.style.display = 'none';
            toggleIcon.className = 'bi bi-chevron-down';
            localStorage.setItem('projectFiltersHidden', 'true');
        } else {
            // Show filters
            filtersContent.style.display = 'block';
            toggleIcon.className = 'bi bi-chevron-up';
            localStorage.setItem('projectFiltersHidden', 'false');
        }
    });
}
</script>

<style>
/* Fix dropdown positioning and prevent overflow issues */
.table-responsive {
    overflow-x: auto;
}

.dropdown-menu {
    border: 1px solid rgba(0,0,0,.15);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
    z-index: 1021;
}

/* Ensure dropdowns don't break table layout */
.dropdown {
    position: static;
}

@media (max-width: 768px) {
    .dropdown.dropstart .dropdown-menu {
        --bs-position: absolute;
        inset: 0px auto auto 0px !important;
        transform: translate(-100%, 0px) !important;
    }
}

/* Fix for small screens */
@media (max-width: 576px) {
    .dropdown.dropstart {
        position: static;
    }

    .dropdown.dropstart .dropdown-menu {
        position: absolute !important;
        right: 0 !important;
        left: auto !important;
        transform: none !important;
    }
}
</style>
@endpush