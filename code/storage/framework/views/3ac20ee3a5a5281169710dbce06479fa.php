<?php $__env->startSection('title', __('app.tasks.title')); ?>
<?php $__env->startSection('page-title', __('app.tasks.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Header Actions -->
    <div class="col-12 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h2 class="mb-1"><?php echo e(__('app.tasks.title')); ?></h2>
                <p class="text-muted mb-0"><?php echo e(__('app.tasks.manage_and_track')); ?></p>
            </div>
            <div class="d-flex gap-2">
                <!-- Filter Button -->
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-funnel me-2"></i><?php echo e(__('app.filter')); ?>

                </button>

                <!-- View Switcher -->
                <div class="btn-group" role="group" aria-label="View Mode">
                    <button type="button" class="btn btn-outline-secondary active" id="tableViewBtn" title="<?php echo e(__('app.list_view')); ?>">
                        <i class="bi bi-list-ul"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="boardViewBtn" title="<?php echo e(__('app.board_view')); ?>">
                        <i class="bi bi-kanban"></i>
                    </button>
                </div>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Task::class)): ?>
                <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    <?php echo e(__('app.tasks.new_task')); ?>

                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Active Filters Summary (Optional, good for UX) -->
    <?php if(request()->hasAny(['status', 'project_id', 'assigned_to', 'priority', 'search'])): ?>
        <div class="col-12 mb-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="text-muted small"><?php echo e(__('app.tasks.task_filters')); ?>:</span>
                <?php if(request('status')): ?>
                    <span class="badge bg-light text-dark border"><?php echo e(__('app.status')); ?>: <?php echo e(ucfirst(str_replace('_', ' ', request('status')))); ?></span>
                <?php endif; ?>
                <?php if(request('priority')): ?>
                    <span class="badge bg-light text-dark border"><?php echo e(__('app.tasks.priority')); ?>: <?php echo e(ucfirst(request('priority'))); ?></span>
                <?php endif; ?>
                <?php if(request('search')): ?>
                    <span class="badge bg-light text-dark border"><?php echo e(__('app.search')); ?>: <?php echo e(request('search')); ?></span>
                <?php endif; ?>
                <a href="<?php echo e(route('tasks.index')); ?>" class="text-decoration-none small ms-2"><?php echo e(__('app.clear_filters')); ?></a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Tasks List (Table View) -->
    <div class="col-12" id="tableView">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h5 class="mb-0">
                    <?php echo e(__('app.tasks.title')); ?>

                    <span class="badge bg-secondary ms-2"><?php echo e($tasks->count()); ?></span>
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if($tasks->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4"><?php echo e(__('app.tasks.task_name')); ?></th>
                                    <th class="d-none d-md-table-cell"><?php echo e(__('app.tasks.project')); ?></th>
                                    <th class="d-none d-lg-table-cell"><?php echo e(__('app.tasks.assigned_to')); ?></th>
                                    <th><?php echo e(__('app.status')); ?></th>
                                    <th class="d-none d-md-table-cell"><?php echo e(__('app.tasks.priority')); ?></th>
                                    <th class="d-none d-lg-table-cell"><?php echo e(__('app.tasks.due_date')); ?></th>
                                    <th class="text-end pe-4" width="100"><?php echo e(__('app.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div>
                                                <a href="<?php echo e(route('tasks.show', $task)); ?>" class="fw-bold text-dark text-decoration-none">
                                                    <?php echo e($task->title); ?>

                                                </a>
                                                <?php if($task->description): ?>
                                                    <div class="small text-muted text-truncate" style="max-width: 250px;">
                                                        <?php echo e($task->description); ?>

                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-1 me-2 text-primary">
                                                    <i class="bi bi-folder"></i>
                                                </div>
                                                <span class="text-truncate" style="max-width: 150px;"><?php echo e($task->project->title); ?></span>
                                            </div>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <?php if($task->assignedUser): ?>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                         style="width: 24px; height: 24px; font-size: 0.75rem;">
                                                        <?php echo e(substr($task->assignedUser->name, 0, 1)); ?>

                                                    </div>
                                                    <span class="text-truncate" style="max-width: 120px;"><?php echo e($task->assignedUser->name); ?></span>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted fst-italic"><?php echo e(__('app.unassigned')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'in_progress' => 'primary',
                                                    'completed' => 'success',
                                                    'cancelled' => 'secondary'
                                                ];
                                                $color = $statusColors[$task->status] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?php echo e($color); ?> bg-opacity-10 text-<?php echo e($color); ?> px-2 py-1">
                                                <?php switch($task->status):
                                                    case ('pending'): ?> <?php echo e(__('app.tasks.pending')); ?> <?php break; ?>
                                                    <?php case ('in_progress'): ?> <?php echo e(__('app.tasks.in_progress')); ?> <?php break; ?>
                                                    <?php case ('completed'): ?> <?php echo e(__('app.tasks.completed')); ?> <?php break; ?>
                                                    <?php case ('cancelled'): ?> <?php echo e(__('app.tasks.cancelled')); ?> <?php break; ?>
                                                    <?php default: ?> <?php echo e(ucfirst(str_replace('_', ' ', $task->status))); ?>

                                                <?php endswitch; ?>
                                            </span>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <?php
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
                                            ?>
                                            <div class="text-<?php echo e($priorityColor); ?> d-flex align-items-center" title="<?php echo e(ucfirst($task->priority)); ?>">
                                                <i class="bi <?php echo e($priorityIcon); ?> me-1"></i>
                                                <span class="d-none d-xl-inline"><?php echo e(ucfirst($task->priority)); ?></span>
                                            </div>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <?php if($task->due_date): ?>
                                                <?php
                                                    $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                                    $isOverdue = $dueDate->isPast() && $task->status !== 'completed';
                                                    $isDueToday = $dueDate->isToday();
                                                ?>
                                                <span class="<?php echo e($isOverdue ? 'text-danger fw-bold' : ($isDueToday ? 'text-warning fw-bold' : 'text-muted')); ?>">
                                                    <?php echo e($dueDate->format('M d')); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('tasks.show', $task)); ?>">
                                                            <i class="bi bi-eye me-2 text-muted"></i><?php echo e(__('app.tasks.view')); ?>

                                                        </a>
                                                    </li>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                                                        <li>
                                                            <a class="dropdown-item" href="<?php echo e(route('tasks.edit', $task)); ?>">
                                                                <i class="bi bi-pencil me-2 text-muted"></i><?php echo e(__('app.edit')); ?>

                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-clipboard-check display-4 text-muted opacity-25"></i>
                        </div>
                        <h5 class="text-muted"><?php echo e(__('app.tasks.no_tasks')); ?></h5>
                        <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                            <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle me-2"></i><?php echo e(__('app.tasks.create')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Kanban Board View -->
    <div class="col-12" id="boardView" style="display: none;">
        <div class="kanban-board">
            <?php $__currentLoopData = ['pending', 'in_progress', 'completed', 'cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="kanban-column">
                    <div class="kanban-header status-<?php echo e($status); ?> d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-uppercase fw-bold text-muted" style="font-size: 0.8rem;">
                            <?php switch($status):
                                case ('pending'): ?> <?php echo e(__('app.tasks.pending')); ?> <?php break; ?>
                                <?php case ('in_progress'): ?> <?php echo e(__('app.tasks.in_progress')); ?> <?php break; ?>
                                <?php case ('completed'): ?> <?php echo e(__('app.tasks.completed')); ?> <?php break; ?>
                                <?php case ('cancelled'): ?> <?php echo e(__('app.tasks.cancelled')); ?> <?php break; ?>
                            <?php endswitch; ?>
                        </h6>
                        <span class="badge bg-white text-dark shadow-sm border"><?php echo e($tasks->where('status', $status)->count()); ?></span>
                    </div>
                    <div class="kanban-body" data-status="<?php echo e($status); ?>">
                        <?php $__currentLoopData = $tasks->where('status', $status); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="kanban-card card mb-3 border-0 shadow-sm" data-task-id="<?php echo e($task->id); ?>">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <?php
                                            $priorityColor = match($task->priority) {
                                                'urgent' => 'danger',
                                                'high' => 'warning',
                                                'medium' => 'info',
                                                default => 'secondary'
                                            };
                                        ?>
                                        <span class="badge bg-<?php echo e($priorityColor); ?> bg-opacity-10 text-<?php echo e($priorityColor); ?>">
                                            <?php echo e(ucfirst($task->priority)); ?>

                                        </span>
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                                <li><a class="dropdown-item small" href="<?php echo e(route('tasks.show', $task)); ?>"><?php echo e(__('app.tasks.view')); ?></a></li>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                                                    <li><a class="dropdown-item small" href="<?php echo e(route('tasks.edit', $task)); ?>"><?php echo e(__('app.edit')); ?></a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <h6 class="card-title mb-2">
                                        <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-decoration-none text-dark fw-bold"><?php echo e($task->title); ?></a>
                                    </h6>
                                    <div class="d-flex align-items-center text-muted small mb-3">
                                        <i class="bi bi-folder me-1"></i>
                                        <span class="text-truncate" style="max-width: 150px;"><?php echo e($task->project->title); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <div class="d-flex align-items-center">
                                            <?php if($task->assignedUser): ?>
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white"
                                                     style="width: 24px; height: 24px; font-size: 0.7rem;" title="<?php echo e($task->assignedUser->name); ?>">
                                                    <?php echo e(substr($task->assignedUser->name, 0, 1)); ?>

                                                </div>
                                            <?php else: ?>
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-muted border"
                                                     style="width: 24px; height: 24px; font-size: 0.7rem;">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($task->due_date): ?>
                                            <?php
                                                $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                            ?>
                                            <span class="small <?php echo e($dueDate->isPast() && $task->status !== 'completed' ? 'text-danger fw-bold' : 'text-muted'); ?>">
                                                <i class="bi bi-calendar me-1"></i><?php echo e($dueDate->format('M d')); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-funnel me-2 text-primary"></i><?php echo e(__('app.tasks.task_filters')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="GET" action="<?php echo e(route('tasks.index')); ?>" id="filterForm">
                    <div class="mb-3">
                        <label for="search" class="form-label small text-muted text-uppercase fw-bold"><?php echo e(__('app.search')); ?></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="search" name="search"
                                   value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('app.tasks.search_placeholder')); ?>">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label for="status" class="form-label small text-muted text-uppercase fw-bold"><?php echo e(__('app.status')); ?></label>
                            <select class="form-select" id="status" name="status">
                                <option value=""><?php echo e(__('app.tasks.all_statuses')); ?></option>
                                <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.pending')); ?></option>
                                <option value="in_progress" <?php echo e(request('status') === 'in_progress' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.in_progress')); ?></option>
                                <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.completed')); ?></option>
                                <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.cancelled')); ?></option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="priority" class="form-label small text-muted text-uppercase fw-bold"><?php echo e(__('app.tasks.priority')); ?></label>
                            <select class="form-select" id="priority" name="priority">
                                <option value=""><?php echo e(__('app.all_priorities')); ?></option>
                                <option value="low" <?php echo e(request('priority') === 'low' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.low')); ?></option>
                                <option value="medium" <?php echo e(request('priority') === 'medium' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.medium')); ?></option>
                                <option value="high" <?php echo e(request('priority') === 'high' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.high')); ?></option>
                                <option value="urgent" <?php echo e(request('priority') === 'urgent' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.urgent')); ?></option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="project_id" class="form-label small text-muted text-uppercase fw-bold"><?php echo e(__('app.tasks.project')); ?></label>
                            <select class="form-select" id="project_id" name="project_id">
                                <option value=""><?php echo e(__('app.reports.all_projects')); ?></option>
                                <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($project->id); ?>" <?php echo e(request('project_id') == $project->id ? 'selected' : ''); ?>>
                                        <?php echo e($project->title); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                        <div class="col-12">
                            <label for="assigned_to" class="form-label small text-muted text-uppercase fw-bold"><?php echo e(__('app.tasks.assigned_to')); ?></label>
                            <select class="form-select" id="assigned_to" name="assigned_to">
                                <option value=""><?php echo e(__('app.reports.all_users')); ?></option>
                                <option value="unassigned" <?php echo e(request('assigned_to') === 'unassigned' ? 'selected' : ''); ?>><?php echo e(__('app.unassigned')); ?></option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" <?php echo e(request('assigned_to') == $user->id ? 'selected' : ''); ?>>
                                        <?php echo e($user->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0">
                <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-light"><?php echo e(__('app.clear_filters')); ?></a>
                <button type="submit" form="filterForm" class="btn btn-primary px-4"><?php echo e(__('app.filter')); ?></button>
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

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
        // Use the named route pattern and replace the placeholder
        const url = "<?php echo e(route('tasks.update-status', ':id')); ?>".replace(':id', taskId);
        
        axios.post(url, {
            status: status,
            _token: '<?php echo e(csrf_token()); ?>'
        })
        .then(response => {
            // Success
            toastEl.classList.remove('bg-danger');
            toastEl.classList.add('bg-success');
            toastMessage.textContent = '<?php echo e(__('app.tasks.status_updated_successfully')); ?>';
            toast.show();
        })
        .catch(error => {
            // Revert change on error
            originalContainer.appendChild(itemEl);
            
            toastEl.classList.remove('bg-success');
            toastEl.classList.add('bg-danger');
            toastMessage.textContent = '<?php echo e(__('app.tasks.error_updating_status')); ?>';
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/tasks/index.blade.php ENDPATH**/ ?>