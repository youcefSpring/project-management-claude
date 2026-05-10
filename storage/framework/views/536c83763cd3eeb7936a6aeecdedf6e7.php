<?php $__env->startSection('title', $task->title); ?>
<?php $__env->startSection('page-title', $task->title); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Left Column: Task Details & Activity -->
    <div class="col-lg-8">
        <!-- Task Header & Details Card -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <div class="mb-2">
                            <?php
                                $statusColor = match($task->status) {
                                    'completed' => 'success',
                                    'in_progress' => 'primary',
                                    'pending' => 'warning',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                                $statusLabel = match($task->status) {
                                    'completed' => __('app.tasks.completed'),
                                    'in_progress' => __('app.tasks.in_progress'),
                                    'pending' => __('app.tasks.pending'),
                                    'cancelled' => __('app.tasks.cancelled'),
                                    default => ucfirst(str_replace('_', ' ', $task->status))
                                };
                            ?>
                            <span class="badge bg-<?php echo e($statusColor); ?> me-2"><?php echo e($statusLabel); ?></span>
                            <a href="<?php echo e(route('projects.show', $task->project)); ?>" class="text-decoration-none text-muted small">
                                <i class="bi bi-folder me-1"></i><?php echo e($task->project->title); ?>

                            </a>
                        </div>
                        <h3 class="card-title mb-1 fw-bold"><?php echo e($task->title); ?></h3>
                    </div>

                    <!-- Actions Dropdown -->
                    <?php if(auth()->user()->can('update', $task) || auth()->user()->can('delete', $task)): ?>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle" type="button" id="taskActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width: 32px; height: 32px;">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="taskActionsDropdown">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                                <li>
                                    <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="dropdown-item">
                                        <i class="bi bi-pencil me-2 text-primary"></i><?php echo e(__('app.edit')); ?>

                                    </a>
                                </li>
                                <?php if($task->status === 'pending'): ?>
                                    <li>
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#startTaskModal">
                                            <i class="bi bi-play me-2 text-warning"></i><?php echo e(__('app.tasks.start_task')); ?>

                                        </button>
                                    </li>
                                <?php endif; ?>
                                <?php if($task->status === 'in_progress'): ?>
                                    <li>
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#completeTaskModal">
                                            <i class="bi bi-check-circle me-2 text-success"></i><?php echo e(__('app.tasks.mark_complete')); ?>

                                        </button>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changePriorityModal">
                                        <i class="bi bi-exclamation-circle me-2 text-info"></i><?php echo e(__('app.tasks.change_priority')); ?>

                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                                        <i class="bi bi-arrow-repeat me-2 text-secondary"></i><?php echo e(__('app.tasks.change_status')); ?>

                                    </button>
                                </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $task)): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="bi bi-trash me-2"></i><?php echo e(__('app.delete')); ?>

                                    </button>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Key Metrics Grid -->
                <div class="row g-4 mb-4">
                    <!-- Priority -->
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <small class="text-muted d-block mb-2 text-uppercase fw-bold" style="font-size: 0.75rem;"><?php echo e(__('app.tasks.priority')); ?></small>
                            <?php
                                $priorityColor = match($task->priority) {
                                    'urgent' => 'danger',
                                    'high' => 'warning',
                                    'medium' => 'info',
                                    default => 'secondary'
                                };
                                $priorityLabel = match($task->priority) {
                                    'urgent' => __('app.tasks.urgent'),
                                    'high' => __('app.tasks.high'),
                                    'medium' => __('app.tasks.medium'),
                                    'low' => __('app.tasks.low'),
                                    default => ucfirst($task->priority)
                                };
                            ?>
                            <span class="fw-bold text-<?php echo e($priorityColor); ?> d-flex align-items-center">
                                <i class="bi bi-flag-fill me-2"></i><?php echo e($priorityLabel); ?>

                            </span>
                        </div>
                    </div>

                    <!-- Assigned To -->
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <small class="text-muted d-block mb-2 text-uppercase fw-bold" style="font-size: 0.75rem;"><?php echo e(__('app.tasks.assigned_to')); ?></small>
                            <?php if($task->assignedUser): ?>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2" style="width: 24px; height: 24px; font-size: 0.8rem;">
                                        <?php echo e(substr($task->assignedUser->name, 0, 1)); ?>

                                    </div>
                                    <span class="fw-bold text-dark text-truncate" title="<?php echo e($task->assignedUser->name); ?>">
                                        <?php echo e(Str::limit($task->assignedUser->name, 15)); ?>

                                    </span>
                                </div>
                            <?php else: ?>
                                <span class="text-muted fst-italic"><?php echo e(__('app.unassigned')); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Due Date -->
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <small class="text-muted d-block mb-2 text-uppercase fw-bold" style="font-size: 0.75rem;"><?php echo e(__('app.tasks.due_date')); ?></small>
                            <?php
                                $dueDate = $task->due_date ? (is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date) : null;
                                $isOverdue = $dueDate && $dueDate->isPast() && $task->status !== 'completed';
                                $isDueToday = $dueDate && $dueDate->isToday();
                            ?>
                            <?php if($dueDate): ?>
                                <span class="fw-bold <?php echo e($isOverdue ? 'text-danger' : ($isDueToday ? 'text-warning' : 'text-dark')); ?> d-flex align-items-center">
                                    <i class="bi bi-calendar-event me-2"></i>
                                    <?php echo e($dueDate->format('M d, Y')); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-muted fst-italic"><?php echo e(__('app.tasks.no_due_date')); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Total Time -->
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <small class="text-muted d-block mb-2 text-uppercase fw-bold" style="font-size: 0.75rem;"><?php echo e(__('app.time.total_time')); ?></small>
                            <span class="fw-bold text-primary d-flex align-items-center">
                                <i class="bi bi-clock me-2"></i>
                                <?php echo e(number_format($task->timeEntries->sum('duration_hours') ?? 0, 1)); ?>h
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-0">
                    <h5 class="h6 fw-bold text-uppercase text-muted mb-3" style="font-size: 0.75rem;"><?php echo e(__('app.description')); ?></h5>
                    <?php if($task->description): ?>
                        <div class="text-dark" style="white-space: pre-line;"><?php echo e($task->description); ?></div>
                    <?php else: ?>
                        <span class="text-muted fst-italic"><?php echo e(__('app.tasks.no_description')); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Activity / Comments Section -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><?php echo e(__('app.tasks.activity')); ?></h5>
                
                <!-- Filter Controls -->
                <div class="btn-group btn-group-sm">
                    <input type="radio" class="btn-check" name="noteFilter" id="filterAll" autocomplete="off" checked>
                    <label class="btn btn-outline-secondary" for="filterAll"><?php echo e(__('app.all')); ?></label>

                    <input type="radio" class="btn-check" name="noteFilter" id="filterComments" autocomplete="off">
                    <label class="btn btn-outline-secondary" for="filterComments"><?php echo e(__('app.notes.comments')); ?></label>

                    <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                        <input type="radio" class="btn-check" name="noteFilter" id="filterInternal" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="filterInternal"><?php echo e(__('app.notes.internal')); ?></label>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card-body p-4">
                <!-- Add Comment Form -->
                <?php if($task->canBeViewedBy(auth()->user())): ?>
                    <div class="mb-5">
                        <form method="POST" action="<?php echo e(route('tasks.notes.store', $task)); ?>" enctype="multipart/form-data" id="commentForm">
                            <?php echo csrf_field(); ?>
                            
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('createIntervention', $task)): ?>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="isInternal" name="is_internal">
                                        <label class="form-check-label small text-muted" for="isInternal">
                                            <?php echo e(__('app.notes.internal_note')); ?>

                                        </label>
                                    </div>
                                    <select class="form-select form-select-sm w-auto border-0 bg-light" name="type" id="noteType">
                                        <option value="comment"><?php echo e(__('app.notes.comment')); ?></option>
                                        <option value="intervention"><?php echo e(__('app.notes.intervention')); ?></option>
                                    </select>
                                </div>
                            <?php else: ?>
                                <input type="hidden" name="type" value="comment">
                            <?php endif; ?>

                            <div class="position-relative">
                                <textarea class="form-control bg-light border-0" 
                                          id="content" 
                                          name="content" 
                                          rows="3" 
                                          placeholder="<?php echo e(__('app.notes.write_comment_placeholder')); ?>"
                                          style="resize: none;"></textarea>
                                
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div class="d-flex align-items-center">
                                        <label class="btn btn-sm btn-link text-muted p-0 me-3" title="<?php echo e(__('app.notes.attach_photos')); ?>">
                                            <i class="bi bi-paperclip fs-5"></i>
                                            <input type="file" name="attachments[]" multiple accept="image/*" class="d-none" onchange="previewImages(this)">
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm px-4 rounded-pill">
                                        <i class="bi bi-send me-1"></i> <?php echo e(__('app.comments.post_comment')); ?>

                                    </button>
                                </div>
                                <div id="imagePreview" class="mt-2"></div>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

                <!-- Timeline -->
                <div class="timeline position-relative">
                    <?php if($task->notes && $task->notes->count() > 0): ?>
                        <?php $__currentLoopData = $task->notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="note-item d-flex mb-4" 
                                 data-type="<?php echo e($note->type); ?>" 
                                 data-internal="<?php echo e($note->is_internal ? 'true' : 'false'); ?>">
                                
                                <!-- Avatar -->
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-secondary border" style="width: 40px; height: 40px;">
                                        <?php echo e(substr($note->user->name, 0, 1)); ?>

                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-grow-1">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <span class="fw-bold text-dark"><?php echo e($note->user->name); ?></span>
                                                    <small class="text-muted ms-2"><?php echo e($note->created_at->diffForHumans()); ?></small>
                                                    <?php if($note->is_internal): ?>
                                                        <span class="badge bg-secondary ms-2" style="font-size: 0.65rem;">
                                                            <i class="bi bi-eye-slash me-1"></i><?php echo e(__('app.notes.internal')); ?>

                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Note Actions -->
                                                <?php if($note->canBeEditedBy(auth()->user()) || $note->canBeDeletedBy(auth()->user())): ?>
                                                    <div class="dropdown">
                                                        <button class="btn btn-link btn-sm text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                                            <?php if($note->canBeEditedBy(auth()->user())): ?>
                                                                <li>
                                                                    <button class="dropdown-item small" onclick="toggleEdit(<?php echo e($note->id); ?>)">
                                                                        <i class="bi bi-pencil me-2"></i><?php echo e(__('app.edit')); ?>

                                                                    </button>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if($note->canBeDeletedBy(auth()->user())): ?>
                                                                <li>
                                                                    <form method="POST" action="<?php echo e(route('tasks.notes.destroy', $note)); ?>" onsubmit="return confirm('<?php echo e(__('app.messages.confirm_delete')); ?>')">
                                                                        <?php echo csrf_field(); ?>
                                                                        <?php echo method_field('DELETE'); ?>
                                                                        <button type="submit" class="dropdown-item small text-danger">
                                                                            <i class="bi bi-trash me-2"></i><?php echo e(__('app.delete')); ?>

                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div id="note-content-<?php echo e($note->id); ?>">
                                                <div class="text-dark mb-2" style="white-space: pre-wrap;"><?php echo e($note->content); ?></div>
                                                
                                                <?php if($note->hasAttachments()): ?>
                                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                                        <?php $__currentLoopData = $note->image_attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <a href="<?php echo e($attachment['versions']['original']['url'] ?? '#'); ?>" target="_blank" class="d-block">
                                                                <img src="<?php echo e($attachment['versions']['thumbnail']['url'] ?? '#'); ?>" 
                                                                     class="rounded border" 
                                                                     style="width: 60px; height: 60px; object-fit: cover;"
                                                                     alt="Attachment">
                                                            </a>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Edit Form -->
                                            <div id="note-edit-form-<?php echo e($note->id); ?>" style="display: none;">
                                                <form method="POST" action="<?php echo e(route('tasks.notes.update', $note)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <textarea class="form-control mb-2" name="content" rows="3"><?php echo e($note->content); ?></textarea>
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <button type="button" class="btn btn-sm btn-light" onclick="toggleEdit(<?php echo e($note->id); ?>)"><?php echo e(__('app.cancel')); ?></button>
                                                        <button type="submit" class="btn btn-sm btn-primary"><?php echo e(__('app.update')); ?></button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="mb-3 text-muted">
                                <i class="bi bi-chat-square-dots display-4 opacity-25"></i>
                            </div>
                            <h6 class="text-muted"><?php echo e(__('app.notes.no_comments_yet')); ?></h6>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Time Entries & Sidebar -->
    <div class="col-lg-4">
        <!-- Time Entries Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><?php echo e(__('app.time.title')); ?></h5>
                <a href="<?php echo e(route('timesheet.create', ['task_id' => $task->id])); ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                    <i class="bi bi-plus-lg me-1"></i><?php echo e(__('app.time.log_time')); ?>

                </a>
            </div>
            <div class="card-body p-0">
                <?php if($task->timeEntries && $task->timeEntries->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $task->timeEntries->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item px-4 py-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark"><?php echo e($entry->user->name); ?></span>
                                    <span class="badge bg-light text-dark border"><?php echo e(number_format($entry->duration_hours, 1)); ?>h</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center small">
                                    <span class="text-muted">
                                        <?php echo e(is_string($entry->start_time) ? \Carbon\Carbon::parse($entry->start_time)->format('M d') : $entry->start_time->format('M d')); ?>

                                    </span>
                                    <span class="text-truncate text-muted ms-2" style="max-width: 150px;">
                                        <?php echo e($entry->comment ?? $entry->description ?? '-'); ?>

                                    </span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php if($task->timeEntries->count() > 5): ?>
                        <div class="card-footer bg-transparent text-center border-0 py-3">
                            <a href="<?php echo e(route('timesheet.index', ['task_id' => $task->id])); ?>" class="text-decoration-none small fw-bold">
                                <?php echo e(__('app.view_all')); ?>

                            </a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-4 px-4">
                        <p class="text-muted small mb-0"><?php echo e(__('app.time.no_entries')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modals (Keep existing modals but ensure they are guarded) -->
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $task)): ?>
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title"><?php echo e(__('app.delete')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="text-danger mb-3">
                        <i class="bi bi-exclamation-triangle display-1"></i>
                    </div>
                    <h5 class="mb-3"><?php echo e(__('app.messages.confirm_delete')); ?></h5>
                    <p class="text-muted mb-0"><?php echo e(__('app.messages.action_cannot_be_undone')); ?></p>
                </div>
                <div class="modal-footer border-top-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                    <form method="POST" action="<?php echo e(route('tasks.destroy', $task)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger px-4"><?php echo e(__('app.delete')); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
    <!-- Start Task Modal -->
    <div class="modal fade" id="startTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title"><?php echo e(__('app.tasks.start_task')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><?php echo e(__('app.tasks.confirm_start_task')); ?></p>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                    <form method="POST" action="<?php echo e(route('tasks.update', $task)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type="hidden" name="status" value="in_progress">
                        <input type="hidden" name="title" value="<?php echo e($task->title); ?>">
                        <input type="hidden" name="project_id" value="<?php echo e($task->project_id); ?>">
                        <input type="hidden" name="priority" value="<?php echo e($task->priority); ?>">
                        <button type="submit" class="btn btn-warning text-white"><?php echo e(__('app.tasks.start_task')); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Task Modal -->
    <div class="modal fade" id="completeTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title"><?php echo e(__('app.tasks.mark_complete')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><?php echo e(__('app.tasks.confirm_complete_task')); ?></p>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                    <form method="POST" action="<?php echo e(route('tasks.update', $task)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type="hidden" name="status" value="completed">
                        <input type="hidden" name="title" value="<?php echo e($task->title); ?>">
                        <input type="hidden" name="project_id" value="<?php echo e($task->project_id); ?>">
                        <input type="hidden" name="priority" value="<?php echo e($task->priority); ?>">
                        <button type="submit" class="btn btn-success"><?php echo e(__('app.tasks.mark_complete')); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Priority Modal -->
    <div class="modal fade" id="changePriorityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title"><?php echo e(__('app.tasks.change_priority')); ?></h5>
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
                            <label class="form-label"><?php echo e(__('app.tasks.priority')); ?></label>
                            <select class="form-select" name="priority">
                                <option value="low" <?php echo e($task->priority === 'low' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.low')); ?></option>
                                <option value="medium" <?php echo e($task->priority === 'medium' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.medium')); ?></option>
                                <option value="high" <?php echo e($task->priority === 'high' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.high')); ?></option>
                                <option value="urgent" <?php echo e($task->priority === 'urgent' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.urgent')); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo e(__('app.update')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Status Modal -->
    <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title"><?php echo e(__('app.tasks.change_status')); ?></h5>
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
                            <label class="form-label"><?php echo e(__('app.status')); ?></label>
                            <select class="form-select" name="status">
                                <option value="pending" <?php echo e($task->status === 'pending' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.pending')); ?></option>
                                <option value="in_progress" <?php echo e($task->status === 'in_progress' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.in_progress')); ?></option>
                                <option value="completed" <?php echo e($task->status === 'completed' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.completed')); ?></option>
                                <option value="cancelled" <?php echo e($task->status === 'cancelled' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.cancelled')); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo e(__('app.update')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // File Preview
    function handleFilePreview(input, previewId) {
        const previewContainer = document.getElementById(previewId);
        previewContainer.innerHTML = '';
        if (input.files) {
            Array.from(input.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail me-2 mb-2';
                        img.style.width = '60px';
                        img.style.height = '60px';
                        img.style.objectFit = 'cover';
                        previewContainer.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    function previewImages(input) {
        handleFilePreview(input, 'imagePreview');
    }

    // Toggle Edit Form
    function toggleEdit(noteId) {
        const content = document.getElementById(`note-content-${noteId}`);
        const form = document.getElementById(`note-edit-form-${noteId}`);
        if (content.style.display === 'none') {
            content.style.display = 'block';
            form.style.display = 'none';
        } else {
            content.style.display = 'none';
            form.style.display = 'block';
        }
    }

    // Filter Notes
    document.addEventListener('DOMContentLoaded', function() {
        const filters = document.querySelectorAll('input[name="noteFilter"]');
        filters.forEach(filter => {
            filter.addEventListener('change', function() {
                const type = this.id.replace('filter', '').toLowerCase();
                document.querySelectorAll('.note-item').forEach(item => {
                    const itemType = item.dataset.type;
                    const isInternal = item.dataset.internal === 'true';
                    
                    if (type === 'all') item.style.display = 'flex';
                    else if (type === 'comments') item.style.display = itemType === 'comment' ? 'flex' : 'none';
                    else if (type === 'internal') item.style.display = isInternal ? 'flex' : 'none';
                });
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/tasks/show.blade.php ENDPATH**/ ?>