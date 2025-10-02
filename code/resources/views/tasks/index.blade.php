@extends('layouts.app')

@section('title', __('Tasks'))
@section('page-title', __('Tasks'))

@section('content')
<div class="row">
    <!-- Header -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">{{ __('Tasks') }}</h2>
                <p class="text-muted mb-0">{{ __('Track and manage project tasks') }}</p>
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
                <form id="filter-form" class="row g-3">
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

                    <div class="col-md-3">
                        <label for="status" class="form-label">{{ __('Status') }}</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="à_faire" {{ request('status') === 'à_faire' ? 'selected' : '' }}>
                                {{ __('To Do') }}
                            </option>
                            <option value="en_cours" {{ request('status') === 'en_cours' ? 'selected' : '' }}>
                                {{ __('In Progress') }}
                            </option>
                            <option value="fait" {{ request('status') === 'fait' ? 'selected' : '' }}>
                                {{ __('Done') }}
                            </option>
                        </select>
                    </div>

                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <div class="col-md-3">
                        <label for="assigned_to" class="form-label">{{ __('Assigned To') }}</label>
                        <select class="form-select" id="assigned_to" name="assigned_to">
                            <option value="">{{ __('All Users') }}</option>
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
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search"
                                   placeholder="{{ __('Search tasks...') }}" value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-funnel me-2"></i>{{ __('Filter') }}
                        </button>
                        <button type="button" class="btn btn-outline-secondary me-2" onclick="clearFilters()">
                            <i class="bi bi-x-circle me-2"></i>{{ __('Clear') }}
                        </button>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="view" id="kanban-view" value="kanban" checked>
                            <label class="btn btn-outline-secondary" for="kanban-view">
                                <i class="bi bi-kanban me-1"></i>{{ __('Kanban') }}
                            </label>
                            <input type="radio" class="btn-check" name="view" id="list-view" value="list">
                            <label class="btn btn-outline-secondary" for="list-view">
                                <i class="bi bi-list me-1"></i>{{ __('List') }}
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tasks Content -->
    <div class="col-12">
        <!-- Kanban View -->
        <div id="kanban-container">
            <div class="row">
                <!-- To Do Column -->
                <div class="col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="bi bi-circle me-2"></i>
                                {{ __('To Do') }}
                                <span class="badge bg-dark ms-2" id="todo-count">0</span>
                            </h5>
                        </div>
                        <div class="card-body p-2" id="todo-tasks" style="min-height: 400px;">
                            <!-- Tasks will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- In Progress Column -->
                <div class="col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-header bg-info text-dark">
                            <h5 class="mb-0">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                {{ __('In Progress') }}
                                <span class="badge bg-dark ms-2" id="progress-count">0</span>
                            </h5>
                        </div>
                        <div class="card-body p-2" id="progress-tasks" style="min-height: 400px;">
                            <!-- Tasks will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Done Column -->
                <div class="col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ __('Done') }}
                                <span class="badge bg-dark ms-2" id="done-count">0</span>
                            </h5>
                        </div>
                        <div class="card-body p-2" id="done-tasks" style="min-height: 400px;">
                            <!-- Tasks will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div id="list-container" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Task') }}</th>
                                    <th>{{ __('Project') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Assigned To') }}</th>
                                    <th>{{ __('Due Date') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="tasks-table-body">
                                <!-- Tasks will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loading-state" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">{{ __('Loading...') }}</span>
            </div>
            <p class="mt-2">{{ __('Loading tasks...') }}</p>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="text-center py-5" style="display: none;">
            <i class="bi bi-check2-square text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3 text-muted">{{ __('No Tasks Found') }}</h4>
            <p class="text-muted">{{ __('No tasks match your current filters') }}</p>
        </div>

        <!-- Pagination -->
        <nav id="pagination-container" aria-label="{{ __('Tasks pagination') }}" style="display: none;">
            <!-- Pagination will be loaded here -->
        </nav>
    </div>
</div>

<!-- Quick Status Change Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Change Status') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-warning" onclick="changeTaskStatus('à_faire')">
                        {{ __('To Do') }}
                    </button>
                    <button class="btn btn-info" onclick="changeTaskStatus('en_cours')">
                        {{ __('In Progress') }}
                    </button>
                    <button class="btn btn-success" onclick="changeTaskStatus('fait')">
                        {{ __('Done') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentView = 'kanban';
let selectedTaskId = null;

document.addEventListener('DOMContentLoaded', function() {
    loadTasks();

    // Filter form submission
    document.getElementById('filter-form').addEventListener('submit', function(e) {
        e.preventDefault();
        loadTasks();
    });

    // View switcher
    document.querySelectorAll('input[name="view"]').forEach(radio => {
        radio.addEventListener('change', function() {
            currentView = this.value;
            toggleView();
        });
    });

    // Real-time search
    let searchTimeout;
    document.getElementById('search').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadTasks, 500);
    });
});

function loadTasks() {
    const formData = new FormData(document.getElementById('filter-form'));
    const params = new URLSearchParams(formData);

    showLoading();

    axios.get(`/ajax/tasks?${params}`)
        .then(response => {
            const tasks = response.data.data;
            if (currentView === 'kanban') {
                renderKanbanTasks(tasks);
            } else {
                renderListTasks(tasks);
            }
            hideLoading();
        })
        .catch(error => {
            console.error('Failed to load tasks:', error);
            showError('{{ __("Failed to load tasks. Please try again.") }}');
            hideLoading();
        });
}

function renderKanbanTasks(tasks) {
    const todoTasks = tasks.filter(t => t.status === 'à_faire');
    const progressTasks = tasks.filter(t => t.status === 'en_cours');
    const doneTasks = tasks.filter(t => t.status === 'fait');

    document.getElementById('todo-count').textContent = todoTasks.length;
    document.getElementById('progress-count').textContent = progressTasks.length;
    document.getElementById('done-count').textContent = doneTasks.length;

    renderTaskColumn('todo-tasks', todoTasks);
    renderTaskColumn('progress-tasks', progressTasks);
    renderTaskColumn('done-tasks', doneTasks);

    showEmptyStateIfNeeded(tasks.length === 0);
}

function renderTaskColumn(containerId, tasks) {
    const container = document.getElementById(containerId);

    if (tasks.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="bi bi-plus-circle fs-3"></i>
                <p class="mt-2 small">{{ __('No tasks') }}</p>
            </div>
        `;
        return;
    }

    container.innerHTML = tasks.map(task => `
        <div class="card mb-2 task-card" data-task-id="${task.id}">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="card-title mb-0">
                        <a href="/tasks/${task.id}" class="text-decoration-none">${task.title}</a>
                    </h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/tasks/${task.id}">
                                <i class="bi bi-eye me-2"></i>{{ __('View') }}
                            </a></li>
                            <li><a class="dropdown-item" href="/tasks/${task.id}/edit">
                                <i class="bi bi-pencil me-2"></i>{{ __('Edit') }}
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="openStatusModal(${task.id})">
                                <i class="bi bi-arrow-repeat me-2"></i>{{ __('Change Status') }}
                            </a></li>
                        </ul>
                    </div>
                </div>

                <p class="card-text text-muted small mb-2 text-truncate-2">
                    ${task.description || '{{ __("No description") }}'}
                </p>

                <div class="mb-2">
                    <small class="text-muted">
                        <i class="bi bi-folder me-1"></i>
                        ${task.project?.title || '{{ __("No project") }}'}
                    </small>
                </div>

                ${task.assigned_user ? `
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                             style="width: 24px; height: 24px;">
                            <i class="bi bi-person-fill" style="font-size: 0.7rem;"></i>
                        </div>
                        <small>${task.assigned_user.name}</small>
                    </div>
                ` : ''}

                ${task.due_date ? `
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar me-1 text-muted"></i>
                        <small class="text-muted">${formatDate(task.due_date)}</small>
                        ${isOverdue(task.due_date) ? '<i class="bi bi-exclamation-triangle text-danger ms-1"></i>' : ''}
                    </div>
                ` : ''}
            </div>
        </div>
    `).join('');
}

function renderListTasks(tasks) {
    const tbody = document.getElementById('tasks-table-body');

    if (tasks.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
                    <i class="bi bi-check2-square fs-3"></i>
                    <p class="mt-2">{{ __('No tasks found') }}</p>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = tasks.map(task => `
        <tr>
            <td>
                <div>
                    <a href="/tasks/${task.id}" class="fw-bold text-decoration-none">${task.title}</a>
                    <div class="text-muted small">${task.description || ''}</div>
                </div>
            </td>
            <td>
                <span class="badge bg-light text-dark">${task.project?.title || ''}</span>
            </td>
            <td>
                <span class="badge status-${task.status} status-badge">
                    ${getStatusText(task.status)}
                </span>
            </td>
            <td>
                ${task.assigned_user ? `
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                             style="width: 24px; height: 24px;">
                            <i class="bi bi-person-fill" style="font-size: 0.7rem;"></i>
                        </div>
                        ${task.assigned_user.name}
                    </div>
                ` : '<span class="text-muted">{{ __("Unassigned") }}</span>'}
            </td>
            <td>
                ${task.due_date ? `
                    <span class="${isOverdue(task.due_date) ? 'text-danger' : 'text-muted'}">
                        ${formatDate(task.due_date)}
                        ${isOverdue(task.due_date) ? '<i class="bi bi-exclamation-triangle ms-1"></i>' : ''}
                    </span>
                ` : '<span class="text-muted">-</span>'}
            </td>
            <td>
                <div class="btn-group" role="group">
                    <a href="/tasks/${task.id}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="/tasks/${task.id}/edit" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button class="btn btn-sm btn-outline-warning" onclick="openStatusModal(${task.id})">
                        <i class="bi bi-arrow-repeat"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    showEmptyStateIfNeeded(tasks.length === 0);
}

function toggleView() {
    const kanbanContainer = document.getElementById('kanban-container');
    const listContainer = document.getElementById('list-container');

    if (currentView === 'kanban') {
        kanbanContainer.style.display = 'block';
        listContainer.style.display = 'none';
    } else {
        kanbanContainer.style.display = 'none';
        listContainer.style.display = 'block';
    }

    loadTasks();
}

function showLoading() {
    document.getElementById('loading-state').style.display = 'block';
    document.getElementById('kanban-container').style.display = 'none';
    document.getElementById('list-container').style.display = 'none';
    document.getElementById('empty-state').style.display = 'none';
}

function hideLoading() {
    document.getElementById('loading-state').style.display = 'none';
    toggleView();
}

function showEmptyStateIfNeeded(isEmpty) {
    if (isEmpty) {
        document.getElementById('empty-state').style.display = 'block';
        document.getElementById('kanban-container').style.display = 'none';
        document.getElementById('list-container').style.display = 'none';
    }
}

function clearFilters() {
    document.getElementById('filter-form').reset();
    loadTasks();
}

function openStatusModal(taskId) {
    selectedTaskId = taskId;
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

function changeTaskStatus(newStatus) {
    if (!selectedTaskId) return;

    axios.patch(`/ajax/tasks/${selectedTaskId}/status`, { status: newStatus })
        .then(response => {
            if (response.data.success) {
                showSuccess('{{ __("Task status updated successfully") }}');
                loadTasks();
                bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
            }
        })
        .catch(error => {
            console.error('Failed to update task status:', error);
            showError('{{ __("Failed to update task status") }}');
        });
}

// Utility functions
function getStatusText(status) {
    const statuses = {
        'à_faire': '{{ __("To Do") }}',
        'en_cours': '{{ __("In Progress") }}',
        'fait': '{{ __("Done") }}'
    };
    return statuses[status] || status;
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString();
}

function isOverdue(dateString) {
    return new Date(dateString) < new Date();
}
</script>
@endpush