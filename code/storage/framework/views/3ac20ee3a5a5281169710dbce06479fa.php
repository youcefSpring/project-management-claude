<?php $__env->startSection('title', __('Tasks')); ?>
<?php $__env->startSection('page-title', __('Tasks')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Header -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1"><?php echo e(__('Tasks')); ?></h2>
                <p class="text-muted mb-0"><?php echo e(__('Track and manage project tasks')); ?></p>
            </div>
            <div>
                <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    <?php echo e(__('New Task')); ?>

                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form id="filter-form" class="row g-3">
                    <div class="col-md-3">
                        <label for="project_id" class="form-label"><?php echo e(__('Project')); ?></label>
                        <select class="form-select" id="project_id" name="project_id">
                            <option value=""><?php echo e(__('All Projects')); ?></option>
                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($project->id); ?>" <?php echo e(request('project_id') == $project->id ? 'selected' : ''); ?>>
                                <?php echo e($project->title); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="status" class="form-label"><?php echo e(__('Status')); ?></label>
                        <select class="form-select" id="status" name="status">
                            <option value=""><?php echo e(__('All Statuses')); ?></option>
                            <option value="à_faire" <?php echo e(request('status') === 'à_faire' ? 'selected' : ''); ?>>
                                <?php echo e(__('To Do')); ?>

                            </option>
                            <option value="en_cours" <?php echo e(request('status') === 'en_cours' ? 'selected' : ''); ?>>
                                <?php echo e(__('In Progress')); ?>

                            </option>
                            <option value="fait" <?php echo e(request('status') === 'fait' ? 'selected' : ''); ?>>
                                <?php echo e(__('Done')); ?>

                            </option>
                        </select>
                    </div>

                    <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                    <div class="col-md-3">
                        <label for="assigned_to" class="form-label"><?php echo e(__('Assigned To')); ?></label>
                        <select class="form-select" id="assigned_to" name="assigned_to">
                            <option value=""><?php echo e(__('All Users')); ?></option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(request('assigned_to') == $user->id ? 'selected' : ''); ?>>
                                <?php echo e($user->name); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="col-md-3">
                        <label for="search" class="form-label"><?php echo e(__('Search')); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search"
                                   placeholder="<?php echo e(__('Search tasks...')); ?>" value="<?php echo e(request('search')); ?>">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-funnel me-2"></i><?php echo e(__('Filter')); ?>

                        </button>
                        <button type="button" class="btn btn-outline-secondary me-2" onclick="clearFilters()">
                            <i class="bi bi-x-circle me-2"></i><?php echo e(__('Clear')); ?>

                        </button>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="view" id="kanban-view" value="kanban" checked>
                            <label class="btn btn-outline-secondary" for="kanban-view">
                                <i class="bi bi-kanban me-1"></i><?php echo e(__('Kanban')); ?>

                            </label>
                            <input type="radio" class="btn-check" name="view" id="list-view" value="list">
                            <label class="btn btn-outline-secondary" for="list-view">
                                <i class="bi bi-list me-1"></i><?php echo e(__('List')); ?>

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
                                <?php echo e(__('To Do')); ?>

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
                                <?php echo e(__('In Progress')); ?>

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
                                <?php echo e(__('Done')); ?>

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
                                    <th><?php echo e(__('Task')); ?></th>
                                    <th><?php echo e(__('Project')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Assigned To')); ?></th>
                                    <th><?php echo e(__('Due Date')); ?></th>
                                    <th><?php echo e(__('Actions')); ?></th>
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
                <span class="visually-hidden"><?php echo e(__('Loading...')); ?></span>
            </div>
            <p class="mt-2"><?php echo e(__('Loading tasks...')); ?></p>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="text-center py-5" style="display: none;">
            <i class="bi bi-check2-square text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3 text-muted"><?php echo e(__('No Tasks Found')); ?></h4>
            <p class="text-muted"><?php echo e(__('No tasks match your current filters')); ?></p>
        </div>

        <!-- Pagination -->
        <nav id="pagination-container" aria-label="<?php echo e(__('Tasks pagination')); ?>" style="display: none;">
            <!-- Pagination will be loaded here -->
        </nav>
    </div>
</div>

<!-- Quick Status Change Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('Change Status')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-warning" onclick="changeTaskStatus('à_faire')">
                        <?php echo e(__('To Do')); ?>

                    </button>
                    <button class="btn btn-info" onclick="changeTaskStatus('en_cours')">
                        <?php echo e(__('In Progress')); ?>

                    </button>
                    <button class="btn btn-success" onclick="changeTaskStatus('fait')">
                        <?php echo e(__('Done')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
            showError('<?php echo e(__("Failed to load tasks. Please try again.")); ?>');
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
                <p class="mt-2 small"><?php echo e(__('No tasks')); ?></p>
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
                                <i class="bi bi-eye me-2"></i><?php echo e(__('View')); ?>

                            </a></li>
                            <li><a class="dropdown-item" href="/tasks/${task.id}/edit">
                                <i class="bi bi-pencil me-2"></i><?php echo e(__('Edit')); ?>

                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="openStatusModal(${task.id})">
                                <i class="bi bi-arrow-repeat me-2"></i><?php echo e(__('Change Status')); ?>

                            </a></li>
                        </ul>
                    </div>
                </div>

                <p class="card-text text-muted small mb-2 text-truncate-2">
                    ${task.description || '<?php echo e(__("No description")); ?>'}
                </p>

                <div class="mb-2">
                    <small class="text-muted">
                        <i class="bi bi-folder me-1"></i>
                        ${task.project?.title || '<?php echo e(__("No project")); ?>'}
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
                    <p class="mt-2"><?php echo e(__('No tasks found')); ?></p>
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
                ` : '<span class="text-muted"><?php echo e(__("Unassigned")); ?></span>'}
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
                showSuccess('<?php echo e(__("Task status updated successfully")); ?>');
                loadTasks();
                bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
            }
        })
        .catch(error => {
            console.error('Failed to update task status:', error);
            showError('<?php echo e(__("Failed to update task status")); ?>');
        });
}

// Utility functions
function getStatusText(status) {
    const statuses = {
        'à_faire': '<?php echo e(__("To Do")); ?>',
        'en_cours': '<?php echo e(__("In Progress")); ?>',
        'fait': '<?php echo e(__("Done")); ?>'
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/tasks/index.blade.php ENDPATH**/ ?>