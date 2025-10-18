@extends('layouts.sidebar')

@section('title', __('app.tasks.title'))
@section('page-title', __('app.tasks.title'))

@section('content')
<div class="row">
    <!-- Header Actions -->
    <div class="col-12 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h2 class="mb-1">{{ __('app.tasks.title') }}</h2>
                <p class="text-muted mb-0">{{ __('app.tasks.manage_and_track') }}</p>
            </div>
            <div class="d-flex gap-2">
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
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-muted">
                    <i class="bi bi-funnel me-2"></i>{{ __('app.tasks.task_filters') }}
                </h6>
                <button type="button" id="toggleFilters" class="btn btn-sm btn-outline-secondary" title="{{ __('app.toggle_filters') }}">
                    <i class="bi bi-chevron-up" id="toggleFiltersIcon"></i>
                </button>
            </div>
            <div class="card-body p-3" id="filtersContent">
                <form method="GET" action="{{ route('tasks.index') }}" class="row g-3 align-items-end">
                    <div class="col-sm-6 col-md-3">
                        <label for="status" class="form-label small text-muted">{{ __('app.status') }}</label>
                        <select class="form-select form-select-sm" id="status" name="status">
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

                    <div class="col-sm-6 col-md-3">
                        <label for="project_id" class="form-label small text-muted">{{ __('app.tasks.project') }}</label>
                        <select class="form-select form-select-sm" id="project_id" name="project_id">
                            <option value="">{{ __('app.reports.all_projects') }}</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <div class="col-sm-6 col-md-3">
                        <label for="assigned_to" class="form-label small text-muted">{{ __('app.tasks.assigned_to') }}</label>
                        <select class="form-select form-select-sm" id="assigned_to" name="assigned_to">
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

                    <div class="col-sm-6 col-md-3">
                        <label for="priority" class="form-label small text-muted">{{ __('app.tasks.priority') }}</label>
                        <select class="form-select form-select-sm" id="priority" name="priority">
                            <option value="">{{ __('app.all_priorities') }}</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>
                                {{ __('app.tasks.low') }}
                            </option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>
                                {{ __('app.tasks.medium') }}
                            </option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>
                                {{ __('app.tasks.high') }}
                            </option>
                            <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>
                                {{ __('app.tasks.urgent') }}
                            </option>
                        </select>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <label for="search" class="form-label small text-muted">{{ __('app.search') }}</label>
                        <input type="text" class="form-control form-control-sm" id="search" name="search"
                               value="{{ request('search') }}" placeholder="{{ __('app.tasks.search_placeholder') }}">
                    </div>

                    <div class="col-sm-6 col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>
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
                                    <th class="d-none d-md-table-cell">{{ __('app.tasks.project') }}</th>
                                    <th class="d-none d-lg-table-cell">{{ __('app.tasks.assigned_to') }}</th>
                                    <th>{{ __('app.status') }}</th>
                                    <th class="d-none d-md-table-cell">{{ __('app.tasks.priority') }}</th>
                                    <th class="d-none d-lg-table-cell">{{ __('app.tasks.due_date') }}</th>
                                    <th class="d-none d-xl-table-cell">{{ __('app.tasks.created') }}</th>
                                    <th width="120">{{ __('app.actions') }}</th>
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
                                        <td class="d-none d-md-table-cell">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                     style="width: 24px; height: 24px;">
                                                    <i class="bi bi-folder-fill" style="font-size: 0.8rem;"></i>
                                                </div>
                                                <span>{{ $task->project->title }}</span>
                                            </div>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            @if($task->assignedUser)
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                         style="width: 24px; height: 24px;">
                                                        <i class="bi bi-person-fill" style="font-size: 0.8rem;"></i>
                                                    </div>
                                                    <span>{{ $task->assignedUser->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('app.unassigned') }}</span>
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
                                        <td class="d-none d-md-table-cell">
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
                                        <td class="d-none d-lg-table-cell">
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
                                        <td class="d-none d-xl-table-cell">
                                            <small class="text-muted">
                                                {{ $task->created_at->format('M d, Y') }}
                                                <br>
                                                {{ $task->created_at->diffForHumans() }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="dropdown dropstart">
                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 180px;">
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
                        <h5 class="text-muted">{{ __('app.tasks.no_tasks') }}</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['status', 'project_id', 'assigned_to', 'search']))
                                {{ __('app.try_adjusting_filters') }}
                                <a href="{{ route('tasks.index') }}">{{ __('app.clear_filters') }}</a>
                            @else
                                {{ __('app.tasks.get_started_create_task') }}
                            @endif
                        </p>
                        @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                {{ __('app.tasks.create') }}
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
    const isHidden = localStorage.getItem('taskFiltersHidden') === 'true';

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
            localStorage.setItem('taskFiltersHidden', 'true');
        } else {
            // Show filters
            filtersContent.style.display = 'block';
            toggleIcon.className = 'bi bi-chevron-up';
            localStorage.setItem('taskFiltersHidden', 'false');
        }
    });
}
</script>
@endpush

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