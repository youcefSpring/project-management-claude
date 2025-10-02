<?php if(isset($breadcrumbs) && count($breadcrumbs) > 0): ?>
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('home')); ?>" class="text-decoration-none">
                    <i class="bi bi-house me-1"></i>Home
                </a>
            </li>
            <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($loop->last): ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php echo e($breadcrumb['title']); ?>

                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item">
                        <a href="<?php echo e($breadcrumb['url']); ?>" class="text-decoration-none">
                            <?php if(isset($breadcrumb['icon'])): ?>
                                <i class="bi bi-<?php echo e($breadcrumb['icon']); ?> me-1"></i>
                            <?php endif; ?>
                            <?php echo e($breadcrumb['title']); ?>

                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ol>
    </div>
</nav>
<?php endif; ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/partials/breadcrumbs.blade.php ENDPATH**/ ?>