<?php $__env->startSection('title', __('app.reports.time_summary')); ?>
<?php $__env->startSection('page-title', __('app.reports.time_summary')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clock me-2"></i>
                    <?php echo e(__('app.reports.time_summary')); ?>

                </h5>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="<?php echo e(route('reports.time-tracking')); ?>" class="row mb-4">
                    <div class="col-md-3">
                        <label for="user_id" class="form-label"><?php echo e(__('app.users.title')); ?></label>
                        <select class="form-select" id="user_id" name="user_id">
                            <option value=""><?php echo e(__('app.reports.all_users')); ?></option>
                            <?php if(isset($data['users'])): ?>
                                <?php $__currentLoopData = $data['users']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                                        <?php echo e($user->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="project_id" class="form-label"><?php echo e(__('app.projects.title')); ?></label>
                        <select class="form-select" id="project_id" name="project_id">
                            <option value=""><?php echo e(__('app.reports.all_projects')); ?></option>
                            <?php if(isset($data['projects'])): ?>
                                <?php $__currentLoopData = $data['projects']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($project->id); ?>" <?php echo e(request('project_id') == $project->id ? 'selected' : ''); ?>>
                                        <?php echo e($project->title); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="start_date" class="form-label"><?php echo e(__('app.projects.start_date')); ?></label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo e(request('start_date')); ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date" class="form-label"><?php echo e(__('app.projects.end_date')); ?></label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo e(request('end_date')); ?>">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search me-1"></i><?php echo e(__('app.filter')); ?>

                        </button>
                        <a href="<?php echo e(route('reports.time-tracking')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i><?php echo e(__('app.cancel')); ?>

                        </a>
                    </div>
                </form>

                <!-- Report Content -->
                <?php if(isset($data['time_entries']) && count($data['time_entries']) > 0): ?>
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo e(__('app.time.total_time')); ?></h5>
                                    <h3><?php echo e(number_format($data['total_hours'] ?? 0, 1)); ?>h</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo e(__('Total Entries')); ?></h5>
                                    <h3><?php echo e(count($data['time_entries'])); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo e(__('Avg Daily Hours')); ?></h5>
                                    <h3><?php echo e(number_format($data['avg_daily_hours'] ?? 0, 1)); ?>h</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo e(__('app.time.billable')); ?></h5>
                                    <h3><?php echo e(number_format($data['billable_hours'] ?? 0, 1)); ?>h</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Time Entries by User -->
                    <?php if(isset($data['users_summary']) && count($data['users_summary']) > 0): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><?php echo e(__('Hours by User')); ?></h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th><?php echo e(__('app.users.title')); ?></th>
                                                <th><?php echo e(__('app.time.total_time')); ?></th>
                                                <th><?php echo e(__('Entries')); ?></th>
                                                <th><?php echo e(__('Avg Per Day')); ?></th>
                                                <th><?php echo e(__('app.nav.projects')); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $data['users_summary']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userSummary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <i class="bi bi-person-circle me-1"></i>
                                                        <?php echo e($userSummary['name']); ?>

                                                    </td>
                                                    <td>
                                                        <strong><?php echo e(number_format($userSummary['total_hours'], 1)); ?>h</strong>
                                                    </td>
                                                    <td><?php echo e($userSummary['entries_count']); ?></td>
                                                    <td><?php echo e(number_format($userSummary['avg_daily_hours'], 1)); ?>h</td>
                                                    <td><?php echo e($userSummary['projects_count']); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Detailed Time Entries -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><?php echo e(__('Detailed Time Entries')); ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo e(__('app.date')); ?></th>
                                            <th><?php echo e(__('app.users.title')); ?></th>
                                            <th><?php echo e(__('app.projects.title')); ?></th>
                                            <th><?php echo e(__('app.tasks.title')); ?></th>
                                            <th><?php echo e(__('app.time.duration')); ?></th>
                                            <th><?php echo e(__('app.description')); ?></th>
                                            <th><?php echo e(__('app.actions')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $data['time_entries']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                    <?php if($entry->start_time): ?>
                                                        <?php
                                                            $startTime = is_string($entry->start_time) ? \Carbon\Carbon::parse($entry->start_time) : $entry->start_time;
                                                        ?>
                                                        <?php echo e($startTime->format('M d, Y')); ?>

                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <i class="bi bi-person-circle me-1"></i>
                                                    <?php echo e($entry->user->name); ?>

                                                </td>
                                                <td>
                                                    <?php if($entry->task && $entry->task->project): ?>
                                                        <a href="<?php echo e(route('projects.show', $entry->task->project)); ?>" class="text-decoration-none">
                                                            <?php echo e($entry->task->project->title); ?>

                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($entry->task): ?>
                                                        <a href="<?php echo e(route('tasks.show', $entry->task)); ?>" class="text-decoration-none">
                                                            <?php echo e(Str::limit($entry->task->title, 30)); ?>

                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        <?php echo e(number_format($entry->duration_hours, 1)); ?>h
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if($entry->comment || $entry->description): ?>
                                                        <?php echo e(Str::limit($entry->comment ?? $entry->description, 50)); ?>

                                                    <?php else: ?>
                                                        <span class="text-muted"><?php echo e(__('app.description')); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($entry->task): ?>
                                                        <a href="<?php echo e(route('tasks.show', $entry->task)); ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Export Options -->
                    <div class="mt-4">
                        <h6><?php echo e(__('app.export')); ?></h6>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-success" onclick="exportReport('csv')">
                                <i class="bi bi-file-earmark-spreadsheet me-1"></i><?php echo e(__('app.reports.export_excel')); ?>

                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="exportReport('pdf')">
                                <i class="bi bi-file-earmark-pdf me-1"></i><?php echo e(__('app.reports.export_pdf')); ?>

                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-clock-history text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3"><?php echo e(__('app.time.no_entries')); ?></h5>
                        <p class="text-muted"><?php echo e(__('Try adjusting your filters or start logging time on tasks.')); ?></p>
                        <a href="<?php echo e(route('timesheet.index')); ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i><?php echo e(__('app.nav.timesheet')); ?>

                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function exportReport(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);

    const url = `<?php echo e(route('reports.time-tracking')); ?>?${params.toString()}`;
    window.open(url, '_blank');
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/reports/time-tracking.blade.php ENDPATH**/ ?>