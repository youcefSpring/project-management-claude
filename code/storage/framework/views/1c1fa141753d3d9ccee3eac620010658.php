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
                            <?php switch($task->status):
                                case ('pending'): ?> <?php echo e(__('app.tasks.pending')); ?> <?php break; ?>
                                <?php case ('in_progress'): ?> <?php echo e(__('app.tasks.in_progress')); ?> <?php break; ?>
                                <?php case ('completed'): ?> <?php echo e(__('app.tasks.completed')); ?> <?php break; ?>
                                <?php case ('cancelled'): ?> <?php echo e(__('app.tasks.cancelled')); ?> <?php break; ?>
                                <?php default: ?> <?php echo e(ucfirst(str_replace('_', ' ', $task->status))); ?>

                            <?php endswitch; ?>
                        </span>
                    </div>
                    <div class="col-md-6">
                        <h6><?php echo e(__('app.tasks.priority')); ?></h6>
                        <span class="badge bg-<?php echo e($task->priority === 'urgent' ? 'danger' : ($task->priority === 'high' ? 'warning' : ($task->priority === 'medium' ? 'info' : 'secondary'))); ?> fs-6">
                            <?php switch($task->priority):
                                case ('low'): ?> <?php echo e(__('app.tasks.low')); ?> <?php break; ?>
                                <?php case ('medium'): ?> <?php echo e(__('app.tasks.medium')); ?> <?php break; ?>
                                <?php case ('high'): ?> <?php echo e(__('app.tasks.high')); ?> <?php break; ?>
                                <?php case ('urgent'): ?> <?php echo e(__('app.tasks.urgent')); ?> <?php break; ?>
                                <?php default: ?> <?php echo e(ucfirst($task->priority)); ?>

                            <?php endswitch; ?>
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
                                <span class="text-muted"><?php echo e(__('app.unassigned')); ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><?php echo e(__('app.tasks.created')); ?></h6>
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
                                <?php echo e(__('app.tasks.no_due_date')); ?>

                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-4">
                    <h6><?php echo e(__('app.tasks.quick_actions')); ?></h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                            <?php if($task->status === 'pending'): ?>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#startTaskModal">
                                    <i class="bi bi-play me-1"></i>
                                    <?php echo e(__('app.tasks.start_task')); ?>

                                </button>
                            <?php endif; ?>

                            <?php if($task->status === 'in_progress'): ?>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#completeTaskModal">
                                    <i class="bi bi-check-circle me-1"></i>
                                    <?php echo e(__('app.tasks.mark_complete')); ?>

                                </button>
                            <?php endif; ?>

                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#changePriorityModal">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                <?php echo e(__('app.tasks.change_priority')); ?>

                            </button>

                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                                <i class="bi bi-arrow-repeat me-1"></i>
                                <?php echo e(__('app.tasks.change_status')); ?>

                            </button>
                        <?php endif; ?>

                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCommentModal">
                            <i class="bi bi-chat-plus me-1"></i>
                            <?php echo e(__('app.tasks.add_comment')); ?>

                        </button>

                        <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                            <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#addInterventionModal">
                                <i class="bi bi-megaphone me-1"></i>
                                <?php echo e(__('app.tasks.add_intervention')); ?>

                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Interventions & Comments -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-chat-text me-2"></i>
                    <?php echo e(__('app.tasks.interventions_comments')); ?>

                </h5>
                <div class="btn-group btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="noteFilter" id="filterAll" autocomplete="off" checked>
                    <label class="btn btn-outline-primary" for="filterAll"><?php echo e(__('app.all')); ?></label>

                    <input type="radio" class="btn-check" name="noteFilter" id="filterComments" autocomplete="off">
                    <label class="btn btn-outline-primary" for="filterComments"><?php echo e(__('app.notes.comments')); ?></label>

                    <input type="radio" class="btn-check" name="noteFilter" id="filterStatus" autocomplete="off">
                    <label class="btn btn-outline-primary" for="filterStatus"><?php echo e(__('app.notes.status_changes')); ?></label>

                    <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                        <input type="radio" class="btn-check" name="noteFilter" id="filterInternal" autocomplete="off">
                        <label class="btn btn-outline-primary" for="filterInternal"><?php echo e(__('app.notes.internal')); ?></label>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <?php if($task->notes && $task->notes->count() > 0): ?>
                    <div id="notesList" class="mb-4">
                        <?php $__currentLoopData = $task->notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="note-item border rounded p-3 mb-3"
                                 data-type="<?php echo e($note->type); ?>"
                                 data-internal="<?php echo e($note->is_internal ? 'true' : 'false'); ?>">

                                <!-- Note Header -->
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi <?php echo e($note->icon ?? 'bi-chat-text'); ?> me-2 text-<?php echo e($note->type === 'intervention' ? 'warning' : 'muted'); ?>"></i>
                                            <strong class="<?php echo e($note->user->isAdmin() ? 'text-danger' : ($note->user->isManager() ? 'text-warning' : '')); ?>">
                                                <?php echo e($note->user->name); ?>

                                                <?php if($note->user->isAdmin()): ?>
                                                    <span class="badge bg-danger ms-1"><?php echo e(__('app.roles.admin')); ?></span>
                                                <?php elseif($note->user->isManager()): ?>
                                                    <span class="badge bg-warning ms-1"><?php echo e(__('app.roles.manager')); ?></span>
                                                <?php endif; ?>
                                            </strong>
                                            <small class="text-muted ms-2"><?php echo e($note->created_at->diffForHumans()); ?></small>
                                            <?php if($note->is_internal ?? false): ?>
                                                <span class="badge bg-secondary ms-2">
                                                    <i class="bi bi-eye-slash me-1"></i><?php echo e(__('app.notes.internal')); ?>

                                                </span>
                                            <?php endif; ?>
                                            <span class="badge bg-light text-dark ms-2">
                                                <?php switch($note->type ?? 'comment'):
                                                    case ('comment'): ?> <?php echo e(__('app.notes.comment')); ?> <?php break; ?>
                                                    <?php case ('status_change'): ?> <?php echo e(__('app.notes.status_change')); ?> <?php break; ?>
                                                    <?php case ('intervention'): ?> <?php echo e(__('app.notes.intervention')); ?> <?php break; ?>
                                                    <?php case ('attachment'): ?> <?php echo e(__('app.notes.attachment')); ?> <?php break; ?>
                                                    <?php default: ?> <?php echo e(ucfirst($note->type ?? 'comment')); ?>

                                                <?php endswitch; ?>
                                            </span>
                                        </div>

                                        <!-- Note Content -->
                                        <div id="note-content-<?php echo e($note->id); ?>">
                                            <?php if($note->content): ?>
                                                <div class="note-content mb-2">
                                                    <?php echo nl2br(e($note->content)); ?>

                                                </div>
                                            <?php endif; ?>

                                            <!-- Attachments -->
                                            <?php if($note->hasAttachments()): ?>
                                                <div class="note-attachments mt-2">
                                                    <div class="row g-2">
                                                        <?php $__currentLoopData = $note->image_attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="col-md-3">
                                                                <div class="attachment-item">
                                                                    <a href="<?php echo e($attachment['versions']['original']['url'] ?? '#'); ?>"
                                                                       target="_blank"
                                                                       data-bs-toggle="modal"
                                                                       data-bs-target="#imageModal<?php echo e($loop->parent->index); ?>_<?php echo e($loop->index); ?>">
                                                                        <img src="<?php echo e($attachment['versions']['thumbnail']['url'] ?? '#'); ?>"
                                                                             class="img-thumbnail w-100"
                                                                             style="height: 80px; object-fit: cover;"
                                                                             alt="<?php echo e($attachment['original_name'] ?? 'Attachment'); ?>">
                                                                    </a>
                                                                    <small class="text-muted d-block mt-1">
                                                                        <?php echo e($attachment['original_name'] ?? 'Image'); ?>

                                                                    </small>
                                                                </div>

                                                                <!-- Image Modal -->
                                                                <div class="modal fade" id="imageModal<?php echo e($loop->parent->index); ?>_<?php echo e($loop->index); ?>" tabindex="-1">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"><?php echo e($attachment['original_name'] ?? 'Image'); ?></h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                            </div>
                                                                            <div class="modal-body text-center">
                                                                                <img src="<?php echo e($attachment['versions']['large']['url'] ?? $attachment['versions']['original']['url']); ?>"
                                                                                     class="img-fluid"
                                                                                     alt="<?php echo e($attachment['original_name'] ?? 'Attachment'); ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Edit Form (Hidden by default) -->
                                        <div id="note-edit-form-<?php echo e($note->id); ?>" style="display: none;">
                                            <form method="POST" action="<?php echo e(route('tasks.notes.update', $note)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <textarea class="form-control mb-2" name="content" rows="3"><?php echo e($note->content); ?></textarea>
                                                <button type="submit" class="btn btn-sm btn-primary"><?php echo e(__('app.update')); ?></button>
                                                <button type="button" class="btn btn-sm btn-secondary ms-1" onclick="toggleEdit(<?php echo e($note->id); ?>)"><?php echo e(__('app.cancel')); ?></button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Note Actions -->
                                    <div class="ms-3">
                                        <?php if($note->canBeEditedBy(auth()->user())): ?>
                                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" onclick="toggleEdit(<?php echo e($note->id); ?>)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        <?php endif; ?>
                                        <?php if($note->canBeDeletedBy(auth()->user())): ?>
                                            <form method="POST" action="<?php echo e(route('tasks.notes.destroy', $note)); ?>" style="display: inline;" onsubmit="return confirm('<?php echo e(__('app.messages.confirm_delete')); ?>')">
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
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-chat-text fs-1 mb-3"></i>
                        <p><?php echo e(__('app.notes.no_comments_yet')); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Add New Comment/Intervention Form -->
                <?php if($task->canBeViewedBy(auth()->user())): ?>
                    <div class="card bg-light">
                        <div class="card-body">
                            <form method="POST" action="<?php echo e(route('tasks.notes.store', $task)); ?>" enctype="multipart/form-data" id="commentForm">
                                <?php echo csrf_field(); ?>

                                <!-- Comment Type Selection -->
                                <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label"><?php echo e(__('app.notes.type')); ?></label>
                                            <select class="form-select" name="type" id="noteType">
                                                <option value="comment"><?php echo e(__('app.notes.comment')); ?></option>
                                                <option value="intervention"><?php echo e(__('app.notes.intervention')); ?></option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" name="is_internal" id="isInternal">
                                                <label class="form-check-label" for="isInternal">
                                                    <i class="bi bi-eye-slash me-1"></i>
                                                    <?php echo e(__('app.notes.internal_note')); ?>

                                                    <small class="text-muted d-block"><?php echo e(__('app.notes.internal_note_help')); ?></small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Comment Content -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">
                                        <span id="contentLabel"><?php echo e(__('app.comments.add_comment')); ?></span>
                                    </label>
                                    <textarea class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                              id="content" name="content" rows="3"
                                              placeholder="<?php echo e(__('app.notes.write_comment_placeholder')); ?>"><?php echo e(old('content')); ?></textarea>
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

                                <!-- Photo Attachments -->
                                <div class="mb-3">
                                    <label for="attachments" class="form-label">
                                        <i class="bi bi-camera me-1"></i>
                                        <?php echo e(__('app.notes.attach_photos')); ?>

                                    </label>
                                    <input type="file" class="form-control" id="attachments" name="attachments[]"
                                           multiple accept="image/*" onchange="previewImages(this)">
                                    <small class="text-muted"><?php echo e(__('app.notes.photo_help')); ?></small>

                                    <!-- Image Preview -->
                                    <div id="imagePreview" class="mt-2"></div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send me-1"></i>
                                        <span id="submitText"><?php echo e(__('app.comments.add_comment')); ?></span>
                                    </button>
                                    <small class="text-muted">
                                        <?php echo e(__('app.notes.stakeholders_notified')); ?>

                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>
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

        <!-- Task Details Card -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i><?php echo e(__('app.tasks.task_details')); ?>

                </h5>
            </div>
            <div class="card-body">
                <!-- Status -->
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background-color: rgba(0,123,255,0.1);">
                    <span class="fw-bold"><?php echo e(__('app.status')); ?></span>
                    <span class="badge bg-<?php echo e($task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'warning' : 'secondary')); ?> fs-6">
                        <?php switch($task->status):
                            case ('pending'): ?> <?php echo e(__('app.tasks.pending')); ?> <?php break; ?>
                            <?php case ('in_progress'): ?> <?php echo e(__('app.tasks.in_progress')); ?> <?php break; ?>
                            <?php case ('completed'): ?> <?php echo e(__('app.tasks.completed')); ?> <?php break; ?>
                            <?php case ('cancelled'): ?> <?php echo e(__('app.tasks.cancelled')); ?> <?php break; ?>
                            <?php default: ?> <?php echo e(ucfirst(str_replace('_', ' ', $task->status))); ?>

                        <?php endswitch; ?>
                    </span>
                </div>

                <!-- Priority -->
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background-color: rgba(255,193,7,0.1);">
                    <span class="fw-bold"><?php echo e(__('app.tasks.priority')); ?></span>
                    <span class="badge bg-<?php echo e($task->priority === 'urgent' ? 'danger' : ($task->priority === 'high' ? 'warning' : ($task->priority === 'medium' ? 'info' : 'secondary'))); ?> fs-6">
                        <?php switch($task->priority):
                            case ('low'): ?> <?php echo e(__('app.tasks.low')); ?> <?php break; ?>
                            <?php case ('medium'): ?> <?php echo e(__('app.tasks.medium')); ?> <?php break; ?>
                            <?php case ('high'): ?> <?php echo e(__('app.tasks.high')); ?> <?php break; ?>
                            <?php case ('urgent'): ?> <?php echo e(__('app.tasks.urgent')); ?> <?php break; ?>
                            <?php default: ?> <?php echo e(ucfirst($task->priority)); ?>

                        <?php endswitch; ?>
                    </span>
                </div>

                <!-- Assigned To -->
                <div class="mb-3 p-2 rounded" style="background-color: rgba(40,167,69,0.1);">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><?php echo e(__('app.tasks.assigned_to')); ?></span>
                        <div class="text-end">
                            <?php if($task->assignedUser): ?>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                         style="width: 24px; height: 24px; font-size: 0.8rem;">
                                        <?php echo e(substr($task->assignedUser->name, 0, 1)); ?>

                                    </div>
                                    <span class="fw-bold"><?php echo e($task->assignedUser->name); ?></span>
                                </div>
                            <?php else: ?>
                                <span class="text-muted"><?php echo e(__('app.unassigned')); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Due Date -->
                <?php if($task->due_date): ?>
                    <?php
                        $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                        $isOverdue = $dueDate->isPast() && $task->status !== 'completed';
                        $isDueToday = $dueDate->isToday();
                    ?>
                    <div class="mb-3 p-2 rounded" style="background-color: rgba(<?php echo e($isOverdue ? '220,53,69' : ($isDueToday ? '255,193,7' : '108,117,125')); ?>,0.1);">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold"><?php echo e(__('app.tasks.due_date')); ?></span>
                            <div class="text-end">
                                <div class="fw-bold <?php echo e($isOverdue ? 'text-danger' : ($isDueToday ? 'text-warning' : '')); ?>">
                                    <?php echo e($dueDate->format('M d, Y')); ?>

                                </div>
                                <small class="text-muted"><?php echo e($dueDate->diffForHumans()); ?></small>
                                <?php if($isOverdue): ?>
                                    <br><span class="badge bg-danger"><?php echo e(__('app.tasks.overdue')); ?></span>
                                <?php elseif($isDueToday): ?>
                                    <br><span class="badge bg-warning"><?php echo e(__('app.tasks.due_today')); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Time Statistics -->
                <div class="mb-3 p-2 rounded" style="background-color: rgba(102,16,242,0.1);">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold"><?php echo e(__('app.time.total_time')); ?></span>
                        <span class="badge bg-info fs-6"><?php echo e(number_format($task->timeEntries->sum('duration_hours') ?? 0, 1)); ?>h</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><?php echo e(__('app.tasks.created')); ?></span>
                        <small class="text-muted"><?php echo e($task->created_at->diffForHumans()); ?></small>
                    </div>
                </div>

                <!-- Project Info -->
                <div class="p-2 rounded" style="background-color: rgba(25,135,84,0.1);">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><?php echo e(__('app.tasks.project')); ?></span>
                        <a href="<?php echo e(route('projects.show', $task->project)); ?>" class="btn btn-sm btn-outline-success text-decoration-none">
                            <i class="bi bi-folder me-1"></i><?php echo e(Str::limit($task->project->title, 15)); ?>

                        </a>
                    </div>
                </div>
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

