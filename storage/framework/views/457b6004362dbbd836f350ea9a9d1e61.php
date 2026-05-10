<?php $__env->startSection('title', __('app.messages.item_not_found')); ?>
<?php $__env->startSection('page-title', __('app.messages.item_not_found')); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-6 text-center">
        <div class="card border-0">
            <div class="card-body py-5">
                <!-- Error Illustration -->
                <div class="mb-4">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 6rem;"></i>
                </div>

                <!-- Error Message -->
                <h1 class="display-4 fw-bold text-primary mb-3">404</h1>
                <h2 class="h4 mb-3"><?php echo e(__('app.messages.item_not_found')); ?></h2>
                <p class="text-muted mb-4">
                    <?php echo e(__('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.')); ?>

                </p>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="<?php echo e(route('dashboard',['locale' => app()->getLocale()])); ?>" class="btn btn-primary">
                        <i class="bi bi-house me-2"></i>
                        <?php echo e(__('app.Dashboard')); ?>

                    </a>

                    <button onclick="window.history.back()" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>
                        <?php echo e(__('app.back')); ?>

                    </button>

                    <a href="<?php echo e(route('projects.index',['locale' => app()->getLocale()])); ?>" class="btn btn-outline-info">
                        <i class="bi bi-folder me-2"></i>
                        <?php echo e(__('app.projects.title')); ?>

                    </a>
                </div>

                <!-- Search -->
                <div class="mt-4">
                    <form action="<?php echo e(route('search.results')); ?>" method="GET" class="d-flex justify-content-center">
                        <div class="input-group" style="max-width: 400px;">
                            <input type="text" name="q" class="form-control"
                                   placeholder="<?php echo e(__('app.Search...')); ?>">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Quick Links -->
                <div class="mt-5">
                    <h5 class="mb-3"><?php echo e(__('Quick Links')); ?></h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="<?php echo e(route('projects.index')); ?>" class="text-decoration-none">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <i class="bi bi-folder text-primary fs-2"></i>
                                        <h6 class="mt-2 mb-0"><?php echo e(__('app.nav.projects')); ?></h6>
                                        <small class="text-muted"><?php echo e(__('app.projects.title')); ?></small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?php echo e(route('tasks.index')); ?>" class="text-decoration-none">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <i class="bi bi-check2-square text-success fs-2"></i>
                                        <h6 class="mt-2 mb-0"><?php echo e(__('app.nav.tasks')); ?></h6>
                                        <small class="text-muted"><?php echo e(__('app.tasks.title')); ?></small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Auto-focus search input if no specific action was taken
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="q"]');
    if (searchInput) {
        setTimeout(() => searchInput.focus(), 500);
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/errors/404.blade.php ENDPATH**/ ?>