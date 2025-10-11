<?php $__env->startSection('title', __('app.users.title')); ?>
<?php $__env->startSection('page-title', __('app.User Management')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Header Actions -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1"><?php echo e(__('app.users.title')); ?></h2>
                <p class="text-muted mb-0"><?php echo e(__('app.users.manage_and_track')); ?></p>
            </div>
            <div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\User::class)): ?>
                <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-person-plus me-2"></i>
                    <?php echo e(__('app.users.create')); ?>

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
                        <label for="role" class="form-label"><?php echo e(__('app.users.role')); ?></label>
                        <select class="form-select" id="role" name="role">
                            <option value=""><?php echo e(__('app.users.all_roles')); ?></option>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role); ?>" <?php echo e(request('role') === $role ? 'selected' : ''); ?>>
                                    <?php echo e($roleLabels[$role]); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="search" class="form-label"><?php echo e(__('app.search')); ?></label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('app.users.search_placeholder')); ?>">
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="bi bi-search me-2"></i>
                            <?php echo e(__('app.filter')); ?>

                        </button>
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>
                            <?php echo e(__('app.clear_filters')); ?>

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
                    <?php echo e(__('app.users.title')); ?>

                    <span class="badge bg-secondary ms-2"><?php echo e($users->total()); ?></span>
                </h5>
            </div>
            <div class="card-body">
                <?php if($users->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('app.user_label')); ?></th>
                                    <th><?php echo e(__('app.users.role')); ?></th>
                                    <th><?php echo e(__('app.email')); ?></th>
                                    <th><?php echo e(__('app.Projects')); ?></th>
                                    <th><?php echo e(__('app.Tasks')); ?></th>
                                    <th><?php echo e(__('app.users.joined')); ?></th>
                                    <th width="200"><?php echo e(__('app.actions')); ?></th>
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
                                                        <small class="text-primary"><?php echo e(__('app.users.admin')); ?></small>
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
                        <h5 class="text-muted"><?php echo e(__('app.users.no_users')); ?></h5>
                        <p class="text-muted">
                            <?php if(request()->hasAny(['role', 'search'])): ?>
                                <?php echo e(__('app.try_adjusting_filters')); ?>

                                <a href="<?php echo e(route('users.index')); ?>"><?php echo e(__('app.clear_filters')); ?></a>
                            <?php else: ?>
                                <?php echo e(__('app.users.get_started_message')); ?>

                            <?php endif; ?>
                        </p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\User::class)): ?>
                            <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">
                                <i class="bi bi-person-plus me-2"></i>
                                <?php echo e(__('app.users.add_first')); ?>

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
                <h5 class="modal-title"><?php echo e(__('app.users.delete')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(__('app.messages.confirm_delete')); ?></p>
                <p class="text-danger">
                    <strong><?php echo e(__('app.warning')); ?></strong>
                    <?php echo e(__('app.messages.action_cannot_be_undone')); ?>

                </p>
                <p><strong><?php echo e(__('app.user_label')); ?>:</strong> <span id="deleteUserName"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger"><?php echo e(__('app.users.delete')); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Role Confirmation Modal -->
<div class="modal fade" id="roleConfirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('app.users.confirm_role_change')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="roleConfirmMessage"><?php echo e(__('app.users.confirm_role_change')); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                <button type="button" class="btn btn-primary" id="confirmRoleBtn">
                    <span class="btn-text"><?php echo e(__('app.confirm')); ?></span>
                    <span class="spinner-border spinner-border-sm ms-2 d-none"></span>
                </button>
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
let currentUserId = null;
let currentNewRole = null;

function updateUserRole(userId, newRole) {
    currentUserId = userId;
    currentNewRole = newRole;

    document.getElementById('roleConfirmMessage').textContent = '<?php echo e(__("app.users.confirm_role_change")); ?>';

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('roleConfirmModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('confirmRoleBtn').addEventListener('click', function() {
        if (!currentUserId || !currentNewRole) return;

        const btn = this;
        const btnText = btn.querySelector('.btn-text');
        const spinner = btn.querySelector('.spinner-border');

        // Show loading state
        btn.disabled = true;
        btnText.textContent = '<?php echo e(__('app.processing')); ?>';
        spinner.classList.remove('d-none');

        fetch(`/users/${currentUserId}/role`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                role: currentNewRole
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide modal
                bootstrap.Modal.getInstance(document.getElementById('roleConfirmModal')).hide();

                // Reload page
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                throw new Error(data.message || '<?php echo e(__("app.users.error_updating_role")); ?>');
            }
        })
        .catch(error => {
            console.error('Error:', error);

            // Show error in modal
            document.getElementById('roleConfirmMessage').innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    ${error.message || '<?php echo e(__("app.users.error_updating_role")); ?>'}
                </div>
            `;
        })
        .finally(() => {
            // Reset button state
            btn.disabled = false;
            btnText.textContent = '<?php echo e(__('app.confirm')); ?>';
            spinner.classList.add('d-none');
        });
    });
});

function deleteUser(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteForm').action = `/users/${userId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/users/index.blade.php ENDPATH**/ ?>