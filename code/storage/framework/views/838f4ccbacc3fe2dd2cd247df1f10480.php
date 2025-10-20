<?php $__env->startSection('page-title', 'View Project'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1"><?php echo e($project->title); ?></h1>
        <p class="text-muted mb-0">Project Details</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.projects.edit', $project)); ?>" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Edit Project
        </a>
        <a href="<?php echo e(route('admin.projects.index')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Projects
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Project Image -->
        <?php if($project->featured_image): ?>
            <div class="card mb-4">
                <div class="card-body p-0">
                    <img src="<?php echo e(Storage::url($project->featured_image)); ?>"
                         alt="<?php echo e($project->title); ?>"
                         class="img-fluid w-100"
                         style="max-height: 400px; object-fit: cover;">
                </div>
            </div>
        <?php endif; ?>

        <!-- Project Description -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-file-text text-primary me-2"></i>
                    Description
                </h5>
                <p class="card-text"><?php echo e($project->description); ?></p>
            </div>
        </div>

        <!-- Project Details -->
        <?php if($project->content): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-journal-text text-primary me-2"></i>
                        Project Details
                    </h5>
                    <div class="content">
                        <?php echo nl2br(e($project->content)); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Technologies -->
        <?php if($project->technologies): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-gear text-primary me-2"></i>
                        Technologies Used
                    </h5>
                    <div class="content">
                        <?php echo nl2br(e($project->technologies)); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Collaborators -->
        <?php if($project->collaborators): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-people text-primary me-2"></i>
                        Collaborators
                    </h5>
                    <div class="content">
                        <?php echo nl2br(e($project->collaborators)); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Key Outcomes -->
        <?php if($project->key_outcomes): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-trophy text-primary me-2"></i>
                        Key Outcomes
                    </h5>
                    <div class="content">
                        <?php echo nl2br(e($project->key_outcomes)); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-lightning text-primary me-2"></i>
                    Quick Actions
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    <?php if($project->project_url): ?>
                        <a href="<?php echo e($project->project_url); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-link-45deg me-1"></i>View Project
                        </a>
                    <?php endif; ?>
                    <?php if($project->repository_url): ?>
                        <a href="<?php echo e($project->repository_url); ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-github me-1"></i>View Repository
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('admin.projects.edit', $project)); ?>" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit Project
                    </a>
                    <form method="POST" action="<?php echo e(route('admin.projects.destroy', $project)); ?>" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm-delete>
                            <i class="bi bi-trash me-1"></i>Delete Project
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Project Info -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Project Information
                </h5>

                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <div>
                        <?php if($project->status === 'active'): ?>
                            <span class="badge bg-success fs-6">Active</span>
                        <?php elseif($project->status === 'completed'): ?>
                            <span class="badge bg-primary fs-6">Completed</span>
                        <?php elseif($project->status === 'on-hold'): ?>
                            <span class="badge bg-warning fs-6">On Hold</span>
                        <?php elseif($project->status === 'cancelled'): ?>
                            <span class="badge bg-danger fs-6">Cancelled</span>
                        <?php else: ?>
                            <span class="badge bg-secondary fs-6"><?php echo e(ucfirst($project->status)); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($project->type): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Project Type</label>
                        <div><?php echo e(ucfirst($project->type)); ?></div>
                    </div>
                <?php endif; ?>

                <?php if($project->start_date): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Start Date</label>
                        <div><?php echo e(is_string($project->start_date) ? \Carbon\Carbon::parse($project->start_date)->format('F d, Y') : $project->start_date->format('F d, Y')); ?></div>
                    </div>
                <?php endif; ?>

                <?php if($project->end_date): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">End Date</label>
                        <div><?php echo e(is_string($project->end_date) ? \Carbon\Carbon::parse($project->end_date)->format('F d, Y') : $project->end_date->format('F d, Y')); ?></div>
                    </div>
                <?php endif; ?>

                <?php if($project->funding_amount): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Funding Amount</label>
                        <div>$<?php echo e(number_format($project->funding_amount, 2)); ?></div>
                    </div>
                <?php endif; ?>

                <?php if($project->client_organization): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Client/Organization</label>
                        <div><?php echo e($project->client_organization); ?></div>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label fw-bold">Published</label>
                    <div>
                        <?php if($project->is_published): ?>
                            <span class="badge bg-success">Published</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Draft</span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($project->is_featured): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Featured</label>
                        <div>
                            <span class="badge bg-warning">Featured Project</span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label fw-bold">Created</label>
                    <div><?php echo e($project->created_at->format('F d, Y \a\t g:i A')); ?></div>
                </div>

                <div>
                    <label class="form-label fw-bold">Last Updated</label>
                    <div><?php echo e($project->updated_at->format('F d, Y \a\t g:i A')); ?></div>
                </div>
            </div>
        </div>

        <!-- Project Links -->
        <?php if($project->project_url || $project->repository_url): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-link text-primary me-2"></i>
                        Project Links
                    </h5>

                    <?php if($project->project_url): ?>
                        <div class="mb-2">
                            <a href="<?php echo e($project->project_url); ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-link-45deg me-1"></i>View Live Project
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if($project->repository_url): ?>
                        <div class="mb-2">
                            <a href="<?php echo e($project->repository_url); ?>" target="_blank" class="btn btn-outline-secondary btn-sm w-100">
                                <i class="bi bi-github me-1"></i>View Repository
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Tags -->
        <?php if($project->tags && $project->tags->count() > 0): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-tags text-primary me-2"></i>
                        Tags
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        <?php $__currentLoopData = $project->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge bg-light text-dark"><?php echo e($tag->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Statistics (if this is a public project) -->
        <?php if($project->is_published): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-bar-chart text-primary me-2"></i>
                        Public Visibility
                    </h5>
                    <div class="text-center">
                        <p class="text-muted mb-2">This project is visible to the public</p>
                        <a href="<?php echo e(url('/projects/' . Str::slug($project->title))); ?>" target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-eye me-1"></i>View Public Page
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .content {
        line-height: 1.6;
        color: #4a5568;
    }

    .content p {
        margin-bottom: 1rem;
    }

    .badge.fs-6 {
        font-size: 0.875rem !important;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/admin/projects/show.blade.php ENDPATH**/ ?>