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
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('Task') }}</th>
                                    <th>{{ __('Project') }}</th>
                                    <th>{{ __('Assigned To') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Priority') }}</th>
                                    <th>{{ __('Due Date') }}</th>
                                    <th>{{ __('Created') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                    <tr>
                                        <td>
                                            <div>
                                                <a href="{{ route('tasks.show', $task) }}" class="fw-bold text-decoration-none">
                                                    {{ $task->title }}
                                                </a>
                                                @if($task->description)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($task->description, 60) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                     style="width: 24px; height: 24px;">
                                                    <i class="bi bi-folder-fill" style="font-size: 0.8rem;"></i>
                                                </div>
                                                <span>{{ $task->project->title }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($task->assignedUser)
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                         style="width: 24px; height: 24px;">
                                                        <i class="bi bi-person-fill" style="font-size: 0.8rem;"></i>
                                                    </div>
                                                    <span>{{ $task->assignedUser->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('Unassigned') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'in_progress' => 'primary',
                                                    'completed' => 'success',
                                                    'cancelled' => 'secondary'
                                                ];
                                                $color = $statusColors[$task->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }}">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $priorityColors = [
                                                    'urgent' => 'danger',
                                                    'high' => 'warning',
                                                    'medium' => 'info',
                                                    'low' => 'secondary'
                                                ];
                                                $priorityColor = $priorityColors[$task->priority] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $priorityColor }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($task->due_date)
                                                @php
                                                    $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                                @endphp
                                                <div>
                                                    <span class="fw-bold">{{ $dueDate->format('M d, Y') }}</span>
                                                    @if($dueDate->isPast() && $task->status !== 'completed')
                                                        <br><span class="badge bg-danger">{{ __('Overdue') }}</span>
                                                    @elseif($dueDate->isToday())
                                                        <br><span class="badge bg-warning">{{ __('Due Today') }}</span>
                                                    @elseif($dueDate->isTomorrow())
                                                        <br><span class="badge bg-info">{{ __('Due Tomorrow') }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('No due date') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $task->created_at->format('M d, Y') }}
                                                <br>
                                                {{ $task->created_at->diffForHumans() }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
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
                                                    @if($task->status !== 'completed')
                                                        <li><hr class="dropdown-divider"></li>
                                                        @if($task->status === 'pending')
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="changeTaskStatus({{ $task->id }}, 'in_progress')">
                                                                    <i class="bi bi-play-circle me-2 text-primary"></i>{{ __('Start Task') }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if($task->status === 'in_progress')
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="changeTaskStatus({{ $task->id }}, 'completed')">
                                                                    <i class="bi bi-check-circle me-2 text-success"></i>{{ __('Mark Complete') }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
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

@push('scripts')
<script>
function changeTaskStatus(taskId, status) {
    if (confirm(`{{ __('Are you sure you want to change the task status?') }}`)) {
        axios.post(`/tasks/${taskId}/status`, {
            status: status,
            _token: '{{ csrf_token() }}'
        })
        .then(response => {
            if (response.data.success) {
                location.reload();
            } else {
                alert('{{ __('Error updating task status') }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __('Error updating task status') }}');
        });
    }
}
</script>
@endpush