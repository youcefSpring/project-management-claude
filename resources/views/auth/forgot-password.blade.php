@extends('layouts.auth')

@section('title', __('app.auth.forgot_password'))

@section('content')
<div class="auth-header">
    <h2 class="h3">{{ __('app.auth.forgot_password_title') ?? 'Forgot Password?' }}</h2>
    <p class="subtitle">{{ __('app.auth.forgot_password_subtitle') ?? 'Enter your email address and we will send you a link to reset your password.' }}</p>
</div>

@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <!-- Email Field -->
    <div class="mb-4">
        <label for="email" class="form-label">{{ __('app.email') }}</label>
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
                   placeholder="name@company.com">
        </div>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Submit Button -->
    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary py-2">
            {{ __('app.auth.send_reset_link') ?? 'Send Reset Link' }}
        </button>
    </div>

    <!-- Back to Login -->
    <div class="text-center">
        <a href="{{ route('login') }}" class="btn-link small">
            <i class="bi bi-arrow-left me-1"></i>
            {{ __('app.auth.back_to_login') ?? 'Back to login' }}
        </a>
    </div>
</form>
@endsection
