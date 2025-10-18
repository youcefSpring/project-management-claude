<?php $__env->startSection('title', __('Register')); ?>

<?php $__env->startSection('content'); ?>
<h2><?php echo e(__('app.create_account')); ?></h2>
<p class="subtitle"><?php echo e(__('app.join_us_to_start_managing')); ?></p>

<?php echo $__env->make('partials.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<form id="register-form" method="POST" action="<?php echo e(route('register')); ?>">
    <?php echo csrf_field(); ?>

    <!-- Name Field -->
    <div class="mb-3">
        <label for="name" class="form-label"><?php echo e(__('app.full_name')); ?></label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-person"></i>
            </span>
            <input type="text"
                   class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   id="name"
                   name="name"
                   value="<?php echo e(old('name')); ?>"
                   required
                   autofocus
                   placeholder="<?php echo e(__('app.enter_your_full_name')); ?>">
        </div>
        <?php $__errorArgs = ['name'];
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

    <!-- Email Field -->
    <div class="mb-3">
        <label for="email" class="form-label"><?php echo e(__('app.email_address')); ?></label>
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
                   placeholder="<?php echo e(__('app.enter_your_email_address')); ?>">
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

    <!-- Company Name Field -->
    <div class="mb-3">
        <label for="company_name" class="form-label"><?php echo e(__('app.company_name')); ?></label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-building"></i>
            </span>
            <input type="text"
                   class="form-control <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   id="company_name"
                   name="company_name"
                   value="<?php echo e(old('company_name')); ?>"
                   required
                   placeholder="<?php echo e(__('app.enter_your_company_name')); ?>">
        </div>
        <?php $__errorArgs = ['company_name'];
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
        <label for="password" class="form-label"><?php echo e(__('Password')); ?></label>
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
                   placeholder="<?php echo e(__('app.create_password')); ?>">
            <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <div class="form-text">
            <?php echo e(__('app.password_must_be_at_least')); ?>

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

    <!-- Confirm Password Field -->
    <div class="mb-3">
        <label for="password_confirmation" class="form-label"><?php echo e(__('Confirm Password')); ?></label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-lock-fill"></i>
            </span>
            <input type="password"
                   class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   id="password_confirmation"
                   name="password_confirmation"
                   required
                   placeholder="<?php echo e(__('app.confirm_your_password')); ?>">
            <button type="button" class="btn btn-outline-secondary" id="toggle-password-confirm">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <?php $__errorArgs = ['password_confirmation'];
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

    <!-- Language Preference -->
    <div class="mb-3">
        <label for="language" class="form-label"><?php echo e(__('app.preferred_language')); ?></label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-globe"></i>
            </span>
            <select class="form-select <?php $__errorArgs = ['language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="language" name="language">
                <option value="fr" <?php echo e(old('language', app()->getLocale()) === 'fr' ? 'selected' : ''); ?>>Français</option>
                <option value="en" <?php echo e(old('language', app()->getLocale()) === 'en' ? 'selected' : ''); ?>>English</option>
                <option value="ar" <?php echo e(old('language', app()->getLocale()) === 'ar' ? 'selected' : ''); ?>>العربية</option>
            </select>
        </div>
        <?php $__errorArgs = ['language'];
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

    <!-- Terms and Privacy -->
    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input <?php $__errorArgs = ['terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   type="checkbox"
                   id="terms"
                   name="terms"
                   required
                   <?php echo e(old('terms') ? 'checked' : ''); ?>>
            <label class="form-check-label" for="terms">
                <?php echo e(__('app.i_agree_to_the')); ?>

                <a href="#" class="btn-link"><?php echo e(__('app.terms_of_service')); ?></a>
                <?php echo e(__('app.and')); ?>

                <a href="#" class="btn-link"><?php echo e(__('app.privacy_policy')); ?></a>
            </label>
        </div>
        <?php $__errorArgs = ['terms'];
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

    <!-- Submit Button -->
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary" id="register-btn">
            <span class="register-text"><?php echo e(__('app.create_account')); ?></span>
            <span class="register-spinner spinner-border spinner-border-sm d-none" role="status">
                <span class="visually-hidden"><?php echo e(__('Loading...')); ?></span>
            </span>
        </button>
    </div>

    <!-- Divider -->
    <div class="divider">
        <span><?php echo e(__('or')); ?></span>
    </div>

    <!-- Login Link -->
    <div class="text-center">
        <p class="mb-0">
            <?php echo e(__('Already have an account?')); ?>

            <a href="<?php echo e(route('login')); ?>" class="btn-link"><?php echo e(__('Sign in')); ?></a>
        </p>
    </div>
</form>

<!-- Password Strength Indicator -->
<div class="mt-3" id="password-strength" style="display: none;">
    <div class="progress" style="height: 5px;">
        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
    </div>
    <small class="form-text mt-1" id="password-strength-text"></small>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('register-form');
    const submitBtn = document.getElementById('register-btn');
    const togglePassword = document.getElementById('toggle-password');
    const togglePasswordConfirm = document.getElementById('toggle-password-confirm');
    const passwordField = document.getElementById('password');
    const passwordConfirmField = document.getElementById('password_confirmation');
    const strengthIndicator = document.getElementById('password-strength');
    const strengthBar = strengthIndicator.querySelector('.progress-bar');
    const strengthText = document.getElementById('password-strength-text');

    // Password toggle functionality
    togglePassword.addEventListener('click', function() {
        togglePasswordVisibility(passwordField, this);
    });

    togglePasswordConfirm.addEventListener('click', function() {
        togglePasswordVisibility(passwordConfirmField, this);
    });

    function togglePasswordVisibility(field, button) {
        const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
        field.setAttribute('type', type);

        const icon = button.querySelector('i');
        icon.className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    }

    // Password strength checker
    passwordField.addEventListener('input', function() {
        const password = this.value;

        if (password.length === 0) {
            strengthIndicator.style.display = 'none';
            return;
        }

        strengthIndicator.style.display = 'block';

        let strength = 0;
        let feedback = '';

        // Length check
        if (password.length >= 8) strength += 20;
        if (password.length >= 12) strength += 10;

        // Character variety checks
        if (/[a-z]/.test(password)) strength += 15;
        if (/[A-Z]/.test(password)) strength += 15;
        if (/[0-9]/.test(password)) strength += 15;
        if (/[^A-Za-z0-9]/.test(password)) strength += 25;

        // Set color and text based on strength
        if (strength < 30) {
            strengthBar.className = 'progress-bar bg-danger';
            feedback = '<?php echo e(__("Weak")); ?>';
        } else if (strength < 60) {
            strengthBar.className = 'progress-bar bg-warning';
            feedback = '<?php echo e(__("Medium")); ?>';
        } else if (strength < 90) {
            strengthBar.className = 'progress-bar bg-info';
            feedback = '<?php echo e(__("Good")); ?>';
        } else {
            strengthBar.className = 'progress-bar bg-success';
            feedback = '<?php echo e(__("Strong")); ?>';
        }

        strengthBar.style.width = strength + '%';
        strengthText.textContent = '<?php echo e(__("Password strength")); ?>: ' + feedback;
    });

    // Password confirmation validation
    passwordConfirmField.addEventListener('input', function() {
        if (this.value !== passwordField.value) {
            this.setCustomValidity('<?php echo e(__("Passwords do not match")); ?>');
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
        }
    });

    // Form submission with AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        clearErrors();

        // Validate password confirmation
        if (passwordField.value !== passwordConfirmField.value) {
            showError('<?php echo e(__("Passwords do not match")); ?>');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        document.querySelector('.register-text').style.display = 'none';
        document.querySelector('.register-spinner').classList.remove('d-none');

        const formData = new FormData(form);

        axios.post(form.action, formData)
            .then(response => {
                if (response.data.success) {
                    showSuccess(response.data.message || '<?php echo e(__("Account created successfully! Redirecting...")); ?>');

                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = response.data.redirect || '<?php echo e(route("dashboard")); ?>';
                    }, 1500);
                } else {
                    showError(response.data.message || '<?php echo e(__("Registration failed. Please try again.")); ?>');
                    resetForm();
                }
            })
            .catch(error => {
                if (error.response?.status === 422) {
                    // Validation errors
                    showFieldErrors(error.response.data.errors);
                } else {
                    showError('<?php echo e(__("An error occurred. Please try again.")); ?>');
                }
                resetForm();
            });
    });

    function resetForm() {
        submitBtn.disabled = false;
        submitBtn.classList.remove('loading');
        document.querySelector('.register-text').style.display = 'inline';
        document.querySelector('.register-spinner').classList.add('d-none');
    }

    // Auto-focus on name field
    document.getElementById('name').focus();

    // Real-time email validation
    document.getElementById('email').addEventListener('blur', function() {
        const email = this.value;
        if (email && !isValidEmail(email)) {
            this.classList.add('is-invalid');
            showFieldErrors({ email: ['<?php echo e(__("Please enter a valid email address")); ?>'] });
        }
    });

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/auth/register.blade.php ENDPATH**/ ?>