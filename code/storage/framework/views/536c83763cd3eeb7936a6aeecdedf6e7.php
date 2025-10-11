<?php $__env->startSection('title', $task->title); ?>
<?php $__env->startSection('page-title', $task->title); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?php echo e(__('app.tasks.title')); ?></h5>
                <div>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                        <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>
                            <?php echo e(__('app.edit')); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><?php echo e(__('app.status')); ?></h6>
                        <span class="badge bg-<?php echo e($task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'warning' : 'secondary')); ?> fs-6">
                            <?php echo e(ucfirst(str_replace('_', ' ', $task->status))); ?>

                        </span>
                    </div>
                    <div class="col-md-6">
                        <h6><?php echo e(__('app.tasks.priority')); ?></h6>
                        <span class="badge bg-<?php echo e($task->priority === 'urgent' ? 'danger' : ($task->priority === 'high' ? 'warning' : ($task->priority === 'medium' ? 'info' : 'secondary'))); ?> fs-6">
                            <?php echo e(ucfirst($task->priority)); ?>

                        </span>
                    </div>
                </div>

                <?php if($task->description): ?>
                <div class="mb-4">
                    <h6><?php echo e(__('app.description')); ?></h6>
                    <p class="text-muted"><?php echo e($task->description); ?></p>
                </div>
                <?php endif; ?>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><?php echo e(__('app.projects.title')); ?></h6>
                        <p>
                            <a href="<?php echo e(route('projects.show', $task->project)); ?>" class="text-decoration-none">
                                <?php echo e($task->project->title); ?>

                            </a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6><?php echo e(__('app.tasks.assigned_to')); ?></h6>
                        <p>
                            <?php if($task->assignedUser): ?>
                                <i class="bi bi-person-circle me-1"></i>
                                <?php echo e($task->assignedUser->name); ?>

                            <?php else: ?>
                                <span class="text-muted"><?php echo e(__('Unassigned')); ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><?php echo e(__('app.date')); ?></h6>
                        <p class="text-muted"><?php echo e($task->created_at->format('M d, Y \a\t H:i')); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6><?php echo e(__('app.tasks.due_date')); ?></h6>
                        <p class="text-muted">
                            <?php if($task->due_date): ?>
                                <?php
                                    $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                ?>
                                <?php echo e($dueDate->format('M d, Y')); ?>

                                <?php if($dueDate->isPast() && $task->status !== 'completed'): ?>
                                    <span class="badge bg-danger ms-2"><?php echo e(__('app.tasks.overdue')); ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php echo e(__('No due date')); ?>

                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <?php if($task->status !== 'completed' && $task->assignedUser === auth()->user()): ?>
                <div class="mt-4">
                    <h6><?php echo e(__('Quick Actions')); ?></h6>
                    <div class="d-flex gap-2">
                        <?php if($task->status === 'pending'): ?>
                            <form method="POST" action="<?php echo e(route('tasks.update', $task)); ?>" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <input type="hidden" name="status" value="in_progress">
                                <input type="hidden" name="title" value="<?php echo e($task->title); ?>">
                                <input type="hidden" name="project_id" value="<?php echo e($task->project_id); ?>">
                                <input type="hidden" name="priority" value="<?php echo e($task->priority); ?>">
                                <button type="submit" class="btn btn-sm btn-warning">
                                    <i class="bi bi-play me-1"></i>
                                    <?php echo e(__('app.time.start_timer')); ?>

                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if($task->status === 'in_progress'): ?>
                            <form method="POST" action="<?php echo e(route('tasks.update', $task)); ?>" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <input type="hidden" name="status" value="completed">
                                <input type="hidden" name="title" value="<?php echo e($task->title); ?>">
                                <input type="hidden" name="project_id" value="<?php echo e($task->project_id); ?>">
                                <input type="hidden" name="priority" value="<?php echo e($task->priority); ?>">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    <?php echo e(__('app.tasks.completed')); ?>

                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Comments/Notes -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Comments')); ?></h5>
            </div>
            <div class="card-body">
                <?php if($task->notes && $task->notes->count() > 0): ?>
                    <div class="mb-4">
                        <?php $__currentLoopData = $task->notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-person-circle me-2 text-muted"></i>
                                            <strong><?php echo e($note->user->name); ?></strong>
                                            <small class="text-muted ms-2"><?php echo e($note->created_at->diffForHumans()); ?></small>
                                        </div>
                                        <div id="note-content-<?php echo e($note->id); ?>">
                                            <p class="mb-0"><?php echo nl2br(e($note->content)); ?></p>
                                        </div>
                                        <div id="note-edit-form-<?php echo e($note->id); ?>" style="display: none;">
                                            <form method="POST" action="<?php echo e(route('tasks.notes.update', $note)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <textarea class="form-control mb-2" name="content" rows="3" required><?php echo e($note->content); ?></textarea>
                                                <button type="submit" class="btn btn-sm btn-primary"><?php echo e(__('app.update')); ?></button>
                                                <button type="button" class="btn btn-sm btn-secondary ms-1" onclick="toggleEdit(<?php echo e($note->id); ?>)"><?php echo e(__('app.cancel')); ?></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <?php if($note->canBeEditedBy(auth()->user())): ?>
                                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" onclick="toggleEdit(<?php echo e($note->id); ?>)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        <?php endif; ?>
                                        <?php if($note->canBeDeletedBy(auth()->user())): ?>
                                            <form method="POST" action="<?php echo e(route('tasks.notes.destroy', $note)); ?>" style="display: inline;" onsubmit="return confirm('<?php echo e(__("app.messages.confirm_delete")); ?>')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <!-- Add new comment form -->
                <?php if($task->canBeViewedBy(auth()->user())): ?>
                    <form method="POST" action="<?php echo e(route('tasks.notes.store', $task)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="content" class="form-label"><?php echo e(__('app.comments.add_comment')); ?></label>
                            <textarea class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      id="content" name="content" rows="3"
                                      placeholder="<?php echo e(__('Write your comment here...')); ?>" required><?php echo e(old('content')); ?></textarea>
                            <?php $__errorArgs = ['content'];
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
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-chat-left-text me-1"></i>
                            <?php echo e(__('app.comments.add_comment')); ?>

                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Time Entries -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?php echo e(__('app.time.title')); ?></h5>
                <a href="<?php echo e(route('timesheet.create', ['task_id' => $task->id])); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    <?php echo e(__('app.time.log_time')); ?>

                </a>
            </div>
            <div class="card-body">
                <?php if($task->timeEntries && $task->timeEntries->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('app.date')); ?></th>
                                    <th><?php echo e(__('app.users.title')); ?></th>
                                    <th><?php echo e(__('app.time.duration')); ?></th>
                                    <th><?php echo e(__('app.description')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $task->timeEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <?php if($entry->start_time): ?>
                                                <?php echo e(is_string($entry->start_time) ? \Carbon\Carbon::parse($entry->start_time)->format('M d, Y') : $entry->start_time->format('M d, Y')); ?>

                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($entry->user->name); ?></td>
                                        <td><?php echo e(number_format($entry->duration_hours, 1)); ?>h</td>
                                        <td><?php echo e($entry->comment ?? $entry->description ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <strong><?php echo e(__('app.time.total_time')); ?>:</strong>
                        <?php echo e(number_format($task->timeEntries->sum('duration_hours'), 1)); ?>h
                    </div>
                <?php else: ?>
                    <div class="text-center py-3">
                        <i class="bi bi-clock text-muted fs-2"></i>
                        <p class="text-muted mt-2"><?php echo e(__('app.time.no_entries')); ?></p>
                        <a href="<?php echo e(route('timesheet.create', ['task_id' => $task->id])); ?>" class="btn btn-sm btn-primary">
                            <?php echo e(__('app.time.log_time')); ?>

                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('app.actions')); ?></h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                        <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-2"></i>
                            <?php echo e(__('app.tasks.edit')); ?>

                        </a>
                    <?php endif; ?>

                    <a href="<?php echo e(route('timesheet.create', ['task_id' => $task->id])); ?>" class="btn btn-outline-success">
                        <i class="bi bi-clock me-2"></i>
                        <?php echo e(__('app.time.log_time')); ?>

                    </a>

                    <a href="<?php echo e(route('projects.show', $task->project)); ?>" class="btn btn-outline-info">
                        <i class="bi bi-folder me-2"></i>
                        <?php echo e(__('app.projects.title')); ?>

                    </a>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $task)): ?>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i>
                            <?php echo e(__('app.delete')); ?>

                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Task Statistics')); ?></h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong><?php echo e(__('app.time.total_time')); ?>:</strong>
                    <span class="float-end"><?php echo e(number_format($task->timeEntries->sum('duration_hours') ?? 0, 1)); ?>h</span>
                </div>
                <div class="mb-2">
                    <strong><?php echo e(__('app.date')); ?>:</strong>
                    <span class="float-end"><?php echo e($task->created_at->diffForHumans()); ?></span>
                </div>
                <?php if($task->due_date): ?>
                <?php
                    $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                ?>
                <div class="mb-2">
                    <strong><?php echo e(__('Time Remaining')); ?>:</strong>
                    <span class="float-end">
                        <?php if($dueDate->isFuture()): ?>
                            <?php echo e($dueDate->diffForHumans()); ?>

                        <?php else: ?>
                            <span class="text-danger"><?php echo e(__('app.tasks.overdue')); ?></span>
                        <?php endif; ?>
                    </span>
                </div>
                <?php endif; ?>
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
                <h5 class="modal-title" id="deleteModalLabel"><?php echo e(__('app.delete')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(__('app.messages.confirm_delete')); ?></p>
                <p class="text-danger">
                    <strong><?php echo e(__('app.warning')); ?>:</strong>
                    <?php echo e(__('app.messages.action_cannot_be_undone')); ?>

                </p>
                <p><strong><?php echo e(__('app.tasks.title')); ?>:</strong> <?php echo e($task->title); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                <form method="POST" action="<?php echo e(route('tasks.destroy', $task)); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger"><?php echo e(__('app.delete')); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
function toggleEdit(noteId) {
    const contentDiv = document.getElementById('note-content-' + noteId);
    const editForm = document.getElementById('note-edit-form-' + noteId);

    if (contentDiv.style.display === 'none') {
        contentDiv.style.display = 'block';
        editForm.style.display = 'none';
    } else {
        contentDiv.style.display = 'none';
        editForm.style.display = 'block';
    }
}
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/tasks/show.blade.php ENDPATH**/ ?>