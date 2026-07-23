@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
@endphp

<!-- Vertical Sidebar Navigation -->
<aside class="sidebar" id="appSidebar">
    <!-- Brand + desktop collapse toggle -->
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="sidebar-brand text-decoration-none">
            <span class="brand-mark"><i class="bi bi-kanban-fill"></i></span>
            <span class="brand-name text-truncate">
                @if(auth()->user() && auth()->user()->organization)
                    {{ auth()->user()->organization->name }}
                @else
                    ProManage
                @endif
            </span>
        </a>
        <button type="button" class="sidebar-collapse-btn d-none d-lg-inline-flex" id="sidebarCollapse"
                aria-label="{{ __('app.Menu') ?? 'Menu' }}">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i><span>{{ __('app.Dashboard') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">
                <i class="bi bi-folder"></i><span>{{ __('app.nav.projects') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">
                <i class="bi bi-check2-square"></i><span>{{ __('app.nav.tasks') }}</span>
            </a>
        </li>
        @if(auth()->user()?->isSuperAdmin())
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('superadmin.plans.*') ? 'active' : '' }}" href="{{ route('superadmin.plans.index') }}">
                <i class="bi bi-credit-card"></i><span>{{ __('app.nav.plans') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('superadmin.landing.*') ? 'active' : '' }}" href="{{ route('superadmin.landing.index') }}">
                <i class="bi bi-window-stack"></i><span>{{ __('app.nav.landing') }}</span>
            </a>
        </li>
        @endif

        @if(auth()->user()?->isAdmin())
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('task-statuses.*') ? 'active' : '' }}" href="{{ route('task-statuses.index') }}">
                <i class="bi bi-tags"></i><span>{{ __('app.nav.task_statuses') }}</span>
            </a>
        </li>
        @endif
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}" href="{{ route('chat.index') }}">
                <i class="bi bi-chat-dots"></i><span>{{ __('app.nav.chat') }}</span>
                <span class="badge rounded-pill bg-danger ms-auto d-none" id="chatUnreadBadge"></span>
            </a>
        </li>

        @hasPermission('view.timetracking')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('timesheet.*') ? 'active' : '' }}" href="{{ route('timesheet.index') }}">
                <i class="bi bi-clock"></i><span>{{ __('app.nav.timesheet') }}</span>
            </a>
        </li>
        @endhasPermission

        @hasPermission('view.reports')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                <i class="bi bi-graph-up"></i><span>{{ __('app.nav.reports') }}</span>
            </a>
        </li>
        @endhasPermission

        @hasPermission('view.users')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="bi bi-people"></i><span>{{ __('app.User Management') }}</span>
            </a>
        </li>
        @endhasPermission

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('trash.*') ? 'active' : '' }}" href="{{ route('trash.index') }}">
                <i class="bi bi-trash"></i><span>{{ __('app.nav.trash') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.index') }}">
                <i class="bi bi-person"></i><span>{{ __('app.Profile') }}</span>
            </a>
        </li>
    </ul>

    <!-- Footer: language + logout -->
    <div class="sidebar-footer">
        @php
            // Map app locales to ISO country codes for real flag images (reliable cross-platform).
            $localeCountry = ['en' => 'us', 'fr' => 'fr', 'ar' => 'sa', 'es' => 'es'];
            $flag = fn ($code) => 'https://flagcdn.com/' . ($localeCountry[$code] ?? $code) . '.svg';
        @endphp
        <div class="dropdown dropup">
            <a class="nav-link dropdown-toggle text-white p-2" href="#" id="sidebarLanguageDropdown"
               data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('app.language') ?? 'Language' }}">
                <img src="{{ $flag(app()->getLocale()) }}" alt="{{ app()->getLocale() }}" class="lang-flag">
            </a>
            <ul class="dropdown-menu shadow" aria-labelledby="sidebarLanguageDropdown">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() == $localeCode ? 'active' : '' }}"
                           rel="alternate" hreflang="{{ $localeCode }}"
                           title="{{ $properties['native'] }}"
                           href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                            <img src="{{ $flag($localeCode) }}" alt="{{ $localeCode }}" class="lang-flag">
                            <span class="lang-name">{{ $properties['native'] }}</span>
                            @if(app()->getLocale() == $localeCode)
                                <i class="bi bi-check-circle-fill text-success ms-auto"></i>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm" title="{{ __('app.logout') }}">
                <i class="bi bi-box-arrow-right me-1"></i><span class="logout-text">{{ __('app.logout') }}</span>
            </button>
        </form>
    </div>
</aside>

<!-- Overlay for mobile drawer -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
