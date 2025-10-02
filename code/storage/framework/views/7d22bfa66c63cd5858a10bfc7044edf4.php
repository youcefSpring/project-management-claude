<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['title' => null, 'icon' => null, 'footer' => null]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['title' => null, 'icon' => null, 'footer' => null]); ?>
<?php foreach (array_filter((['title' => null, 'icon' => null, 'footer' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div <?php echo e($attributes->merge(['class' => 'card'])); ?>>
    <?php if($title || $icon): ?>
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <?php if($icon): ?>
                    <i class="bi <?php echo e($icon); ?> text-primary me-2"></i>
                <?php endif; ?>
                <?php echo e($title); ?>

            </h5>
        </div>
    <?php endif; ?>

    <div class="card-body">
        <?php echo e($slot); ?>

    </div>

    <?php if($footer): ?>
        <div class="card-footer bg-light">
            <?php echo e($footer); ?>

        </div>
    <?php endif; ?>
</div><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/components/card.blade.php ENDPATH**/ ?>