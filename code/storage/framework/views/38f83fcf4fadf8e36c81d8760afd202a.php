<?php $__env->startSection('page-title', __('app.Dashboard')); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="stats-number mb-0"><?php echo e($stats['total_users'] ?? 0); ?></h3>
                    <p class="text-muted mb-0"><?php echo e(__('app.dashboard.total_users')); ?></p>
                </div>
                <div class="text-primary">
                    <i class="bi bi-people" style="font-size: 2.5rem;"></i>
                </div>
            </div>
            <div class="mt-2">
                <div class="btn-group w-100" role="group">
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye me-1"></i><?php echo e(__('app.View all')); ?>

                    </a>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="bi bi-plus"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userStatsModal">
                        <i class="bi bi-graph-up"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="stats-number mb-0"><?php echo e($stats['projects'] ?? 0); ?></h3>
                    <p class="text-muted mb-0"><?php echo e(__('app.dashboard.projects')); ?></p>
                </div>
                <div class="text-success">
                    <i class="bi bi-folder" style="font-size: 2.5rem;"></i>
                </div>
            </div>
            <div class="mt-2">
                <div class="btn-group w-100" role="group">
                    <a href="<?php echo e(route('admin.projects.index')); ?>" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-eye me-1"></i><?php echo e(__('app.View all')); ?>

                    </a>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                        <i class="bi bi-plus"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#projectStatsModal">
                        <i class="bi bi-graph-up"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="stats-number mb-0"><?php echo e($stats['completed_tasks'] ?? 0); ?></h3>
                    <p class="text-muted mb-0"><?php echo e(__('app.dashboard.completed_tasks')); ?></p>
                </div>
                <div class="text-info">
                    <i class="bi bi-check2-square" style="font-size: 2.5rem;"></i>
                </div>
            </div>
            <div class="mt-2">
                <div class="btn-group w-100" role="group">
                    <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-eye me-1"></i><?php echo e(__('app.View all')); ?>

                    </a>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                        <i class="bi bi-plus"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#taskStatsModal">
                        <i class="bi bi-graph-up"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="stats-number mb-0"><?php echo e(number_format($stats['total_time_logged'] ?? 0, 1)); ?>h</h3>
                    <p class="text-muted mb-0"><?php echo e(__('app.dashboard.time_logged')); ?></p>
                </div>
                <div class="text-warning">
                    <i class="bi bi-clock" style="font-size: 2.5rem;"></i>
                </div>
            </div>
            <div class="mt-2">
                <div class="btn-group w-100" role="group">
                    <a href="<?php echo e(route('timesheet.index')); ?>" class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-eye me-1"></i><?php echo e(__('app.View all')); ?>

                    </a>
                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#timeStatsModal">
                        <i class="bi bi-graph-up"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#exportTimeModal">
                        <i class="bi bi-download"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning text-primary me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-outline-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>
                            <?php echo e(__('app.users.create')); ?>

                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo e(route('projects.create')); ?>" class="btn btn-outline-success w-100">
                            <i class="bi bi-plus-circle me-2"></i>
                            <?php echo e(__('app.projects.create')); ?>

                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-outline-info w-100">
                            <i class="bi bi-plus-circle me-2"></i>
                            <?php echo e(__('app.tasks.create')); ?>

                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo e(route('admin.settings.index')); ?>" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-gear me-2"></i>
                            <?php echo e(__('app.settings')); ?>

                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-outline-warning w-100">
                            <i class="bi bi-graph-up me-2"></i>
                            <?php echo e(__('app.reports.title')); ?>

                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo e(route('admin.translations.index')); ?>" class="btn btn-outline-dark w-100">
                            <i class="bi bi-translate me-2"></i>
                            <?php echo e(__('app.Translations')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-envelope text-primary me-2"></i>
                    Messages
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span><?php echo e(__('app.dashboard.active_projects')); ?></span>
                    <span class="badge bg-primary"><?php echo e($stats['active_projects'] ?? 0); ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span><?php echo e(__('app.dashboard.total_users')); ?></span>
                    <span class="badge bg-secondary"><?php echo e($stats['total_users'] ?? 0); ?></span>
                </div>
                <div class="d-grid">
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-people me-1"></i>
                        <?php echo e(__('app.users.title')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history text-primary me-2"></i>
                    <?php echo e(__('app.dashboard.recent_activity')); ?>

                </h5>
                <small class="text-muted">Last 7 days</small>
            </div>
            <div class="card-body">
                <?php if($recentActivity && count($recentActivity) > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <?php if($activity['type'] === 'course'): ?>
                                            <div class="bg-primary bg-opacity-10 p-2 rounded">
                                                <i class="bi bi-book text-primary"></i>
                                            </div>
                                        <?php elseif($activity['type'] === 'project'): ?>
                                            <div class="bg-success bg-opacity-10 p-2 rounded">
                                                <i class="bi bi-code-slash text-success"></i>
                                            </div>
                                        <?php elseif($activity['type'] === 'publication'): ?>
                                            <div class="bg-info bg-opacity-10 p-2 rounded">
                                                <i class="bi bi-journal-text text-info"></i>
                                            </div>
                                        <?php else: ?>
                                            <div class="bg-warning bg-opacity-10 p-2 rounded">
                                                <i class="bi bi-pencil-square text-warning"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?php echo e($activity['action']); ?></h6>
                                        <p class="mb-1 text-muted"><?php echo e($activity['title']); ?></p>
                                        <small class="text-muted"><?php echo e($activity['date']); ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-clock text-muted mb-3" style="font-size: 3rem;"></i>
                        <h6 class="text-muted"><?php echo e(__('app.dashboard.no_recent_activity')); ?></h6>
                        <p class="text-muted mb-0"><?php echo e(__('app.dashboard.recent_actions_appear_here')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up text-primary me-2"></i>
                    Site Performance
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Projects & Tasks</span>
                        <span class="small fw-bold"><?php echo e(($stats['projects'] + $stats['completed_tasks']) ?? 0); ?></span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: 85%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small"><?php echo e(__('app.dashboard.profile_completeness')); ?></span>
                        <span class="small fw-bold"><?php echo e($stats['profile_completion'] ?? 70); ?>%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: <?php echo e($stats['profile_completion'] ?? 70); ?>%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small"><?php echo e(__('app.dashboard.response_rate')); ?></span>
                        <span class="small fw-bold"><?php echo e($stats['response_rate'] ?? 95); ?>%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: <?php echo e($stats['response_rate'] ?? 95); ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-shield-check text-primary me-2"></i>
                    System Status
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small"><?php echo e(__('app.dashboard.application')); ?></span>
                    <span class="badge bg-success"><?php echo e(__('app.dashboard.online')); ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small"><?php echo e(__('app.dashboard.database')); ?></span>
                    <span class="badge bg-success"><?php echo e(__('app.dashboard.connected')); ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small"><?php echo e(__('app.dashboard.file_storage')); ?></span>
                    <span class="badge bg-success">Available</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small">Email Service</span>
                    <span class="badge bg-success">Active</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Action Modals -->

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus text-primary me-2"></i><?php echo e(__('app.users.create')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('admin.users.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user_name" class="form-label"><?php echo e(__('app.name')); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="user_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="user_email" class="form-label"><?php echo e(__('app.email')); ?> <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="user_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="user_role" class="form-label"><?php echo e(__('app.users.role')); ?> <span class="text-danger">*</span></label>
                        <select class="form-select" id="user_role" name="role" required>
                            <option value="member"><?php echo e(__('app.users.member')); ?></option>
                            <option value="manager"><?php echo e(__('app.users.manager')); ?></option>
                            <option value="admin"><?php echo e(__('app.users.admin')); ?></option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="user_password" class="form-label"><?php echo e(__('app.password')); ?> <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="user_password" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i><?php echo e(__('app.create')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Statistics Modal -->
<div class="modal fade" id="userStatsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-graph-up text-primary me-2"></i><?php echo e(__('app.users.statistics')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-primary mb-1"><?php echo e($stats['total_users'] ?? 0); ?></h4>
                            <small class="text-muted"><?php echo e(__('app.dashboard.total_users')); ?></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-success mb-1"><?php echo e($stats['active_users'] ?? 0); ?></h4>
                            <small class="text-muted"><?php echo e(__('app.users.active')); ?></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-warning mb-1"><?php echo e($stats['new_users_this_month'] ?? 0); ?></h4>
                            <small class="text-muted"><?php echo e(__('app.users.new_this_month')); ?></small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-primary"><?php echo e(__('app.users.manage')); ?></a>
                    <a href="<?php echo e(route('admin.users.export')); ?>" class="btn btn-outline-success"><?php echo e(__('app.export')); ?> <?php echo e(__('app.users.title')); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Project Modal -->
<div class="modal fade" id="createProjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-folder-plus text-success me-2"></i><?php echo e(__('app.projects.create')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('projects.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="project_name" class="form-label"><?php echo e(__('app.projects_dashboard.name')); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="project_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="project_description" class="form-label"><?php echo e(__('app.description')); ?></label>
                        <textarea class="form-control" id="project_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="project_start_date" class="form-label"><?php echo e(__('app.projects_dashboard.start_date')); ?> <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="project_start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="project_end_date" class="form-label"><?php echo e(__('app.projects_dashboard.end_date')); ?> <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="project_end_date" name="end_date" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i><?php echo e(__('app.create')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Project Statistics Modal -->
<div class="modal fade" id="projectStatsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-graph-up text-success me-2"></i><?php echo e(__('app.projects_dashboard.statistics')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-success mb-1"><?php echo e($stats['projects'] ?? 0); ?></h4>
                            <small class="text-muted"><?php echo e(__('app.projects_dashboard.total')); ?></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-primary mb-1"><?php echo e($stats['active_projects'] ?? 0); ?></h4>
                            <small class="text-muted"><?php echo e(__('app.projects_dashboard.active')); ?></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-warning mb-1"><?php echo e($stats['projects_due_soon'] ?? 0); ?></h4>
                            <small class="text-muted"><?php echo e(__('app.projects_dashboard.due_soon')); ?></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-info mb-1"><?php echo e($stats['completed_projects'] ?? 0); ?></h4>
                            <small class="text-muted"><?php echo e(__('app.projects_dashboard.completed')); ?></small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <a href="<?php echo e(route('admin.projects.index')); ?>" class="btn btn-outline-success"><?php echo e(__('app.projects_dashboard.manage')); ?></a>
                    <a href="<?php echo e(route('reports.projects')); ?>" class="btn btn-outline-info"><?php echo e(__('app.reports_dashboard.detailed')); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Task Modal -->
<div class="modal fade" id="createTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-square text-info me-2"></i><?php echo e(__('app.tasks.create')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('tasks.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="task_title" class="form-label"><?php echo e(__('app.tasks.title')); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="task_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="task_project" class="form-label"><?php echo e(__('app.projects_dashboard.project')); ?> <span class="text-danger">*</span></label>
                        <select class="form-select" id="task_project" name="project_id" required>
                            <option value=""><?php echo e(__('app.projects_dashboard.select')); ?></option>
                            <?php $__currentLoopData = \App\Models\Project::where('organization_id', auth()->user()->organization_id)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($project->id); ?>"><?php echo e($project->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="task_description" class="form-label"><?php echo e(__('app.description')); ?></label>
                        <textarea class="form-control" id="task_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="task_priority" class="form-label"><?php echo e(__('app.tasks.priority')); ?></label>
                            <select class="form-select" id="task_priority" name="priority">
                                <option value="low"><?php echo e(__('app.tasks.priority_low')); ?></option>
                                <option value="medium" selected><?php echo e(__('app.tasks.priority_medium')); ?></option>
                                <option value="high"><?php echo e(__('app.tasks.priority_high')); ?></option>
                                <option value="urgent"><?php echo e(__('app.tasks.priority_urgent')); ?></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="task_due_date" class="form-label"><?php echo e(__('app.tasks.due_date')); ?></label>
                            <input type="date" class="form-control" id="task_due_date" name="due_date">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-check-lg me-1"></i><?php echo e(__('app.create')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Task Statistics Modal -->
<div class="modal fade" id="taskStatsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-graph-up text-info me-2"></i><?php echo e(__('app.tasks.statistics')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-info mb-1"><?php echo e($stats['completed_tasks'] ?? 0); ?></h4>
                            <small class="text-muted"><?php echo e(__('app.tasks.completed')); ?></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-warning mb-1"><?php echo e($stats['pending_tasks'] ?? 0); ?></h4>
                            <small class="text-muted"><?php echo e(__('app.tasks.pending')); ?></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-primary mb-1"><?php echo e($stats['in_progress_tasks'] ?? 0); ?></h4>
                            <small class="text-muted"><?php echo e(__('app.tasks.in_progress')); ?></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-danger mb-1"><?php echo e($stats['overdue_tasks'] ?? 0); ?></h4>
                            <small class="text-muted"><?php echo e(__('app.tasks.overdue')); ?></small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-outline-info"><?php echo e(__('app.tasks.manage')); ?></a>
                    <a href="<?php echo e(route('reports.tasks')); ?>" class="btn btn-outline-primary"><?php echo e(__('app.reports_dashboard.detailed')); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Time Statistics Modal -->
<div class="modal fade" id="timeStatsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-graph-up text-warning me-2"></i><?php echo e(__('app.timesheet_dashboard.statistics')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-warning mb-1"><?php echo e(number_format($stats['total_time_logged'] ?? 0, 1)); ?>h</h4>
                            <small class="text-muted"><?php echo e(__('app.timesheet_dashboard.total_logged')); ?></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-success mb-1"><?php echo e(number_format($stats['time_this_week'] ?? 0, 1)); ?>h</h4>
                            <small class="text-muted"><?php echo e(__('app.timesheet_dashboard.this_week')); ?></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-info mb-1"><?php echo e(number_format($stats['avg_daily_hours'] ?? 0, 1)); ?>h</h4>
                            <small class="text-muted"><?php echo e(__('app.timesheet_dashboard.avg_daily')); ?></small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <a href="<?php echo e(route('timesheet.index')); ?>" class="btn btn-outline-warning"><?php echo e(__('app.timesheet_dashboard.manage')); ?></a>
                    <a href="<?php echo e(route('reports.time')); ?>" class="btn btn-outline-info"><?php echo e(__('app.reports_dashboard.detailed')); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Time Modal -->
<div class="modal fade" id="exportTimeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-download text-warning me-2"></i><?php echo e(__('app.timesheet_dashboard.export')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('timesheet.export')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="export_start_date" class="form-label"><?php echo e(__('app.date_range.start')); ?></label>
                            <input type="date" class="form-control" id="export_start_date" name="start_date" value="<?php echo e(now()->startOfMonth()->format('Y-m-d')); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="export_end_date" class="form-label"><?php echo e(__('app.date_range.end')); ?></label>
                            <input type="date" class="form-control" id="export_end_date" name="end_date" value="<?php echo e(now()->format('Y-m-d')); ?>">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="export_format" class="form-label"><?php echo e(__('app.export_options.format')); ?></label>
                        <select class="form-select" id="export_format" name="format">
                            <option value="xlsx">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-download me-1"></i><?php echo e(__('app.export')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .stats-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .progress {
        background-color: #f3f4f6;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>