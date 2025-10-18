@php
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('app.auth.login_title')) - {{ config('app.name', 'Gestion de Projets') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Arabic Font Support -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Figtree', sans-serif;
        }

        /* RTL Support */
        [dir="rtl"] {
            text-align: right;
        }

        [dir="rtl"] .form-label {
            text-align: right;
        }

        [dir="rtl"] .input-group {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .input-group .form-control {
            text-align: right;
        }

        [dir="rtl"] .btn i {
            margin-left: 0.5rem;
            margin-right: 0;
        }

        [dir="rtl"] .alert {
            text-align: right;
        }

        /* LTR Support */
        [dir="ltr"] {
            text-align: left;
        }

        [dir="ltr"] .form-label {
            text-align: left;
        }

        [dir="ltr"] .input-group {
            flex-direction: row;
        }

        [dir="ltr"] .input-group .form-control {
            text-align: left;
        }

        [dir="ltr"] .btn i {
            margin-right: 0.5rem;
            margin-left: 0;
        }

        [dir="ltr"] .alert {
            text-align: left;
        }

        /* Arabic Font Support */
        [lang="ar"], [dir="rtl"] {
            font-family: 'Noto Sans Arabic', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Language Switcher Styles */
        .language-switcher {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        [dir="rtl"] .language-switcher {
            right: auto;
            left: 20px;
        }

        .dropdown-item.active {
            background-color: var(--primary-color);
            color: white;
        }

        .dropdown-item.active:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .language-switcher .dropdown-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .language-switcher .dropdown-item i.bi-check2 {
            color: #28a745;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1200px;
            min-height: 80vh;
        }

        .auth-brand {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-brand h1 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .auth-brand p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .auth-brand .features {
            text-align: left;
        }

        [dir="rtl"] .auth-brand .features {
            text-align: right;
        }

        .auth-brand .features li {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .auth-brand .features i {
            margin-right: 0.5rem;
            color: #ffc107;
        }

        [dir="rtl"] .auth-brand .features i {
            margin-right: 0;
            margin-left: 0.5rem;
        }

        .auth-form {
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 80vh;
        }

        .auth-form h2 {
            color: #333;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .auth-form .subtitle {
            color: #666;
            margin-bottom: 2rem;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .btn-link:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e9ecef;
        }

        .divider span {
            padding: 0 1rem;
            color: #666;
            font-size: 0.9rem;
        }

        .language-switcher {
            position: absolute;
            top: 1rem;
            right: 1rem;
        }

        [dir="rtl"] .language-switcher {
            right: auto;
            left: 1rem;
        }

        .language-switcher .dropdown-toggle {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 8px;
            padding: 0.25rem 0.75rem;
        }

        .language-switcher .dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .alert {
            border-radius: 12px;
            border: none;
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-card {
                margin: 1rem;
                min-height: 90vh;
                max-width: calc(100% - 2rem);
            }

            .auth-brand {
                display: none;
            }

            .auth-form {
                padding: 2rem 1.5rem;
                min-height: 90vh;
            }

            .auth-brand h1 {
                font-size: 2rem;
            }

            .auth-container {
                padding: 1rem;
            }
        }

        @media (max-width: 576px) {
            .auth-form {
                padding: 1.5rem 1rem;
            }

            .auth-card {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-card {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Input Icons */
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 12px 0 0 12px;
        }

        [dir="rtl"] .input-group-text {
            border-right: 2px solid #e9ecef;
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        [dir="rtl"] .input-group .form-control {
            border-left: 2px solid #e9ecef;
            border-right: none;
            border-radius: 12px 0 0 12px;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Language Switcher -->
    <div class="language-switcher">
        <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-globe me-1"></i>
                {{ LaravelLocalization::getCurrentLocaleName() }}
            </button>
            <ul class="dropdown-menu">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <li>
                        <a class="dropdown-item {{ app()->getLocale() == $localeCode ? 'active' : '' }}"
                           rel="alternate"
                           hreflang="{{ $localeCode }}"
                           href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                            {{ $properties['native'] }}
                            @if(app()->getLocale() == $localeCode)
                                <i class="bi bi-check2 ms-auto"></i>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="row g-0">
                <!-- Branding Side -->
                <div class="col-md-6">
                    <div class="auth-brand">
                        <div>
                            <i class="bi bi-kanban" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                            <h1>{{ config('app.name', 'PM App') }}</h1>
                            <p>{{ __('Professional Project Management Solution') }}</p>

                            <ul class="features list-unstyled">
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    {{ __('Project & Task Management') }}
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    {{ __('Time Tracking & Reporting') }}
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    {{ __('Team Collaboration') }}
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    {{ __('Multilingual Support') }}
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    {{ __('Real-time Updates') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Form Side -->
                <div class="col-md-6">
                    <div class="auth-form">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
         style="background: rgba(255, 255, 255, 0.8); z-index: 9999; display: none !important;">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">{{ __('app.loading') }}</span>
            </div>
            <p class="mt-2">{{ __('app.loading') }}</p>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Axios for AJAX -->
    <script src="https://cdn.jsdelivr.net/npm/axios@1.1.2/dist/axios.min.js"></script>

    <!-- Common JavaScript -->
    <script>
        // CSRF Token Setup
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

        // Language switching is now handled by direct links with MCamara

        // Loading Functions
        function showLoading() {
            document.getElementById('loading-overlay').style.display = 'flex';
        }

        function hideLoading() {
            document.getElementById('loading-overlay').style.display = 'none';
        }

        // Form Helpers
        function showSuccess(message) {
            const alert = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            document.querySelector('.auth-form').insertAdjacentHTML('afterbegin', alert);
        }

        function showError(message) {
            const alert = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            document.querySelector('.auth-form').insertAdjacentHTML('afterbegin', alert);
        }

        function clearErrors() {
            document.querySelectorAll('.alert').forEach(alert => alert.remove());
            document.querySelectorAll('.is-invalid').forEach(input => {
                input.classList.remove('is-invalid');
                const feedback = input.parentNode.querySelector('.invalid-feedback');
                if (feedback) feedback.remove();
            });
        }

        function showFieldErrors(errors) {
            Object.keys(errors).forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = errors[field][0];
                    input.parentNode.appendChild(feedback);
                }
            });
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelectorAll('.alert-success').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>
