<?php $__env->startSection('title', __('app.login')); ?>

<?php $__env->startSection('content'); ?>
<h2><?php echo e(__('app.welcome')); ?></h2>
<p class="subtitle"><?php echo e(__('app.auth.login_title')); ?></p>

<?php echo $__env->make('partials.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<form id="login-form" method="POST" action="<?php echo e(route('login.post')); ?>">
    <?php echo csrf_field(); ?>

    <!-- Email Field -->
    <div class="mb-3">
        <label for="email" class="form-label"><?php echo e(__('app.email')); ?></label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-envelope"></i>
            </span>
            <input type="email"
                   class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   id="email"
                   name="email"
                   value="<?php echo e(old('email')); ?>"
                   required
                   autofocus
                   placeholder="<?php echo e(__('app.validation.email')); ?>">
        </div>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <!-- Password Field -->
    <div class="mb-3">
        <label for="password" class="form-label"><?php echo e(__('app.password')); ?></label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-lock"></i>
            </span>
            <input type="password"
                   class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   id="password"
                   name="password"
                   required
                   placeholder="<?php echo e(__('app.validation.password_min')); ?>">
            <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <!-- Remember Me -->
    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
            <label class="form-check-label" for="remember">
                <?php echo e(__('app.auth.remember_me')); ?>

            </label>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary" id="login-btn">
            <span class="login-text"><?php echo e(__('app.login')); ?></span>
            <span class="login-spinner spinner-border spinner-border-sm d-none" role="status">
                <span class="visually-hidden"><?php echo e(__('app.loading')); ?></span>
            </span>
        </button>
    </div>

    <!-- Forgot Password -->
    <div class="text-center mb-3">
        <a href="<?php echo e(route('password.request')); ?>" class="btn-link">
            <?php echo e(__('app.auth.forgot_password')); ?>

        </a>
    </div>

    <!-- Divider -->
    <div class="divider">
        <span><?php echo e(__('or')); ?></span>
    </div>

    <!-- Register Link -->
    <div class="text-center">
        <p class="mb-0">
            <?php echo e(__("app.auth.dont_have_account")); ?>

            <a href="<?php echo e(route('register')); ?>" class="btn-link"><?php echo e(__('app.register')); ?></a>
        </p>
    </div>
</form>

<!-- Demo Accounts (for development) -->
<?php if(app()->environment('local')): ?>
<div class="mt-4">
    <div class="card bg-light">
        <div class="card-body">
            <h6 class="card-title"><?php echo e(__('Demo Accounts')); ?></h6>
            <div class="row">
                <div class="col-4">
                    <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="fillLogin('admin@demo.com', 'password')">
                        <?php echo e(__('app.users.admin')); ?>

                    </button>
                </div>
                <div class="col-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="fillLogin('manager@demo.com', 'password')">
                        <?php echo e(__('app.users.manager')); ?>

                    </button>
                </div>
                <div class="col-4">
                    <button type="button" class="btn btn-sm btn-outline-info w-100" onclick="fillLogin('member@demo.com', 'password')">
                        <?php echo e(__('app.users.member')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('login-form');
    const submitBtn = document.getElementById('login-btn');
    const togglePassword = document.getElementById('toggle-password');
    const passwordField = document.getElementById('password');

    // Password toggle
    togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        const icon = this.querySelector('i');
        icon.className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    });

    // Form submission with AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        clearErrors();

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        document.querySelector('.login-text').style.display = 'none';
        document.querySelector('.login-spinner').classList.remove('d-none');

        const formData = new FormData(form);

        axios.post(form.action, formData)
            .then(response => {
                if (response.data.success) {
                    showSuccess(response.data.message || '<?php echo e(__("app.auth.login_successful")); ?>');

                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = response.data.redirect || '<?php echo e(route("dashboard")); ?>';
                    }, 1000);
                } else {
                    showError(response.data.message || '<?php echo e(__("app.messages.operation_failed")); ?>');
                    resetForm();
                }
            })
            .catch(error => {
                if (error.response?.status === 422) {
                    // Validation errors
                    showFieldErrors(error.response.data.errors);
                } else if (error.response?.status === 401) {
                    showError('<?php echo e(__("app.auth.invalid_credentials")); ?>');
                } else {
                    showError('<?php echo e(__("app.messages.server_error")); ?>');
                }
                resetForm();
            });
    });

    function resetForm() {
        submitBtn.disabled = false;
        submitBtn.classList.remove('loading');
        document.querySelector('.login-text').style.display = 'inline';
        document.querySelector('.login-spinner').classList.add('d-none');
    }

    // Demo account filler (development only)
    <?php if(app()->environment('local')): ?>
    window.fillLogin = function(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    };
    <?php endif; ?>

    // Auto-focus on email field
    document.getElementById('email').focus();

    // Enter key handling
    form.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/auth/login.blade.php ENDPATH**/ ?>