@extends('layouts.app')

@section('title', __('Projects'))
@section('page-title', __('Projects'))

@section('content')
<div class="row">
    <!-- Header Actions -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">{{ __('Projects') }}</h2>
                <p class="text-muted mb-0">{{ __('Manage and track your projects') }}</p>
            </div>
            <div>
                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    {{ __('New Project') }}
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('projects.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="status" class="form-label">{{ __('Status') }}</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="planning" {{ request('status') === 'planning' ? 'selected' : '' }}>
                                {{ __('Planning') }}
                            </option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                {{ __('Active') }}
                            </option>
                            <option value="on_hold" {{ request('status') === 'on_hold' ? 'selected' : '' }}>
                                {{ __('On Hold') }}
                            </option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                {{ __('Completed') }}
                            </option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                {{ __('Cancelled') }}
                            </option>
                        </select>
                    </div>

                    @if(auth()->user()->isAdmin())
                    <div class="col-md-4">
                        <label for="manager_id" class="form-label">{{ __('Manager') }}</label>
                        <select class="form-select" id="manager_id" name="manager_id">
                            <option value="">{{ __('All Managers') }}</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" {{ request('manager_id') == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-md-4">
                        <label for="search" class="form-label">{{ __('Search') }}</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="{{ __('Search projects...') }}">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search me-2"></i>
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-x-circle me-2"></i>
                            {{ __('Clear') }}
                        </a>
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
                    {{ __('Projects') }}
                    <span class="badge bg-secondary ms-2">{{ $projects->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($projects->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('Project') }}</th>
                                    <th>{{ __('Manager') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Dates') }}</th>
                                    <th>{{ __('Progress') }}</th>
                                    <th>{{ __('Tasks') }}</th>
                                    <th>{{ __('Actions') }}</th>
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
                                                <span>{{ $project->manager->name ?? __('No manager') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $project->status === 'active' ? 'success' : ($project->status === 'completed' ? 'primary' : 'warning') }}">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                @if($project->start_date && $project->end_date)
                                                    {{ \Carbon\Carbon::parse($project->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($project->end_date)->format('M d, Y') }}
                                                @else
                                                    {{ __('No dates set') }}
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
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('projects.show', $project) }}">
                                                            <i class="bi bi-eye me-2"></i>{{ __('View') }}
                                                        </a>
                                                    </li>
                                                    @can('update', $project)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('projects.edit', $project) }}">
                                                                <i class="bi bi-pencil me-2"></i>{{ __('Edit') }}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('tasks.create') }}?project_id={{ $project->id }}">
                                                            <i class="bi bi-plus-circle me-2"></i>{{ __('Add Task') }}
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
                        <h5 class="text-muted">{{ __('No projects found') }}</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['status', 'manager_id', 'search']))
                                {{ __('Try adjusting your filters or') }}
                                <a href="{{ route('projects.index') }}">{{ __('clear all filters') }}</a>
                            @else
                                {{ __('Get started by creating your first project.') }}
                            @endif
                        </p>
                        @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                            <a href="{{ route('projects.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                {{ __('Create Project') }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection