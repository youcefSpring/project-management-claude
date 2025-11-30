<?php $__env->startSection('title', __('app.projects.title')); ?>
<?php $__env->startSection('page-title', __('app.projects.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Header Actions -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1"><?php echo e(__('app.projects.title')); ?></h2>
                <p class="text-muted mb-0"><?php echo e(__('app.projects.manage_and_track')); ?></p>
            </div>
            <div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Project::class)): ?>
                <a href="<?php echo e(route('projects.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    <?php echo e(__('app.projects.new_project')); ?>

                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-muted">
                    <i class="bi bi-funnel me-2"></i><?php echo e(__('app.projects.project_filters')); ?>

                </h6>
                <button type="button" id="toggleFilters" class="btn btn-sm btn-outline-secondary" title="<?php echo e(__('app.toggle_filters')); ?>">
                    <i class="bi bi-chevron-up" id="toggleFiltersIcon"></i>
                </button>
            </div>
            <div class="card-body p-3" id="filtersContent">
                <form method="GET" action="<?php echo e(route('projects.index')); ?>" class="row g-3 align-items-end">
                    <div class="<?php echo e(auth()->user()->isAdmin() ? 'col-md-3' : 'col-md-4'); ?>">
                        <label for="status" class="form-label small text-muted"><?php echo e(__('app.status')); ?></label>
                        <select class="form-select form-select-sm" id="status" name="status">
                            <option value=""><?php echo e(__('app.projects.all_statuses')); ?></option>
                            <option value="planning" <?php echo e(request('status') === 'planning' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.projects.planning')); ?>

                            </option>
                            <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.projects.active')); ?>

                            </option>
                            <option value="on_hold" <?php echo e(request('status') === 'on_hold' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.projects.on_hold')); ?>

                            </option>
                            <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.projects.completed')); ?>

                            </option>
                            <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>
                                <?php echo e(__('app.projects.cancelled')); ?>

                            </option>
                        </select>
                    </div>

                    <?php if(auth()->user()->isAdmin()): ?>
                    <div class="col-md-3">
                        <label for="manager_id" class="form-label small text-muted"><?php echo e(__('app.projects.manager')); ?></label>
                        <select class="form-select form-select-sm" id="manager_id" name="manager_id">
                            <option value=""><?php echo e(__('app.all_managers')); ?></option>
                            <?php $__currentLoopData = $managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manager): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($manager->id); ?>" <?php echo e(request('manager_id') == $manager->id ? 'selected' : ''); ?>>
                                    <?php echo e($manager->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="<?php echo e(auth()->user()->isAdmin() ? 'col-md-4' : 'col-md-6'); ?>">
                        <label for="search" class="form-label small text-muted"><?php echo e(__('app.search')); ?></label>
                        <input type="text" class="form-control form-control-sm" id="search" name="search"
                               value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('app.projects.search_placeholder')); ?>">
                    </div>

                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="<?php echo e(route('projects.index')); ?>" class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Projects List -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <?php echo e(__('app.projects.title')); ?>

                    <span class="badge bg-secondary ms-2"><?php echo e($projects->count()); ?></span>
                </h5>
            </div>
            <div class="card-body">
                <?php if($projects->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th><?php echo e(__('app.projects.name')); ?></th>
                                    <th><?php echo e(__('app.projects.manager')); ?></th>
                                    <th><?php echo e(__('app.status')); ?></th>
                                    <th><?php echo e(__('app.projects.dates')); ?></th>
                                    <th><?php echo e(__('app.projects.progress')); ?></th>
                                    <th><?php echo e(__('app.tasks.title')); ?></th>
                                    <th><?php echo e(__('app.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $tasksCount = $project->tasks_count ?? $project->tasks->count();
                                        $completedTasks = $project->completed_tasks_count ?? $project->tasks->where('status', 'completed')->count();
                                        $progress = $tasksCount > 0 ? round(($completedTasks / $tasksCount) * 100) : 0;
                                    ?>
                                    <tr>
                                        <td>
                                            <div>
                                                <a href="<?php echo e(route('projects.show', $project)); ?>" class="fw-bold text-decoration-none">
                                                    <?php echo e($project->title); ?>

                                                </a>
                                                <?php if($project->description): ?>
                                                    <br>
                                                    <small class="text-muted"><?php echo e(Str::limit($project->description, 80)); ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                     style="width: 32px; height: 32px;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <span><?php echo e($project->manager->name ?? __('app.no_manager')); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($project->status === 'active' ? 'success' : ($project->status === 'completed' ? 'primary' : 'warning')); ?>">
                                                <?php switch($project->status):
                                                    case ('planning'): ?> <?php echo e(__('app.projects.planning')); ?> <?php break; ?>
                                                    <?php case ('active'): ?> <?php echo e(__('app.projects.active')); ?> <?php break; ?>
                                                    <?php case ('on_hold'): ?> <?php echo e(__('app.projects.on_hold')); ?> <?php break; ?>
                                                    <?php case ('completed'): ?> <?php echo e(__('app.projects.completed')); ?> <?php break; ?>
                                                    <?php case ('cancelled'): ?> <?php echo e(__('app.projects.cancelled')); ?> <?php break; ?>
                                                    <?php default: ?> <?php echo e(ucfirst($project->status)); ?>

                                                <?php endswitch; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php if($project->start_date && $project->end_date): ?>
                                                    <?php echo e(\Carbon\Carbon::parse($project->start_date)->format('M d')); ?> - <?php echo e(\Carbon\Carbon::parse($project->end_date)->format('M d, Y')); ?>

                                                <?php else: ?>
                                                    <?php echo e(__('app.no_dates_set')); ?>

                                                <?php endif; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div style="min-width: 120px;">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <small class="text-muted"><?php echo e($progress); ?>%</small>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-<?php echo e($progress >= 75 ? 'success' : ($progress >= 50 ? 'warning' : 'danger')); ?>"
                                                         style="width: <?php echo e($progress); ?>%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php echo e($completedTasks); ?>/<?php echo e($tasksCount); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown dropstart">
                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 160px;">
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('projects.show', $project)); ?>">
                                                            <i class="bi bi-eye me-2"></i><?php echo e(__('app.projects.view')); ?>

                                                        </a>
                                                    </li>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $project)): ?>
                                                        <li>
                                                            <a class="dropdown-item" href="<?php echo e(route('projects.edit', $project)); ?>">
                                                                <i class="bi bi-pencil me-2"></i><?php echo e(__('app.edit')); ?>

                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('tasks.create')); ?>?project_id=<?php echo e($project->id); ?>">
                                                            <i class="bi bi-plus-circle me-2"></i><?php echo e(__('app.projects.add_task')); ?>

                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-folder-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted"><?php echo e(__('app.projects.no_projects')); ?></h5>
                        <p class="text-muted">
                            <?php if(request()->hasAny(['status', 'manager_id', 'search'])): ?>
                                <?php echo e(__('app.try_adjusting_filters')); ?>

                                <a href="<?php echo e(route('projects.index')); ?>"><?php echo e(__('app.clear_filters')); ?></a>
                            <?php else: ?>
                                <?php echo e(__('app.projects.get_started_message')); ?>

                            <?php endif; ?>
                        </p>
                        <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                            <a href="<?php echo e(route('projects.create')); ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                <?php echo e(__('app.projects.create')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    setupFiltersToggle();
});

