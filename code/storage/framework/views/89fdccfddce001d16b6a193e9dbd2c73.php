<?php $__env->startSection('title', 'Projects'); ?>
<?php $__env->startSection('page-title', 'Projects'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">Manage Projects</h5>
                <p class="text-muted mb-0">Showcase your research and academic projects</p>
            </div>
            <a href="<?php echo e(route('admin.projects.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>New Project
            </a>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('admin.projects.index')); ?>">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select name="tag" class="form-select">
                                <option value="">All Tags</option>
                                <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tag->id); ?>" <?php echo e(request('tag') == $tag->id ? 'selected' : ''); ?>>
                                        <?php echo e($tag->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                <option value="on-hold" <?php echo e(request('status') == 'on-hold' ? 'selected' : ''); ?>>On Hold</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-outline-primary me-2">Filter</button>
                            <a href="<?php echo e(route('admin.projects.index')); ?>" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if($projects->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>Tags</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if($project->featured_image): ?>
                                                <img src="<?php echo e(Storage::url($project->featured_image)); ?>"
                                                     alt="<?php echo e($project->title); ?>"
                                                     class="rounded me-3"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-1"><?php echo e($project->title); ?></h6>
                                                <small class="text-muted"><?php echo e(Str::limit($project->description, 60)); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($project->status === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php elseif($project->status === 'completed'): ?>
                                            <span class="badge bg-primary">Completed</span>
                                        <?php elseif($project->status === 'on-hold'): ?>
                                            <span class="badge bg-warning">On Hold</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo e(ucfirst($project->status)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($project->start_date): ?>
                                            <?php echo e($project->start_date->format('M d, Y')); ?>

                                        <?php else: ?>
                                            <span class="text-muted">Not set</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php $__currentLoopData = $project->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-light text-dark me-1"><?php echo e($tag->name); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('admin.projects.show', $project)); ?>"
                                               class="btn btn-sm btn-outline-info"
                                               title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.projects.edit', $project)); ?>"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="<?php echo e(route('admin.projects.destroy', $project)); ?>" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete"
                                                        data-confirm-delete>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($projects->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-code-slash text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 mb-2">No projects yet</h5>
                        <p class="text-muted mb-4">Start showcasing your research and academic projects</p>
                        <a href="<?php echo e(route('admin.projects.create')); ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-2"></i>Create Your First Project
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/admin/projects/index.blade.php ENDPATH**/ ?>