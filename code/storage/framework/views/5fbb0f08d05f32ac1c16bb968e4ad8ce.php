<?php $__env->startSection('title', __('app.tasks.create')); ?>
<?php $__env->startSection('page-title', __('app.tasks.create_new_task')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('app.tasks.task_information')); ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('tasks.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label for="title" class="form-label"><?php echo e(__('app.tasks.task_title')); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="title" name="title" value="<?php echo e(old('title')); ?>" required>
                        <?php $__errorArgs = ['title'];
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
                                  id="description" name="description" rows="4"><?php echo e(old('description')); ?></textarea>
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

                    <div class="mb-3">
                        <label for="project_id" class="form-label"><?php echo e(__('app.tasks.project')); ?> <span class="text-danger">*</span></label>
                        <select class="form-select <?php $__errorArgs = ['project_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="project_id" name="project_id" required>
                            <option value=""><?php echo e(__('app.select_project')); ?></option>
                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($project->id); ?>"
                                    <?php echo e(old('project_id', $selectedProject?->id) == $project->id ? 'selected' : ''); ?>>
                                    <?php echo e($project->title); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['project_id'];
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
                                <label for="assigned_to" class="form-label"><?php echo e(__('app.tasks.assigned_to')); ?></label>
                                <select class="form-select <?php $__errorArgs = ['assigned_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="assigned_to" name="assigned_to">
                                    <option value=""><?php echo e(__('app.unassigned')); ?></option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>" <?php echo e(old('assigned_to') == $user->id ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['assigned_to'];
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
                                <label for="priority" class="form-label"><?php echo e(__('app.tasks.priority')); ?> <span class="text-danger">*</span></label>
                                <select class="form-select <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="priority" name="priority" required>
                                    <option value="low" <?php echo e(old('priority') === 'low' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.low')); ?></option>
                                    <option value="medium" <?php echo e(old('priority', 'medium') === 'medium' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.medium')); ?></option>
                                    <option value="high" <?php echo e(old('priority') === 'high' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.high')); ?></option>
                                    <option value="urgent" <?php echo e(old('priority') === 'urgent' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.urgent')); ?></option>
                                </select>
                                <?php $__errorArgs = ['priority'];
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
                        <label for="due_date" class="form-label"><?php echo e(__('app.tasks.due_date')); ?></label>
                        <input type="date" class="form-control <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="due_date" name="due_date" value="<?php echo e(old('due_date')); ?>">
                        <?php $__errorArgs = ['due_date'];
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
                        <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            <?php echo e(__('app.cancel')); ?>

                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            <?php echo e(__('app.tasks.create')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('app.guidelines')); ?></h5>
            </div>
            <div class="card-body">
                <h6><?php echo e(__('app.tasks.task_title')); ?></h6>
                <p class="small text-muted"><?php echo e(__('app.tasks.title_guidelines')); ?></p>

                <h6><?php echo e(__('app.tasks.priority_levels')); ?></h6>
                <ul class="small text-muted">
                    <li><strong><?php echo e(__('app.tasks.low')); ?>:</strong> <?php echo e(__('app.tasks.low_description')); ?></li>
                    <li><strong><?php echo e(__('app.tasks.medium')); ?>:</strong> <?php echo e(__('app.tasks.medium_description')); ?></li>
                    <li><strong><?php echo e(__('app.tasks.high')); ?>:</strong> <?php echo e(__('app.tasks.high_description')); ?></li>
                    <li><strong><?php echo e(__('app.tasks.urgent')); ?>:</strong> <?php echo e(__('app.tasks.urgent_description')); ?></li>
                </ul>

                <h6><?php echo e(__('app.tasks.assignment')); ?></h6>
                <p class="small text-muted"><?php echo e(__('app.tasks.assignment_guidelines')); ?></p>
            </div>
        </div>

        <?php if($selectedProject): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('app.selected_project')); ?></h5>
            </div>
            <div class="card-body">
                <h6><?php echo e($selectedProject->title); ?></h6>
                <p class="small text-muted"><?php echo e($selectedProject->description); ?></p>
                <small class="text-muted">
                    <i class="bi bi-person me-1"></i>
                    <?php echo e($selectedProject->manager->name); ?>

                </small>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/tasks/create.blade.php ENDPATH**/ ?>