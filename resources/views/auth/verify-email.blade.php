@extends('layouts.auth')

@section('title', __('app.auth.verify_email') ?? 'Verify Email')

@section('content')
<div class="auth-header text-center">
    <div class="mb-4">
        <i class="bi bi-envelope-check text-primary" style="font-size: 3rem;"></i>
    </div>
    <h2 class="h3">{{ __('app.auth.verify_email_title') ?? 'Verify your email address' }}</h2>
    <p class="subtitle">{{ __('app.auth.verify_email_subtitle') ?? 'Before proceeding, please check your email for a verification link.' }}</p>
</div>

@if (session('message'))
    <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('message') }}
    </div>
@endif

<div class="text-center">
    <p class="text-muted small mb-4">
        {{ __('app.auth.did_not_receive_email') ?? 'If you did not receive the email, click the button below to request another.' }}
    </p>

    <form action="{{ route('verification.send') }}" method="POST" class="mb-3">
        @csrf
        <div class="d-grid">
            <button type="submit" class="btn btn-primary py-2">
                {{ __('app.auth.resend_verification_btn') ?? 'Resend Verification Email' }}
            </button>
        </div>
    </form>

    <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-link small text-muted">
            <i class="bi bi-box-arrow-right me-1"></i>
            {{ __('app.logout') }}
        </button>
    </form>
</div>
@endsection
