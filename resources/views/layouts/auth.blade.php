@php
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Auth') - ProManage</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Palette shared with the landing page (see resources/views/landing.blade.php) */
        :root { --dz-deep: #0B2A22; --dz-green: #0F6B4F; --dz-bright: #21A97C; --dz-sand: #F3EFE4; --dz-clay: #C1592B; --primary-color: #0F6B4F; --primary-hover: #21A97C; --secondary-color: #6b7d76; --bg-light: #F3EFE4; --text-main: #0B2A22; --text-muted: #6b7d76; --border-color: rgba(11, 42, 34, 0.14); --card-shadow: 0 16px 40px rgba(11, 42, 34, 0.10); }
        body { background-color: var(--bg-light); min-height: 100vh; font-family: 'Figtree', sans-serif; color: var(--text-main); margin: 0; overflow-x: hidden; }
        [dir="rtl"] { text-align: right; }
        [lang="ar"], [dir="rtl"] { font-family: 'Noto Sans Arabic', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .auth-wrapper { display: flex; min-height: 100vh; width: 100%; }
        .auth-brand-side { flex: 1; background: linear-gradient(160deg, var(--dz-deep) 0%, var(--dz-green) 100%); color: white; display: flex; flex-direction: column; justify-content: center; padding: 4rem; position: relative; overflow: hidden; }
        .auth-brand-side::before { content: ''; position: absolute; inset: 0; background-image: repeating-linear-gradient(45deg, rgba(255,255,255,0.05) 0 2px, transparent 2px 26px), repeating-linear-gradient(-45deg, rgba(255,255,255,0.05) 0 2px, transparent 2px 26px), repeating-linear-gradient(0deg, rgba(255,255,255,0.04) 0 2px, transparent 2px 26px), repeating-linear-gradient(90deg, rgba(255,255,255,0.04) 0 2px, transparent 2px 26px); mask-image: radial-gradient(120% 90% at 70% 10%, #000 20%, transparent 75%); -webkit-mask-image: radial-gradient(120% 90% at 70% 10%, #000 20%, transparent 75%); pointer-events: none; }
        .brand-content { position: relative; z-index: 2; max-width: 500px; }
        .brand-logo { font-size: 2.5rem; font-weight: 700; margin-bottom: 2rem; display: flex; align-items: center; }
        .brand-logo i { margin-right: 1rem; }
        [dir="rtl"] .brand-logo i { margin-right: 0; margin-left: 1rem; }
        .brand-features { list-style: none; padding: 0; margin-top: 3rem; }
        .brand-features li { margin-bottom: 1.5rem; display: flex; align-items: flex-start; font-size: 1.1rem; }
        .brand-features i { background: rgba(255, 255, 255, 0.2); padding: 0.5rem; border-radius: 10px; margin-right: 1rem; font-size: 1.2rem; }
        [dir="rtl"] .brand-features i { margin-right: 0; margin-left: 1rem; }
        .auth-form-side { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; background-color: white; }
        .auth-form-container { width: 100%; max-width: 480px; animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .auth-header { margin-bottom: 2.5rem; }
        .auth-header h2 { font-weight: 700; color: var(--dz-deep); margin-bottom: 0.5rem; }
        .auth-header .subtitle { color: var(--text-muted); margin-bottom: 0; }
        .form-label { font-weight: 500; color: var(--dz-deep); margin-bottom: 0.5rem; }
        .input-group { border-radius: 12px; overflow: hidden; border: 1px solid var(--border-color); transition: all 0.2s; }
        .input-group:focus-within { border-color: var(--primary-color); box-shadow: 0 0 0 4px rgba(33, 169, 124, 0.16); }
        .input-group-text { background-color: rgba(11, 42, 34, 0.04); border: none; color: var(--text-muted); padding-left: 1rem; padding-right: 1rem; }
        .form-control, .form-select { border: none; padding: 0.75rem 1rem; font-size: 1rem; }
        .form-control:focus, .form-select:focus { box-shadow: none; background-color: white; }
        .btn-primary { background-color: var(--primary-color); border: none; border-radius: 12px; padding: 0.75rem 1.5rem; font-weight: 600; transition: all 0.2s; }
        .btn-primary:hover { background-color: var(--primary-hover); transform: translateY(-1px); }
        .btn-link { color: var(--primary-color); font-weight: 600; text-decoration: none; }
        .divider { position: relative; text-align: center; margin: 2rem 0; }
        .divider::before { content: ''; position: absolute; top: 50%; left: 0; width: 100%; height: 1px; background-color: var(--border-color); z-index: 1; }
        .divider span { position: relative; z-index: 2; background-color: white; padding: 0 1rem; color: var(--text-muted); font-size: 0.875rem; }
        .language-switcher { position: absolute; top: 2rem; right: 2rem; z-index: 100; }
        [dir="rtl"] .language-switcher { right: auto; left: 2rem; }
        .language-switcher .btn { background: white; border: 1px solid var(--border-color); border-radius: 10px; padding: 0.5rem 1rem; font-weight: 500; }
        @media (max-width: 991px) { .auth-brand-side { display: none; } .auth-form-side { background-color: var(--bg-light); } .auth-form-container { background: white; padding: 2.5rem; border-radius: 24px; box-shadow: var(--card-shadow); } }
    </style>
</head>
<body>
    <div class="language-switcher">
        <div class="dropdown">
            <button class="btn dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-globe"></i> <span>{{ LaravelLocalization::getCurrentLocaleName() }}</span></button>
            <ul class="dropdown-menu dropdown-menu-end">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <li><a class="dropdown-item d-flex align-items-center justify-content-between {{ app()->getLocale() == $localeCode ? 'active' : '' }}" rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"><span>{{ $properties['native'] }}</span>@if(app()->getLocale() == $localeCode) <i class="bi bi-check2"></i> @endif</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="auth-wrapper">
        <div class="auth-brand-side">
            <div class="brand-content">
                <div class="brand-logo"><i class="bi bi-kanban-fill"></i><span>ProManage</span></div>
                <h1 class="display-4 fw-bold mb-4">{{ __('app.welcome_back') }}</h1>
                <p class="lead opacity-75 mb-5">{{ __('app.auth.brand_subtitle') ?? 'Streamline your projects, tasks, and team collaboration in one place.' }}</p>
                <ul class="brand-features">
                    <li><i class="bi bi-check-lg"></i><div><strong>Project Management</strong><p class="mb-0 opacity-75">Organize and track all your projects with ease.</p></div></li>
                    <li><i class="bi bi-check-lg"></i><div><strong>Team Collaboration</strong><p class="mb-0 opacity-75">Work together efficiently with your team members.</p></div></li>
                </ul>
            </div>
        </div>
        <div class="auth-form-side"><div class="auth-form-container"><div class="mobile-logo d-lg-none text-center mb-4"><i class="bi bi-kanban-fill text-primary" style="font-size: 3rem;"></i><h3 class="fw-bold">ProManage</h3></div>@yield('content')</div></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.1.2/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        function showSuccess(msg) {
            const h = document.querySelector('.auth-header'); if (!h) return;
            h.insertAdjacentHTML('afterend', `<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert"><i class="bi bi-check-circle-fill me-2"></i> ${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`);
        }
        function showError(msg) {
            const h = document.querySelector('.auth-header'); if (!h) return;
            h.insertAdjacentHTML('afterend', `<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i> ${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`);
        }
        function clearErrors() {
            document.querySelectorAll('.alert').forEach(a => a.remove());
            document.querySelectorAll('.is-invalid').forEach(i => { i.classList.remove('is-invalid'); const g = i.closest('.input-group'); if (g) g.style.borderColor = ''; });
            document.querySelectorAll('.invalid-feedback').forEach(f => f.remove());
        }
        function showFieldErrors(errs) {
            if (!errs) return;
            Object.keys(errs).forEach(f => {
                const i = document.querySelector(`[name="${f}"]`);
                if (i) {
                    i.classList.add('is-invalid'); const g = i.closest('.input-group'); if (g) g.style.borderColor = '#dc3545';
                    const c = i.closest('.mb-3') || i.closest('.mb-4') || i.parentNode;
                    const fb = document.createElement('div'); fb.className = 'invalid-feedback d-block mt-1'; fb.textContent = errs[f][0]; c.appendChild(fb);
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            const obs = new MutationObserver((ms) => { ms.forEach((m) => { m.addedNodes.forEach((n) => { if (n.nodeType === 1 && n.classList.contains('alert-success')) { setTimeout(() => { const b = bootstrap.Alert.getOrCreateInstance(n); if (b) b.close(); }, 5000); } }); }); });
            obs.observe(document.body, { childList: true, subtree: true });
        });
    </script>
    @stack('scripts')
</body>
</html>
