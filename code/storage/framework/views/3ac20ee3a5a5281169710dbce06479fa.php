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
                    <div class="row">
                        <?php
                            $groupedTasks = $tasks->groupBy('status');
                        ?>

                        <!-- Pending Tasks -->
                        <div class="col-md-4">
                            <h6 class="text-warning mb-3">
                                <i class="bi bi-clock me-1"></i>
                                <?php echo e(__('Pending')); ?>

                                <span class="badge bg-warning text-dark"><?php echo e($groupedTasks->get('pending', collect())->count()); ?></span>
                            </h6>
                            <?php $__currentLoopData = $groupedTasks->get('pending', collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card border-start border-warning border-3 mb-3">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">
                                            <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-decoration-none">
                                                <?php echo e($task->title); ?>

                                            </a>
                                        </h6>

                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-folder me-1"></i>
                                                <?php echo e($task->project->title); ?>

                                            </small>
                                        </div>

                                        <?php if($task->assignedUser): ?>
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>
                                                <?php echo e($task->assignedUser->name); ?>

                                            </small>
                                        </div>
                                        <?php endif; ?>

                                        <?php if($task->due_date): ?>
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                <?php
                                                    $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                                ?>
                                                <?php echo e($dueDate->format('M d, Y')); ?>

                                                <?php if($dueDate->isPast()): ?>
                                                    <span class="badge bg-danger ms-1"><?php echo e(__('Overdue')); ?></span>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                        <?php endif; ?>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-<?php echo e($task->priority === 'urgent' ? 'danger' : ($task->priority === 'high' ? 'warning' : 'secondary')); ?>">
                                                <?php echo e(ucfirst($task->priority)); ?>

                                            </span>
                                            <div>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                                                    <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="btn btn-sm btn-outline-secondary">
                                                        <?php echo e(__('Edit')); ?>

                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- In Progress Tasks -->
                        <div class="col-md-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-play-circle me-1"></i>
                                <?php echo e(__('In Progress')); ?>

                                <span class="badge bg-primary"><?php echo e($groupedTasks->get('in_progress', collect())->count()); ?></span>
                            </h6>
                            <?php $__currentLoopData = $groupedTasks->get('in_progress', collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card border-start border-primary border-3 mb-3">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">
                                            <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-decoration-none">
                                                <?php echo e($task->title); ?>

                                            </a>
                                        </h6>

                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-folder me-1"></i>
                                                <?php echo e($task->project->title); ?>

                                            </small>
                                        </div>

                                        <?php if($task->assignedUser): ?>
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>
                                                <?php echo e($task->assignedUser->name); ?>

                                            </small>
                                        </div>
                                        <?php endif; ?>

                                        <?php if($task->due_date): ?>
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                <?php
                                                    $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                                ?>
                                                <?php echo e($dueDate->format('M d, Y')); ?>

                                                <?php if($dueDate->isPast()): ?>
                                                    <span class="badge bg-danger ms-1"><?php echo e(__('Overdue')); ?></span>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                        <?php endif; ?>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-<?php echo e($task->priority === 'urgent' ? 'danger' : ($task->priority === 'high' ? 'warning' : 'secondary')); ?>">
                                                <?php echo e(ucfirst($task->priority)); ?>

                                            </span>
                                            <div>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                                                    <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="btn btn-sm btn-outline-secondary">
                                                        <?php echo e(__('Edit')); ?>

                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- Completed Tasks -->
                        <div class="col-md-4">
                            <h6 class="text-success mb-3">
                                <i class="bi bi-check-circle me-1"></i>
                                <?php echo e(__('Completed')); ?>

                                <span class="badge bg-success"><?php echo e($groupedTasks->get('completed', collect())->count()); ?></span>
                            </h6>
                            <?php $__currentLoopData = $groupedTasks->get('completed', collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card border-start border-success border-3 mb-3">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">
                                            <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-decoration-none">
                                                <?php echo e($task->title); ?>

                                            </a>
                                        </h6>

                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-folder me-1"></i>
                                                <?php echo e($task->project->title); ?>

                                            </small>
                                        </div>

                                        <?php if($task->assignedUser): ?>
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>
                                                <?php echo e($task->assignedUser->name); ?>

                                            </small>
                                        </div>
                                        <?php endif; ?>

                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-check-circle me-1"></i>
                                                <?php echo e(__('Completed')); ?> <?php echo e($task->updated_at->diffForHumans()); ?>

                                            </small>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-success">
                                                <?php echo e(ucfirst($task->priority)); ?>

                                            </span>
                                            <div>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                                                    <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="btn btn-sm btn-outline-secondary">
                                                        <?php echo e(__('Edit')); ?>

                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <?php if($groupedTasks->has('cancelled') && $groupedTasks->get('cancelled')->count() > 0): ?>
                    <div class="mt-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-x-circle me-1"></i>
                            <?php echo e(__('Cancelled')); ?>

                            <span class="badge bg-secondary"><?php echo e($groupedTasks->get('cancelled')->count()); ?></span>
                        </h6>
                        <div class="row">
                            <?php $__currentLoopData = $groupedTasks->get('cancelled', collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4">
                                    <div class="card border-start border-secondary border-3 mb-3">
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-2">
                                                <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-decoration-none text-muted">
                                                    <?php echo e($task->title); ?>

                                                </a>
                                            </h6>
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-folder me-1"></i>
                                                    <?php echo e($task->project->title); ?>

                                                </small>
                                            </div>
                                            <span class="badge bg-secondary"><?php echo e(__('Cancelled')); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/tasks/index.blade.php ENDPATH**/ ?>