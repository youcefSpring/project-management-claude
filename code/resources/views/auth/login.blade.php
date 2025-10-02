@extends('layouts.auth')

@section('title', __('Login'))

@section('content')
<h2>{{ __('Welcome Back') }}</h2>
<p class="subtitle">{{ __('Sign in to your account to continue') }}</p>

@include('partials.alerts')

<form id="login-form" method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email Field -->
    <div class="mb-3">
        <label for="email" class="form-label">{{ __('Email Address') }}</label>
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
                   autofocus
                   placeholder="{{ __('Enter your email') }}">
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
                   placeholder="{{ __('Enter your password') }}">
            <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Remember Me -->
    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
                {{ __('Remember me') }}
            </label>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary" id="login-btn">
            <span class="login-text">{{ __('Sign In') }}</span>
            <span class="login-spinner spinner-border spinner-border-sm d-none" role="status">
                <span class="visually-hidden">{{ __('Loading...') }}</span>
            </span>
        </button>
    </div>

    <!-- Forgot Password -->
    <div class="text-center mb-3">
        <a href="{{ route('password.request') }}" class="btn-link">
            {{ __('Forgot your password?') }}
        </a>
    </div>

    <!-- Divider -->
    <div class="divider">
        <span>{{ __('or') }}</span>
    </div>

    <!-- Register Link -->
    <div class="text-center">
        <p class="mb-0">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}" class="btn-link">{{ __('Sign up') }}</a>
        </p>
    </div>
</form>

<!-- Demo Accounts (for development) -->
@if(app()->environment('local'))
<div class="mt-4">
    <div class="card bg-light">
        <div class="card-body">
            <h6 class="card-title">{{ __('Demo Accounts') }}</h6>
            <div class="row">
                <div class="col-4">
                    <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="fillLogin('admin@demo.com', 'password')">
                        {{ __('Admin') }}
                    </button>
                </div>
                <div class="col-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="fillLogin('manager@demo.com', 'password')">
                        {{ __('Manager') }}
                    </button>
                </div>
                <div class="col-4">
                    <button type="button" class="btn btn-sm btn-outline-info w-100" onclick="fillLogin('member@demo.com', 'password')">
                        {{ __('Member') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
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
                    showSuccess(response.data.message || '{{ __("Login successful! Redirecting...") }}');

                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = response.data.redirect || '{{ route("dashboard") }}';
                    }, 1000);
                } else {
                    showError(response.data.message || '{{ __("Login failed. Please try again.") }}');
                    resetForm();
                }
            })
            .catch(error => {
                if (error.response?.status === 422) {
                    // Validation errors
                    showFieldErrors(error.response.data.errors);
                } else if (error.response?.status === 401) {
                    showError('{{ __("Invalid credentials. Please check your email and password.") }}');
                } else {
                    showError('{{ __("An error occurred. Please try again.") }}');
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
    @if(app()->environment('local'))
    window.fillLogin = function(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    };
    @endif

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
@endpush