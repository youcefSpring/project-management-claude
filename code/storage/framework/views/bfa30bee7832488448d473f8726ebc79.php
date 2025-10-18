<?php $__env->startSection('title', __('app.timesheet.title')); ?>
<?php $__env->startSection('page-title', __('app.timesheet.management')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Header Actions -->
    <div class="col-12 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h2 class="mb-1"><?php echo e(__('app.timesheet.title')); ?></h2>
                <p class="text-muted mb-0"><?php echo e(__('app.timesheet.manage_and_track')); ?></p>
            </div>
            <div class="d-flex flex-column flex-sm-row gap-2">
                <a href="<?php echo e(route('timesheet.index', ['show_all' => 1])); ?>" class="btn btn-outline-info">
                    <i class="bi bi-eye me-2"></i><?php echo e(__('app.timesheet.show_all')); ?>

                </a>
                <a href="<?php echo e(route('timesheet.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i><?php echo e(__('app.timesheet.add_entry')); ?>

                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-muted">
                    <i class="bi bi-graph-up me-2"></i><?php echo e(__('app.timesheet.statistics')); ?>

                </h6>
                <button type="button" id="toggleStats" class="btn btn-sm btn-outline-secondary" title="<?php echo e(__('app.timesheet.toggle_statistics')); ?>">
                    <i class="bi bi-chevron-up" id="toggleIcon"></i>
                </button>
            </div>
            <div class="card-body p-3" id="statsContent">
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <div class="card border-primary h-100">
                            <div class="card-body text-center py-3">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="bg-primary rounded-circle p-2 me-2">
                                        <i class="bi bi-clock text-white"></i>
                                    </div>
                                    <h4 class="card-title text-primary mb-0"><?php echo e($summary['total_hours'] ?? 0); ?>h</h4>
                                </div>
                                <p class="card-text text-muted small mb-0"><?php echo e(__('app.timesheet.total_hours')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card border-success h-100">
                            <div class="card-body text-center py-3">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="bg-success rounded-circle p-2 me-2">
                                        <i class="bi bi-check-circle text-white"></i>
                                    </div>
                                    <h4 class="card-title text-success mb-0"><?php echo e($summary['approved_hours'] ?? 0); ?>h</h4>
                                </div>
                                <p class="card-text text-muted small mb-0"><?php echo e(__('app.timesheet.approved_hours')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card border-warning h-100">
                            <div class="card-body text-center py-3">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="bg-warning rounded-circle p-2 me-2">
                                        <i class="bi bi-clock-history text-white"></i>
                                    </div>
                                    <h4 class="card-title text-warning mb-0"><?php echo e($summary['pending_hours'] ?? 0); ?>h</h4>
                                </div>
                                <p class="card-text text-muted small mb-0"><?php echo e(__('app.timesheet.pending_hours')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card border-danger h-100">
                            <div class="card-body text-center py-3">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="bg-danger rounded-circle p-2 me-2">
                                        <i class="bi bi-x-circle text-white"></i>
                                    </div>
                                    <h4 class="card-title text-danger mb-0"><?php echo e($summary['rejected_hours'] ?? 0); ?>h</h4>
                                </div>
                                <p class="card-text text-muted small mb-0"><?php echo e(__('app.timesheet.rejected_hours')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Statistics Row -->
                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center p-2 bg-light rounded">
                            <i class="bi bi-calendar-week text-primary me-2"></i>
                            <div>
                                <div class="fw-bold"><?php echo e($timeEntries->count()); ?></div>
                                <small class="text-muted"><?php echo e(__('app.timesheet.total_entries')); ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center p-2 bg-light rounded">
                            <i class="bi bi-people text-info me-2"></i>
                            <div>
                                <div class="fw-bold"><?php echo e($timeEntries->pluck('user_id')->unique()->count()); ?></div>
                                <small class="text-muted"><?php echo e(__('app.timesheet.active_users')); ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center p-2 bg-light rounded">
                            <i class="bi bi-clock text-success me-2"></i>
                            <div>
                                <div class="fw-bold">
                                    <?php if($timeEntries->count() > 0): ?>
                                        <?php echo e(number_format(($summary['total_hours'] ?? 0) / $timeEntries->count(), 1)); ?>h
                                    <?php else: ?>
                                        0h
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted"><?php echo e(__('app.timesheet.avg_per_entry')); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-muted">
                    <i class="bi bi-funnel me-2"></i><?php echo e(__('app.timesheet.filters')); ?>

                </h6>
                <button type="button" id="toggleTimesheetFilters" class="btn btn-sm btn-outline-secondary" title="<?php echo e(__('app.toggle_filters')); ?>">
                    <i class="bi bi-chevron-up" id="toggleTimesheetFiltersIcon"></i>
                </button>
            </div>
            <div class="card-body p-3" id="timesheetFiltersContent">
                <div class="row g-3 align-items-end">
                    <div class="col-sm-6 col-md-3">
                        <label class="form-label small text-muted"><?php echo e(__('app.search')); ?></label>
                        <input type="text" class="form-control form-control-sm" id="search-input" placeholder="<?php echo e(__('app.search')); ?>...">
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <label class="form-label small text-muted"><?php echo e(__('app.projects.title')); ?></label>
                        <select class="form-select form-select-sm" id="project-filter">
                            <option value=""><?php echo e(__('app.reports.all_projects')); ?></option>
                            <?php $__currentLoopData = $projects ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($project->id); ?>"><?php echo e($project->title); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <label class="form-label small text-muted"><?php echo e(__('app.users.title')); ?></label>
                        <select class="form-select form-select-sm" id="user-filter">
                            <option value=""><?php echo e(__('app.reports.all_users')); ?></option>
                            <?php $__currentLoopData = $users ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <label class="form-label small text-muted"><?php echo e(__('app.reports.from')); ?></label>
                        <input type="date" class="form-control form-control-sm" id="date-from" placeholder="<?php echo e(__('app.reports.from')); ?>">
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <label class="form-label small text-muted"><?php echo e(__('app.reports.to')); ?></label>
                        <input type="date" class="form-control form-control-sm" id="date-to" placeholder="<?php echo e(__('app.reports.to')); ?>">
                    </div>
                    <div class="col-sm-6 col-md-1">
                        <button class="btn btn-outline-secondary btn-sm w-100" onclick="clearFilters()">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timesheet Entries List -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    <?php echo e(__('app.timesheet.entries')); ?>

                    <span class="badge bg-secondary ms-2"><?php echo e($timeEntries->count()); ?></span>
                </h5>
            </div>
            <div class="card-body">

                <!-- Timesheet Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="timesheet-table">
                        <thead class="table-dark">
                            <tr>
                                <th><?php echo e(__('app.date')); ?></th>
                                <th class="d-none d-md-table-cell"><?php echo e(__('app.user_label')); ?></th>
                                <th><?php echo e(__('app.tasks.title')); ?></th>
                                <th class="d-none d-sm-table-cell"><?php echo e(__('app.time.hours')); ?></th>
                                <th class="d-none d-lg-table-cell"><?php echo e(__('app.status')); ?></th>
                                <th width="120"><?php echo e(__('app.actions')); ?></th>
                            </tr>
                        </thead>
                        <tbody id="timesheet-tbody">
                            <?php $__empty_1 = true; $__currentLoopData = $timeEntries ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timeEntry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <?php if($timeEntry->start_time && $timeEntry->end_time): ?>
                                            <?php
                                                try {
                                                    $startTime = $timeEntry->start_time instanceof \Carbon\Carbon
                                                        ? $timeEntry->start_time
                                                        : \Carbon\Carbon::parse($timeEntry->start_time);
                                                    $endTime = $timeEntry->end_time instanceof \Carbon\Carbon
                                                        ? $timeEntry->end_time
                                                        : \Carbon\Carbon::parse($timeEntry->end_time);
                                                } catch (\Exception $e) {
                                                    $startTime = \Carbon\Carbon::now();
                                                    $endTime = \Carbon\Carbon::now();
                                                }
                                            ?>
                                            <span class="fw-bold"><?php echo e($startTime->format('M d, Y')); ?></span>
                                            <br>
                                            <small class="text-muted"><?php echo e($startTime->format('H:i')); ?> - <?php echo e($endTime->format('H:i')); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted"><?php echo e(__('Invalid date')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                 style="width: 32px; height: 32px;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?php echo e($timeEntry->user->name ?? __('Unknown')); ?></div>
                                                <small class="text-muted"><?php echo e($timeEntry->user->email ?? ''); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center mb-1">
                                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                     style="width: 20px; height: 20px;">
                                                    <i class="bi bi-folder-fill" style="font-size: 0.7rem;"></i>
                                                </div>
                                                <small class="text-muted"><?php echo e(optional(optional($timeEntry->task)->project)->title ?? __('No Project')); ?></small>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                     style="width: 20px; height: 20px;">
                                                    <i class="bi bi-check-square" style="font-size: 0.7rem;"></i>
                                                </div>
                                                <span class="fw-bold">
                                                    <?php if($timeEntry->task): ?>
                                                        <?php echo e($timeEntry->task->title); ?>

                                                    <?php else: ?>
                                                        <?php echo e(__('app.timesheet.general_task')); ?>

                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-sm-table-cell">
                                        <span class="badge bg-primary fs-6"><?php echo e(number_format($timeEntry->duration_hours ?? 0, 2)); ?>h</span>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <span class="badge bg-success"><?php echo e(__('Logged')); ?></span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <?php
                                                    $canView = $timeEntry->canBeViewedBy(auth()->user());
                                                    $canEdit = $timeEntry->canBeEditedBy(auth()->user());
                                                    $canDelete = $timeEntry->canBeDeletedBy(auth()->user());
                                                ?>

                                                <?php if($canView): ?>
                                                    <li>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo e($timeEntry->id); ?>">
                                                            <i class="bi bi-eye me-2"></i><?php echo e(__('View')); ?>

                                                        </a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if($canEdit): ?>
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('timesheet.edit', $timeEntry->id)); ?>">
                                                            <i class="bi bi-pencil me-2"></i><?php echo e(__('Edit')); ?>

                                                        </a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if($canDelete): ?>
                                                    <?php if($canView || $canEdit): ?><li><hr class="dropdown-divider"></li><?php endif; ?>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="#"
                                                           onclick="if(confirm('<?php echo e(__('Are you sure?')); ?>')) {
                                                               document.getElementById('delete-form-<?php echo e($timeEntry->id); ?>').submit();
                                                           }">
                                                            <i class="bi bi-trash me-2"></i><?php echo e(__('Delete')); ?>

                                                        </a>
                                                        <form id="delete-form-<?php echo e($timeEntry->id); ?>"
                                                              action="<?php echo e(route('timesheet.destroy', $timeEntry->id)); ?>"
                                                              method="POST" style="display: none;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                        </form>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <!-- View Modal for this entry -->
                                <?php if($timeEntry->canBeViewedBy(auth()->user())): ?>
                                    <div class="modal fade" id="viewModal<?php echo e($timeEntry->id); ?>" tabindex="-1" aria-labelledby="viewModalLabel<?php echo e($timeEntry->id); ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="viewModalLabel<?php echo e($timeEntry->id); ?>">
                                                        <i class="bi bi-clock-history me-2"></i><?php echo e(__('Time Entry Details')); ?>

                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6 class="text-muted mb-3"><?php echo e(__('Basic Information')); ?></h6>
                                                            <div class="mb-3">
                                                                <strong><?php echo e(__('Date')); ?>:</strong><br>
                                                                <?php if($timeEntry->start_time && $timeEntry->end_time): ?>
                                                                    <?php
                                                                        try {
                                                                            $startTime = $timeEntry->start_time instanceof \Carbon\Carbon
                                                                                ? $timeEntry->start_time
                                                                                : \Carbon\Carbon::parse($timeEntry->start_time);
                                                                            $endTime = $timeEntry->end_time instanceof \Carbon\Carbon
                                                                                ? $timeEntry->end_time
                                                                                : \Carbon\Carbon::parse($timeEntry->end_time);
                                                                        } catch (\Exception $e) {
                                                                            $startTime = \Carbon\Carbon::now();
                                                                            $endTime = \Carbon\Carbon::now();
                                                                        }
                                                                    ?>
                                                                    <span class="badge bg-primary"><?php echo e($startTime->format('M d, Y')); ?></span><br>
                                                                    <small class="text-muted"><?php echo e($startTime->format('H:i')); ?> - <?php echo e($endTime->format('H:i')); ?></small>
                                                                <?php else: ?>
                                                                    <span class="text-muted"><?php echo e(__('Invalid date')); ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="mb-3">
                                                                <strong><?php echo e(__('Duration')); ?>:</strong><br>
                                                                <span class="badge bg-success fs-6"><?php echo e(number_format($timeEntry->duration_hours ?? 0, 2)); ?> <?php echo e(__('hours')); ?></span>
                                                            </div>
                                                            <div class="mb-3">
                                                                <strong><?php echo e(__('User')); ?>:</strong><br>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                                         style="width: 32px; height: 32px;">
                                                                        <i class="bi bi-person-fill"></i>
                                                                    </div>
                                                                    <div>
                                                                        <div><?php echo e($timeEntry->user->name ?? __('Unknown')); ?></div>
                                                                        <small class="text-muted"><?php echo e($timeEntry->user->email ?? ''); ?></small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6 class="text-muted mb-3"><?php echo e(__('Project & Task')); ?></h6>
                                                            <div class="mb-3">
                                                                <strong><?php echo e(__('Project')); ?>:</strong><br>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                                         style="width: 24px; height: 24px;">
                                                                        <i class="bi bi-folder-fill" style="font-size: 0.8rem;"></i>
                                                                    </div>
                                                                    <span><?php echo e(optional(optional($timeEntry->task)->project)->title ?? __('No Project')); ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <strong><?php echo e(__('Task')); ?>:</strong><br>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="bg-info rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                                         style="width: 24px; height: 24px;">
                                                                        <i class="bi bi-check-square" style="font-size: 0.8rem;"></i>
                                                                    </div>
                                                                    <span>
                                                                        <?php if($timeEntry->task): ?>
                                                                            <?php echo e($timeEntry->task->title); ?>

                                                                        <?php else: ?>
                                                                            <?php echo e(__('General Task')); ?>

                                                                        <?php endif; ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <strong><?php echo e(__('Status')); ?>:</strong><br>
                                                                <span class="badge bg-success"><?php echo e(__('Logged')); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h6 class="text-muted mb-3"><?php echo e(__('Description')); ?></h6>
                                                            <div class="border rounded p-3 bg-light">
                                                                <?php if($timeEntry->comment): ?>
                                                                    <p class="mb-0"><?php echo e($timeEntry->comment); ?></p>
                                                                <?php else: ?>
                                                                    <p class="mb-0 text-muted fst-italic"><?php echo e(__('No description provided')); ?></p>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <?php if($timeEntry->canBeEditedBy(auth()->user())): ?>
                                                        <a href="<?php echo e(route('timesheet.edit', $timeEntry->id)); ?>" class="btn btn-primary">
                                                            <i class="bi bi-pencil me-2"></i><?php echo e(__('Edit Entry')); ?>

                                                        </a>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-clock-history fs-2"></i>
                                            <p class="mt-2"><?php echo e(__('No timesheet entries found')); ?></p>
                                            <a href="<?php echo e(route('timesheet.create')); ?>" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-1"></i><?php echo e(__('Add Your First Entry')); ?>

                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if(isset($timesheets) && $timesheets->hasPages()): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($timesheets->links()); ?>

                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('app.confirm')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage"><?php echo e(__('app.timesheet.confirm_action')); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                <button type="button" class="btn btn-primary" id="confirmActionBtn">
                    <span id="confirmBtnText"><?php echo e(__('app.confirm')); ?></span>
                    <span class="spinner-border spinner-border-sm ms-2 d-none" id="confirmSpinner"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    setupFilters();
    setupStatsToggle();
});

function setupFilters() {
    const searchInput = document.getElementById('search-input');
    const projectFilter = document.getElementById('project-filter');
    const userFilter = document.getElementById('user-filter');
    const dateFrom = document.getElementById('date-from');
    const dateTo = document.getElementById('date-to');

    let filterTimeout;

    function applyFilters() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            const params = new URLSearchParams(window.location.search);

            if (searchInput.value) params.set('search', searchInput.value);
            else params.delete('search');

            if (projectFilter.value) params.set('project', projectFilter.value);
            else params.delete('project');

            if (userFilter.value) params.set('user', userFilter.value);
            else params.delete('user');

            if (dateFrom.value) params.set('date_from', dateFrom.value);
            else params.delete('date_from');

            if (dateTo.value) params.set('date_to', dateTo.value);
            else params.delete('date_to');

            const newUrl = window.location.pathname + '?' + params.toString();
            window.location.href = newUrl;
        }, 500);
    }

    searchInput.addEventListener('input', applyFilters);
    projectFilter.addEventListener('change', applyFilters);
    userFilter.addEventListener('change', applyFilters);
    dateFrom.addEventListener('change', applyFilters);
    dateTo.addEventListener('change', applyFilters);

    // Set current values from URL params
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('search')) searchInput.value = urlParams.get('search');
    if (urlParams.get('project')) projectFilter.value = urlParams.get('project');
    if (urlParams.get('user')) userFilter.value = urlParams.get('user');
    if (urlParams.get('date_from')) dateFrom.value = urlParams.get('date_from');
    if (urlParams.get('date_to')) dateTo.value = urlParams.get('date_to');
}

