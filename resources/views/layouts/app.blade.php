@php
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <meta name="user-role" content="{{ auth()->check() ? auth()->user()->role : 'guest' }}">
    <meta name="app-locale" content="{{ app()->getLocale() }}">

    <title>@yield('title', __('app.Dashboard')) - {{ config('app.name', 'Gestion de Projets') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Arabic Font Support -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Component Styles -->
    @include('components.styles')

    @stack('styles')
</head>

<body class="bg-light">
    <div id="app">
        <!-- Sidebar Component -->
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar Component -->
            @include('components.navbar')

            <!-- Alerts -->
            @include('partials.alerts')

            <!-- Main Content Area -->
            <main class="container-fluid p-4">
                @yield('content')
            </main>
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

    <!-- Component Scripts -->
    @include('components.scripts')

    @stack('scripts')
</body>
</html>
