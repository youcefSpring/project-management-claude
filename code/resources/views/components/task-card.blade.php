@props(['task', 'showProject' => true])

<div class="card mb-3 task-card hover-shadow" data-task-id="{{ $task->id }}">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="card-title mb-0">
                <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none">
                    {{ $task->title }}
                </a>
            </h6>

            <div class="dropdown">
                <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('tasks.show', $task) }}">
                            <i class="bi bi-eye me-2"></i>{{ __('View') }}
                        </a>
                    </li>
                    @can('update', $task)
                    <li>
                        <a class="dropdown-item" href="{{ route('tasks.edit', $task) }}">
                            <i class="bi bi-pencil me-2"></i>{{ __('Edit') }}
                        </a>
                    </li>
                    @endcan
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="quickStatusChange({{ $task->id }})">
                            <i class="bi bi-arrow-repeat me-2"></i>{{ __('Change Status') }}
                        </a>
                    </li>
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <li>
                        <a class="dropdown-item" href="{{ route('timesheet.create') }}?task_id={{ $task->id }}">
                            <i class="bi bi-clock me-2"></i>{{ __('Log Time') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        @if($task->description)
        <p class="card-text text-muted small mb-2 text-truncate-2">
            {{ $task->description }}
        </p>
        @endif

        <div class="mb-2">
            <span class="badge status-{{ $task->status }} status-badge">
                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
            </span>

            @if($task->priority)
            <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'secondary') }} ms-1">
                {{ ucfirst($task->priority) }}
            </span>
            @endif
        </div>

        @if($showProject && $task->project)
        <div class="mb-2">
            <small class="text-muted">
                <i class="bi bi-folder me-1"></i>
                <a href="{{ route('projects.show', $task->project) }}" class="text-decoration-none">
                    {{ $task->project->title }}
                </a>
            </small>
        </div>
        @endif

        @if($task->assignedUser)
        <div class="d-flex align-items-center mb-2">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                 style="width: 24px; height: 24px;">
                <i class="bi bi-person-fill" style="font-size: 0.7rem;"></i>
            </div>
            <small>{{ $task->assignedUser->name }}</small>
        </div>
        @endif

        @if($task->due_date)
        <div class="d-flex align-items-center">
            <i class="bi bi-calendar me-1 {{ $task->due_date->isPast() && $task->status !== 'fait' ? 'text-danger' : 'text-muted' }}"></i>
            <small class="{{ $task->due_date->isPast() && $task->status !== 'fait' ? 'text-danger fw-bold' : 'text-muted' }}">
                {{ $task->due_date->format('M d, Y') }}
            </small>
            @if($task->due_date->isPast() && $task->status !== 'fait')
                <i class="bi bi-exclamation-triangle text-danger ms-1" title="{{ __('Overdue') }}"></i>
            @endif
        </div>
        @endif

        <!-- Task Progress/Stats -->
        @if($task->timeEntries->count() > 0)
        <div class="mt-2 pt-2 border-top">
            <small class="text-muted">
                <i class="bi bi-clock me-1"></i>
                {{ $task->timeEntries->sum('duration_hours') }}h {{ __('logged') }}
            </small>
        </div>
        @endif
    </div>
</div>