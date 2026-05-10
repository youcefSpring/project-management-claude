@extends('layouts.auth')

@section('title', __('app.auth.reset_password') ?? 'Reset Password')

@section('content')
<div class="auth-header">
    <h2 class="h3">{{ __('app.auth.reset_password_title') ?? 'Reset Password' }}</h2>
    <p class="subtitle">{{ __('app.auth.reset_password_subtitle') ?? 'Enter your new password below to reset your account access.' }}</p>
</div>

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <!-- Email Field -->
    <div class="mb-3">
        <label for="email" class="form-label">{{ __('app.email') }}</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-envelope"></i>
            </span>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email', request()->email) }}"
                   required
                   placeholder="name@company.com">
        </div>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password Field -->
    <div class="mb-3">
        <label for="password" class="form-label">{{ __('app.auth.new_password') ?? 'New Password' }}</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-lock"></i>
            </span>
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="password"
                   name="password"
                   required
                   autofocus
                   placeholder="••••••••">
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirm Password Field -->
    <div class="mb-4">
        <label for="password_confirmation" class="form-label">{{ __('app.confirm_password') ?? 'Confirm Password' }}</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-lock-fill"></i>
            </span>
            <input type="password"
                   class="form-control"
                   id="password_confirmation"
                   name="password_confirmation"
                   required
                   placeholder="••••••••">
        </div>
    </div>

    <!-- Submit Button -->
    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary py-2">
            {{ __('app.auth.reset_password_btn') ?? 'Reset Password' }}
        </button>
    </div>

    <!-- Back to Login -->
    <div class="text-center">
        <a href="{{ route('login') }}" class="btn-link small">
            {{ __('app.auth.back_to_login') ?? 'Back to login' }}
        </a>
    </div>
</form>
@endsection
