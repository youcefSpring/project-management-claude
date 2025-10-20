<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['size' => 'md', 'text' => 'Loading...']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['size' => 'md', 'text' => 'Loading...']); ?>
<?php foreach (array_filter((['size' => 'md', 'text' => 'Loading...']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $sizeClasses = [
        'sm' => 'spinner-border-sm',
        'md' => '',
        'lg' => 'spinner-grow'
    ];

    $sizeClass = $sizeClasses[$size] ?? '';
?>

<div <?php echo e($attributes->merge(['class' => 'd-flex justify-content-center align-items-center'])); ?>>
    <div class="spinner-border <?php echo e($sizeClass); ?>" role="status" aria-hidden="true"></div>
    <?php if($text): ?>
        <span class="ms-2"><?php echo e($text); ?></span>
    <?php endif; ?>
</div><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/components/loading-spinner.blade.php ENDPATH**/ ?>