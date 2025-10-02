<?php $__env->startSection('title', $blogPost->title); ?>
<?php $__env->startSection('page-title', 'View Blog Post'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1"><?php echo e($blogPost->title); ?></h5>
                <p class="text-muted mb-0">
                    <?php if($blogPost->is_published): ?>
                        <span class="badge bg-success me-2">Published</span>
                    <?php else: ?>
                        <span class="badge bg-warning me-2">Draft</span>
                    <?php endif; ?>
                    <?php if($blogPost->published_at): ?>
                        <?php echo e($blogPost->published_at->format('M d, Y \a\t g:i A')); ?>

                    <?php else: ?>
                        Created <?php echo e($blogPost->created_at->format('M d, Y \a\t g:i A')); ?>

                    <?php endif; ?>
                </p>
            </div>
            <div class="btn-group">
                <a href="<?php echo e(route('admin.blog.edit', $blogPost)); ?>" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Post
                </a>
                <a href="<?php echo e(route('admin.blog.index')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Posts
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <?php if($blogPost->featured_image): ?>
                        <div class="card-header p-0">
                            <img src="<?php echo e(Storage::url($blogPost->featured_image)); ?>"
                                 alt="<?php echo e($blogPost->title); ?>"
                                 class="img-fluid w-100"
                                 style="max-height: 400px; object-fit: cover;">
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <?php if($blogPost->excerpt): ?>
                            <div class="alert alert-light border-start border-primary border-4 mb-4">
                                <h6 class="mb-2">Excerpt:</h6>
                                <p class="mb-0 fst-italic"><?php echo e($blogPost->excerpt); ?></p>
                            </div>
                        <?php endif; ?>

                        <div class="content">
                            <?php echo nl2br(e($blogPost->content)); ?>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Post Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <div>
                                <?php if($blogPost->is_published): ?>
                                    <span class="badge bg-success">Published</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Draft</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Slug</label>
                            <div class="text-muted"><?php echo e($blogPost->slug); ?></div>
                        </div>

                        <?php if($blogPost->published_at): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Published Date</label>
                                <div><?php echo e($blogPost->published_at->format('F j, Y \a\t g:i A')); ?></div>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Created</label>
                            <div><?php echo e($blogPost->created_at->format('F j, Y \a\t g:i A')); ?></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Last Updated</label>
                            <div><?php echo e($blogPost->updated_at->format('F j, Y \a\t g:i A')); ?></div>
                        </div>

                        <?php if($blogPost->is_featured): ?>
                            <div class="mb-3">
                                <span class="badge bg-primary">
                                    <i class="bi bi-star me-1"></i>Featured Post
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($blogPost->tags->count() > 0): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Tags</h6>
                        </div>
                        <div class="card-body">
                            <?php $__currentLoopData = $blogPost->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-light text-dark me-2 mb-2"><?php echo e($tag->name); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?php if($blogPost->is_published): ?>
                                <a href="<?php echo e(route('public.blog.show', $blogPost->slug)); ?>"
                                   class="btn btn-outline-primary" target="_blank">
                                    <i class="bi bi-eye me-2"></i>View on Site
                                </a>
                            <?php endif; ?>

                            <a href="<?php echo e(route('admin.blog.edit', $blogPost)); ?>"
                               class="btn btn-primary">
                                <i class="bi bi-pencil me-2"></i>Edit Post
                            </a>

                            <form method="POST" action="<?php echo e(route('admin.blog.destroy', $blogPost)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit"
                                        class="btn btn-outline-danger w-100"
                                        data-confirm-delete>
                                    <i class="bi bi-trash me-2"></i>Delete Post
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/admin/blog/show.blade.php ENDPATH**/ ?>