function clearFilters() {
    document.getElementById('search-input').value = '';
    document.getElementById('project-filter').value = '';
    document.getElementById('user-filter').value = '';
    document.getElementById('date-from').value = '';
    document.getElementById('date-to').value = '';
    window.location.href = window.location.pathname;
}

function setupStatsToggle() {
    const toggleBtn = document.getElementById('toggleStats');
    const statsContent = document.getElementById('statsContent');
    const toggleIcon = document.getElementById('toggleIcon');

    // Check localStorage for saved state (default: visible)
    const isHidden = localStorage.getItem('statsHidden') === 'true';

    if (isHidden) {
        statsContent.style.display = 'none';
        toggleIcon.className = 'bi bi-chevron-down';
    } else {
        statsContent.style.display = 'block';
        toggleIcon.className = 'bi bi-chevron-up';
    }

    toggleBtn.addEventListener('click', function() {
        const isCurrentlyVisible = statsContent.style.display !== 'none';

        if (isCurrentlyVisible) {
            // Hide stats
            statsContent.style.display = 'none';
            toggleIcon.className = 'bi bi-chevron-down';
            localStorage.setItem('statsHidden', 'true');
        } else {
            // Show stats
            statsContent.style.display = 'block';
            toggleIcon.className = 'bi bi-chevron-up';
            localStorage.setItem('statsHidden', 'false');
        }
    });
}