function setupFiltersToggle() {
    const toggleBtn = document.getElementById('toggleFilters');
    const filtersContent = document.getElementById('filtersContent');
    const toggleIcon = document.getElementById('toggleFiltersIcon');

    // Check localStorage for saved state (default: visible)
    const isHidden = localStorage.getItem('projectFiltersHidden') === 'true';

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
            localStorage.setItem('projectFiltersHidden', 'true');
        } else {
            // Show filters
            filtersContent.style.display = 'block';
            toggleIcon.className = 'bi bi-chevron-up';
            localStorage.setItem('projectFiltersHidden', 'false');
        }
    });
}
</script>

<style>
/* Fix dropdown positioning and prevent overflow issues */
.table-responsive {
    overflow-x: auto;
}

.dropdown-menu {
    border: 1px solid rgba(0,0,0,.15);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
    z-index: 1021;
}

/* Ensure dropdowns don't break table layout */
.dropdown {
    position: static;
}

@media (max-width: 768px) {
    .dropdown.dropstart .dropdown-menu {
        --bs-position: absolute;
        inset: 0px auto auto 0px !important;
        transform: translate(-100%, 0px) !important;
    }
}

/* Fix for small screens */
@media (max-width: 576px) {
    .dropdown.dropstart {
        position: static;
    }

    .dropdown.dropstart .dropdown-menu {
        position: absolute !important;
        right: 0 !important;
        left: auto !important;
        transform: none !important;
    }
}
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/projects/index.blade.php ENDPATH**/ ?>