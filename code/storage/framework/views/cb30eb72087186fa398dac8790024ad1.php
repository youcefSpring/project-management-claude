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
                <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    <?php echo e(__('app.tasks.new_task')); ?>

                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-muted">
                    <i class="bi bi-funnel me-2"></i><?php echo e(__('app.tasks.task_filters')); ?>

                </h6>
                <button type="button" id="toggleFilters" class="btn btn-sm btn-outline-secondary" title="<?php echo e(__('app.toggle_filters')); ?>">
                    <i class="bi bi-chevron-up" id="toggleFiltersIcon"></i>
                </button>
            </div>
            <div class="card-body p-3" id="filtersContent">
                <form method="GET" action="<?php echo e(route('tasks.index')); ?>" class="row g-3 align-items-end">
                    <div class="col-sm-6 col-md-3">
                        <label for="status" class="form-label small text-muted"><?php echo e(__('app.status')); ?></label>
                        <select class="form-select form-select-sm" id="status" name="status">
                            <option value=""><?php echo e(__('app.tasks.all_statuses')); ?></option>
                            <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.tasks.pending')); ?>

                            </option>
                            <option value="in_progress" <?php echo e(request('status') === 'in_progress' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.tasks.in_progress')); ?>

                            </option>
                            <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.tasks.completed')); ?>

                            </option>
                            <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.tasks.cancelled')); ?>

                            </option>
                        </select>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <label for="project_id" class="form-label small text-muted"><?php echo e(__('app.tasks.project')); ?></label>
                        <select class="form-select form-select-sm" id="project_id" name="project_id">
                            <option value=""><?php echo e(__('app.reports.all_projects')); ?></option>
                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($project->id); ?>" <?php echo e(request('project_id') == $project->id ? 'selected' : ''); ?>>
                                    <?php echo e($project->title); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                    <div class="col-sm-6 col-md-3">
                        <label for="assigned_to" class="form-label small text-muted"><?php echo e(__('app.tasks.assigned_to')); ?></label>
                        <select class="form-select form-select-sm" id="assigned_to" name="assigned_to">
                            <option value=""><?php echo e(__('app.reports.all_users')); ?></option>
                            <option value="unassigned" <?php echo e(request('assigned_to') === 'unassigned' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.unassigned')); ?>

                            </option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" <?php echo e(request('assigned_to') == $user->id ? 'selected' : ''); ?>>
                                    <?php echo e($user->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="col-sm-6 col-md-3">
                        <label for="priority" class="form-label small text-muted"><?php echo e(__('app.tasks.priority')); ?></label>
                        <select class="form-select form-select-sm" id="priority" name="priority">
                            <option value=""><?php echo e(__('app.all_priorities')); ?></option>
                            <option value="low" <?php echo e(request('priority') === 'low' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.tasks.low')); ?>

                            </option>
                            <option value="medium" <?php echo e(request('priority') === 'medium' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.tasks.medium')); ?>

                            </option>
                            <option value="high" <?php echo e(request('priority') === 'high' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.tasks.high')); ?>

                            </option>
                            <option value="urgent" <?php echo e(request('priority') === 'urgent' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.tasks.urgent')); ?>

                            </option>
                        </select>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <label for="search" class="form-label small text-muted"><?php echo e(__('app.search')); ?></label>
                        <input type="text" class="form-control form-control-sm" id="search" name="search"
                               value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('app.tasks.search_placeholder')); ?>">
                    </div>

                    <div class="col-sm-6 col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-outline-secondary btn-sm flex-fill">
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
                    <?php echo e(__('app.tasks.title')); ?>

                    <span class="badge bg-secondary ms-2"><?php echo e($tasks->count()); ?></span>
                </h5>
            </div>
            <div class="card-body">
                <?php if($tasks->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th><?php echo e(__('app.tasks.task_name')); ?></th>
                                    <th class="d-none d-md-table-cell"><?php echo e(__('app.tasks.project')); ?></th>
                                    <th class="d-none d-lg-table-cell"><?php echo e(__('app.tasks.assigned_to')); ?></th>
                                    <th><?php echo e(__('app.status')); ?></th>
                                    <th class="d-none d-md-table-cell"><?php echo e(__('app.tasks.priority')); ?></th>
                                    <th class="d-none d-lg-table-cell"><?php echo e(__('app.tasks.due_date')); ?></th>
                                    <th class="d-none d-xl-table-cell"><?php echo e(__('app.tasks.created')); ?></th>
                                    <th width="120"><?php echo e(__('app.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div>
                                                <a href="<?php echo e(route('tasks.show', $task)); ?>" class="fw-bold text-decoration-none">
                                                    <?php echo e($task->title); ?>

                                                </a>
                                                <?php if($task->description): ?>
                                                    <br>
                                                    <small class="text-muted"><?php echo e(Str::limit($task->description, 60)); ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                     style="width: 24px; height: 24px;">
                                                    <i class="bi bi-folder-fill" style="font-size: 0.8rem;"></i>
                                                </div>
                                                <span><?php echo e($task->project->title); ?></span>
                                            </div>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <?php if($task->assignedUser): ?>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                         style="width: 24px; height: 24px;">
                                                        <i class="bi bi-person-fill" style="font-size: 0.8rem;"></i>
                                                    </div>
                                                    <span><?php echo e($task->assignedUser->name); ?></span>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('app.unassigned')); ?></span>
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
                                            <span class="badge bg-<?php echo e($color); ?>">
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
                                            ?>
                                            <span class="badge bg-<?php echo e($priorityColor); ?>">
                                                <?php switch($task->priority):
                                                    case ('low'): ?> <?php echo e(__('app.tasks.low')); ?> <?php break; ?>
                                                    <?php case ('medium'): ?> <?php echo e(__('app.tasks.medium')); ?> <?php break; ?>
                                                    <?php case ('high'): ?> <?php echo e(__('app.tasks.high')); ?> <?php break; ?>
                                                    <?php case ('urgent'): ?> <?php echo e(__('app.tasks.urgent')); ?> <?php break; ?>
                                                    <?php default: ?> <?php echo e(ucfirst($task->priority)); ?>

                                                <?php endswitch; ?>
                                            </span>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <?php if($task->due_date): ?>
                                                <?php
                                                    $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                                ?>
                                                <div>
                                                    <span class="fw-bold"><?php echo e($dueDate->format('M d, Y')); ?></span>
                                                    <?php if($dueDate->isPast() && $task->status !== 'completed'): ?>
                                                        <br><span class="badge bg-danger"><?php echo e(__('app.tasks.overdue')); ?></span>
                                                    <?php elseif($dueDate->isToday()): ?>
                                                        <br><span class="badge bg-warning"><?php echo e(__('app.tasks.due_today')); ?></span>
                                                    <?php elseif($dueDate->isTomorrow()): ?>
                                                        <br><span class="badge bg-info"><?php echo e(__('app.tasks.due_tomorrow')); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('app.tasks.no_due_date')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-none d-xl-table-cell">
                                            <small class="text-muted">
                                                <?php echo e($task->created_at->format('M d, Y')); ?>

                                                <br>
                                                <?php echo e($task->created_at->diffForHumans()); ?>

                                            </small>
                                        </td>
                                        <td>
                                            <div class="dropdown dropstart">
                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 180px;">
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('tasks.show', $task)); ?>">
                                                            <i class="bi bi-eye me-2"></i><?php echo e(__('app.tasks.view')); ?>

                                                        </a>
                                                    </li>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                                                        <li>
                                                            <a class="dropdown-item" href="<?php echo e(route('tasks.edit', $task)); ?>">
                                                                <i class="bi bi-pencil me-2"></i><?php echo e(__('app.edit')); ?>

                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if($task->status !== 'completed'): ?>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <?php if($task->status === 'pending'): ?>
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="changeTaskStatus(<?php echo e($task->id); ?>, 'in_progress')">
                                                                    <i class="bi bi-play-circle me-2 text-primary"></i><?php echo e(__('app.tasks.start_task')); ?>

                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if($task->status === 'in_progress'): ?>
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="changeTaskStatus(<?php echo e($task->id); ?>, 'completed')">
                                                                    <i class="bi bi-check-circle me-2 text-success"></i><?php echo e(__('app.tasks.mark_complete')); ?>

                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
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
                        <i class="bi bi-check2-square fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted"><?php echo e(__('app.tasks.no_tasks')); ?></h5>
                        <p class="text-muted">
                            <?php if(request()->hasAny(['status', 'project_id', 'assigned_to', 'search'])): ?>
                                <?php echo e(__('app.try_adjusting_filters')); ?>

                                <a href="<?php echo e(route('tasks.index')); ?>"><?php echo e(__('app.clear_filters')); ?></a>
                            <?php else: ?>
                                <?php echo e(__('app.tasks.get_started_create_task')); ?>

                            <?php endif; ?>
                        </p>
                        <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                            <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                <?php echo e(__('app.tasks.create')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('app.tasks.confirm_status_change')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage"><?php echo e(__('app.tasks.confirm_status_change_message')); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                <button type="button" class="btn btn-primary" id="confirmStatusBtn">
                    <span id="confirmBtnText"><?php echo e(__('app.confirm')); ?></span>
                    <span class="spinner-border spinner-border-sm ms-2 d-none" id="confirmSpinner"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let currentTaskId = null;
let currentStatus = null;

function changeTaskStatus(taskId, status) {
    currentTaskId = taskId;
    currentStatus = status;

    // Update modal content based on status
    const statusMessages = {
        'in_progress': '<?php echo e(__('app.tasks.confirm_start_task')); ?>',
        'completed': '<?php echo e(__('app.tasks.confirm_complete_task')); ?>',
        'cancelled': '<?php echo e(__('app.tasks.confirm_cancel_task')); ?>'
    };

    document.getElementById('confirmMessage').textContent = statusMessages[status] || '<?php echo e(__('app.tasks.confirm_status_change_message')); ?>';

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
    btnText.textContent = '<?php echo e(__('app.processing')); ?>';
    spinner.classList.remove('d-none');

    axios.post(`/tasks/${currentTaskId}/status`, {
        status: currentStatus,
        _token: '<?php echo e(csrf_token()); ?>'
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
            throw new Error(response.data.message || '<?php echo e(__('app.tasks.error_updating_status')); ?>');
        }
    })
    .catch(error => {
        console.error('Error:', error);

        // Show error in modal
        document.getElementById('confirmMessage').innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                ${error.response?.data?.message || '<?php echo e(__('app.tasks.error_updating_status')); ?>'}
            </div>
        `;
    })
    .finally(() => {
        // Reset button state
        btn.disabled = false;
        btnText.textContent = '<?php echo e(__('app.confirm')); ?>';
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/tasks/index.blade.php ENDPATH**/ ?>