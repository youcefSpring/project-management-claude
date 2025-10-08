<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <meta name="user-role" content="{{ auth()->user()->role }}">
    <meta name="app-locale" content="{{ app()->getLocale() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Gestion de Projets') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
            --sidebar-width: 250px;
        }

        /* RTL Support */
        [dir="rtl"] {
            text-align: right;
        }

        [dir="rtl"] .navbar-brand {
            margin-right: 0;
            margin-left: 1rem;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        [dir="rtl"] .sidebar {
            left: auto;
            right: 0;
        }

        .sidebar-collapsed .sidebar {
            transform: translateX(-100%);
        }

        [dir="rtl"] .sidebar-collapsed .sidebar {
            transform: translateX(100%);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        [dir="rtl"] .main-content {
            margin-left: 0;
            margin-right: var(--sidebar-width);
        }

        .sidebar-collapsed .main-content {
            margin-left: 0;
        }

        [dir="rtl"] .sidebar-collapsed .main-content {
            margin-right: 0;
        }

        /* Navigation Styles */
        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            margin: 2px 0;
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link i {
            width: 20px;
            margin-right: 10px;
        }

        [dir="rtl"] .nav-link i {
            margin-right: 0;
            margin-left: 10px;
        }

        /* Card Styles */
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 12px;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-radius: 12px 12px 0 0 !important;
        }

        /* Status Badges */
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
        }

        .status-à_faire { background-color: #ffc107; color: #000; }
        .status-en_cours { background-color: #0dcaf0; color: #000; }
        .status-fait { background-color: #198754; color: #fff; }
        .status-terminé { background-color: #198754; color: #fff; }
        .status-annulé { background-color: #dc3545; color: #fff; }

        /* Loading States */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            [dir="rtl"] .sidebar {
                transform: translateX(100%);
            }

            .main-content {
                margin-left: 0;
            }

            [dir="rtl"] .main-content {
                margin-right: 0;
            }

            .sidebar.show {
                transform: translateX(0);
            }
        }

        /* Custom utilities */
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .hover-shadow {
            transition: box-shadow 0.15s ease-in-out;
        }

        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-light">
    <div id="app">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="p-3">
                <!-- Brand -->
                <div class="d-flex align-items-center mb-4">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-kanban me-2"></i>
                        {{ config('app.name', 'PM App') }}
                    </h4>
                </div>

                <!-- User Info -->
                <div class="card bg-transparent border-light mb-4">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                 style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-white">{{ auth()->user()->name }}</div>
                                <small class="text-light opacity-75">{{ ucfirst(auth()->user()->role) }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i>
                            {{ __('Dashboard') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}"
                           href="{{ route('projects.index') }}">
                            <i class="bi bi-folder"></i>
                            {{ __('Projects') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}"
                           href="{{ route('tasks.index') }}">
                            <i class="bi bi-check2-square"></i>
                            {{ __('Tasks') }}
                        </a>
                    </li>

                    @hasPermission('view.timetracking')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('timesheet.*') ? 'active' : '' }}"
                           href="{{ route('timesheet.index') }}">
                            <i class="bi bi-clock"></i>
                            {{ __('Timesheet') }}
                        </a>
                    </li>
                    @endhasPermission

                    @hasPermission('view.reports')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                           href="{{ route('reports.index') }}">
                            <i class="bi bi-graph-up"></i>
                            {{ __('Reports') }}
                        </a>
                    </li>
                    @endhasPermission

                    @hasPermission('view.users')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                           href="{{ route('users.index') }}">
                            <i class="bi bi-people"></i>
                            {{ __('User Management') }}
                        </a>
                    </li>
                    @endhasPermission

                    @hasPermission('access.admin.dashboard')
                    <hr class="my-3 text-light">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}"
                           href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-gear"></i>
                            {{ __('Administration') }}
                        </a>
                    </li>
                    @endhasPermission

                    <hr class="my-3 text-light">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                           href="{{ route('profile.index') }}">
                            <i class="bi bi-person"></i>
                            {{ __('Profile') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('help') }}">
                            <i class="bi bi-question-circle"></i>
                            {{ __('Help') }}
                        </a>
                    </li>
                </ul>

                <!-- Language Switcher -->
                <div class="mt-4">
                    <div class="dropdown">
                        <button class="btn btn-outline-light btn-sm dropdown-toggle w-100"
                                type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-globe me-1"></i>
                            {{ strtoupper(app()->getLocale()) }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="changeLanguage('fr')">Français</a></li>
                            <li><a class="dropdown-item" href="#" onclick="changeLanguage('en')">English</a></li>
                            <li><a class="dropdown-item" href="#" onclick="changeLanguage('ar')">العربية</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Logout -->
                <div class="mt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm w-100">
                            <i class="bi bi-box-arrow-right me-1"></i>
                            {{ __('Logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    <!-- Mobile Toggle -->
                    <button class="btn btn-outline-secondary d-lg-none" type="button" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>

                    <!-- Page Title -->
                    <div class="navbar-brand mb-0 h1 ms-2">
                        @yield('page-title', 'Dashboard')
                    </div>

                    <!-- Right Side -->
                    <div class="d-flex align-items-center">
                        <!-- Search -->
                        <form class="d-flex me-3" action="{{ route('search') }}" method="GET">
                            <div class="input-group">
                                <input class="form-control form-control-sm" type="search"
                                       placeholder="{{ __('Search...') }}" name="q" value="{{ request('q') }}">
                                <button class="btn btn-outline-secondary btn-sm" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Notifications -->
                        <div class="dropdown me-2">
                            <button class="btn btn-outline-secondary btn-sm position-relative"
                                    type="button" id="notificationsDropdown" data-bs-toggle="dropdown">
                                <i class="bi bi-bell"></i>
                                @php
                                    $notificationService = app(\App\Services\NotificationService::class);
                                    $headerNotifications = $notificationService->getUserNotifications(auth()->user(), 5);
                                    $unreadCount = $notificationService->getUnreadCount(auth()->user());
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="width: 350px;">
                                <li><h6 class="dropdown-header">{{ __('Notifications') }}</h6></li>
                                @if(count($headerNotifications) > 0)
                                    @foreach($headerNotifications as $notification)
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <div class="d-flex justify-content-between">
                                                    <span>{{ $notification['title'] ?? $notification['message'] ?? 'Notification' }}</span>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}</small>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                @else
                                    <li><span class="dropdown-item-text text-muted">{{ __('No notifications') }}</span></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="#">{{ __('View all') }}</a></li>
                            </ul>
                        </div>

                        <!-- User Menu -->
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                    type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <i class="bi bi-person me-2"></i>{{ __('Profile') }}
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.settings') }}">
                                    <i class="bi bi-gear me-2"></i>{{ __('Settings') }}
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>{{ __('Logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

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
                <span class="visually-hidden">{{ __('Loading...') }}</span>
            </div>
            <p class="mt-2">{{ __('Loading...') }}</p>
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

        // Global Variables
        window.userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
        window.userRole = document.querySelector('meta[name="user-role"]').getAttribute('content');
        window.appLocale = document.querySelector('meta[name="app-locale"]').getAttribute('content');

        // Sidebar Toggle
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }

        // Language Switcher
        function changeLanguage(lang) {
            axios.post('/language', { language: lang })
                .then(() => window.location.reload())
                .catch(error => console.error('Language change failed:', error));
        }

        // Loading Overlay
        function showLoading() {
            document.getElementById('loading-overlay').style.display = 'flex';
        }

        function hideLoading() {
            document.getElementById('loading-overlay').style.display = 'none';
        }

        // Global AJAX Setup
        axios.interceptors.request.use(config => {
            showLoading();
            return config;
        });

        axios.interceptors.response.use(
            response => {
                hideLoading();
                return response;
            },
            error => {
                hideLoading();
                console.error('AJAX Error:', error);

                if (error.response?.status === 401) {
                    window.location.href = '/login';
                } else if (error.response?.status === 403) {
                    alert('{{ __("Access denied") }}');
                } else if (error.response?.status >= 500) {
                    alert('{{ __("Server error. Please try again.") }}');
                }

                return Promise.reject(error);
            }
        );


        // Form helpers
        function showSuccess(message) {
            const alert = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            document.querySelector('main').insertAdjacentHTML('afterbegin', alert);
        }

        function showError(message) {
            const alert = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            document.querySelector('main').insertAdjacentHTML('afterbegin', alert);
        }
    </script>

    @stack('scripts')
</body>
</html>