let currentTimesheetId = null;
let currentAction = null;

function changeStatus(timesheetId, status) {
    currentTimesheetId = timesheetId;
    currentAction = 'status';

    document.getElementById('confirmMessage').textContent = '<?php echo e(__('app.timesheet.confirm_status_change')); ?>';
    document.getElementById('confirmActionBtn').innerHTML = '<span id="confirmBtnText"><?php echo e(__('app.confirm')); ?></span><span class="spinner-border spinner-border-sm ms-2 d-none" id="confirmSpinner"></span>';

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('confirmActionModal'));
    modal.show();
}

function deleteEntry(timesheetId) {
    currentTimesheetId = timesheetId;
    currentAction = 'delete';

    document.getElementById('confirmMessage').textContent = '<?php echo e(__('app.timesheet.confirm_delete_entry')); ?>';
    document.getElementById('confirmActionBtn').innerHTML = '<span id="confirmBtnText"><?php echo e(__('app.delete')); ?></span><span class="spinner-border spinner-border-sm ms-2 d-none" id="confirmSpinner"></span>';
    document.getElementById('confirmActionBtn').className = 'btn btn-danger';

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('confirmActionModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('confirmActionBtn').addEventListener('click', function() {
        if (!currentTimesheetId || !currentAction) return;

        const btn = this;
        const btnText = document.getElementById('confirmBtnText');
        const spinner = document.getElementById('confirmSpinner');

        // Show loading state
        btn.disabled = true;
        btnText.textContent = '<?php echo e(__('app.processing')); ?>';
        spinner.classList.remove('d-none');

        let request;
        if (currentAction === 'status') {
            request = axios.post(`/timesheet/${currentTimesheetId}/status`, {
                status: 'approved',
                _token: '<?php echo e(csrf_token()); ?>'
            });
        } else if (currentAction === 'delete') {
            request = axios.delete(`/timesheet/${currentTimesheetId}`, {
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            });
        }

        request.then(response => {
            if (response.data.success) {
                // Hide modal
                bootstrap.Modal.getInstance(document.getElementById('confirmActionModal')).hide();

                // Reload page
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                throw new Error(response.data.message || '<?php echo e(__('app.timesheet.error_updating_status')); ?>');
            }
        })
        .catch(error => {
            console.error('Error:', error);

            // Show error in modal
            document.getElementById('confirmMessage').innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    ${error.response?.data?.message || (currentAction === 'delete' ? '<?php echo e(__('app.timesheet.error_deleting_entry')); ?>' : '<?php echo e(__('app.timesheet.error_updating_status')); ?>')}
                </div>
            `;
        })
        .finally(() => {
            // Reset button state
            btn.disabled = false;
            btnText.textContent = currentAction === 'delete' ? '<?php echo e(__('app.delete')); ?>' : '<?php echo e(__('app.confirm')); ?>';
            spinner.classList.add('d-none');

            // Reset button color
            if (currentAction === 'delete') {
                btn.className = 'btn btn-danger';
            } else {
                btn.className = 'btn btn-primary';
            }
        });
    });

    // Setup timesheet filters toggle
    setupTimesheetFiltersToggle();
});

function setupTimesheetFiltersToggle() {
    const toggleBtn = document.getElementById('toggleTimesheetFilters');
    const filtersContent = document.getElementById('timesheetFiltersContent');
    const toggleIcon = document.getElementById('toggleTimesheetFiltersIcon');

    // Check localStorage for saved state (default: visible)
    const isHidden = localStorage.getItem('timesheetFiltersHidden') === 'true';

    if (isHidden) {
        filtersContent.style.display = 'none';
        toggleIcon.className = 'bi bi-chevron-down';
    } else {
        filtersContent.style.display = 'block';
        toggleIcon.className = 'bi bi-chevron-up';
    }

    toggleBtn.addEventListener('click', function() {
        const isCurrentlyVisible = filtersContent.style.display !== 'none';

        if (isCurrentlyVisible) {
            // Hide filters
            filtersContent.style.display = 'none';
            toggleIcon.className = 'bi bi-chevron-down';
            localStorage.setItem('timesheetFiltersHidden', 'true');
        } else {
            // Show filters
            filtersContent.style.display = 'block';
            toggleIcon.className = 'bi bi-chevron-up';
            localStorage.setItem('timesheetFiltersHidden', 'false');
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/timesheet/index.blade.php ENDPATH**/ ?>