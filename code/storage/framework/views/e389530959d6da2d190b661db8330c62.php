<?php $__env->startSection('title', __('Create User')); ?>
<?php $__env->startSection('page-title', __('Add New User')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('User Information')); ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('users.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label for="name" class="form-label"><?php echo e(__('Full Name')); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="name" name="name" value="<?php echo e(old('name')); ?>" required autofocus>
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
                        <label for="email" class="form-label"><?php echo e(__('Email Address')); ?> <span class="text-danger">*</span></label>
                        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="email" name="email" value="<?php echo e(old('email')); ?>" required>
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
                        <label for="role" class="form-label"><?php echo e(__('Primary Role')); ?> <span class="text-danger">*</span></label>
                        <select class="form-select <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="role" name="role" required>
                            <option value=""><?php echo e(__('Select a primary role')); ?></option>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role); ?>" <?php echo e(old('role') === $role ? 'selected' : ''); ?>>
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
                        <div class="form-text"><?php echo e(__('This will be the user\'s primary role for permissions and display.')); ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><?php echo e(__('Additional Roles')); ?> <span class="text-muted">(<?php echo e(__('Optional')); ?>)</span></label>
                        <div class="row">
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($role !== 'admin'): ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input additional-role" type="checkbox"
                                                   name="additional_roles[]" value="<?php echo e($role); ?>"
                                                   id="additional_role_<?php echo e($role); ?>"
                                                   <?php echo e(in_array($role, old('additional_roles', [])) ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="additional_role_<?php echo e($role); ?>">
                                                <?php echo e($roleLabels[$role]); ?>

                                            </label>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__errorArgs = ['additional_roles'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text"><?php echo e(__('User can have multiple roles. For example, a developer can also be a manager.')); ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="language" class="form-label"><?php echo e(__('Language')); ?></label>
                        <select class="form-select <?php $__errorArgs = ['language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="language" name="language">
                            <option value="en" <?php echo e(old('language', 'en') === 'en' ? 'selected' : ''); ?>><?php echo e(__('English')); ?></option>
                            <option value="fr" <?php echo e(old('language') === 'fr' ? 'selected' : ''); ?>><?php echo e(__('French')); ?></option>
                            <option value="ar" <?php echo e(old('language') === 'ar' ? 'selected' : ''); ?>><?php echo e(__('Arabic')); ?></option>
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

                    <div class="mb-3">
                        <label for="password" class="form-label"><?php echo e(__('Password')); ?> <span class="text-danger">*</span></label>
                        <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="password" name="password" required>
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
                        <label for="password_confirmation" class="form-label"><?php echo e(__('Confirm Password')); ?> <span class="text-danger">*</span></label>
                        <input type="password" class="form-control"
                               id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            <?php echo e(__('Cancel')); ?>

                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>
                            <?php echo e(__('Create User')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Role Information')); ?></h5>
            </div>
            <div class="card-body">
                <div id="role-info">
                    <p class="text-muted"><?php echo e(__('Select a role to see permissions and description.')); ?></p>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Guidelines')); ?></h5>
            </div>
            <div class="card-body">
                <h6><?php echo e(__('Password Requirements')); ?></h6>
                <ul class="small text-muted">
                    <li><?php echo e(__('Minimum 8 characters')); ?></li>
                    <li><?php echo e(__('Mix of letters, numbers recommended')); ?></li>
                    <li><?php echo e(__('User will be able to change password later')); ?></li>
                </ul>

                <h6 class="mt-3"><?php echo e(__('User Management')); ?></h6>
                <ul class="small text-muted">
                    <li><?php echo e(__('Email must be unique')); ?></li>
                    <li><?php echo e(__('Role can be changed later')); ?></li>
                    <li><?php echo e(__('Users receive account creation notification')); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
const roleDescriptions = {
    'admin': {
        title: 'Administrator',
        description: 'Full system access. Can manage all users, projects, and system settings.',
        permissions: ['Manage all users', 'Manage all projects', 'View all reports', 'System configuration']
    },
    'manager': {
        title: 'Project Manager',
        description: 'Can manage projects and teams. Has access to reports and project oversight.',
        permissions: ['Create/manage projects', 'Assign tasks', 'View reports', 'Manage team members']
    },
    'developer': {
        title: 'Developer',
        description: 'Technical team member who works on development tasks.',
        permissions: ['Work on assigned tasks', 'Log time entries', 'Comment on tasks', 'View project details']
    },
    'designer': {
        title: 'Designer',
        description: 'Creative team member who works on design-related tasks.',
        permissions: ['Work on design tasks', 'Log time entries', 'Comment on tasks', 'View project details']
    },
    'tester': {
        title: 'QA Tester',
        description: 'Quality assurance team member who tests and validates work.',
        permissions: ['Work on testing tasks', 'Log time entries', 'Report bugs', 'View project details']
    },
    'hr': {
        title: 'Human Resources',
        description: 'HR team member with access to user management and reports.',
        permissions: ['View user reports', 'Access HR features', 'Limited project visibility']
    },
    'accountant': {
        title: 'Accountant',
        description: 'Financial team member with access to time tracking and billing reports.',
        permissions: ['View time reports', 'Access billing information', 'Financial reports']
    },
    'client': {
        title: 'Client',
        description: 'External client with limited access to view project progress.',
        permissions: ['View assigned projects', 'View progress reports', 'Limited task visibility']
    },
    'member': {
        title: 'Member',
        description: 'General team member with basic access.',
        permissions: ['Work on assigned tasks', 'Log time entries', 'Basic project access']
    }
};

// Update role information display
function updateRoleInfo() {
    const selectedRole = document.getElementById('role').value;
    const infoDiv = document.getElementById('role-info');

    if (selectedRole && roleDescriptions[selectedRole]) {
        const role = roleDescriptions[selectedRole];
        const permissionsList = role.permissions.map(p => `<li>${p}</li>`).join('');

        infoDiv.innerHTML = `
            <h6 class="text-primary">${role.title} (Primary)</h6>
            <p class="small">${role.description}</p>
            <h6 class="mt-3"><?php echo e(__('Primary Role Permissions')); ?></h6>
            <ul class="small text-muted">
                ${permissionsList}
            </ul>
        `;
    } else {
        infoDiv.innerHTML = '<p class="text-muted"><?php echo e(__("Select a primary role to see permissions and description.")); ?></p>';
    }
}

// Prevent selecting primary role as additional role
function updateAdditionalRoles() {
    const primaryRole = document.getElementById('role').value;
    const additionalCheckboxes = document.querySelectorAll('.additional-role');

    additionalCheckboxes.forEach(checkbox => {
        if (checkbox.value === primaryRole) {
            checkbox.checked = false;
            checkbox.disabled = true;
            checkbox.parentElement.classList.add('text-muted');
        } else {
            checkbox.disabled = false;
            checkbox.parentElement.classList.remove('text-muted');
        }
    });
}

document.getElementById('role').addEventListener('change', function() {
    updateRoleInfo();
    updateAdditionalRoles();
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateRoleInfo();
    updateAdditionalRoles();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/users/create.blade.php ENDPATH**/ ?>