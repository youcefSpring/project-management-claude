@extends('layouts.auth')

@section('title', __('app.login'))

@section('content')
<div class="auth-header">
    <h2 class="h3">{{ __('app.welcome') }}</h2>
    <p class="subtitle">{{ __('app.auth.login_title') }}</p>
</div>

@include('partials.alerts')

<form id="login-form" method="POST" action="{{ route('login.post') }}">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label">{{ __('app.email') }}</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control" id="email" name="email" required autofocus placeholder="name@company.com">
        </div>
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <label for="password" class="form-label mb-0">{{ __('app.password') }}</label>
            <a href="{{ route('password.request') }}" class="small btn-link">{{ __('app.auth.forgot_password') }}</a>
        </div>
        <div class="input-group mt-2">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
            <button type="button" class="btn btn-outline-light border-0 text-muted px-3" id="toggle-password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <div class="mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label text-muted small" for="remember">{{ __('app.auth.remember_me') }}</label>
        </div>
    </div>

    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary py-2" id="login-btn">
            <span class="login-text">{{ __('app.login') }}</span>
            <span class="login-spinner spinner-border spinner-border-sm d-none" role="status"></span>
        </button>
    </div>

    <div class="divider"><span>{{ __('app.auth.or_continue_with') ?? 'Or continue with' }}</span></div>

    <div class="text-center">
        <p class="mb-0 text-muted small">{{ __("app.auth.dont_have_account") }}
            <a href="{{ route('register') }}" class="btn-link ms-1">{{ __('app.register') }}</a>
        </p>
    </div>
</form>

@if(app()->environment('local'))
<div class="mt-5 demo-accounts">
    <div class="card bg-light border-0 rounded-4">
        <div class="card-body p-3">
            <h6 class="card-title text-muted small fw-bold text-uppercase mb-3">Demo Accounts</h6>
            <div class="row g-2">
                <div class="col-4"><button type="button" class="btn btn-sm btn-white border w-100 shadow-sm" onclick="fillLogin('admin@demo.com', 'password')">Admin</button></div>
                <div class="col-4"><button type="button" class="btn btn-sm btn-white border w-100 shadow-sm" onclick="fillLogin('manager@demo.com', 'password')">Manager</button></div>
                <div class="col-4"><button type="button" class="btn btn-sm btn-white border w-100 shadow-sm" onclick="fillLogin('member@demo.com', 'password')">Member</button></div>
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
    const loginText = document.querySelector('.login-text');
    const loginSpinner = document.querySelector('.login-spinner');

    document.getElementById('toggle-password')?.addEventListener('click', function() {
        const field = document.getElementById('password');
        const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
        field.setAttribute('type', type);
        this.querySelector('i').className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    });

    form?.addEventListener('submit', function(e) {
        e.preventDefault();
        clearErrors();
        submitBtn.disabled = true;
        loginText?.classList.add('d-none');
        loginSpinner?.classList.remove('d-none');

        axios.post(this.action, new FormData(this))
            .then(res => {
                if (res.data.success) {
                    showSuccess(res.data.message);
                    setTimeout(() => window.location.href = res.data.redirect || '{{ route("dashboard") }}', 500);
                } else {
                    showError(res.data.message);
                    resetForm();
                }
            })
            .catch(err => {
                if (err.response?.status === 422) showFieldErrors(err.response.data.errors);
                else showError('Error: ' + (err.response?.data?.message || 'Server error'));
                resetForm();
            });
    });

    function resetForm() {
        submitBtn.disabled = false;
        loginText?.classList.remove('d-none');
        loginSpinner?.classList.add('d-none');
    }

    window.fillLogin = (e, p) => {
        const ei = document.getElementById('email'), pi = document.getElementById('password');
        if (ei) ei.value = e; if (pi) { pi.value = p; pi.focus(); }
    };
});
</script>
@endpush
