<?php $__env->startSection('title', $project->title); ?>
<?php $__env->startSection('page-title', $project->title); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Project Header -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <h1 class="mb-0 me-3"><?php echo e($project->title); ?></h1>
                            <span class="badge status-<?php echo e($project->status); ?> status-badge">
                                <?php switch($project->status):
                                    case ('planning'): ?> <?php echo e(__('app.projects.planning')); ?> <?php break; ?>
                                    <?php case ('active'): ?> <?php echo e(__('app.projects.active')); ?> <?php break; ?>
                                    <?php case ('on_hold'): ?> <?php echo e(__('app.projects.on_hold')); ?> <?php break; ?>
                                    <?php case ('completed'): ?> <?php echo e(__('app.projects.completed')); ?> <?php break; ?>
                                    <?php case ('cancelled'): ?> <?php echo e(__('app.projects.cancelled')); ?> <?php break; ?>
                                    <?php default: ?> <?php echo e(ucfirst($project->status)); ?>

                                <?php endswitch; ?>
                            </span>
                        </div>
                        <p class="text-muted mb-3"><?php echo e($project->description); ?></p>

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <i class="bi bi-person-fill me-2 text-primary"></i>
                                <strong><?php echo e(__('app.projects.manager')); ?>:</strong> <?php echo e($project->manager->name); ?>

                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="bi bi-calendar me-2 text-primary"></i>
                                <strong><?php echo e(__('app.projects.start_date')); ?>:</strong> <?php echo e($project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : __('app.not_available')); ?>

                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="bi bi-calendar-check me-2 text-primary"></i>
                                <strong><?php echo e(__('app.projects.end_date')); ?>:</strong> <?php echo e($project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M d, Y') : __('app.not_available')); ?>

                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="bi bi-clock me-2 text-primary"></i>
                                <strong><?php echo e(__('app.projects.total_hours')); ?>:</strong> <?php echo e($stats['total_hours']); ?>h
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 text-center">
                        <!-- Progress Chart -->
                        <div class="position-relative d-inline-block mb-3">
                            <canvas id="progressChart" width="150" height="150"></canvas>
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <h3 class="mb-0"><?php echo e($stats['progress_percentage']); ?>%</h3>
                                <small class="text-muted"><?php echo e(__('app.projects.complete')); ?></small>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col-6">
                                <div class="fw-bold text-primary fs-4"><?php echo e($stats['total_tasks']); ?></div>
                                <small class="text-muted"><?php echo e(__('app.projects.total_tasks')); ?></small>
                            </div>
                            <div class="col-6">
                                <div class="fw-bold text-success fs-4"><?php echo e($stats['completed_tasks']); ?></div>
                                <small class="text-muted"><?php echo e(__('app.projects.completed_tasks')); ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                <div class="mt-3 pt-3 border-top">
                    <a href="<?php echo e(route('projects.edit', $project)); ?>" class="btn btn-primary me-2">
                        <i class="bi bi-pencil me-2"></i><?php echo e(__('app.projects.edit_project')); ?>

                    </a>
                    <a href="<?php echo e(route('tasks.create')); ?>?project_id=<?php echo e($project->id); ?>" class="btn btn-outline-success me-2">
                        <i class="bi bi-plus-circle me-2"></i><?php echo e(__('app.projects.add_task')); ?>

                    </a>
                    <button class="btn btn-outline-warning" onclick="changeProjectStatus()">
                        <i class="bi bi-arrow-repeat me-2"></i><?php echo e(__('app.projects.change_status')); ?>

                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tasks Section -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-check2-square me-2"></i>
                    <?php echo e(__('app.projects.project_tasks')); ?>

                </h5>
                <div>
                    <button class="btn btn-sm btn-outline-secondary me-2" onclick="refreshTasks()">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                    <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                    <a href="<?php echo e(route('tasks.create')); ?>?project_id=<?php echo e($project->id); ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i><?php echo e(__('app.projects.add_task')); ?>

                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <!-- Task Filters -->
                <form method="GET" action="<?php echo e(route('projects.show', $project)); ?>" class="row mb-3">
                    <div class="col-md-4">
                        <select class="form-select form-select-sm" name="status" onchange="this.form.submit()">
                            <option value=""><?php echo e(__('app.projects.all_tasks')); ?></option>
                            <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.pending')); ?></option>
                            <option value="in_progress" <?php echo e(request('status') === 'in_progress' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.in_progress')); ?></option>
                            <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>><?php echo e(__('app.tasks.completed')); ?></option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control form-control-sm" name="search"
                               value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('app.projects.search_tasks')); ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Tasks List -->
                <div id="tasks-container">
                    <?php if($project->tasks->count() > 0): ?>
                        <?php $__currentLoopData = $project->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card border-start border-<?php echo e($task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'warning' : 'secondary')); ?> border-3 mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-1">
                                                <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-decoration-none"><?php echo e($task->title); ?></a>
                                            </h6>
                                            <p class="text-muted small mb-2"><?php echo e($task->description ?: ''); ?></p>
                                            <div class="d-flex align-items-center flex-wrap gap-2">
                                                <span class="badge status-<?php echo e($task->status); ?> status-badge">
                                                    <?php switch($task->status):
                                                        case ('pending'): ?> <?php echo e(__('app.tasks.pending')); ?> <?php break; ?>
                                                        <?php case ('in_progress'): ?> <?php echo e(__('app.tasks.in_progress')); ?> <?php break; ?>
                                                        <?php case ('completed'): ?> <?php echo e(__('app.tasks.completed')); ?> <?php break; ?>
                                                        <?php case ('cancelled'): ?> <?php echo e(__('app.tasks.cancelled')); ?> <?php break; ?>
                                                        <?php default: ?> <?php echo e(ucfirst(str_replace('_', ' ', $task->status))); ?>

                                                    <?php endswitch; ?>
                                                </span>
                                                <?php if($task->assignedUser): ?>
                                                    <span class="badge bg-light text-dark">
                                                        <i class="bi bi-person me-1"></i><?php echo e($task->assignedUser->name); ?>

                                                    </span>
                                                <?php endif; ?>
                                                <?php if($task->due_date): ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-calendar me-1"></i><?php echo e(\Carbon\Carbon::parse($task->due_date)->format('M d')); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="<?php echo e(route('tasks.show', $task)); ?>">
                                                    <i class="bi bi-eye me-2"></i><?php echo e(__('app.view')); ?>

                                                </a></li>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                                                <li><a class="dropdown-item" href="<?php echo e(route('tasks.edit', $task)); ?>">
                                                    <i class="bi bi-pencil me-2"></i><?php echo e(__('app.edit')); ?>

                                                </a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-check2-square fs-2"></i>
                            <p class="mt-2"><?php echo e(__('app.projects.no_tasks_found')); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Team Members -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>
                    <?php echo e(__('app.projects.team_members')); ?>

                </h5>
            </div>
            <div class="card-body">
                <div id="team-members">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                             style="width: 32px; height: 32px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <div class="fw-bold"><?php echo e($project->manager->name); ?></div>
                            <small class="text-muted"><?php echo e(__('app.projects.project_manager')); ?></small>
                        </div>
                    </div>
                    <?php $__currentLoopData = $allTasks->where('assigned_to', '!=', null)->unique('assigned_to'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                             style="width: 32px; height: 32px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <div class="fw-bold"><?php echo e($task->assignedUser->name ?? __('app.unassigned')); ?></div>
                            <small class="text-muted"><?php echo e(__('app.projects.team_member')); ?></small>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-activity me-2"></i>
                    <?php echo e(__('app.projects.recent_activity')); ?>

                </h5>
            </div>
            <div class="card-body">
                <div id="project-activity">
                    <!-- Activity will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    initProgressChart();
    loadProjectActivity();
});

