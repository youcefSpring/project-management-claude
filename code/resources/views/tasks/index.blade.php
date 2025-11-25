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
                <!-- Filter Button -->
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-funnel me-2"></i>{{ __('app.filter') }}
                </button>

                <!-- View Switcher -->
                <div class="btn-group" role="group" aria-label="View Mode">
                    <button type="button" class="btn btn-outline-secondary active" id="tableViewBtn" title="{{ __('app.list_view') }}">
                        <i class="bi bi-list-ul"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="boardViewBtn" title="{{ __('app.board_view') }}">
                        <i class="bi bi-kanban"></i>
                    </button>
                </div>

                @can('create', App\Models\Task::class)
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    {{ __('app.tasks.new_task') }}
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Active Filters Summary (Optional, good for UX) -->
    @if(request()->hasAny(['status', 'project_id', 'assigned_to', 'priority', 'search']))
        <div class="col-12 mb-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="text-muted small">{{ __('app.tasks.task_filters') }}:</span>
                @if(request('status'))
                    <span class="badge bg-light text-dark border">{{ __('app.status') }}: {{ ucfirst(str_replace('_', ' ', request('status'))) }}</span>
                @endif
                @if(request('priority'))
                    <span class="badge bg-light text-dark border">{{ __('app.tasks.priority') }}: {{ ucfirst(request('priority')) }}</span>
                @endif
                @if(request('search'))
                    <span class="badge bg-light text-dark border">{{ __('app.search') }}: {{ request('search') }}</span>
                @endif
                <a href="{{ route('tasks.index') }}" class="text-decoration-none small ms-2">{{ __('app.clear_filters') }}</a>
            </div>
        </div>
    @endif

    <!-- Tasks List (Table View) -->
    <div class="col-12" id="tableView">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h5 class="mb-0">
                    {{ __('app.tasks.title') }}
                    <span class="badge bg-secondary ms-2">{{ $tasks->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                @if($tasks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">{{ __('app.tasks.task_name') }}</th>
                                    <th class="d-none d-md-table-cell">{{ __('app.tasks.project') }}</th>
                                    <th class="d-none d-lg-table-cell">{{ __('app.tasks.assigned_to') }}</th>
                                    <th>{{ __('app.status') }}</th>
                                    <th class="d-none d-md-table-cell">{{ __('app.tasks.priority') }}</th>
                                    <th class="d-none d-lg-table-cell">{{ __('app.tasks.due_date') }}</th>
                                    <th class="text-end pe-4" width="100">{{ __('app.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                    <tr>
                                        <td class="ps-4">
                                            <div>
                                                <a href="{{ route('tasks.show', $task) }}" class="fw-bold text-dark text-decoration-none">
                                                    {{ $task->title }}
                                                </a>
                                                @if($task->description)
                                                    <div class="small text-muted text-truncate" style="max-width: 250px;">
                                                        {{ $task->description }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-1 me-2 text-primary">
                                                    <i class="bi bi-folder"></i>
                                                </div>
                                                <span class="text-truncate" style="max-width: 150px;">{{ $task->project->title }}</span>
                                            </div>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            @if($task->assignedUser)
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                         style="width: 24px; height: 24px; font-size: 0.75rem;">
                                                        {{ substr($task->assignedUser->name, 0, 1) }}
                                                    </div>
                                                    <span class="text-truncate" style="max-width: 120px;">{{ $task->assignedUser->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted fst-italic">{{ __('app.unassigned') }}</span>
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
                                            <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} px-2 py-1">
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
                                                $priorityIcon = match($task->priority) {
                                                    'urgent' => 'bi-exclamation-octagon-fill',
                                                    'high' => 'bi-arrow-up-circle-fill',
                                                    'medium' => 'bi-dash-circle-fill',
                                                    'low' => 'bi-arrow-down-circle-fill',
                                                    default => 'bi-circle-fill'
                                                };
                                            @endphp
                                            <div class="text-{{ $priorityColor }} d-flex align-items-center" title="{{ ucfirst($task->priority) }}">
                                                <i class="bi {{ $priorityIcon }} me-1"></i>
                                                <span class="d-none d-xl-inline">{{ ucfirst($task->priority) }}</span>
                                            </div>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            @if($task->due_date)
                                                @php
                                                    $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                                    $isOverdue = $dueDate->isPast() && $task->status !== 'completed';
                                                    $isDueToday = $dueDate->isToday();
                                                @endphp
                                                <span class="{{ $isOverdue ? 'text-danger fw-bold' : ($isDueToday ? 'text-warning fw-bold' : 'text-muted') }}">
                                                    {{ $dueDate->format('M d') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('tasks.show', $task) }}">
                                                            <i class="bi bi-eye me-2 text-muted"></i>{{ __('app.tasks.view') }}
                                                        </a>
                                                    </li>
                                                    @can('update', $task)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('tasks.edit', $task) }}">
                                                                <i class="bi bi-pencil me-2 text-muted"></i>{{ __('app.edit') }}
                                                            </a>
                                                        </li>
                                                    @endcan
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
                        <div class="mb-3">
                            <i class="bi bi-clipboard-check display-4 text-muted opacity-25"></i>
                        </div>
                        <h5 class="text-muted">{{ __('app.tasks.no_tasks') }}</h5>
                        @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                            <a href="{{ route('tasks.create') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle me-2"></i>{{ __('app.tasks.create') }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Kanban Board View -->
    <div class="col-12" id="boardView" style="display: none;">
        <div class="kanban-board">
            @foreach(['pending', 'in_progress', 'completed', 'cancelled'] as $status)
                <div class="kanban-column">
                    <div class="kanban-header status-{{ $status }} d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-uppercase fw-bold text-muted" style="font-size: 0.8rem;">
                            @switch($status)
                                @case('pending') {{ __('app.tasks.pending') }} @break
                                @case('in_progress') {{ __('app.tasks.in_progress') }} @break
                                @case('completed') {{ __('app.tasks.completed') }} @break
                                @case('cancelled') {{ __('app.tasks.cancelled') }} @break
                            @endswitch
                        </h6>
                        <span class="badge bg-white text-dark shadow-sm border">{{ $tasks->where('status', $status)->count() }}</span>
                    </div>
                    <div class="kanban-body" data-status="{{ $status }}">
                        @foreach($tasks->where('status', $status) as $task)
                            <div class="kanban-card card mb-3 border-0 shadow-sm" data-task-id="{{ $task->id }}">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        @php
                                            $priorityColor = match($task->priority) {
                                                'urgent' => 'danger',
                                                'high' => 'warning',
                                                'medium' => 'info',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $priorityColor }} bg-opacity-10 text-{{ $priorityColor }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                                <li><a class="dropdown-item small" href="{{ route('tasks.show', $task) }}">{{ __('app.tasks.view') }}</a></li>
                                                @can('update', $task)
                                                    <li><a class="dropdown-item small" href="{{ route('tasks.edit', $task) }}">{{ __('app.edit') }}</a></li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </div>
                                    <h6 class="card-title mb-2">
                                        <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none text-dark fw-bold">{{ $task->title }}</a>
                                    </h6>
                                    <div class="d-flex align-items-center text-muted small mb-3">
                                        <i class="bi bi-folder me-1"></i>
                                        <span class="text-truncate" style="max-width: 150px;">{{ $task->project->title }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <div class="d-flex align-items-center">
                                            @if($task->assignedUser)
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white"
                                                     style="width: 24px; height: 24px; font-size: 0.7rem;" title="{{ $task->assignedUser->name }}">
                                                    {{ substr($task->assignedUser->name, 0, 1) }}
                                                </div>
                                            @else
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-muted border"
                                                     style="width: 24px; height: 24px; font-size: 0.7rem;">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            @endif
                                        </div>
                                        @if($task->due_date)
                                            @php
                                                $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                            @endphp
                                            <span class="small {{ $dueDate->isPast() && $task->status !== 'completed' ? 'text-danger fw-bold' : 'text-muted' }}">
                                                <i class="bi bi-calendar me-1"></i>{{ $dueDate->format('M d') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-funnel me-2 text-primary"></i>{{ __('app.tasks.task_filters') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="GET" action="{{ route('tasks.index') }}" id="filterForm">
                    <div class="mb-3">
                        <label for="search" class="form-label small text-muted text-uppercase fw-bold">{{ __('app.search') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="{{ __('app.tasks.search_placeholder') }}">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label for="status" class="form-label small text-muted text-uppercase fw-bold">{{ __('app.status') }}</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">{{ __('app.tasks.all_statuses') }}</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('app.tasks.pending') }}</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('app.tasks.in_progress') }}</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('app.tasks.completed') }}</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('app.tasks.cancelled') }}</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="priority" class="form-label small text-muted text-uppercase fw-bold">{{ __('app.tasks.priority') }}</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="">{{ __('app.all_priorities') }}</option>
                                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>{{ __('app.tasks.low') }}</option>
                                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>{{ __('app.tasks.medium') }}</option>
                                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>{{ __('app.tasks.high') }}</option>
                                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>{{ __('app.tasks.urgent') }}</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="project_id" class="form-label small text-muted text-uppercase fw-bold">{{ __('app.tasks.project') }}</label>
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
                        <div class="col-12">
                            <label for="assigned_to" class="form-label small text-muted text-uppercase fw-bold">{{ __('app.tasks.assigned_to') }}</label>
                            <select class="form-select" id="assigned_to" name="assigned_to">
                                <option value="">{{ __('app.reports.all_users') }}</option>
                                <option value="unassigned" {{ request('assigned_to') === 'unassigned' ? 'selected' : '' }}>{{ __('app.unassigned') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0">
                <a href="{{ route('tasks.index') }}" class="btn btn-light">{{ __('app.clear_filters') }}</a>
                <button type="submit" form="filterForm" class="btn btn-primary px-4">{{ __('app.filter') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast for Notifications -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="liveToast" class="toast align-items-center text-white bg-success border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle me-2"></i><span id="toastMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- SortableJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    setupViewSwitcher();
    setupKanbanSortable();
});

function setupViewSwitcher() {
    const tableViewBtn = document.getElementById('tableViewBtn');
    const boardViewBtn = document.getElementById('boardViewBtn');
    const tableView = document.getElementById('tableView');
    const boardView = document.getElementById('boardView');

    // Check localStorage
    const currentView = localStorage.getItem('tasksViewMode') || 'table';

    if (currentView === 'board') {
        switchToBoard();
    } else {
        switchToTable();
    }

    tableViewBtn.addEventListener('click', function() {
        switchToTable();
        localStorage.setItem('tasksViewMode', 'table');
    });

    boardViewBtn.addEventListener('click', function() {
        switchToBoard();
        localStorage.setItem('tasksViewMode', 'board');
    });

    function switchToTable() {
        tableView.style.display = 'block';
        boardView.style.display = 'none';
        tableViewBtn.classList.add('active');
        boardViewBtn.classList.remove('active');
    }

    function switchToBoard() {
        tableView.style.display = 'none';
        boardView.style.display = 'block';
        tableViewBtn.classList.remove('active');
        boardViewBtn.classList.add('active');
    }
}

function setupKanbanSortable() {
    const columns = document.querySelectorAll('.kanban-body');
    const toastEl = document.getElementById('liveToast');
    const toast = new bootstrap.Toast(toastEl);
    const toastMessage = document.getElementById('toastMessage');

    columns.forEach(column => {
        new Sortable(column, {
            group: 'kanban', // Allow dragging between columns
            animation: 150,
            ghostClass: 'bg-light',
            onEnd: function (evt) {
                const itemEl = evt.item;
                const newStatus = evt.to.getAttribute('data-status');
                const oldStatus = evt.from.getAttribute('data-status');
                const taskId = itemEl.getAttribute('data-task-id');

                // Only update if status changed
                if (newStatus !== oldStatus) {
                    updateTaskStatus(taskId, newStatus, itemEl, evt.from);
                }
            }
        });
    });

    function updateTaskStatus(taskId, status, itemEl, originalContainer) {
        axios.put(`/tasks/${taskId}`, {
            status: status,
            _token: '{{ csrf_token() }}'
        })
        .then(response => {
            // Success
            toastEl.classList.remove('bg-danger');
            toastEl.classList.add('bg-success');
            toastMessage.textContent = '{{ __('app.tasks.status_updated_successfully') }}';
            toast.show();
        })
        .catch(error => {
            // Revert change on error
            originalContainer.appendChild(itemEl);
            
            toastEl.classList.remove('bg-success');
            toastEl.classList.add('bg-danger');
            toastMessage.textContent = '{{ __('app.tasks.error_updating_status') }}';
            toast.show();
            console.error('Error updating status:', error);
        });
    }
}
</script>

<style>
/* Kanban Board Styles */
.kanban-board {
    display: flex;
    overflow-x: auto;
    gap: 1.5rem;
    padding-bottom: 1rem;
    min-height: calc(100vh - 200px);
}

.kanban-column {
    flex: 0 0 320px;
    background-color: #f8f9fa;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 200px);
    border: 1px solid rgba(0,0,0,0.05);
}

.kanban-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    border-radius: 12px 12px 0 0;
}

.kanban-header.status-pending { border-top: 4px solid #ffc107; }
.kanban-header.status-in_progress { border-top: 4px solid #0d6efd; }
.kanban-header.status-completed { border-top: 4px solid #198754; }
.kanban-header.status-cancelled { border-top: 4px solid #6c757d; }

.kanban-body {
    padding: 1rem;
    overflow-y: auto;
    flex: 1;
}

.kanban-card {
    cursor: grab;
    transition: transform 0.2s, box-shadow 0.2s;
}

.kanban-card:active {
    cursor: grabbing;
}

.kanban-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08) !important;
}

/* Custom Scrollbar */
.kanban-board::-webkit-scrollbar { height: 8px; }
.kanban-board::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
.kanban-board::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
.kanban-board::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }

.kanban-body::-webkit-scrollbar { width: 6px; }
.kanban-body::-webkit-scrollbar-track { background: transparent; }
.kanban-body::-webkit-scrollbar-thumb { background: #d1d1d1; border-radius: 3px; }
</style>
@endpush