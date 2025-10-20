<?php $__env->startSection('title', __('Search Results')); ?>
<?php $__env->startSection('page-title', __('Search Results')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <!-- Search Header -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-1"><?php echo e(__('Search Results')); ?></h4>
                        <p class="text-muted mb-0">
                            <?php if(request('q')): ?>
                                <?php echo e(__('Results for')); ?>: "<strong><?php echo e(request('q')); ?></strong>"
                                <?php if(isset($totalResults)): ?>
                                    - <?php echo e($totalResults); ?> <?php echo e(__('results found')); ?>

                                <?php endif; ?>
                            <?php else: ?>
                                <?php echo e(__('Enter a search term to find projects, tasks, and users')); ?>

                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <form method="GET" action="<?php echo e(route('search.results')); ?>">
                            <div class="input-group">
                                <input type="text" class="form-control" name="q" value="<?php echo e(request('q')); ?>"
                                       placeholder="<?php echo e(__('Search...')); ?>" autofocus>
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if(request('q')): ?>
            <!-- Search Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="type-filter" class="form-label"><?php echo e(__('Type')); ?></label>
                            <select class="form-select" id="type-filter" name="type">
                                <option value=""><?php echo e(__('All Types')); ?></option>
                                <option value="projects" <?php echo e(request('type') === 'projects' ? 'selected' : ''); ?>>
                                    <?php echo e(__('Projects')); ?>

                                </option>
                                <option value="tasks" <?php echo e(request('type') === 'tasks' ? 'selected' : ''); ?>>
                                    <?php echo e(__('Tasks')); ?>

                                </option>
                                <option value="users" <?php echo e(request('type') === 'users' ? 'selected' : ''); ?>>
                                    <?php echo e(__('Users')); ?>

                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status-filter" class="form-label"><?php echo e(__('Status')); ?></label>
                            <select class="form-select" id="status-filter" name="status">
                                <option value=""><?php echo e(__('All Statuses')); ?></option>
                                <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>
                                    <?php echo e(__('Active')); ?>

                                </option>
                                <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>
                                    <?php echo e(__('Completed')); ?>

                                </option>
                                <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>
                                    <?php echo e(__('Pending')); ?>

                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="sort-filter" class="form-label"><?php echo e(__('Sort By')); ?></label>
                            <select class="form-select" id="sort-filter" name="sort">
                                <option value="relevance" <?php echo e(request('sort') === 'relevance' ? 'selected' : ''); ?>>
                                    <?php echo e(__('Relevance')); ?>

                                </option>
                                <option value="date" <?php echo e(request('sort') === 'date' ? 'selected' : ''); ?>>
                                    <?php echo e(__('Date')); ?>

                                </option>
                                <option value="name" <?php echo e(request('sort') === 'name' ? 'selected' : ''); ?>>
                                    <?php echo e(__('Name')); ?>

                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-primary me-2" onclick="applyFilters()">
                                <?php echo e(__('Apply')); ?>

                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                <?php echo e(__('Clear')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Results -->
            <?php if(isset($projects) && $projects->count() > 0): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-folder me-2"></i>
                            <?php echo e(__('Projects')); ?>

                            <span class="badge bg-primary ms-2"><?php echo e($projects->count()); ?></span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td style="width: 60px;">
                                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-folder-fill"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <a href="<?php echo e(route('projects.show', $project)); ?>" class="fw-bold text-decoration-none">
                                                        <?php echo e($project->title); ?>

                                                    </a>
                                                    <?php if($project->description): ?>
                                                        <br>
                                                        <small class="text-muted"><?php echo e(Str::limit($project->description, 100)); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td style="width: 120px;">
                                                <span class="badge bg-<?php echo e($project->status === 'active' ? 'success' : ($project->status === 'completed' ? 'primary' : 'warning')); ?>">
                                                    <?php echo e(ucfirst($project->status)); ?>

                                                </span>
                                            </td>
                                            <td style="width: 150px;">
                                                <small class="text-muted">
                                                    <?php if($project->start_date): ?>
                                                        <?php echo e(\Carbon\Carbon::parse($project->start_date)->format('M d, Y')); ?>

                                                    <?php else: ?>
                                                        <?php echo e(__('No start date')); ?>

                                                    <?php endif; ?>
                                                </small>
                                            </td>
                                            <td style="width: 100px;">
                                                <a href="<?php echo e(route('projects.show', $project)); ?>" class="btn btn-sm btn-outline-primary">
                                                    <?php echo e(__('View')); ?>

                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(isset($tasks) && $tasks->count() > 0): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-check-square me-2"></i>
                            <?php echo e(__('Tasks')); ?>

                            <span class="badge bg-primary ms-2"><?php echo e($tasks->count()); ?></span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td style="width: 60px;">
                                                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center text-white"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-check-square-fill"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <a href="<?php echo e(route('tasks.show', $task)); ?>" class="fw-bold text-decoration-none">
                                                        <?php echo e($task->title); ?>

                                                    </a>
                                                    <?php if($task->description): ?>
                                                        <br>
                                                        <small class="text-muted"><?php echo e(Str::limit($task->description, 100)); ?></small>
                                                    <?php endif; ?>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="bi bi-folder me-1"></i><?php echo e($task->project->title); ?>

                                                    </small>
                                                </div>
                                            </td>
                                            <td style="width: 120px;">
                                                <?php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'in_progress' => 'primary',
                                                        'completed' => 'success',
                                                        'cancelled' => 'secondary'
                                                    ];
                                                    $color = $statusColors[$task->status] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo e($color); ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $task->status))); ?>

                                                </span>
                                            </td>
                                            <td style="width: 150px;">
                                                <?php if($task->due_date): ?>
                                                    <small class="text-muted">
                                                        <?php echo e(\Carbon\Carbon::parse($task->due_date)->format('M d, Y')); ?>

                                                    </small>
                                                <?php else: ?>
                                                    <small class="text-muted"><?php echo e(__('No due date')); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td style="width: 100px;">
                                                <a href="<?php echo e(route('tasks.show', $task)); ?>" class="btn btn-sm btn-outline-primary">
                                                    <?php echo e(__('View')); ?>

                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(isset($users) && $users->count() > 0): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-people me-2"></i>
                            <?php echo e(__('Users')); ?>

                            <span class="badge bg-primary ms-2"><?php echo e($users->count()); ?></span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td style="width: 60px;">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="fw-bold"><?php echo e($user->name); ?></span>
                                                    <br>
                                                    <small class="text-muted"><?php echo e($user->email); ?></small>
                                                </div>
                                            </td>
                                            <td style="width: 120px;">
                                                <span class="badge bg-info">
                                                    <?php echo e(ucfirst($user->role ?? 'User')); ?>

                                                </span>
                                            </td>
                                            <td style="width: 150px;">
                                                <small class="text-muted">
                                                    <?php echo e(__('Joined')); ?> <?php echo e($user->created_at->format('M Y')); ?>

                                                </small>
                                            </td>
                                            <td style="width: 100px;">
                                                <?php if(auth()->user()->isAdmin()): ?>
                                                    <a href="<?php echo e(route('users.show', $user)); ?>" class="btn btn-sm btn-outline-primary">
                                                        <?php echo e(__('View')); ?>

                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if((!isset($projects) || $projects->count() === 0) &&
                (!isset($tasks) || $tasks->count() === 0) &&
                (!isset($users) || $users->count() === 0)): ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-search fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted"><?php echo e(__('No results found')); ?></h5>
                        <p class="text-muted mb-4">
                            <?php echo e(__('Try adjusting your search terms or filters to find what you\'re looking for.')); ?>

                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item border-0 py-1">
                                        <small class="text-muted">• <?php echo e(__('Check your spelling')); ?></small>
                                    </div>
                                    <div class="list-group-item border-0 py-1">
                                        <small class="text-muted">• <?php echo e(__('Try more general terms')); ?></small>
                                    </div>
                                    <div class="list-group-item border-0 py-1">
                                        <small class="text-muted">• <?php echo e(__('Use different keywords')); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- No Search Query -->
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-search fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted"><?php echo e(__('Start searching')); ?></h5>
                    <p class="text-muted">
                        <?php echo e(__('Enter a search term above to find projects, tasks, and users.')); ?>

                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function applyFilters() {
    const params = new URLSearchParams(window.location.search);

    const type = document.getElementById('type-filter').value;
    const status = document.getElementById('status-filter').value;
    const sort = document.getElementById('sort-filter').value;

    if (type) params.set('type', type);
    else params.delete('type');

    if (status) params.set('status', status);
    else params.delete('status');

    if (sort) params.set('sort', sort);
    else params.delete('sort');

    window.location.search = params.toString();
}

function clearFilters() {
    const params = new URLSearchParams(window.location.search);

    params.delete('type');
    params.delete('status');
    params.delete('sort');

    window.location.search = params.toString();
}

// Auto-apply filters on change
document.addEventListener('DOMContentLoaded', function() {
    const filters = ['type-filter', 'status-filter', 'sort-filter'];

    filters.forEach(filterId => {
        const element = document.getElementById(filterId);
        if (element) {
            element.addEventListener('change', applyFilters);
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/search/results.blade.php ENDPATH**/ ?>