function initProgressChart() {
    const ctx = document.getElementById('progressChart').getContext('2d');
    const progress = <?php echo e($stats['progress_percentage']); ?>;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [progress, 100 - progress],
                backgroundColor: ['#198754', '#e9ecef'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                }
            },
            responsive: false,
            maintainAspectRatio: false
        }
    });
}


function loadProjectActivity() {
    const container = document.getElementById('project-activity');

    // Show loader
    container.innerHTML = `
        <div class="d-flex justify-content-center align-items-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span class="ms-2 text-muted small"><?php echo e(__('app.projects.loading_activity')); ?></span>
        </div>
    `;

    // Load project activity (using static data for now, but structure is ready for API)
    setTimeout(() => {
        container.innerHTML = `
            <div class="small">
                <div class="d-flex mb-3">
                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                         style="width: 24px; height: 24px;">
                        <i class="bi bi-check" style="font-size: 0.7rem;"></i>
                    </div>
                    <div>
                        <div><?php echo e(__('app.projects.task_completed')); ?></div>
                        <small class="text-muted"><?php echo e(__('app.projects.hours_ago', ['hours' => 2])); ?></small>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                         style="width: 24px; height: 24px;">
                        <i class="bi bi-plus" style="font-size: 0.7rem;"></i>
                    </div>
                    <div>
                        <div><?php echo e(__('app.projects.new_task_added')); ?></div>
                        <small class="text-muted"><?php echo e(__('app.projects.hours_ago', ['hours' => 5])); ?></small>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                         style="width: 24px; height: 24px;">
                        <i class="bi bi-clock" style="font-size: 0.7rem;"></i>
                    </div>
                    <div>
                        <div><?php echo e(__('app.projects.time_logged')); ?></div>
                        <small class="text-muted"><?php echo e(__('app.projects.day_ago')); ?></small>
                    </div>
                </div>
            </div>
        `;
    }, 600);
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/projects/show.blade.php ENDPATH**/ ?>