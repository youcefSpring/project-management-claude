<?php $__env->startSection('title', 'Blog Posts'); ?>
<?php $__env->startSection('page-title', 'Blog Posts'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">Manage Blog Posts</h5>
                <p class="text-muted mb-0">Create and manage your blog posts</p>
            </div>
            <a href="<?php echo e(route('admin.blog.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>New Post
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if($posts->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Published Date</th>
                                    <th>Tags</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if($post->featured_image): ?>
                                                <img src="<?php echo e(Storage::url($post->featured_image)); ?>"
                                                     alt="<?php echo e($post->title); ?>"
                                                     class="rounded me-3"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-1"><?php echo e($post->title); ?></h6>
                                                <small class="text-muted"><?php echo e(Str::limit($post->excerpt, 60)); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($post->is_published): ?>
                                            <span class="badge bg-success">Published</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Draft</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($post->published_at): ?>
                                            <?php echo e($post->published_at->format('M d, Y')); ?>

                                        <?php else: ?>
                                            <span class="text-muted">Not published</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php $__currentLoopData = $post->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-light text-dark me-1"><?php echo e($tag->name); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('admin.blog.show', $post)); ?>"
                                               class="btn btn-sm btn-outline-info"
                                               title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.blog.edit', $post)); ?>"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="<?php echo e(route('admin.blog.destroy', $post)); ?>" class="d-inline">
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
                        <?php echo e($posts->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-pencil-square text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 mb-2">No blog posts yet</h5>
                        <p class="text-muted mb-4">Start creating engaging content for your audience</p>
                        <a href="<?php echo e(route('admin.blog.create')); ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-2"></i>Create Your First Post
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/admin/blog/index.blade.php ENDPATH**/ ?>