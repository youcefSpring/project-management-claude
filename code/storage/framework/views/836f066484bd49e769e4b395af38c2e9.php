<?php $__env->startSection('title', __('app.reports.project_summary')); ?>
<?php $__env->startSection('page-title', __('app.reports.project_summary')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-folder me-2"></i>
                    <?php echo e(__('app.reports.project_summary')); ?>

                </h5>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="<?php echo e(route('reports.projects')); ?>" class="row mb-4">
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label for="start_date" class="form-label"><?php echo e(__('app.projects.start_date')); ?></label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo e(request('start_date')); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label"><?php echo e(__('app.projects.end_date')); ?></label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo e(request('end_date')); ?>">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search me-1"></i><?php echo e(__('app.filter')); ?>

                        </button>
                        <a href="<?php echo e(route('reports.projects')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i><?php echo e(__('app.cancel')); ?>

                        </a>
                    </div>
                </form>

                <!-- Report Content -->
                <?php if(isset($data['projects']) && count($data['projects']) > 0): ?>
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo e(__('app.dashboard.total_projects')); ?></h5>
                                    <h3><?php echo e(count($data['projects'])); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo e(__('app.tasks.title')); ?></h5>
                                    <h3><?php echo e($data['total_tasks'] ?? 0); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo e(__('app.time.total_time')); ?></h5>
                                    <h3><?php echo e(number_format($data['total_hours'] ?? 0, 1)); ?>h</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo e(__('Avg Completion')); ?></h5>
                                    <h3><?php echo e(number_format($data['avg_completion'] ?? 0, 1)); ?>%</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Projects Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('app.projects.title')); ?></th>
                                    <th><?php echo e(__('app.projects.manager')); ?></th>
                                    <th><?php echo e(__('app.status')); ?></th>
                                    <th><?php echo e(__('app.tasks.title')); ?></th>
                                    <th><?php echo e(__('Completion')); ?></th>
                                    <th><?php echo e(__('app.time.total_time')); ?></th>
                                    <th><?php echo e(__('Team Size')); ?></th>
                                    <th><?php echo e(__('app.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data['projects']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($project->title); ?></strong>
                                            <?php if($project->description): ?>
                                                <br><small class="text-muted"><?php echo e(Str::limit($project->description, 50)); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($project->manager): ?>
                                                <i class="bi bi-person-circle me-1"></i>
                                                <?php echo e($project->manager->name); ?>

                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('app.projects.manager')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($project->status === 'active' ? 'success' : ($project->status === 'completed' ? 'primary' : 'warning')); ?>">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $project->status))); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                                $totalTasks = $project->tasks->count();
                                                $completedTasks = $project->tasks->where('status', 'completed')->count();
                                            ?>
                                            <?php echo e($completedTasks); ?>/<?php echo e($totalTasks); ?>

                                        </td>
                                        <td>
                                            <?php
                                                $completion = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                                            ?>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" role="progressbar" style="width: <?php echo e($completion); ?>%" aria-valuenow="<?php echo e($completion); ?>" aria-valuemin="0" aria-valuemax="100">
                                                    <?php echo e(number_format($completion, 1)); ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                                $projectHours = $project->tasks->flatMap->timeEntries->sum('duration_hours');
                                            ?>
                                            <i class="bi bi-clock me-1"></i>
                                            <?php echo e(number_format($projectHours, 1)); ?>h
                                        </td>
                                        <td>
                                            <?php
                                                $teamSize = $project->tasks->pluck('assigned_to')->filter()->unique()->count();
                                            ?>
                                            <i class="bi bi-people me-1"></i>
                                            <?php echo e($teamSize); ?>

                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('projects.show', $project)); ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i><?php echo e(__('app.View all')); ?>

                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
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
                        <i class="bi bi-folder-x text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3"><?php echo e(__('app.projects.no_projects')); ?></h5>
                        <p class="text-muted"><?php echo e(__('Try adjusting your filters or create a new project.')); ?></p>
                        <a href="<?php echo e(route('projects.create')); ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i><?php echo e(__('app.projects.create')); ?>

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

    const url = `<?php echo e(route('reports.projects')); ?>?${params.toString()}`;
    window.open(url, '_blank');
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/reports/projects.blade.php ENDPATH**/ ?>