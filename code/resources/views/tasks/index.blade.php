@extends('layouts.app')

@section('title', __('Tasks'))
@section('page-title', __('Tasks'))

@section('content')
<div class="row">
    <!-- Header Actions -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">{{ __('Tasks') }}</h2>
                <p class="text-muted mb-0">{{ __('Manage and track your tasks') }}</p>
            </div>
            <div>
                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    {{ __('New Task') }}
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('tasks.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">{{ __('Status') }}</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                {{ __('Pending') }}
                            </option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>
                                {{ __('In Progress') }}
                            </option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                {{ __('Completed') }}
                            </option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                {{ __('Cancelled') }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="project_id" class="form-label">{{ __('Project') }}</label>
                        <select class="form-select" id="project_id" name="project_id">
                            <option value="">{{ __('All Projects') }}</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <div class="col-md-3">
                        <label for="assigned_to" class="form-label">{{ __('Assigned To') }}</label>
                        <select class="form-select" id="assigned_to" name="assigned_to">
                            <option value="">{{ __('All Users') }}</option>
                            <option value="unassigned" {{ request('assigned_to') === 'unassigned' ? 'selected' : '' }}>
                                {{ __('Unassigned') }}
                            </option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-md-3">
                        <label for="search" class="form-label">{{ __('Search') }}</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="{{ __('Search tasks...') }}">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search me-2"></i>
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-x-circle me-2"></i>
                            {{ __('Clear') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    {{ __('Tasks') }}
                    <span class="badge bg-secondary ms-2">{{ $tasks->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($tasks->count() > 0)
                    <div class="row">
                        @php
                            $groupedTasks = $tasks->groupBy('status');
                        @endphp

                        <!-- Pending Tasks -->
                        <div class="col-md-4">
                            <h6 class="text-warning mb-3">
                                <i class="bi bi-clock me-1"></i>
                                {{ __('Pending') }}
                                <span class="badge bg-warning text-dark">{{ $groupedTasks->get('pending', collect())->count() }}</span>
                            </h6>
                            @foreach($groupedTasks->get('pending', collect()) as $task)
                                <div class="card border-start border-warning border-3 mb-3">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">
                                            <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none">
                                                {{ $task->title }}
                                            </a>
                                        </h6>

                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-folder me-1"></i>
                                                {{ $task->project->title }}
                                            </small>
                                        </div>

                                        @if($task->assignedUser)
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>
                                                {{ $task->assignedUser->name }}
                                            </small>
                                        </div>
                                        @endif

                                        @if($task->due_date)
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                @php
                                                    $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                                @endphp
                                                {{ $dueDate->format('M d, Y') }}
                                                @if($dueDate->isPast())
                                                    <span class="badge bg-danger ms-1">{{ __('Overdue') }}</span>
                                                @endif
                                            </small>
                                        </div>
                                        @endif

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-{{ $task->priority === 'urgent' ? 'danger' : ($task->priority === 'high' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                            <div>
                                                @can('update', $task)
                                                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary">
                                                        {{ __('Edit') }}
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- In Progress Tasks -->
                        <div class="col-md-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-play-circle me-1"></i>
                                {{ __('In Progress') }}
                                <span class="badge bg-primary">{{ $groupedTasks->get('in_progress', collect())->count() }}</span>
                            </h6>
                            @foreach($groupedTasks->get('in_progress', collect()) as $task)
                                <div class="card border-start border-primary border-3 mb-3">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">
                                            <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none">
                                                {{ $task->title }}
                                            </a>
                                        </h6>

                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-folder me-1"></i>
                                                {{ $task->project->title }}
                                            </small>
                                        </div>

                                        @if($task->assignedUser)
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>
                                                {{ $task->assignedUser->name }}
                                            </small>
                                        </div>
                                        @endif

                                        @if($task->due_date)
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                @php
                                                    $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                                @endphp
                                                {{ $dueDate->format('M d, Y') }}
                                                @if($dueDate->isPast())
                                                    <span class="badge bg-danger ms-1">{{ __('Overdue') }}</span>
                                                @endif
                                            </small>
                                        </div>
                                        @endif

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-{{ $task->priority === 'urgent' ? 'danger' : ($task->priority === 'high' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                            <div>
                                                @can('update', $task)
                                                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary">
                                                        {{ __('Edit') }}
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Completed Tasks -->
                        <div class="col-md-4">
                            <h6 class="text-success mb-3">
                                <i class="bi bi-check-circle me-1"></i>
                                {{ __('Completed') }}
                                <span class="badge bg-success">{{ $groupedTasks->get('completed', collect())->count() }}</span>
                            </h6>
                            @foreach($groupedTasks->get('completed', collect()) as $task)
                                <div class="card border-start border-success border-3 mb-3">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">
                                            <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none">
                                                {{ $task->title }}
                                            </a>
                                        </h6>

                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-folder me-1"></i>
                                                {{ $task->project->title }}
                                            </small>
                                        </div>

                                        @if($task->assignedUser)
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>
                                                {{ $task->assignedUser->name }}
                                            </small>
                                        </div>
                                        @endif

                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-check-circle me-1"></i>
                                                {{ __('Completed') }} {{ $task->updated_at->diffForHumans() }}
                                            </small>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-success">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                            <div>
                                                @can('update', $task)
                                                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary">
                                                        {{ __('Edit') }}
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($groupedTasks->has('cancelled') && $groupedTasks->get('cancelled')->count() > 0)
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-x-circle me-1"></i>
                            {{ __('Cancelled') }}
                            <span class="badge bg-secondary">{{ $groupedTasks->get('cancelled')->count() }}</span>
                        </h6>
                        <div class="row">
                            @foreach($groupedTasks->get('cancelled', collect()) as $task)
                                <div class="col-md-4">
                                    <div class="card border-start border-secondary border-3 mb-3">
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-2">
                                                <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none text-muted">
                                                    {{ $task->title }}
                                                </a>
                                            </h6>
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-folder me-1"></i>
                                                    {{ $task->project->title }}
                                                </small>
                                            </div>
                                            <span class="badge bg-secondary">{{ __('Cancelled') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-check2-square fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('No tasks found') }}</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['status', 'project_id', 'assigned_to', 'search']))
                                {{ __('Try adjusting your filters or') }}
                                <a href="{{ route('tasks.index') }}">{{ __('clear all filters') }}</a>
                            @else
                                {{ __('Get started by creating your first task.') }}
                            @endif
                        </p>
                        @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                {{ __('Create Task') }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection