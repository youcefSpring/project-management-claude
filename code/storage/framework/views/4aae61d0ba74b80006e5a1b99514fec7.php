<?php $__env->startSection('title', 'Publications'); ?>
<?php $__env->startSection('page-title', 'Publications'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">Manage Publications</h5>
                <p class="text-muted mb-0">Showcase your research papers, articles, and academic publications</p>
            </div>
            <a href="<?php echo e(route('admin.publications.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>New Publication
            </a>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('admin.publications.index')); ?>">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="journal" <?php echo e(request('type') == 'journal' ? 'selected' : ''); ?>>Journal Article</option>
                                <option value="conference" <?php echo e(request('type') == 'conference' ? 'selected' : ''); ?>>Conference Paper</option>
                                <option value="book" <?php echo e(request('type') == 'book' ? 'selected' : ''); ?>>Book</option>
                                <option value="book_chapter" <?php echo e(request('type') == 'book_chapter' ? 'selected' : ''); ?>>Book Chapter</option>
                                <option value="thesis" <?php echo e(request('type') == 'thesis' ? 'selected' : ''); ?>>Thesis</option>
                                <option value="report" <?php echo e(request('type') == 'report' ? 'selected' : ''); ?>>Report</option>
                                <option value="preprint" <?php echo e(request('type') == 'preprint' ? 'selected' : ''); ?>>Preprint</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="published" <?php echo e(request('status') == 'published' ? 'selected' : ''); ?>>Published</option>
                                <option value="accepted" <?php echo e(request('status') == 'accepted' ? 'selected' : ''); ?>>Accepted</option>
                                <option value="under_review" <?php echo e(request('status') == 'under_review' ? 'selected' : ''); ?>>Under Review</option>
                                <option value="in_preparation" <?php echo e(request('status') == 'in_preparation' ? 'selected' : ''); ?>>In Preparation</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="year" class="form-control" placeholder="Publication Year" value="<?php echo e(request('year')); ?>">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-outline-primary me-2">Filter</button>
                            <a href="<?php echo e(route('admin.publications.index')); ?>" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if($publications->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Publication Date</th>
                                    <th>Journal/Venue</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $publications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $publication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-1"><?php echo e($publication->title); ?></h6>
                                            <small class="text-muted">
                                                <?php if($publication->authors): ?>
                                                    <?php echo e(Str::limit($publication->authors, 80)); ?>

                                                <?php else: ?>
                                                    No authors specified
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <?php echo e(ucwords(str_replace('_', ' ', $publication->type))); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($publication->status === 'published'): ?>
                                            <span class="badge bg-success">Published</span>
                                        <?php elseif($publication->status === 'accepted'): ?>
                                            <span class="badge bg-info">Accepted</span>
                                        <?php elseif($publication->status === 'under_review'): ?>
                                            <span class="badge bg-warning">Under Review</span>
                                        <?php elseif($publication->status === 'in_preparation'): ?>
                                            <span class="badge bg-secondary">In Preparation</span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark"><?php echo e(ucfirst($publication->status)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($publication->publication_date): ?>
                                            <?php echo e($publication->publication_date->format('M Y')); ?>

                                        <?php else: ?>
                                            <span class="text-muted">Not set</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($publication->journal_name || $publication->venue): ?>
                                            <?php echo e($publication->journal_name ?: $publication->venue); ?>

                                        <?php else: ?>
                                            <span class="text-muted">Not specified</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('admin.publications.show', $publication)); ?>"
                                               class="btn btn-sm btn-outline-info"
                                               title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.publications.edit', $publication)); ?>"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="<?php echo e(route('admin.publications.destroy', $publication)); ?>" class="d-inline">
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
                        <?php echo e($publications->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-journal-text text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 mb-2">No publications yet</h5>
                        <p class="text-muted mb-4">Start showcasing your research papers and academic publications</p>
                        <a href="<?php echo e(route('admin.publications.create')); ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-2"></i>Add Your First Publication
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/admin/publications/index.blade.php ENDPATH**/ ?>