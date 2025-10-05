<?php $__env->startSection('title', __('Users')); ?>
<?php $__env->startSection('page-title', __('User Management')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Header Actions -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1"><?php echo e(__('Users')); ?></h2>
                <p class="text-muted mb-0"><?php echo e(__('Manage users and their roles')); ?></p>
            </div>
            <div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\User::class)): ?>
                <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-person-plus me-2"></i>
                    <?php echo e(__('Add New User')); ?>

                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('users.index')); ?>" class="row g-3">
                    <div class="col-md-4">
                        <label for="role" class="form-label"><?php echo e(__('Role')); ?></label>
                        <select class="form-select" id="role" name="role">
                            <option value=""><?php echo e(__('All Roles')); ?></option>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role); ?>" <?php echo e(request('role') === $role ? 'selected' : ''); ?>>
                                    <?php echo e($roleLabels[$role]); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="search" class="form-label"><?php echo e(__('Search')); ?></label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('Search users...')); ?>">
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="bi bi-search me-2"></i>
                            <?php echo e(__('Filter')); ?>

                        </button>
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>
                            <?php echo e(__('Clear')); ?>

                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Users List -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <?php echo e(__('Users')); ?>

                    <span class="badge bg-secondary ms-2"><?php echo e($users->total()); ?></span>
                </h5>
            </div>
            <div class="card-body">
                <?php if($users->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('User')); ?></th>
                                    <th><?php echo e(__('Role')); ?></th>
                                    <th><?php echo e(__('Email')); ?></th>
                                    <th><?php echo e(__('Projects')); ?></th>
                                    <th><?php echo e(__('Tasks')); ?></th>
                                    <th><?php echo e(__('Joined')); ?></th>
                                    <th width="200"><?php echo e(__('Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3">
                                                    <?php echo e(substr($user->name, 0, 2)); ?>

                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?php echo e($user->name); ?></h6>
                                                    <?php if($user->isAdmin()): ?>
                                                        <small class="text-primary"><?php echo e(__('Administrator')); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown">
                                                    <span class="role-badge badge bg-<?php echo e($user->isAdmin() ? 'danger' : ($user->isManager() ? 'warning' : 'primary')); ?>">
                                                        <?php echo e($user->getRoleLabel()); ?>

                                                    </span>
                                                </button>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $user)): ?>
                                                <ul class="dropdown-menu">
                                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($role !== $user->role): ?>
                                                            <li>
                                                                <a class="dropdown-item" href="#"
                                                                   onclick="updateUserRole(<?php echo e($user->id); ?>, '<?php echo e($role); ?>')">
                                                                    <?php echo e($roleLabels[$role]); ?>

                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td>
                                            <?php if($user->isManager()): ?>
                                                <span class="badge bg-info"><?php echo e($user->managedProjects->count()); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($user->canWorkOnTasks()): ?>
                                                <span class="badge bg-success"><?php echo e($user->assignedTasks->count()); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?php echo e($user->created_at->format('M d, Y')); ?></small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('users.show', $user)); ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $user)): ?>
                                                    <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $user)): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="deleteUser(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($users->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-people fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted"><?php echo e(__('No users found')); ?></h5>
                        <p class="text-muted">
                            <?php if(request()->hasAny(['role', 'search'])): ?>
                                <?php echo e(__('Try adjusting your filters or')); ?>

                                <a href="<?php echo e(route('users.index')); ?>"><?php echo e(__('clear all filters')); ?></a>
                            <?php else: ?>
                                <?php echo e(__('Get started by adding your first user.')); ?>

                            <?php endif; ?>
                        </p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\User::class)): ?>
                            <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">
                                <i class="bi bi-person-plus me-2"></i>
                                <?php echo e(__('Add First User')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('Delete User')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(__('Are you sure you want to delete this user?')); ?></p>
                <p class="text-danger">
                    <strong><?php echo e(__('Warning:')); ?></strong>
                    <?php echo e(__('This action cannot be undone.')); ?>

                </p>
                <p><strong><?php echo e(__('User:')); ?></strong> <span id="deleteUserName"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger"><?php echo e(__('Delete User')); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    text-transform: uppercase;
}
</style>

<script>
function updateUserRole(userId, newRole) {
    if (confirm('<?php echo e(__("Are you sure you want to change this user\'s role?")); ?>')) {
        fetch(`/users/${userId}/role`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                role: newRole
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || '<?php echo e(__("Error updating user role")); ?>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?php echo e(__("Error updating user role")); ?>');
        });
    }
}

function deleteUser(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteForm').action = `/users/${userId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/users/index.blade.php ENDPATH**/ ?>