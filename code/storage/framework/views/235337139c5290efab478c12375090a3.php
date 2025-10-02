<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="stats-number mb-0"><?php echo e($stats['courses'] ?? 0); ?></h3>
                    <p class="text-muted mb-0">Courses</p>
                </div>
                <div class="text-primary">
                    <i class="bi bi-book" style="font-size: 2.5rem;"></i>
                </div>
            </div>
            <div class="mt-2">
                <a href="<?php echo e(route('admin.courses.index')); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye me-1"></i>View All
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="stats-number mb-0"><?php echo e($stats['projects'] ?? 0); ?></h3>
                    <p class="text-muted mb-0">Projects</p>
                </div>
                <div class="text-success">
                    <i class="bi bi-code-slash" style="font-size: 2.5rem;"></i>
                </div>
            </div>
            <div class="mt-2">
                <a href="<?php echo e(route('admin.projects.index')); ?>" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-eye me-1"></i>View All
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="stats-number mb-0"><?php echo e($stats['publications'] ?? 0); ?></h3>
                    <p class="text-muted mb-0">Publications</p>
                </div>
                <div class="text-info">
                    <i class="bi bi-journal-text" style="font-size: 2.5rem;"></i>
                </div>
            </div>
            <div class="mt-2">
                <a href="<?php echo e(route('admin.publications.index')); ?>" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-eye me-1"></i>View All
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="stats-number mb-0"><?php echo e($stats['blog_posts'] ?? 0); ?></h3>
                    <p class="text-muted mb-0">Blog Posts</p>
                </div>
                <div class="text-warning">
                    <i class="bi bi-pencil-square" style="font-size: 2.5rem;"></i>
                </div>
            </div>
            <div class="mt-2">
                <a href="<?php echo e(route('admin.blog.index')); ?>" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-eye me-1"></i>View All
                </a>
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
                        <a href="<?php echo e(route('admin.courses.create')); ?>" class="btn btn-outline-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>
                            Add Course
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo e(route('admin.projects.create')); ?>" class="btn btn-outline-success w-100">
                            <i class="bi bi-plus-circle me-2"></i>
                            Add Project
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo e(route('admin.blog.create')); ?>" class="btn btn-outline-warning w-100">
                            <i class="bi bi-plus-circle me-2"></i>
                            Write Post
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo e(route('admin.publications.create')); ?>" class="btn btn-outline-info w-100">
                            <i class="bi bi-plus-circle me-2"></i>
                            Add Publication
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo e(route('admin.tags.create')); ?>" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-plus-circle me-2"></i>
                            Add Tag
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo e(route('admin.profile.edit')); ?>" class="btn btn-outline-dark w-100">
                            <i class="bi bi-person-gear me-2"></i>
                            Edit Profile
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
                    <span>Unread Messages</span>
                    <span class="badge bg-primary"><?php echo e($stats['unread_messages'] ?? 0); ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Total Messages</span>
                    <span class="badge bg-secondary"><?php echo e($stats['total_messages'] ?? 0); ?></span>
                </div>
                <div class="d-grid">
                    <a href="<?php echo e(route('admin.contact.index')); ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-envelope-open me-1"></i>
                        View Messages
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
                    Recent Activity
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
                        <h6 class="text-muted">No recent activity</h6>
                        <p class="text-muted mb-0">Your recent actions will appear here</p>
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
                        <span class="small">Content Published</span>
                        <span class="small fw-bold"><?php echo e(($stats['courses'] + $stats['projects'] + $stats['blog_posts'] + $stats['publications']) ?? 0); ?></span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: 85%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Profile Completeness</span>
                        <span class="small fw-bold"><?php echo e($stats['profile_completion'] ?? 70); ?>%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: <?php echo e($stats['profile_completion'] ?? 70); ?>%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Response Rate</span>
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
                    <span class="small">Application</span>
                    <span class="badge bg-success">Online</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small">Database</span>
                    <span class="badge bg-success">Connected</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small">File Storage</span>
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>