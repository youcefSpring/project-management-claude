<!-- Filter Sidebar Component -->
<div class="filter-sidebar">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">
                <i class="bi bi-funnel me-2"></i>Filters
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e($action ?? request()->url()); ?>" id="filterForm">
                <!-- Preserve search query -->
                <?php if(request('search')): ?>
                    <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
                <?php endif; ?>

                <?php if(isset($filters)): ?>
                    <?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?php echo e($filter['label']); ?></label>

                            <?php if($filter['type'] === 'select'): ?>
                                <select name="<?php echo e($filter['name']); ?>" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">All <?php echo e($filter['label']); ?></option>
                                    <?php $__currentLoopData = $filter['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value); ?>" <?php echo e(request($filter['name']) == $value ? 'selected' : ''); ?>>
                                            <?php echo e($label); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>

                            <?php elseif($filter['type'] === 'checkbox'): ?>
                                <?php $__currentLoopData = $filter['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="<?php echo e($filter['name']); ?>[]" value="<?php echo e($value); ?>"
                                               id="<?php echo e($filter['name']); ?>_<?php echo e($value); ?>"
                                               <?php echo e(in_array($value, (array) request($filter['name'], [])) ? 'checked' : ''); ?>

                                               onchange="document.getElementById('filterForm').submit()">
                                        <label class="form-check-label" for="<?php echo e($filter['name']); ?>_<?php echo e($value); ?>">
                                            <?php echo e($label); ?>

                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php elseif($filter['type'] === 'range'): ?>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label small">From</label>
                                        <input type="number" name="<?php echo e($filter['name']); ?>_min"
                                               class="form-control form-control-sm"
                                               value="<?php echo e(request($filter['name'] . '_min')); ?>"
                                               placeholder="Min"
                                               onchange="document.getElementById('filterForm').submit()">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small">To</label>
                                        <input type="number" name="<?php echo e($filter['name']); ?>_max"
                                               class="form-control form-control-sm"
                                               value="<?php echo e(request($filter['name'] . '_max')); ?>"
                                               placeholder="Max"
                                               onchange="document.getElementById('filterForm').submit()">
                                    </div>
                                </div>

                            <?php elseif($filter['type'] === 'date_range'): ?>
                                <div class="mb-2">
                                    <label class="form-label small">From</label>
                                    <input type="date" name="<?php echo e($filter['name']); ?>_start"
                                           class="form-control form-control-sm"
                                           value="<?php echo e(request($filter['name'] . '_start')); ?>"
                                           onchange="document.getElementById('filterForm').submit()">
                                </div>
                                <div>
                                    <label class="form-label small">To</label>
                                    <input type="date" name="<?php echo e($filter['name']); ?>_end"
                                           class="form-control form-control-sm"
                                           value="<?php echo e(request($filter['name'] . '_end')); ?>"
                                           onchange="document.getElementById('filterForm').submit()">
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if(!$loop->last): ?>
                            <hr>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

                <!-- Sort Options -->
                <?php if(isset($sortOptions)): ?>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sort By</label>
                        <select name="sort" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                            <?php $__currentLoopData = $sortOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php echo e(request('sort') == $value ? 'selected' : ''); ?>>
                                    <?php echo e($label); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Order</label>
                        <select name="direction" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                            <option value="desc" <?php echo e(request('direction') == 'desc' ? 'selected' : ''); ?>>Descending</option>
                            <option value="asc" <?php echo e(request('direction') == 'asc' ? 'selected' : ''); ?>>Ascending</option>
                        </select>
                    </div>
                <?php endif; ?>

                <!-- Clear Filters -->
                <?php if(request()->hasAny(['search']) ||
                    (isset($filters) && collect($filters)->pluck('name')->some(fn($name) => request()->has($name))) ||
                    request()->has(['sort', 'direction'])): ?>
                    <hr>
                    <div class="d-grid">
                        <a href="<?php echo e($clearUrl ?? request()->url()); ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle me-1"></i>Clear All Filters
                        </a>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Active Filters Display -->
    <?php
        $activeFilters = [];
        if(request('search')) $activeFilters[] = ['label' => 'Search', 'value' => request('search')];
        if(isset($filters)) {
            foreach($filters as $filter) {
                if(request($filter['name'])) {
                    $value = request($filter['name']);
                    if(is_array($value)) {
                        foreach($value as $v) {
                            $activeFilters[] = ['label' => $filter['label'], 'value' => $v];
                        }
                    } else {
                        $activeFilters[] = ['label' => $filter['label'], 'value' => $value];
                    }
                }
            }
        }
    ?>

    <?php if(count($activeFilters) > 0): ?>
        <div class="mt-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3">Active Filters</h6>
                    <?php $__currentLoopData = $activeFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge bg-primary me-1 mb-1">
                            <?php echo e($filter['label']); ?>: <?php echo e($filter['value']); ?>

                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/partials/filter-sidebar.blade.php ENDPATH**/ ?>