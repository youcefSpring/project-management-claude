<?php $__env->startSection('title', __('Edit Task')); ?>
<?php $__env->startSection('page-title', __('Edit Task: :title', ['title' => $task->title])); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('app.tasks.task_information')); ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('tasks.update', $task)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="mb-3">
                        <label for="title" class="form-label"><?php echo e(__('Task Title')); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="title" name="title" value="<?php echo e(old('title', $task->title)); ?>" required>
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
                                  id="description" name="description" rows="4"><?php echo e(old('description', $task->description)); ?></textarea>
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

                    <?php if(count($projects) > 0): ?>
                    <div class="mb-3">
                        <label for="project_id" class="form-label"><?php echo e(__('Project')); ?> <span class="text-danger">*</span></label>
                        <select class="form-select <?php $__errorArgs = ['project_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="project_id" name="project_id" required>
                            <option value=""><?php echo e(__('Select a project')); ?></option>
                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($project->id); ?>"
                                    <?php echo e(old('project_id', $task->project_id) == $project->id ? 'selected' : ''); ?>>
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
                    <?php else: ?>
                        <input type="hidden" name="project_id" value="<?php echo e($task->project_id); ?>">
                        <div class="mb-3">
                            <label class="form-label"><?php echo e(__('Project')); ?></label>
                            <p class="form-control-plaintext"><?php echo e($task->project->title); ?></p>
                            <small class="text-muted"><?php echo e(__('You can only edit tasks in projects you manage.')); ?></small>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label"><?php echo e(__('Assigned To')); ?></label>
                                <select class="form-select <?php $__errorArgs = ['assigned_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="assigned_to" name="assigned_to">
                                    <option value=""><?php echo e(__('Unassigned')); ?></option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"
                                            <?php echo e(old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : ''); ?>>
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
                                <label for="priority" class="form-label"><?php echo e(__('Priority')); ?> <span class="text-danger">*</span></label>
                                <select class="form-select <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="priority" name="priority" required>
                                    <option value="low" <?php echo e(old('priority', $task->priority) === 'low' ? 'selected' : ''); ?>><?php echo e(__('Low')); ?></option>
                                    <option value="medium" <?php echo e(old('priority', $task->priority) === 'medium' ? 'selected' : ''); ?>><?php echo e(__('Medium')); ?></option>
                                    <option value="high" <?php echo e(old('priority', $task->priority) === 'high' ? 'selected' : ''); ?>><?php echo e(__('High')); ?></option>
                                    <option value="urgent" <?php echo e(old('priority', $task->priority) === 'urgent' ? 'selected' : ''); ?>><?php echo e(__('Urgent')); ?></option>
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

                    <div class="row">
                        <div class="col-md-6">
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
                                    <option value="pending" <?php echo e(old('status', $task->status) === 'pending' ? 'selected' : ''); ?>><?php echo e(__('Pending')); ?></option>
                                    <option value="in_progress" <?php echo e(old('status', $task->status) === 'in_progress' ? 'selected' : ''); ?>><?php echo e(__('In Progress')); ?></option>
                                    <option value="completed" <?php echo e(old('status', $task->status) === 'completed' ? 'selected' : ''); ?>><?php echo e(__('Completed')); ?></option>
                                    <option value="cancelled" <?php echo e(old('status', $task->status) === 'cancelled' ? 'selected' : ''); ?>><?php echo e(__('Cancelled')); ?></option>
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
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="due_date" class="form-label"><?php echo e(__('Due Date')); ?></label>
                                <input type="date" class="form-control <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="due_date" name="due_date"
                                       value="<?php echo e(old('due_date', $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '')); ?>">
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
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="<?php echo e(route('tasks.show', $task)); ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>
                                <?php echo e(__('Cancel')); ?>

                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>
                                <?php echo e(__('Update Task')); ?>

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
                <h5 class="mb-0"><?php echo e(__('Task Actions')); ?></h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('tasks.show', $task)); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>
                        <?php echo e(__('View Task')); ?>

                    </a>

                    <a href="<?php echo e(route('timesheet.create', ['task_id' => $task->id])); ?>" class="btn btn-outline-success">
                        <i class="bi bi-clock me-2"></i>
                        <?php echo e(__('Log Time')); ?>

                    </a>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $task)): ?>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i>
                            <?php echo e(__('Delete Task')); ?>

                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Task Information')); ?></h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong><?php echo e(__('Current Project')); ?>:</strong>
                    <br><small><?php echo e($task->project->title); ?></small>
                </div>
                <div class="mb-2">
                    <strong><?php echo e(__('Created')); ?>:</strong>
                    <br><small><?php echo e($task->created_at->format('M d, Y')); ?></small>
                </div>
                <div class="mb-2">
                    <strong><?php echo e(__('Time Logged')); ?>:</strong>
                    <br><small><?php echo e(number_format($task->timeEntries->sum('duration_hours') ?? 0, 1)); ?>h</small>
                </div>
                <?php if($task->assignedUser): ?>
                <div class="mb-2">
                    <strong><?php echo e(__('Currently Assigned')); ?>:</strong>
                    <br><small><?php echo e($task->assignedUser->name); ?></small>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Status Guide')); ?></h5>
            </div>
            <div class="card-body">
                <ul class="small text-muted">
                    <li><strong><?php echo e(__('Pending')); ?>:</strong> <?php echo e(__('Not started yet')); ?></li>
                    <li><strong><?php echo e(__('In Progress')); ?>:</strong> <?php echo e(__('Currently being worked on')); ?></li>
                    <li><strong><?php echo e(__('Completed')); ?>:</strong> <?php echo e(__('Task is finished')); ?></li>
                    <li><strong><?php echo e(__('Cancelled')); ?>:</strong> <?php echo e(__('Task is no longer needed')); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $task)): ?>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel"><?php echo e(__('Delete Task')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(__('Are you sure you want to delete this task?')); ?></p>
                <p class="text-danger">
                    <strong><?php echo e(__('Warning:')); ?></strong>
                    <?php echo e(__('This action cannot be undone and will also delete all associated time entries.')); ?>

                </p>
                <p><strong><?php echo e(__('Task:')); ?></strong> <?php echo e($task->title); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                <form method="POST" action="<?php echo e(route('tasks.destroy', $task)); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger"><?php echo e(__('Delete Task')); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/tasks/edit.blade.php ENDPATH**/ ?>