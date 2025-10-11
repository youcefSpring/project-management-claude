<?php $__env->startSection('title', __('app.users.edit')); ?>
<?php $__env->startSection('page-title', __('app.users.edit_user_name', ['name' => $user->name])); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('app.users.user_information')); ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('users.update', $user)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="mb-3">
                        <label for="name" class="form-label"><?php echo e(__('app.full_name')); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>" required autofocus>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label"><?php echo e(__('app.email_address')); ?> <span class="text-danger">*</span></label>
                        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label"><?php echo e(__('app.users.role')); ?> <span class="text-danger">*</span></label>
                        <select class="form-select <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="role" name="role" required>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role); ?>" <?php echo e(old('role', $user->role) === $role ? 'selected' : ''); ?>>
                                    <?php echo e($roleLabels[$role]); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php if($user->isAdmin() && \App\Models\User::where('role', 'admin')->count() <= 1): ?>
                            <div class="form-text text-warning">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                <?php echo e(__('app.users.last_admin_warning')); ?>

                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="language" class="form-label"><?php echo e(__('app.profile.language')); ?></label>
                        <select class="form-select <?php $__errorArgs = ['language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="language" name="language">
                            <option value="en" <?php echo e(old('language', $user->language) === 'en' ? 'selected' : ''); ?>><?php echo e(__('app.users.language_english')); ?></option>
                            <option value="fr" <?php echo e(old('language', $user->language) === 'fr' ? 'selected' : ''); ?>><?php echo e(__('app.users.language_french')); ?></option>
                            <option value="ar" <?php echo e(old('language', $user->language) === 'ar' ? 'selected' : ''); ?>><?php echo e(__('app.users.language_arabic')); ?></option>
                        </select>
                        <?php $__errorArgs = ['language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <hr>
                    <h6><?php echo e(__('app.profile.change_password')); ?> <small class="text-muted">(<?php echo e(__('app.users.password_leave_blank')); ?>)</small></h6>

                    <div class="mb-3">
                        <label for="password" class="form-label"><?php echo e(__('app.profile.new_password')); ?></label>
                        <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="password" name="password">
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label"><?php echo e(__('app.users.confirm_new_password')); ?></label>
                        <input type="password" class="form-control"
                               id="password_confirmation" name="password_confirmation">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('users.show', $user)); ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            <?php echo e(__('app.cancel')); ?>

                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            <?php echo e(__('app.users.update')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('app.users.user_actions')); ?></h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('users.show', $user)); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>
                        <?php echo e(__('app.users.view_profile')); ?>

                    </a>

                    <?php if($user->canWorkOnTasks()): ?>
                        <a href="<?php echo e(route('tasks.index', ['assigned_to' => $user->id])); ?>" class="btn btn-outline-success">
                            <i class="bi bi-list-task me-2"></i>
                            <?php echo e(__('app.users.view_tasks')); ?>

                        </a>
                    <?php endif; ?>

                    <?php if($user->isManager()): ?>
                        <a href="<?php echo e(route('projects.index', ['manager_id' => $user->id])); ?>" class="btn btn-outline-info">
                            <i class="bi bi-folder me-2"></i>
                            <?php echo e(__('app.users.view_projects')); ?>

                        </a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $user)): ?>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i>
                            <?php echo e(__('app.users.delete')); ?>

                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('app.users.current_role')); ?></h5>
            </div>
            <div class="card-body">
                <div id="current-role-info">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('app.users.user_statistics')); ?></h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong><?php echo e(__('app.users.member_since')); ?>:</strong>
                    <span class="float-end"><?php echo e($user->created_at->format('M d, Y')); ?></span>
                </div>
                <?php if($user->isManager()): ?>
                    <div class="mb-2">
                        <strong><?php echo e(__('app.users.projects_managed')); ?>:</strong>
                        <span class="float-end"><?php echo e($user->managedProjects->count()); ?></span>
                    </div>
                <?php endif; ?>
                <?php if($user->canWorkOnTasks()): ?>
                    <div class="mb-2">
                        <strong><?php echo e(__('app.users.tasks_assigned')); ?>:</strong>
                        <span class="float-end"><?php echo e($user->assignedTasks->count()); ?></span>
                    </div>
                    <div class="mb-2">
                        <strong><?php echo e(__('app.users.time_logged')); ?>:</strong>
                        <span class="float-end"><?php echo e(number_format($user->timeEntries->sum('duration_hours') ?? 0, 1)); ?>h</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $user)): ?>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('app.users.delete')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(__('app.users.confirm_delete')); ?></p>
                <p class="text-danger">
                    <strong><?php echo e(__('app.warning')); ?>:</strong>
                    <?php echo e(__('app.users.delete_warning')); ?>

                </p>
                <p><strong><?php echo e(__('app.user_label')); ?>:</strong> <?php echo e($user->name); ?></p>
                <?php if($user->managedProjects->count() > 0 || $user->assignedTasks->count() > 0 || $user->timeEntries->count() > 0): ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?php echo e(__('app.users.delete_has_data')); ?>

                        <ul class="mb-0 mt-2">
                            <?php if($user->managedProjects->count() > 0): ?>
                                <li><?php echo e(__('app.users.count_managed_projects', ['count' => $user->managedProjects->count()])); ?></li>
                            <?php endif; ?>
                            <?php if($user->assignedTasks->count() > 0): ?>
                                <li><?php echo e(__('app.users.count_assigned_tasks', ['count' => $user->assignedTasks->count()])); ?></li>
                            <?php endif; ?>
                            <?php if($user->timeEntries->count() > 0): ?>
                                <li><?php echo e(__('app.users.count_time_entries', ['count' => $user->timeEntries->count()])); ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                <form method="POST" action="<?php echo e(route('users.destroy', $user)); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger"><?php echo e(__('app.users.delete')); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
