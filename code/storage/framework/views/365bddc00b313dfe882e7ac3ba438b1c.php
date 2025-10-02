<?php $__env->startSection('page-title', 'Courses Management'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header Actions -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Courses</h1>
        <p class="text-muted mb-0">Manage your course offerings and content</p>
    </div>
    <a href="<?php echo e(route('admin.courses.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Add New Course
    </a>
</div>

<!-- Filters & Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.courses.index')); ?>" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text"
                       class="form-control"
                       id="search"
                       name="search"
                       placeholder="Search by title or description..."
                       value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="upcoming" <?php echo e(request('status') == 'upcoming' ? 'selected' : ''); ?>>Upcoming</option>
                    <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                    <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>Draft</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="level" class="form-label">Level</label>
                <select class="form-select" id="level" name="level">
                    <option value="">All Levels</option>
                    <option value="beginner" <?php echo e(request('level') == 'beginner' ? 'selected' : ''); ?>>Beginner</option>
                    <option value="intermediate" <?php echo e(request('level') == 'intermediate' ? 'selected' : ''); ?>>Intermediate</option>
                    <option value="advanced" <?php echo e(request('level') == 'advanced' ? 'selected' : ''); ?>>Advanced</option>
                    <option value="graduate" <?php echo e(request('level') == 'graduate' ? 'selected' : ''); ?>>Graduate</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i>
                </button>
                <a href="<?php echo e(route('admin.courses.index')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Courses Table -->
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="bi bi-book me-2"></i>
                Courses (<?php echo e($courses->total()); ?>)
            </h5>
            <?php if(request()->hasAny(['search', 'status', 'level'])): ?>
                <small class="text-muted">Filtered results</small>
            <?php endif; ?>
        </div>
    </div>

    <?php if($courses->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Level</th>
                        <th>Duration</th>
                        <th>Credits</th>
                        <th>Last Updated</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="<?php echo e(route('admin.courses.show', $course)); ?>" class="text-decoration-none">
                                                <?php echo e($course->title); ?>

                                            </a>
                                        </h6>
                                        <?php if($course->description): ?>
                                            <p class="text-muted small mb-0"><?php echo e(Str::limit($course->description, 80)); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if($course->status === 'active'): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php elseif($course->status === 'upcoming'): ?>
                                    <span class="badge bg-warning">Upcoming</span>
                                <?php elseif($course->status === 'completed'): ?>
                                    <span class="badge bg-secondary">Completed</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-dark">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($course->level): ?>
                                    <span class="badge bg-info"><?php echo e(ucfirst($course->level)); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($course->start_date && $course->end_date): ?>
                                    <small class="text-muted">
                                        <?php echo e($course->start_date->format('M j')); ?> - <?php echo e($course->end_date->format('M j, Y')); ?>

                                    </small>
                                <?php elseif($course->start_date): ?>
                                    <small class="text-muted">Starts <?php echo e($course->start_date->format('M j, Y')); ?></small>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo e($course->credits ?? '-'); ?>

                            </td>
                            <td>
                                <small class="text-muted"><?php echo e($course->updated_at->diffForHumans()); ?></small>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="<?php echo e(route('admin.courses.show', $course)); ?>">
                                                <i class="bi bi-eye me-2"></i>View
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="<?php echo e(route('admin.courses.edit', $course)); ?>">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="<?php echo e(route('courses.show', $course->slug)); ?>" target="_blank">
                                                <i class="bi bi-box-arrow-up-right me-2"></i>View on Site
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="<?php echo e(route('admin.courses.destroy', $course)); ?>" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="dropdown-item text-danger" data-confirm-delete>
                                                    <i class="bi bi-trash me-2"></i>Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($courses->hasPages()): ?>
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Showing <?php echo e($courses->firstItem()); ?> to <?php echo e($courses->lastItem()); ?> of <?php echo e($courses->total()); ?> results
                    </small>
                    <?php echo e($courses->appends(request()->query())->links()); ?>

                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="card-body text-center py-5">
            <i class="bi bi-book text-muted mb-3" style="font-size: 3rem;"></i>
            <?php if(request()->hasAny(['search', 'status', 'level'])): ?>
                <h5 class="text-muted">No courses found</h5>
                <p class="text-muted mb-3">No courses match your current filters.</p>
                <a href="<?php echo e(route('admin.courses.index')); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i>Clear Filters
                </a>
            <?php else: ?>
                <h5 class="text-muted">No courses yet</h5>
                <p class="text-muted mb-3">Create your first course to get started.</p>
                <a href="<?php echo e(route('admin.courses.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Add First Course
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #374151;
    }

    .table td {
        vertical-align: middle;
    }

    .dropdown-toggle::after {
        display: none;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit search form on select changes
        const statusSelect = document.getElementById('status');
        const levelSelect = document.getElementById('level');

        [statusSelect, levelSelect].forEach(function(select) {
            if (select) {
                select.addEventListener('change', function() {
                    this.form.submit();
                });
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/admin/courses/index.blade.php ENDPATH**/ ?>