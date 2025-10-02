<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['type' => 'info', 'dismissible' => true, 'icon' => true]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['type' => 'info', 'dismissible' => true, 'icon' => true]); ?>
<?php foreach (array_filter((['type' => 'info', 'dismissible' => true, 'icon' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $alertClasses = [
        'success' => 'alert-success',
        'error' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info'
    ];

    $alertIcons = [
        'success' => 'bi-check-circle',
        'error' => 'bi-exclamation-triangle',
        'warning' => 'bi-exclamation-triangle',
        'info' => 'bi-info-circle'
    ];

    $alertClass = $alertClasses[$type] ?? 'alert-info';
    $alertIcon = $alertIcons[$type] ?? 'bi-info-circle';
?>

<div <?php echo e($attributes->merge(['class' => 'alert ' . $alertClass . ($dismissible ? ' alert-dismissible fade show' : '')])); ?> role="alert">
    <?php if($icon): ?>
        <i class="bi <?php echo e($alertIcon); ?> me-2"></i>
    <?php endif; ?>

    <?php echo e($slot); ?>


    <?php if($dismissible): ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <?php endif; ?>
</div><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/components/alert.blade.php ENDPATH**/ ?>