<?php $__env->startSection('title', __('app.Dashboard')); ?>
<?php $__env->startSection('page-title', __('app.Dashboard')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Stats Cards -->
    <div class="col-12">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h3 class="mb-0"><?php echo e($stats['total_projects'] ?? 0); ?></h3>
                                <p class="mb-0"><?php echo e(__('app.Projects')); ?></p>
                            </div>
                            <div class="opacity-75">
                                <i class="bi bi-folder fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h3 class="mb-0"><?php echo e($stats['total_tasks'] ?? 0); ?></h3>
                                <p class="mb-0"><?php echo e(__('app.tasks.title')); ?></p>
                            </div>
                            <div class="opacity-75">
                                <i class="bi bi-check2-square fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h3 class="mb-0"><?php echo e($stats['pending_tasks'] ?? 0); ?></h3>
                                <p class="mb-0"><?php echo e(__('app.dashboard.my_tasks')); ?></p>
                            </div>
                            <div class="opacity-75">
                                <i class="bi bi-person-check fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h3 class="mb-0"><?php echo e(number_format($stats['total_time_this_week'] ?? 0, 1)); ?>h</h3>
                                <p class="mb-0"><?php echo e(__('app.time.this_week')); ?></p>
                            </div>
                            <div class="opacity-75">
                                <i class="bi bi-clock fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="col-lg-8">
        <!-- Recent Activity -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-activity me-2"></i>
                    <?php echo e(__('app.dashboard.recent_activity')); ?>

                </h5>
            </div>
            <div class="card-body">
                <?php if($recentActivity && count($recentActivity) > 0): ?>
                    <?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-<?php echo e($activity['type'] === 'task_update' ? 'primary' : 'success'); ?> rounded-circle d-flex align-items-center justify-content-center text-white"
                                     style="width: 40px; height: 40px;">
                                    <i class="bi <?php echo e($activity['type'] === 'task_update' ? 'bi-check2-square' : 'bi-clock'); ?>"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1"><?php echo e($activity['description'] ?? 'Activity'); ?></p>
                                <small class="text-muted"><?php echo e(isset($activity['date']) ? $activity['date']->diffForHumans() : 'Recently'); ?></small>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center text-muted">
                        <i class="bi bi-activity fs-2"></i>
                        <p class="mt-2"><?php echo e(__('app.dashboard.no_recent_activity')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- My Tasks -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-check2-square me-2"></i>
                    <?php echo e(__('app.dashboard.my_tasks')); ?>

                </h5>
                <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-sm btn-primary">
                    <?php echo e(__('app.View all')); ?>

                </a>
            </div>
            <div class="card-body">
                <?php if($myTasks && count($myTasks) > 0): ?>
                    <div class="row">
                        <?php
                            $tasksByStatus = [
                                'pending' => $myTasks->where('status', 'pending'),
                                'in_progress' => $myTasks->where('status', 'in_progress'),
                                'completed' => $myTasks->where('status', 'completed')
                            ];
                        ?>

                        <div class="col-md-4">
                            <h6 class="text-warning"><?php echo e(__('app.tasks.pending')); ?></h6>
                            <?php $__currentLoopData = $tasksByStatus['pending']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card border-start border-warning border-3 mb-2">
                                    <div class="card-body p-2">
                                        <h6 class="card-title mb-1">
                                            <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-decoration-none"><?php echo e($task->title); ?></a>
                                        </h6>
                                        <small class="text-muted"><?php echo e($task->project->title ?? ''); ?></small>
                                        <?php if($task->due_date): ?>
                                            <br><small class="text-muted"><?php echo e(__('app.tasks.due_date')); ?>: <?php echo e(is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : $task->due_date->format('M d, Y')); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="col-md-4">
                            <h6 class="text-info"><?php echo e(__('app.tasks.in_progress')); ?></h6>
                            <?php $__currentLoopData = $tasksByStatus['in_progress']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card border-start border-info border-3 mb-2">
                                    <div class="card-body p-2">
                                        <h6 class="card-title mb-1">
                                            <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-decoration-none"><?php echo e($task->title); ?></a>
                                        </h6>
                                        <small class="text-muted"><?php echo e($task->project->title ?? ''); ?></small>
                                        <?php if($task->due_date): ?>
                                            <br><small class="text-muted"><?php echo e(__('app.tasks.due_date')); ?>: <?php echo e(is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : $task->due_date->format('M d, Y')); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="col-md-4">
                            <h6 class="text-success"><?php echo e(__('app.tasks.completed')); ?></h6>
                            <?php $__currentLoopData = $tasksByStatus['completed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card border-start border-success border-3 mb-2">
                                    <div class="card-body p-2">
                                        <h6 class="card-title mb-1">
                                            <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-decoration-none"><?php echo e($task->title); ?></a>
                                        </h6>
                                        <small class="text-muted"><?php echo e($task->project->title ?? ''); ?></small>
                                        <?php if($task->due_date): ?>
                                            <br><small class="text-muted"><?php echo e(__('app.tasks.due_date')); ?>: <?php echo e(is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : $task->due_date->format('M d, Y')); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted">
                        <i class="bi bi-check2-square fs-2"></i>
                        <p class="mt-2"><?php echo e(__('app.tasks.no_tasks')); ?></p>
                        <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-outline-primary">
                            <?php echo e(__('app.tasks.browse_available')); ?>

                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    <?php echo e(__('app.dashboard.quick_actions')); ?>

                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                    <a href="<?php echo e(route('projects.create')); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle me-2"></i>
                        <?php echo e(__('app.projects.create')); ?>

                    </a>
                    <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-outline-success">
                        <i class="bi bi-plus-square me-2"></i>
                        <?php echo e(__('app.tasks.create')); ?>

                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('timesheet.create')); ?>" class="btn btn-outline-info">
                        <i class="bi bi-clock me-2"></i>
                        <?php echo e(__('app.time.log_time')); ?>

                    </a>
                    <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                    <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-outline-warning">
                        <i class="bi bi-graph-up me-2"></i>
                        <?php echo e(__('app.reports.title')); ?>

                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-bell me-2"></i>
                    <?php echo e(__('app.Notifications')); ?>

                </h5>
                <?php if($notifications && count($notifications) > 0): ?>
                    <span class="badge bg-primary"><?php echo e(count($notifications)); ?></span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if($notifications && count($notifications) > 0): ?>
                    <?php $__currentLoopData = array_slice($notifications, 0, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white"
                                     style="width: 30px; height: 30px;">
                                    <i class="bi bi-bell-fill"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1 small"><?php echo e($notification['title'] ?? 'Notification'); ?></p>
                                <small class="text-muted"><?php echo e($notification['message'] ?? ''); ?></small>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center text-muted">
                        <i class="bi bi-bell-slash fs-2"></i>
                        <p class="mt-2"><?php echo e(__('app.No notifications')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Time Summary -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-stopwatch me-2"></i>
                    <?php echo e(__('app.reports.time_summary')); ?>

                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="fs-4 fw-bold text-primary"><?php echo e(number_format($stats['total_time_today'] ?? 0, 1)); ?>h</div>
                    <p class="text-muted"><?php echo e(__("app.time.today")); ?></p>
                </div>

                <div class="mb-3">
                    <small class="text-muted"><?php echo e(__('app.time.this_week')); ?>: </small>
                    <span class="fw-bold"><?php echo e(number_format($stats['total_time_this_week'] ?? 0, 1)); ?>h</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted"><?php echo e(__('app.time.this_month')); ?>: </small>
                    <span class="fw-bold"><?php echo e(number_format($stats['total_time_this_month'] ?? 0, 1)); ?>h</span>
                </div>

                <div class="d-grid">
                    <a href="<?php echo e(route('timesheet.create')); ?>" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>
                        <?php echo e(__('app.time.log_time')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/dashboard/index.blade.php ENDPATH**/ ?>