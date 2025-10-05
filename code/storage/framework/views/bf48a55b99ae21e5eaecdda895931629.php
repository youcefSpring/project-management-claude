<?php $__env->startSection('title', __('Log Time Entry')); ?>
<?php $__env->startSection('page-title', __('Log New Time Entry')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Time Entry Information')); ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('timesheet.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label for="task_id" class="form-label"><?php echo e(__('Task')); ?> <span class="text-danger">*</span></label>
                        <select class="form-select <?php $__errorArgs = ['task_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="task_id" name="task_id" required>
                            <option value=""><?php echo e(__('Select a task')); ?></option>
                            <?php $__currentLoopData = $availableTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($task->id); ?>"
                                    <?php echo e(old('task_id', request('task_id')) == $task->id ? 'selected' : ''); ?>>
                                    <?php echo e($task->title); ?> (<?php echo e($task->project->title); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['task_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_time" class="form-label"><?php echo e(__('Start Time')); ?> <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="start_time" name="start_time"
                                       value="<?php echo e(old('start_time', now()->format('Y-m-d\TH:i'))); ?>" required>
                                <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_time" class="form-label"><?php echo e(__('End Time')); ?> <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="end_time" name="end_time"
                                       value="<?php echo e(old('end_time', now()->addHour()->format('Y-m-d\TH:i'))); ?>" required>
                                <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="duration_hours" class="form-label"><?php echo e(__('Duration (hours)')); ?></label>
                        <input type="number" class="form-control <?php $__errorArgs = ['duration_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="duration_hours" name="duration_hours" step="0.25" min="0" max="24"
                               value="<?php echo e(old('duration_hours')); ?>" readonly>
                        <small class="form-text text-muted"><?php echo e(__('Calculated automatically from start and end time')); ?></small>
                        <?php $__errorArgs = ['duration_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label"><?php echo e(__('Description')); ?></label>
                        <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  id="description" name="description" rows="4"
                                  placeholder="<?php echo e(__('Describe what you worked on...')); ?>"><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('timesheet.index')); ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            <?php echo e(__('Cancel')); ?>

                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-clock me-2"></i>
                            <?php echo e(__('Log Time Entry')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Quick Actions')); ?></h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="setQuickTime(1)">
                        <?php echo e(__('1 Hour')); ?>

                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="setQuickTime(2)">
                        <?php echo e(__('2 Hours')); ?>

                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="setQuickTime(4)">
                        <?php echo e(__('4 Hours')); ?>

                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="setQuickTime(8)">
                        <?php echo e(__('8 Hours')); ?>

                    </button>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Guidelines')); ?></h5>
            </div>
            <div class="card-body">
                <h6><?php echo e(__('Time Tracking Tips')); ?></h6>
                <ul class="small text-muted">
                    <li><?php echo e(__('Be accurate with your time entries')); ?></li>
                    <li><?php echo e(__('Include detailed descriptions of work done')); ?></li>
                    <li><?php echo e(__('Log time daily for best accuracy')); ?></li>
                    <li><?php echo e(__('Maximum 24 hours per entry')); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function calculateDuration() {
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;

    if (startTime && endTime) {
        const start = new Date(startTime);
        const end = new Date(endTime);

        if (end > start) {
            const diffMs = end - start;
            const diffHours = diffMs / (1000 * 60 * 60);
            document.getElementById('duration_hours').value = diffHours.toFixed(2);
        }
    }
}

function setQuickTime(hours) {
    const now = new Date();
    const endTime = new Date(now.getTime() + (hours * 60 * 60 * 1000));

    document.getElementById('start_time').value = now.toISOString().slice(0, 16);
    document.getElementById('end_time').value = endTime.toISOString().slice(0, 16);

    calculateDuration();
}

// Calculate duration when times change
document.getElementById('start_time').addEventListener('change', calculateDuration);
document.getElementById('end_time').addEventListener('change', calculateDuration);

// Calculate initial duration
document.addEventListener('DOMContentLoaded', calculateDuration);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/timesheet/create.blade.php ENDPATH**/ ?>