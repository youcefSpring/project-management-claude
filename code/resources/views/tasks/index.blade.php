@extends('layouts.app')

@section('title', __('app.tasks.title'))
@section('page-title', __('app.tasks.title'))

@section('content')
<div class="row">
    <!-- Header Actions -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">{{ __('app.tasks.title') }}</h2>
                <p class="text-muted mb-0">{{ __('app.tasks.manage_and_track') }}</p>
            </div>
            <div>
                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    {{ __('app.tasks.new_task') }}
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
                        <label for="status" class="form-label">{{ __('app.status') }}</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">{{ __('app.tasks.all_statuses') }}</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                {{ __('app.tasks.pending') }}
                            </option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>
                                {{ __('app.tasks.in_progress') }}
                            </option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                {{ __('app.tasks.completed') }}
                            </option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                {{ __('app.tasks.cancelled') }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="project_id" class="form-label">{{ __('app.tasks.project') }}</label>
                        <select class="form-select" id="project_id" name="project_id">
                            <option value="">{{ __('app.reports.all_projects') }}</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <div class="col-md-3">
                        <label for="assigned_to" class="form-label">{{ __('app.tasks.assigned_to') }}</label>
                        <select class="form-select" id="assigned_to" name="assigned_to">
                            <option value="">{{ __('app.reports.all_users') }}</option>
                            <option value="unassigned" {{ request('assigned_to') === 'unassigned' ? 'selected' : '' }}>
                                {{ __('app.unassigned') }}
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
                        <label for="search" class="form-label">{{ __('app.search') }}</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="{{ __('app.tasks.search_placeholder') }}">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search me-2"></i>
                            {{ __('app.filter') }}
                        </button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-x-circle me-2"></i>
                            {{ __('app.tasks.clear_filters') }}
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
                    {{ __('app.tasks.title') }}
                    <span class="badge bg-secondary ms-2">{{ $tasks->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($tasks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('app.tasks.task_name') }}</th>
                                    <th>{{ __('app.tasks.project') }}</th>
                                    <th>{{ __('app.tasks.assigned_to') }}</th>
                                    <th>{{ __('app.status') }}</th>
                                    <th>{{ __('app.tasks.priority') }}</th>
                                    <th>{{ __('app.tasks.due_date') }}</th>
                                    <th>{{ __('app.tasks.created') }}</th>
                                    <th>{{ __('app.actions') }}</th>
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
                                                @switch($task->status)
                                                    @case('pending') {{ __('app.tasks.pending') }} @break
                                                    @case('in_progress') {{ __('app.tasks.in_progress') }} @break
                                                    @case('completed') {{ __('app.tasks.completed') }} @break
                                                    @case('cancelled') {{ __('app.tasks.cancelled') }} @break
                                                    @default {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                @endswitch
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
                                                @switch($task->priority)
                                                    @case('low') {{ __('app.tasks.low') }} @break
                                                    @case('medium') {{ __('app.tasks.medium') }} @break
                                                    @case('high') {{ __('app.tasks.high') }} @break
                                                    @case('urgent') {{ __('app.tasks.urgent') }} @break
                                                    @default {{ ucfirst($task->priority) }}
                                                @endswitch
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
                                                        <br><span class="badge bg-danger">{{ __('app.tasks.overdue') }}</span>
                                                    @elseif($dueDate->isToday())
                                                        <br><span class="badge bg-warning">{{ __('app.tasks.due_today') }}</span>
                                                    @elseif($dueDate->isTomorrow())
                                                        <br><span class="badge bg-info">{{ __('app.tasks.due_tomorrow') }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('app.tasks.no_due_date') }}</span>
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
                                                            <i class="bi bi-eye me-2"></i>{{ __('app.tasks.view') }}
                                                        </a>
                                                    </li>
                                                    @can('update', $task)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('tasks.edit', $task) }}">
                                                                <i class="bi bi-pencil me-2"></i>{{ __('app.edit') }}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @if($task->status !== 'completed')
                                                        <li><hr class="dropdown-divider"></li>
                                                        @if($task->status === 'pending')
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="changeTaskStatus({{ $task->id }}, 'in_progress')">
                                                                    <i class="bi bi-play-circle me-2 text-primary"></i>{{ __('app.tasks.start_task') }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if($task->status === 'in_progress')
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="changeTaskStatus({{ $task->id }}, 'completed')">
                                                                    <i class="bi bi-check-circle me-2 text-success"></i>{{ __('app.tasks.mark_complete') }}
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

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('app.tasks.confirm_status_change') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage">{{ __('app.tasks.confirm_status_change_message') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                <button type="button" class="btn btn-primary" id="confirmStatusBtn">
                    <span id="confirmBtnText">{{ __('app.confirm') }}</span>
                    <span class="spinner-border spinner-border-sm ms-2 d-none" id="confirmSpinner"></span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentTaskId = null;
let currentStatus = null;

function changeTaskStatus(taskId, status) {
    currentTaskId = taskId;
    currentStatus = status;

    // Update modal content based on status
    const statusMessages = {
        'in_progress': '{{ __('app.tasks.confirm_start_task') }}',
        'completed': '{{ __('app.tasks.confirm_complete_task') }}',
        'cancelled': '{{ __('app.tasks.confirm_cancel_task') }}'
    };

    document.getElementById('confirmMessage').textContent = statusMessages[status] || '{{ __('app.tasks.confirm_status_change_message') }}';

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('confirmStatusModal'));
    modal.show();
}

document.getElementById('confirmStatusBtn').addEventListener('click', function() {
    if (!currentTaskId || !currentStatus) return;

    const btn = this;
    const btnText = document.getElementById('confirmBtnText');
    const spinner = document.getElementById('confirmSpinner');

    // Show loading state
    btn.disabled = true;
    btnText.textContent = '{{ __('app.processing') }}';
    spinner.classList.remove('d-none');

    axios.post(`/tasks/${currentTaskId}/status`, {
        status: currentStatus,
        _token: '{{ csrf_token() }}'
    })
    .then(response => {
        if (response.data.success) {
            // Hide modal
            bootstrap.Modal.getInstance(document.getElementById('confirmStatusModal')).hide();

            // Show success message and reload
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            throw new Error(response.data.message || '{{ __('app.tasks.error_updating_status') }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);

        // Show error in modal
        document.getElementById('confirmMessage').innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                ${error.response?.data?.message || '{{ __('app.tasks.error_updating_status') }}'}
            </div>
        `;
    })
    .finally(() => {
        // Reset button state
        btn.disabled = false;
        btnText.textContent = '{{ __('app.confirm') }}';
        spinner.classList.add('d-none');
    });
});
</script>
@endpush