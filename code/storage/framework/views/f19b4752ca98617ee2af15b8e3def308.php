<!-- Search Bar Component -->
<div class="search-bar">
    <form method="GET" action="<?php echo e($action ?? request()->url()); ?>" class="d-flex">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text"
                   class="form-control border-start-0 ps-0"
                   name="search"
                   placeholder="<?php echo e($placeholder ?? 'Search...'); ?>"
                   value="<?php echo e(request('search')); ?>"
                   aria-label="Search">

            <?php if(isset($filters) && count($filters) > 0): ?>
                <?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <input type="hidden" name="<?php echo e($filter['name']); ?>" value="<?php echo e(request($filter['name'])); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search me-1"></i>Search
            </button>

            <?php if(request()->hasAny(['search', 'filter', 'sort'])): ?>
                <a href="<?php echo e($clearUrl ?? request()->url()); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i>Clear
                </a>
            <?php endif; ?>
        </div>
    </form>

    <?php if(request('search')): ?>
        <div class="mt-2">
            <small class="text-muted">
                <?php if(isset($resultsCount)): ?>
                    <?php echo e($resultsCount); ?> result<?php echo e($resultsCount !== 1 ? 's' : ''); ?> found for
                <?php endif; ?>
                "<strong><?php echo e(request('search')); ?></strong>"
            </small>
        </div>
    <?php endif; ?>
</div><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/partials/search-bar.blade.php ENDPATH**/ ?>