<!-- Quick Action Modals -->

<!-- Start Task Modal -->
<div class="modal fade" id="startTaskModal" tabindex="-1" aria-labelledby="startTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="startTaskModalLabel">
                    <i class="bi bi-play-circle me-2"></i><?php echo e(__('app.tasks.start_task')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(__('app.tasks.confirm_start_task')); ?></p>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <?php echo e(__('app.tasks.start_task_help')); ?>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                <form method="POST" action="<?php echo e(route('tasks.update', $task)); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="status" value="in_progress">
                    <input type="hidden" name="title" value="<?php echo e($task->title); ?>">
                    <input type="hidden" name="project_id" value="<?php echo e($task->project_id); ?>">
                    <input type="hidden" name="priority" value="<?php echo e($task->priority); ?>">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-play me-1"></i><?php echo e(__('app.tasks.start_task')); ?>

                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Complete Task Modal -->
<div class="modal fade" id="completeTaskModal" tabindex="-1" aria-labelledby="completeTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeTaskModalLabel">
                    <i class="bi bi-check-circle me-2"></i><?php echo e(__('app.tasks.mark_complete')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(__('app.tasks.confirm_complete_task')); ?></p>
                <div class="mb-3">
                    <label for="completionNote" class="form-label"><?php echo e(__('app.tasks.completion_note')); ?> (<?php echo e(__('app.optional')); ?>)</label>
                    <textarea class="form-control" id="completionNote" name="completion_note" rows="3"
                              placeholder="<?php echo e(__('app.tasks.completion_note_placeholder')); ?>"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                <form method="POST" action="<?php echo e(route('tasks.update', $task)); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="status" value="completed">
                    <input type="hidden" name="title" value="<?php echo e($task->title); ?>">
                    <input type="hidden" name="project_id" value="<?php echo e($task->project_id); ?>">
                    <input type="hidden" name="priority" value="<?php echo e($task->priority); ?>">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i><?php echo e(__('app.tasks.mark_complete')); ?>

                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Priority Modal -->
<div class="modal fade" id="changePriorityModal" tabindex="-1" aria-labelledby="changePriorityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePriorityModalLabel">
                    <i class="bi bi-exclamation-circle me-2"></i><?php echo e(__('app.tasks.change_priority')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo e(route('tasks.update', $task)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <input type="hidden" name="title" value="<?php echo e($task->title); ?>">
                    <input type="hidden" name="project_id" value="<?php echo e($task->project_id); ?>">
                    <input type="hidden" name="status" value="<?php echo e($task->status); ?>">

                    <div class="mb-3">
                        <label for="priority" class="form-label"><?php echo e(__('app.tasks.priority')); ?></label>
                        <select class="form-select" id="priority" name="priority" required>
                            <option value="low" <?php echo e($task->priority === 'low' ? 'selected' : ''); ?>>
                                <span class="badge bg-secondary"><?php echo e(__('app.tasks.low')); ?></span>
                            </option>
                            <option value="medium" <?php echo e($task->priority === 'medium' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.tasks.medium')); ?>

                            </option>
                            <option value="high" <?php echo e($task->priority === 'high' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.tasks.high')); ?>

                            </option>
                            <option value="urgent" <?php echo e($task->priority === 'urgent' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.tasks.urgent')); ?>

                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="priorityReason" class="form-label"><?php echo e(__('app.tasks.priority_change_reason')); ?> (<?php echo e(__('app.optional')); ?>)</label>
                        <textarea class="form-control" id="priorityReason" name="priority_reason" rows="2"
                                  placeholder="<?php echo e(__('app.tasks.priority_change_reason_placeholder')); ?>"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-exclamation-circle me-1"></i><?php echo e(__('app.tasks.update_priority')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Status Modal -->
<div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeStatusModalLabel">
                    <i class="bi bi-arrow-repeat me-2"></i><?php echo e(__('app.tasks.change_status')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo e(route('tasks.update', $task)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <input type="hidden" name="title" value="<?php echo e($task->title); ?>">
                    <input type="hidden" name="project_id" value="<?php echo e($task->project_id); ?>">
                    <input type="hidden" name="priority" value="<?php echo e($task->priority); ?>">

                    <div class="mb-3">
                        <label for="status" class="form-label"><?php echo e(__('app.status')); ?></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" <?php echo e($task->status === 'pending' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.tasks.pending')); ?>

                            </option>
                            <option value="in_progress" <?php echo e($task->status === 'in_progress' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.tasks.in_progress')); ?>

                            </option>
                            <option value="completed" <?php echo e($task->status === 'completed' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.tasks.completed')); ?>

                            </option>
                            <option value="cancelled" <?php echo e($task->status === 'cancelled' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.tasks.cancelled')); ?>

                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="statusReason" class="form-label"><?php echo e(__('app.tasks.status_change_reason')); ?> (<?php echo e(__('app.optional')); ?>)</label>
                        <textarea class="form-control" id="statusReason" name="status_reason" rows="2"
                                  placeholder="<?php echo e(__('app.tasks.status_change_reason_placeholder')); ?>"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat me-1"></i><?php echo e(__('app.tasks.update_status')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Comment Modal -->
<div class="modal fade" id="addCommentModal" tabindex="-1" aria-labelledby="addCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCommentModalLabel">
                    <i class="bi bi-chat-plus me-2"></i><?php echo e(__('app.tasks.add_comment')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo e(route('tasks.notes.store', $task)); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <input type="hidden" name="task_id" value="<?php echo e($task->id); ?>">
                    <input type="hidden" name="type" value="comment">

                    <div class="mb-3">
                        <label for="commentContent" class="form-label"><?php echo e(__('app.tasks.comment')); ?></label>
                        <textarea class="form-control" id="commentContent" name="content" rows="4" required
                                  placeholder="<?php echo e(__('app.tasks.comment_placeholder')); ?>"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="commentAttachments" class="form-label"><?php echo e(__('app.tasks.attach_files')); ?></label>
                        <input type="file" class="form-control" id="commentAttachments" name="attachments[]" multiple
                               accept="image/*">
                        <div class="form-text"><?php echo e(__('app.tasks.attach_files_help')); ?></div>
                        <div id="commentPreview" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-chat-plus me-1"></i><?php echo e(__('app.tasks.post_comment')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Intervention Modal -->
<?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
<div class="modal fade" id="addInterventionModal" tabindex="-1" aria-labelledby="addInterventionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addInterventionModalLabel">
                    <i class="bi bi-megaphone me-2"></i><?php echo e(__('app.tasks.add_intervention')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo e(route('tasks.notes.store', $task)); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <input type="hidden" name="task_id" value="<?php echo e($task->id); ?>">
                    <input type="hidden" name="type" value="intervention">

                    <div class="mb-3">
                        <label for="interventionContent" class="form-label"><?php echo e(__('app.tasks.intervention')); ?></label>
                        <textarea class="form-control" id="interventionContent" name="content" rows="4" required
                                  placeholder="<?php echo e(__('app.tasks.intervention_placeholder')); ?>"></textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isInternal" name="is_internal" value="1">
                            <label class="form-check-label" for="isInternal">
                                <?php echo e(__('app.tasks.internal_note')); ?>

                            </label>
                            <div class="form-text"><?php echo e(__('app.tasks.internal_note_help')); ?></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="interventionAttachments" class="form-label"><?php echo e(__('app.tasks.attach_files')); ?></label>
                        <input type="file" class="form-control" id="interventionAttachments" name="attachments[]" multiple
                               accept="image/*">
                        <div class="form-text"><?php echo e(__('app.tasks.attach_files_help')); ?></div>
                        <div id="interventionPreview" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-megaphone me-1"></i><?php echo e(__('app.tasks.post_intervention')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Modal file preview functionality
document.addEventListener('DOMContentLoaded', function() {
    // Handle file preview for comment modal
    const commentAttachments = document.getElementById('commentAttachments');
    if (commentAttachments) {
        commentAttachments.addEventListener('change', function() {
            handleFilePreview(this, 'commentPreview');
        });
    }

    // Handle file preview for intervention modal
    const interventionAttachments = document.getElementById('interventionAttachments');
    if (interventionAttachments) {
        interventionAttachments.addEventListener('change', function() {
            handleFilePreview(this, 'interventionPreview');
        });
    }
});

function handleFilePreview(input, previewId) {
    const previewContainer = document.getElementById(previewId);
    previewContainer.innerHTML = '';

    if (input.files && input.files.length > 0) {
        Array.from(input.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageContainer = document.createElement('div');
                    imageContainer.className = 'position-relative d-inline-block me-2 mb-2';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail';
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle p-1';
                    removeBtn.style.width = '20px';
                    removeBtn.style.height = '20px';
                    removeBtn.style.fontSize = '10px';
                    removeBtn.style.transform = 'translate(50%, -50%)';
                    removeBtn.innerHTML = '<i class="bi bi-x"></i>';
                    removeBtn.onclick = function() {
                        imageContainer.remove();
                        // Remove file from input
                        const dt = new DataTransfer();
                        Array.from(input.files).forEach((f, i) => {
                            if (i !== index) dt.items.add(f);
                        });
                        input.files = dt.files;
                    };

                    imageContainer.appendChild(img);
                    imageContainer.appendChild(removeBtn);
                    previewContainer.appendChild(imageContainer);
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

// Note editing functionality
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

// Note filtering functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('input[name="noteFilter"]');
    const noteItems = document.querySelectorAll('.note-item');

    filterButtons.forEach(button => {
        button.addEventListener('change', function() {
            const filterType = this.id.replace('filter', '').toLowerCase();

            noteItems.forEach(item => {
                const noteType = item.dataset.type;
                const isInternal = item.dataset.internal === 'true';

                let shouldShow = false;

                switch(filterType) {
                    case 'all':
                        shouldShow = true;
                        break;
                    case 'comments':
                        shouldShow = noteType === 'comment';
                        break;
                    case 'status':
                        shouldShow = noteType === 'status_change';
                        break;
                    case 'internal':
                        shouldShow = isInternal;
                        break;
                }

                item.style.display = shouldShow ? 'block' : 'none';
            });
        });
    });

    // Note type selection changes
    const noteTypeSelect = document.getElementById('noteType');
    const contentLabel = document.getElementById('contentLabel');
    const submitText = document.getElementById('submitText');

    if (noteTypeSelect) {
        noteTypeSelect.addEventListener('change', function() {
            const selectedType = this.value;

            if (selectedType === 'intervention') {
                contentLabel.textContent = '<?php echo e(__("app.notes.add_intervention")); ?>';
                submitText.textContent = '<?php echo e(__("app.notes.submit_intervention")); ?>';
            } else {
                contentLabel.textContent = '<?php echo e(__("app.comments.add_comment")); ?>';
                submitText.textContent = '<?php echo e(__("app.comments.add_comment")); ?>';
            }
        });
    }
});

// Image preview functionality
function previewImages(input) {
    const previewContainer = document.getElementById('imagePreview');
    previewContainer.innerHTML = '';

    if (input.files) {
        Array.from(input.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imageContainer = document.createElement('div');
                    imageContainer.className = 'position-relative d-inline-block me-2 mb-2';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail';
                    img.style.width = '80px';
                    img.style.height = '80px';
                    img.style.objectFit = 'cover';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle';
                    removeBtn.style.transform = 'translate(50%, -50%)';
                    removeBtn.innerHTML = '<i class="bi bi-x"></i>';
                    removeBtn.onclick = function() {
                        imageContainer.remove();
                        // Remove file from input (requires recreating the input)
                        const dt = new DataTransfer();
                        Array.from(input.files).forEach((f, i) => {
                            if (i !== index) dt.items.add(f);
                        });
                        input.files = dt.files;
                    };

                    imageContainer.appendChild(img);
                    imageContainer.appendChild(removeBtn);
                    previewContainer.appendChild(imageContainer);
                };

                reader.readAsDataURL(file);
            }
        });
    }
}

// Status update functionality (if quick status change is added)
function updateTaskStatus(taskId, newStatus) {
    fetch(`/tasks/${taskId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh page or update UI
            location.reload();
        } else {
            alert('Failed to update status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/tasks/show.blade.php ENDPATH**/ ?>