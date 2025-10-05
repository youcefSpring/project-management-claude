<?php $__env->startSection('title', __('Edit User')); ?>
<?php $__env->startSection('page-title', __('Edit User: :name', ['name' => $user->name])); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('User Information')); ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('users.update', $user)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

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
                        <label for="email" class="form-label"><?php echo e(__('Email Address')); ?> <span class="text-danger">*</span></label>
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
                        <label for="role" class="form-label"><?php echo e(__('Role')); ?> <span class="text-danger">*</span></label>
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
                                <?php echo e(__('This is the last administrator. Role change may restrict system access.')); ?>

                            </div>
                        <?php endif; ?>
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
                            <option value="en" <?php echo e(old('language', $user->language) === 'en' ? 'selected' : ''); ?>><?php echo e(__('English')); ?></option>
                            <option value="fr" <?php echo e(old('language', $user->language) === 'fr' ? 'selected' : ''); ?>><?php echo e(__('French')); ?></option>
                            <option value="ar" <?php echo e(old('language', $user->language) === 'ar' ? 'selected' : ''); ?>><?php echo e(__('Arabic')); ?></option>
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
                    <h6><?php echo e(__('Change Password')); ?> <small class="text-muted">(<?php echo e(__('leave blank to keep current password')); ?>)</small></h6>

                    <div class="mb-3">
                        <label for="password" class="form-label"><?php echo e(__('New Password')); ?></label>
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
                        <label for="password_confirmation" class="form-label"><?php echo e(__('Confirm New Password')); ?></label>
                        <input type="password" class="form-control"
                               id="password_confirmation" name="password_confirmation">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('users.show', $user)); ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            <?php echo e(__('Cancel')); ?>

                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            <?php echo e(__('Update User')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('User Actions')); ?></h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('users.show', $user)); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>
                        <?php echo e(__('View Profile')); ?>

                    </a>

                    <?php if($user->canWorkOnTasks()): ?>
                        <a href="<?php echo e(route('tasks.index', ['assigned_to' => $user->id])); ?>" class="btn btn-outline-success">
                            <i class="bi bi-list-task me-2"></i>
                            <?php echo e(__('View Tasks')); ?>

                        </a>
                    <?php endif; ?>

                    <?php if($user->isManager()): ?>
                        <a href="<?php echo e(route('projects.index', ['manager_id' => $user->id])); ?>" class="btn btn-outline-info">
                            <i class="bi bi-folder me-2"></i>
                            <?php echo e(__('View Projects')); ?>

                        </a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $user)): ?>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i>
                            <?php echo e(__('Delete User')); ?>

                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Current Role')); ?></h5>
            </div>
            <div class="card-body">
                <div id="current-role-info">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('User Statistics')); ?></h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong><?php echo e(__('Member Since')); ?>:</strong>
                    <span class="float-end"><?php echo e($user->created_at->format('M d, Y')); ?></span>
                </div>
                <?php if($user->isManager()): ?>
                    <div class="mb-2">
                        <strong><?php echo e(__('Projects Managed')); ?>:</strong>
                        <span class="float-end"><?php echo e($user->managedProjects->count()); ?></span>
                    </div>
                <?php endif; ?>
                <?php if($user->canWorkOnTasks()): ?>
                    <div class="mb-2">
                        <strong><?php echo e(__('Tasks Assigned')); ?>:</strong>
                        <span class="float-end"><?php echo e($user->assignedTasks->count()); ?></span>
                    </div>
                    <div class="mb-2">
                        <strong><?php echo e(__('Time Logged')); ?>:</strong>
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
                <h5 class="modal-title"><?php echo e(__('Delete User')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(__('Are you sure you want to delete this user?')); ?></p>
                <p class="text-danger">
                    <strong><?php echo e(__('Warning:')); ?></strong>
                    <?php echo e(__('This action cannot be undone.')); ?>

                </p>
                <p><strong><?php echo e(__('User:')); ?></strong> <?php echo e($user->name); ?></p>
                <?php if($user->managedProjects->count() > 0 || $user->assignedTasks->count() > 0 || $user->timeEntries->count() > 0): ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?php echo e(__('This user has associated data that will prevent deletion:')); ?>

                        <ul class="mb-0 mt-2">
                            <?php if($user->managedProjects->count() > 0): ?>
                                <li><?php echo e(__(':count managed projects', ['count' => $user->managedProjects->count()])); ?></li>
                            <?php endif; ?>
                            <?php if($user->assignedTasks->count() > 0): ?>
                                <li><?php echo e(__(':count assigned tasks', ['count' => $user->assignedTasks->count()])); ?></li>
                            <?php endif; ?>
                            <?php if($user->timeEntries->count() > 0): ?>
                                <li><?php echo e(__(':count time entries', ['count' => $user->timeEntries->count()])); ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                <form method="POST" action="<?php echo e(route('users.destroy', $user)); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger"><?php echo e(__('Delete User')); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

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

function updateRoleInfo() {
    const selectedRole = document.getElementById('role').value;
    const infoDiv = document.getElementById('current-role-info');

    if (selectedRole && roleDescriptions[selectedRole]) {
        const role = roleDescriptions[selectedRole];
        const permissionsList = role.permissions.map(p => `<li>${p}</li>`).join('');

        infoDiv.innerHTML = `
            <h6 class="text-primary">${role.title}</h6>
            <p class="small">${role.description}</p>
            <h6 class="mt-3"><?php echo e(__('Permissions')); ?></h6>
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