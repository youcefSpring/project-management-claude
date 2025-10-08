<?php $__env->startSection('title', __('Tasks')); ?>
<?php $__env->startSection('page-title', __('Tasks')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Header Actions -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1"><?php echo e(__('Tasks')); ?></h2>
                <p class="text-muted mb-0"><?php echo e(__('Manage and track your tasks')); ?></p>
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
                <form method="GET" action="<?php echo e(route('tasks.index')); ?>" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label"><?php echo e(__('Status')); ?></label>
                        <select class="form-select" id="status" name="status">
                            <option value=""><?php echo e(__('All Statuses')); ?></option>
                            <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>
                                <?php echo e(__('Pending')); ?>

                            </option>
                            <option value="in_progress" <?php echo e(request('status') === 'in_progress' ? 'selected' : ''); ?>>
                                <?php echo e(__('In Progress')); ?>

                            </option>
                            <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>
                                <?php echo e(__('Completed')); ?>

                            </option>
                            <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>
                                <?php echo e(__('Cancelled')); ?>

                            </option>
                        </select>
                    </div>

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

                    <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                    <div class="col-md-3">
                        <label for="assigned_to" class="form-label"><?php echo e(__('Assigned To')); ?></label>
                        <select class="form-select" id="assigned_to" name="assigned_to">
                            <option value=""><?php echo e(__('All Users')); ?></option>
                            <option value="unassigned" <?php echo e(request('assigned_to') === 'unassigned' ? 'selected' : ''); ?>>
                                <?php echo e(__('Unassigned')); ?>

                            </option>
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
                        <input type="text" class="form-control" id="search" name="search"
                               value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('Search tasks...')); ?>">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search me-2"></i>
                            <?php echo e(__('Filter')); ?>

                        </button>
                        <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-x-circle me-2"></i>
                            <?php echo e(__('Clear')); ?>

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
                    <?php echo e(__('Tasks')); ?>

                    <span class="badge bg-secondary ms-2"><?php echo e($tasks->count()); ?></span>
                </h5>
            </div>
            <div class="card-body">
                <?php if($tasks->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th><?php echo e(__('Task')); ?></th>
                                    <th><?php echo e(__('Project')); ?></th>
                                    <th><?php echo e(__('Assigned To')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Priority')); ?></th>
                                    <th><?php echo e(__('Due Date')); ?></th>
                                    <th><?php echo e(__('Created')); ?></th>
                                    <th><?php echo e(__('Actions')); ?></th>
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
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                     style="width: 24px; height: 24px;">
                                                    <i class="bi bi-folder-fill" style="font-size: 0.8rem;"></i>
                                                </div>
                                                <span><?php echo e($task->project->title); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($task->assignedUser): ?>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                         style="width: 24px; height: 24px;">
                                                        <i class="bi bi-person-fill" style="font-size: 0.8rem;"></i>
                                                    </div>
                                                    <span><?php echo e($task->assignedUser->name); ?></span>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('Unassigned')); ?></span>
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
                                                <?php echo e(ucfirst(str_replace('_', ' ', $task->status))); ?>

                                            </span>
                                        </td>
                                        <td>
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
                                                <?php echo e(ucfirst($task->priority)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($task->due_date): ?>
                                                <?php
                                                    $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                                ?>
                                                <div>
                                                    <span class="fw-bold"><?php echo e($dueDate->format('M d, Y')); ?></span>
                                                    <?php if($dueDate->isPast() && $task->status !== 'completed'): ?>
                                                        <br><span class="badge bg-danger"><?php echo e(__('Overdue')); ?></span>
                                                    <?php elseif($dueDate->isToday()): ?>
                                                        <br><span class="badge bg-warning"><?php echo e(__('Due Today')); ?></span>
                                                    <?php elseif($dueDate->isTomorrow()): ?>
                                                        <br><span class="badge bg-info"><?php echo e(__('Due Tomorrow')); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('No due date')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo e($task->created_at->format('M d, Y')); ?>

                                                <br>
                                                <?php echo e($task->created_at->diffForHumans()); ?>

                                            </small>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('tasks.show', $task)); ?>">
                                                            <i class="bi bi-eye me-2"></i><?php echo e(__('View')); ?>

                                                        </a>
                                                    </li>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                                                        <li>
                                                            <a class="dropdown-item" href="<?php echo e(route('tasks.edit', $task)); ?>">
                                                                <i class="bi bi-pencil me-2"></i><?php echo e(__('Edit')); ?>

                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if($task->status !== 'completed'): ?>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <?php if($task->status === 'pending'): ?>
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="changeTaskStatus(<?php echo e($task->id); ?>, 'in_progress')">
                                                                    <i class="bi bi-play-circle me-2 text-primary"></i><?php echo e(__('Start Task')); ?>

                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if($task->status === 'in_progress'): ?>
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="changeTaskStatus(<?php echo e($task->id); ?>, 'completed')">
                                                                    <i class="bi bi-check-circle me-2 text-success"></i><?php echo e(__('Mark Complete')); ?>

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
                        <h5 class="text-muted"><?php echo e(__('No tasks found')); ?></h5>
                        <p class="text-muted">
                            <?php if(request()->hasAny(['status', 'project_id', 'assigned_to', 'search'])): ?>
                                <?php echo e(__('Try adjusting your filters or')); ?>

                                <a href="<?php echo e(route('tasks.index')); ?>"><?php echo e(__('clear all filters')); ?></a>
                            <?php else: ?>
                                <?php echo e(__('Get started by creating your first task.')); ?>

                            <?php endif; ?>
                        </p>
                        <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                            <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                <?php echo e(__('Create Task')); ?>

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
function changeTaskStatus(taskId, status) {
    if (confirm(`<?php echo e(__('Are you sure you want to change the task status?')); ?>`)) {
        axios.post(`/tasks/${taskId}/status`, {
            status: status,
            _token: '<?php echo e(csrf_token()); ?>'
        })
        .then(response => {
            if (response.data.success) {
                location.reload();
            } else {
                alert('<?php echo e(__('Error updating task status')); ?>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?php echo e(__('Error updating task status')); ?>');
        });
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/tasks/index.blade.php ENDPATH**/ ?>