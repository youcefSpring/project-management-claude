<?php $__env->startSection('title', __('Projects')); ?>
<?php $__env->startSection('page-title', __('Projects')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Header Actions -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1"><?php echo e(__('Projects')); ?></h2>
                <p class="text-muted mb-0"><?php echo e(__('Manage and track your projects')); ?></p>
            </div>
            <div>
                <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                <a href="<?php echo e(route('projects.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    <?php echo e(__('New Project')); ?>

                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('projects.index')); ?>" class="row g-3">
                    <div class="col-md-4">
                        <label for="status" class="form-label"><?php echo e(__('Status')); ?></label>
                        <select class="form-select" id="status" name="status">
                            <option value=""><?php echo e(__('All Statuses')); ?></option>
                            <option value="planning" <?php echo e(request('status') === 'planning' ? 'selected' : ''); ?>>
                                <?php echo e(__('Planning')); ?>

                            </option>
                            <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>
                                <?php echo e(__('Active')); ?>

                            </option>
                            <option value="on_hold" <?php echo e(request('status') === 'on_hold' ? 'selected' : ''); ?>>
                                <?php echo e(__('On Hold')); ?>

                            </option>
                            <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>
                                <?php echo e(__('Completed')); ?>

                            </option>
                            <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>
                                <?php echo e(__('Cancelled')); ?>

                            </option>
                        </select>
                    </div>

                    <?php if(auth()->user()->isAdmin()): ?>
                    <div class="col-md-4">
                        <label for="manager_id" class="form-label"><?php echo e(__('Manager')); ?></label>
                        <select class="form-select" id="manager_id" name="manager_id">
                            <option value=""><?php echo e(__('All Managers')); ?></option>
                            <?php $__currentLoopData = $managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manager): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($manager->id); ?>" <?php echo e(request('manager_id') == $manager->id ? 'selected' : ''); ?>>
                                    <?php echo e($manager->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="col-md-4">
                        <label for="search" class="form-label"><?php echo e(__('Search')); ?></label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('Search projects...')); ?>">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search me-2"></i>
                            <?php echo e(__('Filter')); ?>

                        </button>
                        <a href="<?php echo e(route('projects.index')); ?>" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-x-circle me-2"></i>
                            <?php echo e(__('Clear')); ?>

                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Projects List -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <?php echo e(__('Projects')); ?>

                    <span class="badge bg-secondary ms-2"><?php echo e($projects->count()); ?></span>
                </h5>
            </div>
            <div class="card-body">
                <?php if($projects->count() > 0): ?>
                    <div class="row">
                        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card border-start border-<?php echo e($project->status === 'active' ? 'success' : ($project->status === 'completed' ? 'primary' : 'warning')); ?> border-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h6 class="card-title mb-0">
                                                <a href="<?php echo e(route('projects.show', $project)); ?>" class="text-decoration-none">
                                                    <?php echo e($project->title); ?>

                                                </a>
                                            </h6>
                                            <span class="badge bg-<?php echo e($project->status === 'active' ? 'success' : ($project->status === 'completed' ? 'primary' : 'warning')); ?>">
                                                <?php echo e(ucfirst($project->status)); ?>

                                            </span>
                                        </div>

                                        <?php if($project->description): ?>
                                            <p class="card-text text-muted small"><?php echo e(Str::limit($project->description, 100)); ?></p>
                                        <?php endif; ?>

                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>
                                                <?php echo e($project->manager->name ?? __('No manager')); ?>

                                            </small>
                                        </div>

                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                <?php if($project->start_date && $project->end_date): ?>
                                                    <?php echo e(\Carbon\Carbon::parse($project->start_date)->format('M d')); ?> - <?php echo e(\Carbon\Carbon::parse($project->end_date)->format('M d, Y')); ?>

                                                <?php else: ?>
                                                    <?php echo e(__('No dates set')); ?>

                                                <?php endif; ?>
                                            </small>
                                        </div>

                                        <?php
                                            $tasksCount = $project->tasks_count ?? $project->tasks->count();
                                            $completedTasks = $project->completed_tasks_count ?? $project->tasks->where('status', 'completed')->count();
                                            $progress = $tasksCount > 0 ? round(($completedTasks / $tasksCount) * 100) : 0;
                                        ?>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted"><?php echo e(__('Progress')); ?></small>
                                                <small class="text-muted"><?php echo e($progress); ?>%</small>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-<?php echo e($progress >= 75 ? 'success' : ($progress >= 50 ? 'warning' : 'danger')); ?>"
                                                     style="width: <?php echo e($progress); ?>%"></div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <?php echo e($tasksCount); ?> <?php echo e(__('tasks')); ?>

                                            </small>
                                            <div>
                                                <a href="<?php echo e(route('projects.show', $project)); ?>" class="btn btn-sm btn-outline-primary">
                                                    <?php echo e(__('View')); ?>

                                                </a>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $project)): ?>
                                                    <a href="<?php echo e(route('projects.edit', $project)); ?>" class="btn btn-sm btn-outline-secondary">
                                                        <?php echo e(__('Edit')); ?>

                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-folder-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted"><?php echo e(__('No projects found')); ?></h5>
                        <p class="text-muted">
                            <?php if(request()->hasAny(['status', 'manager_id', 'search'])): ?>
                                <?php echo e(__('Try adjusting your filters or')); ?>

                                <a href="<?php echo e(route('projects.index')); ?>"><?php echo e(__('clear all filters')); ?></a>
                            <?php else: ?>
                                <?php echo e(__('Get started by creating your first project.')); ?>

                            <?php endif; ?>
                        </p>
                        <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                            <a href="<?php echo e(route('projects.create')); ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                <?php echo e(__('Create Project')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/projects/index.blade.php ENDPATH**/ ?>