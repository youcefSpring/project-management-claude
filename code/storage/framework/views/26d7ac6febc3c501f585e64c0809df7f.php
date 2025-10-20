<?php $__env->startSection('title', __('app.timesheet.log_time_entry')); ?>
<?php $__env->startSection('page-title', __('app.timesheet.log_new_entry')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('app.timesheet.entry_information')); ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('timesheet.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label for="task_id" class="form-label"><?php echo e(__('app.tasks.task')); ?> <span class="text-danger">*</span></label>
                        <select class="form-select <?php $__errorArgs = ['task_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="task_id" name="task_id" required>
                            <option value=""><?php echo e(__('app.timesheet.select_task')); ?></option>
                            <?php $__currentLoopData = $availableTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($task->id); ?>"
                                    <?php echo e(old('task_id', request('task_id')) == $task->id ? 'selected' : ''); ?>>
                                    <?php echo e($task->title); ?> (<?php echo e(optional($task->project)->title ?? __('app.projects.no_project')); ?>)
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
                                <label for="start_time" class="form-label"><?php echo e(__('app.timesheet.start_time')); ?> <span class="text-danger">*</span></label>
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
                                <label for="end_time" class="form-label"><?php echo e(__('app.timesheet.end_time')); ?> <span class="text-danger">*</span></label>
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
                        <label for="hours" class="form-label"><?php echo e(__('app.timesheet.duration_hours')); ?></label>
                        <input type="number" class="form-control <?php $__errorArgs = ['hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="hours" name="hours" step="0.25" min="0.1" max="24"
                               value="<?php echo e(old('hours')); ?>" placeholder="<?php echo e(__('app.timesheet.enter_hours_placeholder')); ?>">
                        <small class="form-text text-muted"><?php echo e(__('app.timesheet.hours_helper_text')); ?></small>
                        <?php $__errorArgs = ['hours'];
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
                        <label for="description" class="form-label"><?php echo e(__('app.description')); ?></label>
                        <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  id="description" name="description" rows="4"
                                  placeholder="<?php echo e(__('app.timesheet.describe_work_placeholder')); ?>"><?php echo e(old('description')); ?></textarea>
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
                            <?php echo e(__('app.cancel')); ?>

                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-clock me-2"></i>
                            <?php echo e(__('app.timesheet.log_time_entry')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('app.timesheet.quick_actions')); ?></h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="setQuickTime(1)">
                        <?php echo e(__('app.timesheet.one_hour')); ?>

                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="setQuickTime(2)">
                        <?php echo e(__('app.timesheet.two_hours')); ?>

                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="setQuickTime(4)">
                        <?php echo e(__('app.timesheet.four_hours')); ?>

                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="setQuickTime(8)">
                        <?php echo e(__('app.timesheet.eight_hours')); ?>

                    </button>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('app.timesheet.guidelines')); ?></h5>
            </div>
            <div class="card-body">
                <h6><?php echo e(__('app.timesheet.time_tracking_tips')); ?></h6>
                <ul class="small text-muted">
                    <li><?php echo e(__('app.timesheet.tip_be_accurate')); ?></li>
                    <li><?php echo e(__('app.timesheet.tip_detailed_descriptions')); ?></li>
                    <li><?php echo e(__('app.timesheet.tip_log_daily')); ?></li>
                    <li><?php echo e(__('app.timesheet.tip_max_hours')); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
let isCalculatingFromTime = false;
let isCalculatingFromHours = false;

function calculateDuration() {
    if (isCalculatingFromHours) return;

    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    const hoursField = document.getElementById('hours');

    if (startTime && endTime) {
        const start = new Date(startTime);
        const end = new Date(endTime);

        if (end > start) {
            isCalculatingFromTime = true;
            const diffMs = end - start;
            const diffHours = diffMs / (1000 * 60 * 60);
            hoursField.value = diffHours.toFixed(2);
            isCalculatingFromTime = false;
        }
    }
}

function calculateEndTime() {
    if (isCalculatingFromTime) return;

    const startTime = document.getElementById('start_time').value;
    const hours = parseFloat(document.getElementById('hours').value);
    const endTimeField = document.getElementById('end_time');

    if (startTime && hours && hours > 0) {
        isCalculatingFromHours = true;
        const start = new Date(startTime);
        const end = new Date(start.getTime() + (hours * 60 * 60 * 1000));
        endTimeField.value = end.toISOString().slice(0, 16);
        isCalculatingFromHours = false;
    }
}

function setQuickTime(hours) {
    const now = new Date();
    const endTime = new Date(now.getTime() + (hours * 60 * 60 * 1000));

    document.getElementById('start_time').value = now.toISOString().slice(0, 16);
    document.getElementById('end_time').value = endTime.toISOString().slice(0, 16);
    document.getElementById('hours').value = hours.toFixed(2);
}

// Calculate duration when times change
document.getElementById('start_time').addEventListener('change', function() {
    calculateDuration();
    calculateEndTime(); // Also update end time if hours are set
});
document.getElementById('end_time').addEventListener('change', calculateDuration);

// Calculate end time when hours change
document.getElementById('hours').addEventListener('input', calculateEndTime);

// Calculate initial duration
document.addEventListener('DOMContentLoaded', function() {
    calculateDuration();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/timesheet/create.blade.php ENDPATH**/ ?>