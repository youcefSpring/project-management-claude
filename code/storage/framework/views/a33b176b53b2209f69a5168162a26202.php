<?php $__env->startSection('title', __('app.reports.title')); ?>
<?php $__env->startSection('page-title', __('app.reports.analytics')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Header Actions -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1"><?php echo e(__('app.reports.title')); ?></h2>
                <p class="text-muted mb-0"><?php echo e(__('app.reports.view_analytics_and_insights')); ?></p>
            </div>
            <div>
                <a href="<?php echo e(route('reports.export')); ?>" class="btn btn-outline-success me-2">
                    <i class="bi bi-download me-2"></i><?php echo e(__('app.export')); ?>

                </a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateReportModal">
                    <i class="bi bi-graph-up me-2"></i><?php echo e(__('app.reports.generate_report')); ?>

                </button>
            </div>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title"><?php echo e(__('app.dashboard.total_projects')); ?></h6>
                                <h3 class="mb-0"><?php echo e($overview['total_projects'] ?? 0); ?></h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-folder fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title"><?php echo e(__('app.dashboard.total_tasks')); ?></h6>
                                <h3 class="mb-0"><?php echo e($overview['total_tasks'] ?? 0); ?></h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check2-square fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title"><?php echo e(__('app.reports.total_hours')); ?></h6>
                                <h3 class="mb-0"><?php echo e(number_format($overview['total_hours'] ?? 0, 1)); ?>h</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-clock fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title"><?php echo e(__('app.dashboard.active_users')); ?></h6>
                                <h3 class="mb-0"><?php echo e($overview['active_users'] ?? 0); ?></h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-people fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Links -->
    <div class="col-12">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-folder-fill text-primary fs-1 mb-3"></i>
                        <h5 class="card-title"><?php echo e(__('app.reports.project_reports')); ?></h5>
                        <p class="card-text"><?php echo e(__('app.reports.project_reports_desc')); ?></p>
                        <a href="<?php echo e(route('reports.projects')); ?>" class="btn btn-primary">
                            <?php echo e(__('app.reports.view_project_reports')); ?>

                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill text-success fs-1 mb-3"></i>
                        <h5 class="card-title"><?php echo e(__('app.reports.user_reports')); ?></h5>
                        <p class="card-text"><?php echo e(__('app.reports.user_reports_desc')); ?></p>
                        <a href="<?php echo e(route('reports.users')); ?>" class="btn btn-success">
                            <?php echo e(__('app.reports.view_user_reports')); ?>

                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-clock-fill text-warning fs-1 mb-3"></i>
                        <h5 class="card-title"><?php echo e(__('app.reports.time_tracking_reports')); ?></h5>
                        <p class="card-text"><?php echo e(__('app.reports.time_tracking_reports_desc')); ?></p>
                        <a href="<?php echo e(route('reports.time-tracking')); ?>" class="btn btn-warning">
                            <?php echo e(__('app.reports.view_time_reports')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Reports Section -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('app.reports.quick_reports')); ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><?php echo e(__('app.dashboard.recent_activity')); ?></h6>
                        <div class="list-group list-group-flush">
                            <?php if(isset($recent_activity) && count($recent_activity) > 0): ?>
                                <?php $__currentLoopData = $recent_activity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1"><?php echo e($activity['title'] ?? 'Activity'); ?></h6>
                                                <p class="mb-1 small"><?php echo e($activity['description'] ?? ''); ?></p>
                                                <small class="text-muted"><?php echo e($activity['date'] ?? ''); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="list-group-item">
                                    <p class="text-muted mb-0"><?php echo e(__('app.dashboard.no_recent_activity')); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6><?php echo e(__('app.reports.project_status_overview')); ?></h6>
                        <?php if(isset($project_status) && count($project_status) > 0): ?>
                            <?php $__currentLoopData = $project_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-<?php echo e($status === 'active' ? 'success' : ($status === 'completed' ? 'primary' : 'warning')); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $status))); ?>

                                    </span>
                                    <span><?php echo e($count); ?> <?php echo e(__('app.projects_plural')); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <p class="text-muted"><?php echo e(__('app.no_data_available')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/reports/index.blade.php ENDPATH**/ ?>