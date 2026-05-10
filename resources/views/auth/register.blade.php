@extends('layouts.auth')

@section('title', __('app.register'))

@section('content')
<div class="auth-header">
    <h2 class="h3">{{ __('app.create_account') }}</h2>
    <p class="subtitle">{{ __('app.join_us_to_start_managing') }}</p>
</div>

@include('partials.alerts')

<form id="register-form" method="POST" action="{{ route('register') }}">
    @csrf

    <div class="row">
        <!-- Name Field -->
        <div class="col-md-6 mb-3">
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
                       placeholder="John Doe">
            </div>
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="col-md-6 mb-3">
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
                       placeholder="name@company.com">
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        <!-- Company Name Field -->
        <div class="col-md-6 mb-3">
            <label for="company_name" class="form-label">{{ __('app.company_name') }}</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-building"></i>
                </span>
                <input type="text"
                       class="form-control @error('company_name') is-invalid @enderror"
                       id="company_name"
                       name="company_name"
                       value="{{ old('company_name') }}"
                       required
                       placeholder="Acme Corp">
            </div>
            @error('company_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Language Preference -->
        <div class="col-md-6 mb-3">
            <label for="language" class="form-label">{{ __('app.preferred_language') }}</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-globe"></i>
                </span>
                <select class="form-select @error('language') is-invalid @enderror" id="language" name="language">
                    <option value="fr" {{ old('language', app()->getLocale()) === 'fr' ? 'selected' : '' }}>Français</option>
                    <option value="en" {{ old('language', app()->getLocale()) === 'en' ? 'selected' : '' }}>English</option>
                    <option value="ar" {{ old('language', app()->getLocale()) === 'ar' ? 'selected' : '' }}>العربية</option>
                    <option value="es" {{ old('language', app()->getLocale()) === 'es' ? 'selected' : '' }}>Español</option>
                </select>
            </div>
            @error('language')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        <!-- Password Field -->
        <div class="col-md-6 mb-3">
            <label for="password" class="form-label">{{ __('app.password') }}</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                </span>
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       id="password"
                       name="password"
                       required
                       placeholder="••••••••">
                <button type="button" class="btn btn-outline-light border-0 text-muted px-3" id="toggle-password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password Field -->
        <div class="col-md-6 mb-3">
            <label for="password_confirmation" class="form-label">{{ __('app.confirm_password') ?? 'Confirm Password' }}</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock-fill"></i>
                </span>
                <input type="password"
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       id="password_confirmation"
                       name="password_confirmation"
                       required
                       placeholder="••••••••">
                <button type="button" class="btn btn-outline-light border-0 text-muted px-3" id="toggle-password-confirm">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Terms and Privacy -->
    <div class="mb-4">
        <div class="form-check">
            <input class="form-check-input @error('terms') is-invalid @enderror"
                   type="checkbox"
                   id="terms"
                   name="terms"
                   required
                   {{ old('terms') ? 'checked' : '' }}>
            <label class="form-check-label text-muted small" for="terms">
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
    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary py-2" id="register-btn">
            <span class="register-text">{{ __('app.create_account') }}</span>
            <span class="register-spinner spinner-border spinner-border-sm d-none" role="status">
                <span class="visually-hidden">{{ __('app.loading') }}</span>
            </span>
        </button>
    </div>

    <!-- Divider -->
    <div class="divider">
        <span>{{ __('app.auth.or_continue_with') ?? 'Or continue with' }}</span>
    </div>

    <!-- Login Link -->
    <div class="text-center">
        <p class="mb-0 text-muted small">
            {{ __('app.auth.already_have_account') ?? 'Already have an account?' }}
            <a href="{{ route('login') }}" class="btn-link ms-1">{{ __('app.auth.sign_in') ?? 'Sign in' }}</a>
        </p>
    </div>
</form>

<!-- Password Strength Indicator -->
<div class="mt-3" id="password-strength" style="display: none;">
    <div class="progress" style="height: 4px;">
        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
    </div>
    <small class="text-muted small mt-1 d-block" id="password-strength-text"></small>
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
    const registerText = document.querySelector('.register-text');
    const registerSpinner = document.querySelector('.register-spinner');
    const strengthIndicator = document.getElementById('password-strength');
    const strengthBar = strengthIndicator ? strengthIndicator.querySelector('.progress-bar') : null;
    const strengthText = document.getElementById('password-strength-text');

    if (togglePassword && passwordField) {
        togglePassword.addEventListener('click', function() {
            togglePasswordVisibility(passwordField, this);
        });
    }

    if (togglePasswordConfirm && passwordConfirmField) {
        togglePasswordConfirm.addEventListener('click', function() {
            togglePasswordVisibility(passwordConfirmField, this);
        });
    }

    function togglePasswordVisibility(field, button) {
        const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
        field.setAttribute('type', type);
        const icon = button.querySelector('i');
        if (icon) icon.className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    }

    if (passwordField && strengthIndicator) {
        passwordField.addEventListener('input', function() {
            const password = this.value;
            if (password.length === 0) {
                strengthIndicator.style.display = 'none';
                return;
            }
            strengthIndicator.style.display = 'block';
            let strength = 0;
            let feedback = '';
            if (password.length >= 8) strength += 20;
            if (password.length >= 12) strength += 10;
            if (/[a-z]/.test(password)) strength += 15;
            if (/[A-Z]/.test(password)) strength += 15;
            if (/[0-9]/.test(password)) strength += 15;
            if (/[^A-Za-z0-9]/.test(password)) strength += 25;

            if (strengthBar) {
                if (strength < 30) { strengthBar.className = 'progress-bar bg-danger'; feedback = '{{ __("app.auth.weak") ?? "Weak" }}'; }
                else if (strength < 60) { strengthBar.className = 'progress-bar bg-warning'; feedback = '{{ __("app.auth.medium") ?? "Medium" }}'; }
                else if (strength < 90) { strengthBar.className = 'progress-bar bg-info'; feedback = '{{ __("app.auth.good") ?? "Good" }}'; }
                else { strengthBar.className = 'progress-bar bg-success'; feedback = '{{ __("app.auth.strong") ?? "Strong" }}'; }
                strengthBar.style.width = strength + '%';
            }
            if (strengthText) strengthText.textContent = '{{ __("app.auth.password_strength") ?? "Password strength" }}: ' + feedback;
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();
            if (passwordField.value !== passwordConfirmField.value) {
                showError('{{ __("app.auth.passwords_do_not_match") ?? "Passwords do not match" }}');
                passwordConfirmField.classList.add('is-invalid');
                return;
            }
            submitBtn.disabled = true;
            if (registerText) registerText.classList.add('d-none');
            if (registerSpinner) registerSpinner.classList.remove('d-none');

            axios.post(form.action, new FormData(form))
                .then(response => {
                    if (response.data.success) {
                        showSuccess(response.data.message || '{{ __("app.auth.registration_successful") ?? "Account created successfully!" }}');
                        setTimeout(() => window.location.href = response.data.redirect || '{{ route("dashboard") }}', 1000);
                    } else {
                        showError(response.data.message || '{{ __("app.messages.operation_failed") }}');
                        resetForm();
                    }
                })
                .catch(error => {
                    if (error.response?.status === 422) {
                        showFieldErrors(error.response.data.errors);
                    } else {
                        showError('{{ __("app.messages.server_error") }}');
                    }
                    resetForm();
                });
        });
    }

    function resetForm() {
        submitBtn.disabled = false;
        if (registerText) registerText.classList.remove('d-none');
        if (registerSpinner) registerSpinner.classList.add('d-none');
    }

    if (document.getElementById('name')) document.getElementById('name').focus();
});
</script>
@endpush
