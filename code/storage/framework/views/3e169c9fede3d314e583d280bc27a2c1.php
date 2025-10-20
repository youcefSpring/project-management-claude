<?php $__env->startSection('title', __('Edit Project')); ?>
<?php $__env->startSection('page-title', __('Edit Project: :title', ['title' => $project->title])); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Project Information')); ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('projects.update', $project)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="mb-3">
                        <label for="title" class="form-label"><?php echo e(__('Project Title')); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="title" name="title" value="<?php echo e(old('title', $project->title)); ?>" required>
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
                        <label for="description" class="form-label"><?php echo e(__('Description')); ?></label>
                        <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  id="description" name="description" rows="4"><?php echo e(old('description', $project->description)); ?></textarea>
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label"><?php echo e(__('Start Date')); ?> <span class="text-danger">*</span></label>
                                <input type="date" class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="start_date" name="start_date" value="<?php echo e(old('start_date', $project->start_date instanceof \Carbon\Carbon ? $project->start_date->format('Y-m-d') : $project->start_date)); ?>" required>
                                <?php $__errorArgs = ['start_date'];
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
                                <label for="end_date" class="form-label"><?php echo e(__('End Date')); ?> <span class="text-danger">*</span></label>
                                <input type="date" class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="end_date" name="end_date" value="<?php echo e(old('end_date', $project->end_date instanceof \Carbon\Carbon ? $project->end_date->format('Y-m-d') : $project->end_date)); ?>" required>
                                <?php $__errorArgs = ['end_date'];
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
                        <label for="manager_id" class="form-label"><?php echo e(__('Project Manager')); ?> <span class="text-danger">*</span></label>
                        <select class="form-select <?php $__errorArgs = ['manager_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="manager_id" name="manager_id" required>
                            <option value=""><?php echo e(__('Select a manager')); ?></option>
                            <?php $__currentLoopData = $managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manager): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($manager->id); ?>" <?php echo e(old('manager_id', $project->manager_id) == $manager->id ? 'selected' : ''); ?>>
                                    <?php echo e($manager->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['manager_id'];
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
                        <label for="status" class="form-label"><?php echo e(__('Status')); ?> <span class="text-danger">*</span></label>
                        <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="status" name="status" required>
                            <option value="planning" <?php echo e(old('status', $project->status) === 'planning' ? 'selected' : ''); ?>><?php echo e(__('Planning')); ?></option>
                            <option value="active" <?php echo e(old('status', $project->status) === 'active' ? 'selected' : ''); ?>><?php echo e(__('Active')); ?></option>
                            <option value="on_hold" <?php echo e(old('status', $project->status) === 'on_hold' ? 'selected' : ''); ?>><?php echo e(__('On Hold')); ?></option>
                            <option value="completed" <?php echo e(old('status', $project->status) === 'completed' ? 'selected' : ''); ?>><?php echo e(__('Completed')); ?></option>
                            <option value="cancelled" <?php echo e(old('status', $project->status) === 'cancelled' ? 'selected' : ''); ?>><?php echo e(__('Cancelled')); ?></option>
                        </select>
                        <?php $__errorArgs = ['status'];
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
                        <div>
                            <a href="<?php echo e(route('projects.show', $project)); ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>
                                <?php echo e(__('Cancel')); ?>

                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>
                                <?php echo e(__('Update Project')); ?>

                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Project Actions')); ?></h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('projects.show', $project)); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>
                        <?php echo e(__('View Project')); ?>

                    </a>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $project)): ?>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i>
                            <?php echo e(__('Delete Project')); ?>

                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Project Stats')); ?></h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong><?php echo e(__('Tasks')); ?>:</strong>
                    <span class="float-end"><?php echo e($project->tasks_count ?? $project->tasks->count()); ?></span>
                </div>
                <div class="mb-2">
                    <strong><?php echo e(__('Created')); ?>:</strong>
                    <span class="float-end"><?php echo e($project->created_at->format('M d, Y')); ?></span>
                </div>
                <div class="mb-2">
                    <strong><?php echo e(__('Duration')); ?>:</strong>
                    <span class="float-end">
                        <?php if($project->start_date && $project->end_date): ?>
                            <?php
                                $startDate = is_string($project->start_date) ? \Carbon\Carbon::parse($project->start_date) : $project->start_date;
                                $endDate = is_string($project->end_date) ? \Carbon\Carbon::parse($project->end_date) : $project->end_date;
                            ?>
                            <?php echo e($startDate->diffInDays($endDate)); ?> <?php echo e(__('days')); ?>

                        <?php else: ?>
                            <?php echo e(__('app.no_dates_set')); ?>

                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $project)): ?>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel"><?php echo e(__('Delete Project')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(__('Are you sure you want to delete this project?')); ?></p>
                <p class="text-danger">
                    <strong><?php echo e(__('Warning:')); ?></strong>
                    <?php echo e(__('This action cannot be undone and will also delete all associated tasks and time entries.')); ?>

                </p>
                <p><strong><?php echo e(__('Project:')); ?></strong> <?php echo e($project->title); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                <form method="POST" action="<?php echo e(route('projects.destroy', $project)); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger"><?php echo e(__('Delete Project')); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/projects/edit.blade.php ENDPATH**/ ?>