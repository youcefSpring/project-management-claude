<!-- Horizontal Sidebar Navigation -->
<nav class="sidebar d-flex flex-column flex-md-row align-items-center p-3 shadow-sm">
    <!-- Organization Name / Brand -->
    <div class="d-flex align-items-center mb-2 mb-md-0 me-md-auto text-white text-decoration-none">
        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-2"
             style="width: 40px; height: 40px; min-width: 40px;">
            <i class="bi bi-building text-primary" style="font-size: 1.2rem;"></i>
        </div>
        <h6 class="mb-0 text-white fw-bold text-truncate" style="max-width: 200px;">
            @if(auth()->user() && auth()->user()->organization)
                {{ auth()->user()->organization->name }}
            @else
                {{ __('app.organization') }}
            @endif
        </h6>
    </div>

    <!-- Navigation Menu -->
    <ul class="nav nav-pills flex-row justify-content-center flex-wrap gap-1 my-2 my-md-0 mx-md-3">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}" title="{{ __('app.Dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span class="d-none d-lg-inline">{{ __('app.Dashboard') }}</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}"
               href="{{ route('projects.index') }}" title="{{ __('app.nav.projects') }}">
                <i class="bi bi-folder"></i>
                <span class="d-none d-lg-inline">{{ __('app.nav.projects') }}</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}"
               href="{{ route('tasks.index') }}" title="{{ __('app.nav.tasks') }}">
                <i class="bi bi-check2-square"></i>
                <span class="d-none d-lg-inline">{{ __('app.nav.tasks') }}</span>
            </a>
        </li>

        @hasPermission('view.timetracking')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('timesheet.*') ? 'active' : '' }}"
               href="{{ route('timesheet.index') }}" title="{{ __('app.nav.timesheet') }}">
                <i class="bi bi-clock"></i>
                <span class="d-none d-lg-inline">{{ __('app.nav.timesheet') }}</span>
            </a>
        </li>
        @endhasPermission

        @hasPermission('view.reports')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
               href="{{ route('reports.index') }}" title="{{ __('app.nav.reports') }}">
                <i class="bi bi-graph-up"></i>
                <span class="d-none d-lg-inline">{{ __('app.nav.reports') }}</span>
            </a>
        </li>
        @endhasPermission

        @hasPermission('view.users')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
               href="{{ route('users.index') }}" title="{{ __('app.User Management') }}">
                <i class="bi bi-people"></i>
                <span class="d-none d-lg-inline">{{ __('app.User Management') }}</span>
            </a>
        </li>
        @endhasPermission

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
               href="{{ route('profile.index') }}" title="{{ __('app.Profile') }}">
                <i class="bi bi-person"></i>
                <span class="d-none d-lg-inline">{{ __('app.Profile') }}</span>
            </a>
        </li>
    </ul>

    <!-- Right Side Actions -->
    <div class="d-flex align-items-center ms-md-auto">
        <!-- Language Switcher -->
        <div class="dropdown me-2">
            <a class="nav-link dropdown-toggle text-white" href="#" id="sidebarLanguageDropdown"
               data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-globe"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
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
        </div>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm" title="{{ __('app.logout') }}">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
</nav>