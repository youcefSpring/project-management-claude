<!-- Sidebar Navigation -->
<nav class="sidebar">
    <div class="p-3">
        <!-- Organization Name -->
        <div class="text-center mb-3">
            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                 style="width: 50px; height: 50px;">
                <i class="bi bi-building text-primary" style="font-size: 1.2rem;"></i>
            </div>
            <h6 class="mb-0 text-white text-center">
                @if(auth()->user() && auth()->user()->organization)
                    {{ auth()->user()->organization->name }}
                @else
                    {{ __('app.organization') }}
                @endif
            </h6>
        </div>

        <!-- Navigation Menu -->
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    {{ __('app.Dashboard') }}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}"
                   href="{{ route('projects.index') }}">
                    <i class="bi bi-folder"></i>
                    {{ __('app.nav.projects') }}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}"
                   href="{{ route('tasks.index') }}">
                    <i class="bi bi-check2-square"></i>
                    {{ __('app.nav.tasks') }}
                </a>
            </li>

            @hasPermission('view.timetracking')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('timesheet.*') ? 'active' : '' }}"
                   href="{{ route('timesheet.index') }}">
                    <i class="bi bi-clock"></i>
                    {{ __('app.nav.timesheet') }}
                </a>
            </li>
            @endhasPermission

            @hasPermission('view.reports')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                   href="{{ route('reports.index') }}">
                    <i class="bi bi-graph-up"></i>
                    {{ __('app.nav.reports') }}
                </a>
            </li>
            @endhasPermission

            @hasPermission('view.users')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                   href="{{ route('users.index') }}">
                    <i class="bi bi-people"></i>
                    {{ __('app.User Management') }}
                </a>
            </li>
            @endhasPermission

            {{-- @hasPermission('access.admin.dashboard')
            <hr class="my-3 text-light">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}"
                   href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-gear"></i>
                    {{ __('app.Administration') }}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('translations*') ? 'active' : '' }}"
                   href="/translations">
                    <i class="bi bi-translate"></i>
                    {{ __('app.Translations') }}
                </a>
            </li>
            @endhasPermission --}}

            <hr class="my-3 text-light">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                   href="{{ route('profile.index') }}">
                    <i class="bi bi-person"></i>
                    {{ __('app.Profile') }}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('help') }}">
                    <i class="bi bi-question-circle"></i>
                    {{ __('app.Help') }}
                </a>
            </li>

            <!-- Language Switcher -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="sidebarLanguageDropdown"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-globe"></i>
                    {{ __('app.preferred_language') }}
                </a>
                <ul class="dropdown-menu dropdown-menu-dark" style="margin-left: 1rem;">
                    @php
                        use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
                    @endphp
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() == $localeCode ? 'active' : '' }}"
                               rel="alternate"
                               hreflang="{{ $localeCode }}"
                               href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-flag me-2"></i>
                                    <span class="flex-grow-1">{{ $properties['native'] }}</span>
                                    @if(app()->getLocale() == $localeCode)
                                        <i class="bi bi-check-circle-fill text-success ms-2"></i>
                                    @endif
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        </ul>

        <!-- Logout -->
        <div class="mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    {{ __('app.logout') }}
                </button>
            </form>
        </div>
    </div>
</nav>