<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['task', 'showProject' => true]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['task', 'showProject' => true]); ?>
<?php foreach (array_filter((['task', 'showProject' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="card mb-3 task-card hover-shadow" data-task-id="<?php echo e($task->id); ?>">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="card-title mb-0">
                <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-decoration-none">
                    <?php echo e($task->title); ?>

                </a>
            </h6>

            <div class="dropdown">
                <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('tasks.show', $task)); ?>">
                            <i class="bi bi-eye me-2"></i><?php echo e(__('app.View all')); ?>

                        </a>
                    </li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('tasks.edit', $task)); ?>">
                            <i class="bi bi-pencil me-2"></i><?php echo e(__('app.edit')); ?>

                        </a>
                    </li>
                    <?php endif; ?>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="quickStatusChange(<?php echo e($task->id); ?>)">
                            <i class="bi bi-arrow-repeat me-2"></i><?php echo e(__('app.status')); ?>

                        </a>
                    </li>
                    <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('timesheet.create')); ?>?task_id=<?php echo e($task->id); ?>">
                            <i class="bi bi-clock me-2"></i><?php echo e(__('app.time.log_time')); ?>

                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <?php if($task->description): ?>
        <p class="card-text text-muted small mb-2 text-truncate-2">
            <?php echo e($task->description); ?>

        </p>
        <?php endif; ?>

        <div class="mb-2">
            <span class="badge status-<?php echo e($task->status); ?> status-badge">
                <?php echo e(ucfirst(str_replace('_', ' ', $task->status))); ?>

            </span>

            <?php if($task->priority): ?>
            <span class="badge bg-<?php echo e($task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'secondary')); ?> ms-1">
                <?php echo e(ucfirst($task->priority)); ?>

            </span>
            <?php endif; ?>
        </div>

        <?php if($showProject && $task->project): ?>
        <div class="mb-2">
            <small class="text-muted">
                <i class="bi bi-folder me-1"></i>
                <a href="<?php echo e(route('projects.show', $task->project)); ?>" class="text-decoration-none">
                    <?php echo e($task->project->title); ?>

                </a>
            </small>
        </div>
        <?php endif; ?>

        <?php if($task->assignedUser): ?>
        <div class="d-flex align-items-center mb-2">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                 style="width: 24px; height: 24px;">
                <i class="bi bi-person-fill" style="font-size: 0.7rem;"></i>
            </div>
            <small><?php echo e($task->assignedUser->name); ?></small>
        </div>
        <?php endif; ?>

        <?php if($task->due_date): ?>
        <?php
            $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
        ?>
        <div class="d-flex align-items-center">
            <i class="bi bi-calendar me-1 <?php echo e($dueDate->isPast() && $task->status !== 'fait' ? 'text-danger' : 'text-muted'); ?>"></i>
            <small class="<?php echo e($dueDate->isPast() && $task->status !== 'fait' ? 'text-danger fw-bold' : 'text-muted'); ?>">
                <?php echo e($dueDate->format('M d, Y')); ?>

            </small>
            <?php if($dueDate->isPast() && $task->status !== 'fait'): ?>
                <i class="bi bi-exclamation-triangle text-danger ms-1" title="<?php echo e(__('app.tasks.overdue')); ?>"></i>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Task Progress/Stats -->
        <?php if($task->timeEntries->count() > 0): ?>
        <div class="mt-2 pt-2 border-top">
            <small class="text-muted">
                <i class="bi bi-clock me-1"></i>
                <?php echo e($task->timeEntries->sum('duration_hours')); ?>h <?php echo e(__('logged')); ?>

            </small>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/components/task-card.blade.php ENDPATH**/ ?>