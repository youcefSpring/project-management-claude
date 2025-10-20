<?php $__env->startSection('title', __('app.reports.user_activity')); ?>
<?php $__env->startSection('page-title', __('app.reports.user_activity')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>
                    <?php echo e(__('app.reports.user_activity')); ?>

                </h5>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="<?php echo e(route('reports.users')); ?>" class="row mb-4">
                    <div class="col-md-3">
                        <label for="project_id" class="form-label"><?php echo e(__('app.projects.title')); ?></label>
                        <select class="form-select" id="project_id" name="project_id">
                            <option value=""><?php echo e(__('app.reports.all_projects')); ?></option>
                            <?php if(isset($data['projects'])): ?>
                                <?php $__currentLoopData = $data['projects']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($project->id); ?>" <?php echo e(request('project_id') == $project->id ? 'selected' : ''); ?>>
                                        <?php echo e($project->title); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label"><?php echo e(__('app.projects.start_date')); ?></label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo e(request('start_date')); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label"><?php echo e(__('app.projects.end_date')); ?></label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo e(request('end_date')); ?>">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search me-1"></i><?php echo e(__('app.filter')); ?>

                        </button>
                        <a href="<?php echo e(route('reports.users')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i><?php echo e(__('app.cancel')); ?>

                        </a>
                    </div>
                </form>

                <!-- Report Content -->
                <?php if(isset($data['users']) && count($data['users']) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('app.users.title')); ?></th>
                                    <th><?php echo e(__('app.users.role')); ?></th>
                                    <th><?php echo e(__('app.tasks.title')); ?></th>
                                    <th><?php echo e(__('app.dashboard.completed_tasks')); ?></th>
                                    <th><?php echo e(__('Completion Rate')); ?></th>
                                    <th><?php echo e(__('app.time.total_time')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data['users']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                     style="width: 32px; height: 32px;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?php echo e($user['name'] ?? 'N/A'); ?></div>
                                                    <small class="text-muted"><?php echo e($user['email'] ?? ''); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo e(ucfirst($user['role'] ?? 'member')); ?></span>
                                        </td>
                                        <td><?php echo e($user['total_tasks'] ?? 0); ?></td>
                                        <td><?php echo e($user['completed_tasks'] ?? 0); ?></td>
                                        <td>
                                            <?php
                                                $rate = ($user['total_tasks'] ?? 0) > 0
                                                    ? round((($user['completed_tasks'] ?? 0) / ($user['total_tasks'] ?? 1)) * 100)
                                                    : 0;
                                            ?>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2"><?php echo e($rate); ?>%</span>
                                                <div class="progress" style="width: 60px; height: 8px;">
                                                    <div class="progress-bar bg-<?php echo e($rate >= 75 ? 'success' : ($rate >= 50 ? 'warning' : 'danger')); ?>"
                                                         style="width: <?php echo e($rate); ?>%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo e(number_format($user['total_hours'] ?? 0, 1)); ?>h</td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-people fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted"><?php echo e(__('app.users.no_users')); ?></h5>
                        <p class="text-muted"><?php echo e(__('Try adjusting your filters to see user reports.')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/reports/users.blade.php ENDPATH**/ ?>