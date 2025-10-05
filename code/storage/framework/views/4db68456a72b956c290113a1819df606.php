<?php $__env->startSection('title', __('Profile')); ?>
<?php $__env->startSection('page-title', __('Profile')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Profile Information')); ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong><?php echo e(__('Name')); ?>:</strong>
                        <p><?php echo e(auth()->user()->name); ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong><?php echo e(__('Email')); ?>:</strong>
                        <p><?php echo e(auth()->user()->email); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong><?php echo e(__('Role')); ?>:</strong>
                        <p>
                            <span class="badge bg-<?php echo e(auth()->user()->role === 'admin' ? 'danger' : (auth()->user()->role === 'manager' ? 'warning' : 'info')); ?>">
                                <?php echo e(ucfirst(auth()->user()->role)); ?>

                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong><?php echo e(__('Member Since')); ?>:</strong>
                        <p><?php echo e(auth()->user()->created_at->format('M d, Y')); ?></p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="<?php echo e(route('profile.settings')); ?>" class="btn btn-primary">
                        <i class="bi bi-gear me-2"></i>
                        <?php echo e(__('Edit Profile')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Quick Stats')); ?></h5>
            </div>
            <div class="card-body">
                <?php
                    $user = auth()->user();
                    $myTasks = \App\Models\Task::where('assigned_to', $user->id)->count();
                    $completedTasks = \App\Models\Task::where('assigned_to', $user->id)->where('status', 'completed')->count();
                    $totalTimeThisMonth = \App\Models\TimeEntry::where('user_id', $user->id)
                        ->whereBetween('start_time', [now()->startOfMonth(), now()->endOfMonth()])
                        ->sum('duration_hours');
                ?>

                <div class="mb-3">
                    <strong><?php echo e(__('My Tasks')); ?>:</strong>
                    <span class="float-end"><?php echo e($myTasks); ?></span>
                </div>
                <div class="mb-3">
                    <strong><?php echo e(__('Completed Tasks')); ?>:</strong>
                    <span class="float-end"><?php echo e($completedTasks); ?></span>
                </div>
                <div class="mb-3">
                    <strong><?php echo e(__('Time This Month')); ?>:</strong>
                    <span class="float-end"><?php echo e(number_format($totalTimeThisMonth, 1)); ?>h</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/profile/index.blade.php ENDPATH**/ ?>