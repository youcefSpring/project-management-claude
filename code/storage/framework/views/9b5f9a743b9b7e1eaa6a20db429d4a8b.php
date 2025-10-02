<?php $__env->startSection('title', 'Tags'); ?>
<?php $__env->startSection('page-title', 'Tags'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">Manage Tags</h5>
                <p class="text-muted mb-0">Organize your content with tags and categories</p>
            </div>
            <a href="<?php echo e(route('admin.tags.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>New Tag
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Total Tags</h6>
                            <div class="stats-number"><?php echo e($tags->total()); ?></div>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-tags" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Most Used</h6>
                            <div class="text-sm">
                                <?php if(isset($mostUsedTag)): ?>
                                    <strong><?php echo e($mostUsedTag->name); ?></strong><br>
                                    <small class="text-muted"><?php echo e($mostUsedTag->usage_count); ?> items</small>
                                <?php else: ?>
                                    <small class="text-muted">No usage data</small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Blog Tags</h6>
                            <div class="stats-number"><?php echo e($blogTagsCount ?? 0); ?></div>
                        </div>
                        <div class="text-info">
                            <i class="bi bi-pencil-square" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Project Tags</h6>
                            <div class="stats-number"><?php echo e($projectTagsCount ?? 0); ?></div>
                        </div>
                        <div class="text-warning">
                            <i class="bi bi-code-slash" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('admin.tags.index')); ?>">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search tags..." value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="sort" class="form-select">
                                <option value="name" <?php echo e(request('sort') == 'name' ? 'selected' : ''); ?>>Sort by Name</option>
                                <option value="created_at" <?php echo e(request('sort') == 'created_at' ? 'selected' : ''); ?>>Sort by Date</option>
                                <option value="usage" <?php echo e(request('sort') == 'usage' ? 'selected' : ''); ?>>Sort by Usage</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="order" class="form-select">
                                <option value="asc" <?php echo e(request('order') == 'asc' ? 'selected' : ''); ?>>Ascending</option>
                                <option value="desc" <?php echo e(request('order') == 'desc' ? 'selected' : ''); ?>>Descending</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-outline-primary me-2">Search</button>
                            <a href="<?php echo e(route('admin.tags.index')); ?>" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if($tags->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Description</th>
                                    <th>Usage Count</th>
                                    <th>Created</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle me-2"
                                                 style="width: 20px; height: 20px; background-color: <?php echo e($tag->color ?? '#6c757d'); ?>;"></div>
                                            <div>
                                                <h6 class="mb-0"><?php echo e($tag->name); ?></h6>
                                                <?php if($tag->color): ?>
                                                    <small class="text-muted"><?php echo e($tag->color); ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="text-muted"><?php echo e($tag->slug); ?></code>
                                    </td>
                                    <td>
                                        <?php if($tag->description): ?>
                                            <?php echo e(Str::limit($tag->description, 50)); ?>

                                        <?php else: ?>
                                            <span class="text-muted">No description</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <?php echo e($tag->posts_count + $tag->projects_count + $tag->courses_count ?? 0); ?> items
                                        </span>
                                    </td>
                                    <td>
                                        <small><?php echo e($tag->created_at->format('M d, Y')); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('admin.tags.edit', $tag)); ?>"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="<?php echo e(route('admin.tags.destroy', $tag)); ?>" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete"
                                                        data-confirm-delete
                                                        <?php echo e(($tag->posts_count + $tag->projects_count + $tag->courses_count ?? 0) > 0 ? 'data-has-items=true' : ''); ?>>
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

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing <?php echo e($tags->firstItem()); ?> to <?php echo e($tags->lastItem()); ?> of <?php echo e($tags->total()); ?> results
                        </div>
                        <div>
                            <?php echo e($tags->links()); ?>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-tags text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 mb-2">No tags yet</h5>
                        <p class="text-muted mb-4">Start organizing your content with tags</p>
                        <a href="<?php echo e(route('admin.tags.create')); ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-2"></i>Create Your First Tag
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bulk Actions (if there are tags) -->
        <?php if($tags->count() > 0): ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-gear text-primary me-2"></i>
                        Bulk Actions
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="<?php echo e(route('admin.tags.index', ['sort' => 'usage', 'order' => 'desc'])); ?>"
                               class="btn btn-outline-info btn-sm w-100">
                                <i class="bi bi-sort-numeric-down me-1"></i>Show Most Used Tags
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="<?php echo e(route('admin.tags.index', ['unused' => 'true'])); ?>"
                               class="btn btn-outline-warning btn-sm w-100">
                                <i class="bi bi-exclamation-triangle me-1"></i>Show Unused Tags
                            </a>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="bulkDeleteUnused()">
                                <i class="bi bi-trash me-1"></i>Delete Unused Tags
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced delete confirmation for tags with items
        const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                const hasItems = this.dataset.hasItems === 'true';

                let message = 'Are you sure you want to delete this tag?';
                if (hasItems) {
                    message = 'This tag is currently being used by posts, projects, or courses. Deleting it will remove the tag from all associated content. Are you sure you want to continue?';
                }

                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });
    });

    function bulkDeleteUnused() {
        if (confirm('Are you sure you want to delete all unused tags? This action cannot be undone.')) {
            // Create a form dynamically to handle bulk delete
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo e(route("admin.tags.bulk-delete-unused")); ?>';

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '<?php echo e(csrf_token()); ?>';
            form.appendChild(csrfToken);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .text-sm {
        font-size: 0.875rem;
    }

    .stats-card .stats-number {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--admin-primary);
    }

    code {
        background-color: #f8f9fa;
        padding: 0.2rem 0.4rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/admin/tags/index.blade.php ENDPATH**/ ?>