@extends('layouts.auth')

@section('title', __('Register'))

@section('content')
<h2>{{ __('app.create_account') }}</h2>
<p class="subtitle">{{ __('app.join_us_to_start_managing') }}</p>

@include('partials.alerts')

<form id="register-form" method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Name Field -->
    <div class="mb-3">
        <label for="name" class="form-label">{{ __('app.full_name') }}</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-person"></i>
            </span>
            <input type="text"
                   class="form-control @error('name') is-invalid @enderror"
                   id="name"
                   name="name"
                   value="{{ old('name') }}"
                   required
                   autofocus
                   placeholder="{{ __('app.enter_your_full_name') }}">
        </div>
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email Field -->
    <div class="mb-3">
        <label for="email" class="form-label">{{ __('app.email_address') }}</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-envelope"></i>
            </span>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   placeholder="{{ __('app.enter_your_email_address') }}">
        </div>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password Field -->
    <div class="mb-3">
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-lock"></i>
            </span>
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="password"
                   name="password"
                   required
                   placeholder="{{ __('app.create_password') }}">
            <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <div class="form-text">
            {{ __('app.password_must_be_at_least') }}
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirm Password Field -->
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-lock-fill"></i>
            </span>
            <input type="password"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   id="password_confirmation"
                   name="password_confirmation"
                   required
                   placeholder="{{ __('app.confirm_your_password') }}">
            <button type="button" class="btn btn-outline-secondary" id="toggle-password-confirm">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        @error('password_confirmation')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Language Preference -->
    <div class="mb-3">
        <label for="language" class="form-label">{{ __('app.preferred_language') }}</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-globe"></i>
            </span>
            <select class="form-select @error('language') is-invalid @enderror" id="language" name="language">
                <option value="fr" {{ old('language', app()->getLocale()) === 'fr' ? 'selected' : '' }}>Français</option>
                <option value="en" {{ old('language', app()->getLocale()) === 'en' ? 'selected' : '' }}>English</option>
                <option value="ar" {{ old('language', app()->getLocale()) === 'ar' ? 'selected' : '' }}>العربية</option>
            </select>
        </div>
        @error('language')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Terms and Privacy -->
    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input @error('terms') is-invalid @enderror"
                   type="checkbox"
                   id="terms"
                   name="terms"
                   required
                   {{ old('terms') ? 'checked' : '' }}>
            <label class="form-check-label" for="terms">
                {{ __('app.i_agree_to_the') }}
                <a href="#" class="btn-link">{{ __('app.terms_of_service') }}</a>
                {{ __('app.and') }}
                <a href="#" class="btn-link">{{ __('app.privacy_policy') }}</a>
            </label>
        </div>
        @error('terms')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Submit Button -->
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary" id="register-btn">
            <span class="register-text">{{ __('app.create_account') }}</span>
            <span class="register-spinner spinner-border spinner-border-sm d-none" role="status">
                <span class="visually-hidden">{{ __('Loading...') }}</span>
            </span>
        </button>
    </div>

    <!-- Divider -->
    <div class="divider">
        <span>{{ __('or') }}</span>
    </div>

    <!-- Login Link -->
    <div class="text-center">
        <p class="mb-0">
            {{ __('Already have an account?') }}
            <a href="{{ route('login') }}" class="btn-link">{{ __('Sign in') }}</a>
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
@endsection

@push('scripts')
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
            feedback = '{{ __("Weak") }}';
        } else if (strength < 60) {
            strengthBar.className = 'progress-bar bg-warning';
            feedback = '{{ __("Medium") }}';
        } else if (strength < 90) {
            strengthBar.className = 'progress-bar bg-info';
            feedback = '{{ __("Good") }}';
        } else {
            strengthBar.className = 'progress-bar bg-success';
            feedback = '{{ __("Strong") }}';
        }

        strengthBar.style.width = strength + '%';
        strengthText.textContent = '{{ __("Password strength") }}: ' + feedback;
    });

    // Password confirmation validation
    passwordConfirmField.addEventListener('input', function() {
        if (this.value !== passwordField.value) {
            this.setCustomValidity('{{ __("Passwords do not match") }}');
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
            showError('{{ __("Passwords do not match") }}');
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
                    showSuccess(response.data.message || '{{ __("Account created successfully! Redirecting...") }}');

                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = response.data.redirect || '{{ route("dashboard") }}';
                    }, 1500);
                } else {
                    showError(response.data.message || '{{ __("Registration failed. Please try again.") }}');
                    resetForm();
                }
            })
            .catch(error => {
                if (error.response?.status === 422) {
                    // Validation errors
                    showFieldErrors(error.response.data.errors);
                } else {
                    showError('{{ __("An error occurred. Please try again.") }}');
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
            showFieldErrors({ email: ['{{ __("Please enter a valid email address") }}'] });
        }
    });

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
});
</script>
@endpush