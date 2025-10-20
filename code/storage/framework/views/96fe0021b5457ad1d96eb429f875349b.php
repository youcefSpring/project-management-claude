<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'name',
    'label' => null,
    'type' => 'text',
    'required' => false,
    'help' => null,
    'icon' => null
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'name',
    'label' => null,
    'type' => 'text',
    'required' => false,
    'help' => null,
    'icon' => null
]); ?>
<?php foreach (array_filter(([
    'name',
    'label' => null,
    'type' => 'text',
    'required' => false,
    'help' => null,
    'icon' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="mb-3">
    <?php if($label): ?>
        <label for="<?php echo e($name); ?>" class="form-label">
            <?php echo e($label); ?>

            <?php if($required): ?>
                <span class="text-danger">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>

    <?php if($icon): ?>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi <?php echo e($icon); ?>"></i>
            </span>
            <input
                type="<?php echo e($type); ?>"
                name="<?php echo e($name); ?>"
                id="<?php echo e($name); ?>"
                <?php echo e($attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')])); ?>

                value="<?php echo e(old($name, $attributes->get('value'))); ?>"
                <?php echo e($required ? 'required' : ''); ?>

            >
        </div>
    <?php else: ?>
        <input
            type="<?php echo e($type); ?>"
            name="<?php echo e($name); ?>"
            id="<?php echo e($name); ?>"
            <?php echo e($attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')])); ?>

            value="<?php echo e(old($name, $attributes->get('value'))); ?>"
            <?php echo e($required ? 'required' : ''); ?>

        >
    <?php endif; ?>

    <?php if($errors->has($name)): ?>
        <div class="invalid-feedback">
            <?php echo e($errors->first($name)); ?>

        </div>
    <?php endif; ?>

    <?php if($help): ?>
        <div class="form-text"><?php echo e($help); ?></div>
    <?php endif; ?>
</div><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/components/form/input.blade.php ENDPATH**/ ?>