const roleDescriptions = {
    'admin': {
        title: '<?php echo e(__("app.users.admin")); ?>',
        description: '<?php echo e(__("app.users.admin_desc")); ?>',
        permissions: [
            '<?php echo e(__("app.users.admin_perm1")); ?>',
            '<?php echo e(__("app.users.admin_perm2")); ?>',
            '<?php echo e(__("app.users.admin_perm3")); ?>',
            '<?php echo e(__("app.users.admin_perm4")); ?>'
        ]
    },
    'manager': {
        title: '<?php echo e(__("app.users.manager")); ?>',
        description: '<?php echo e(__("app.users.manager_desc")); ?>',
        permissions: [
            '<?php echo e(__("app.users.manager_perm1")); ?>',
            '<?php echo e(__("app.users.manager_perm2")); ?>',
            '<?php echo e(__("app.users.manager_perm3")); ?>',
            '<?php echo e(__("app.users.manager_perm4")); ?>'
        ]
    },
    'developer': {
        title: '<?php echo e(__("app.users.developer")); ?>',
        description: '<?php echo e(__("app.users.developer_desc")); ?>',
        permissions: [
            '<?php echo e(__("app.users.developer_perm1")); ?>',
            '<?php echo e(__("app.users.developer_perm2")); ?>',
            '<?php echo e(__("app.users.developer_perm3")); ?>',
            '<?php echo e(__("app.users.developer_perm4")); ?>'
        ]
    },
    'designer': {
        title: '<?php echo e(__("app.users.designer")); ?>',
        description: '<?php echo e(__("app.users.designer_desc")); ?>',
        permissions: [
            '<?php echo e(__("app.users.designer_perm1")); ?>',
            '<?php echo e(__("app.users.designer_perm2")); ?>',
            '<?php echo e(__("app.users.designer_perm3")); ?>',
            '<?php echo e(__("app.users.designer_perm4")); ?>'
        ]
    },
    'tester': {
        title: '<?php echo e(__("app.users.tester")); ?>',
        description: '<?php echo e(__("app.users.tester_desc")); ?>',
        permissions: [
            '<?php echo e(__("app.users.tester_perm1")); ?>',
            '<?php echo e(__("app.users.tester_perm2")); ?>',
            '<?php echo e(__("app.users.tester_perm3")); ?>',
            '<?php echo e(__("app.users.tester_perm4")); ?>'
        ]
    },
    'hr': {
        title: '<?php echo e(__("app.users.hr")); ?>',
        description: '<?php echo e(__("app.users.hr_desc")); ?>',
        permissions: [
            '<?php echo e(__("app.users.hr_perm1")); ?>',
            '<?php echo e(__("app.users.hr_perm2")); ?>',
            '<?php echo e(__("app.users.hr_perm3")); ?>'
        ]
    },
    'accountant': {
        title: '<?php echo e(__("app.users.accountant")); ?>',
        description: '<?php echo e(__("app.users.accountant_desc")); ?>',
        permissions: [
            '<?php echo e(__("app.users.accountant_perm1")); ?>',
            '<?php echo e(__("app.users.accountant_perm2")); ?>',
            '<?php echo e(__("app.users.accountant_perm3")); ?>'
        ]
    },
    'client': {
        title: '<?php echo e(__("app.users.client")); ?>',
        description: '<?php echo e(__("app.users.client_desc")); ?>',
        permissions: [
            '<?php echo e(__("app.users.client_perm1")); ?>',
            '<?php echo e(__("app.users.client_perm2")); ?>',
            '<?php echo e(__("app.users.client_perm3")); ?>'
        ]
    },
    'member': {
        title: '<?php echo e(__("app.users.member")); ?>',
        description: '<?php echo e(__("app.users.member_desc")); ?>',
        permissions: [
            '<?php echo e(__("app.users.member_perm1")); ?>',
            '<?php echo e(__("app.users.member_perm2")); ?>',
            '<?php echo e(__("app.users.member_perm3")); ?>'
        ]
    }
};

function updateRoleInfo() {
    const selectedRole = document.getElementById('role').value;
    const infoDiv = document.getElementById('current-role-info');

    if (selectedRole && roleDescriptions[selectedRole]) {
        const role = roleDescriptions[selectedRole];
        const permissionsList = role.permissions.map(p => `<li>${p}</li>`).join('');

        infoDiv.innerHTML = `
            <h6 class="text-primary">${role.title}</h6>
            <p class="small">${role.description}</p>
            <h6 class="mt-3"><?php echo e(__('app.users.permissions')); ?></h6>
            <ul class="small text-muted">
                ${permissionsList}
            </ul>
        `;
    }
}

document.getElementById('role').addEventListener('change', updateRoleInfo);

// Initialize on page load
document.addEventListener('DOMContentLoaded', updateRoleInfo);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/users/edit.blade.php ENDPATH**/ ?>