<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'Teacher Portfolio') }} @hasSection('title') - @yield('title') @endif</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Admin Custom CSS -->
    <style>
        :root {
            --admin-primary: #1e40af;
            --admin-secondary: #64748b;
            --admin-sidebar: #1f2937;
            --admin-sidebar-hover: #374151;
        }

        body {
            font-family: 'Figtree', sans-serif;
        }

        .admin-sidebar {
            background-color: var(--admin-sidebar);
            min-height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s;
        }

        .admin-sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid #374151;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-link {
            color: #d1d5db !important;
            padding: 0.75rem 1rem;
            margin: 0.125rem 0.5rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: var(--admin-sidebar-hover);
            color: white !important;
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .admin-content {
            margin-left: 250px;
            min-height: 100vh;
            background-color: #f8fafc;
            transition: all 0.3s;
        }

        .admin-content.expanded {
            margin-left: 80px;
        }

        .admin-header {
            background: white;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 2rem;
        }

        .content-wrapper {
            padding: 0 2rem 2rem;
        }

        .stats-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }

        .stats-card .stats-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--admin-primary);
        }

        .table th {
            background-color: #f8fafc;
            border-bottom: 2px solid #e5e7eb;
            font-weight: 600;
            color: #374151;
        }

        .btn-primary {
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
        }

        .btn-primary:hover {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
        }

        .alert {
            border: none;
            border-radius: 0.5rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25);
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                width: 80px;
            }

            .admin-content {
                margin-left: 80px;
            }

            .nav-link span {
                display: none;
            }
        }
    </style>

    @yield('styles')
</head>

<body>
    <!-- Admin Sidebar -->
    <div class="admin-sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center text-white">
                <i class="bi bi-mortarboard fs-4 me-2"></i>
                <span class="fw-bold" id="sidebar-title">Admin Panel</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.courses.index') }}" class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                <i class="bi bi-book"></i>
                <span>Courses</span>
            </a>

            <a href="{{ route('admin.projects.index') }}" class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                <i class="bi bi-code-slash"></i>
                <span>Projects</span>
            </a>

            <a href="{{ route('admin.publications.index') }}" class="nav-link {{ request()->routeIs('admin.publications.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i>
                <span>Publications</span>
            </a>

            <a href="{{ route('admin.blog.index') }}" class="nav-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                <i class="bi bi-pencil-square"></i>
                <span>Blog Posts</span>
            </a>

            <a href="{{ route('admin.tags.index') }}" class="nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i>
                <span>Tags</span>
            </a>

            <a href="{{ route('admin.contact.index') }}" class="nav-link {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
                <i class="bi bi-envelope"></i>
                <span>Messages</span>
            </a>

            <hr class="my-3 mx-3 border-secondary">

            <a href="{{ route('admin.profile.edit') }}" class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i>
                <span>Profile</span>
            </a>

            <a href="{{ route('home') }}" class="nav-link">
                <i class="bi bi-eye"></i>
                <span>View Site</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </nav>
    </div>

    <!-- Admin Content -->
    <div class="admin-content" id="content">
        <!-- Admin Header -->
        <div class="admin-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="btn btn-link text-secondary p-0 me-3" id="sidebar-toggle">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted me-3">Welcome, {{ Auth::user()->name }}</span>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                                <i class="bi bi-person-gear me-2"></i>Profile Settings
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('home') }}">
                                <i class="bi bi-eye me-2"></i>View Site
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <div class="content-wrapper">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Main Content -->
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Admin JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const sidebarTitle = document.getElementById('sidebar-title');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('expanded');

                if (sidebar.classList.contains('collapsed')) {
                    sidebarTitle.style.display = 'none';
                } else {
                    sidebarTitle.style.display = 'inline';
                }
            });

            // Auto-dismiss alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert && alert.classList.contains('show')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            });

            // Confirm delete actions
            const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>

    @yield('scripts')
</body>
</html>