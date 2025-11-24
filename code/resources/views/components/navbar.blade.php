@php
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
@endphp

<!-- Top Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <!-- Mobile Toggle - Removed for horizontal layout -->
        {{-- <button class="btn btn-outline-secondary d-lg-none" type="button" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button> --}}

        <!-- Page Title -->
        <div class="navbar-brand mb-0 h1 ms-2">
            @yield('page-title', __('app.Dashboard'))
        </div>

        <!-- Right Side -->
        <div class="d-flex align-items-center">
            <!-- Search -->
            <form class="d-flex me-3" action="{{ route('search.results') }}" method="GET">
                <div class="input-group">
                    <input class="form-control form-control-sm" type="search"
                           placeholder="{{ __('app.Search...') }}" name="q" value="{{ request('q') }}">
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
                        $user = auth()->user();
                        $headerNotifications = $user ? $notificationService->getUserNotifications($user, 5) : collect();
                        $unreadCount = $user ? $notificationService->getUnreadCount($user) : 0;
                    @endphp
                    @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="width: 350px; max-height: 400px; overflow-y: auto;">
                    <li><h6 class="dropdown-header d-flex align-items-center">
                        <i class="bi bi-bell me-2"></i>{{ __('app.Notifications') }}
                    </h6></li>
                    @if(count($headerNotifications) > 0)
                        @foreach($headerNotifications as $notification)
                            <li>
                                <a class="dropdown-item notification-item" href="#">
                                    <div class="notification-content">
                                        <div class="notification-title">
                                            {{ $notification['title'] ?? $notification['message'] ?? 'Notification' }}
                                        </div>
                                        <div class="notification-time">
                                            {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li>
                            <div class="dropdown-item-text text-muted text-center py-3">
                                <i class="bi bi-bell-slash mb-2 d-block" style="font-size: 1.5rem;"></i>
                                {{ __('app.No notifications') }}
                            </div>
                        </li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-center fw-bold" href="#">
                            <i class="bi bi-arrow-right me-2"></i>{{ __('app.View all') }}
                        </a>
                    </li>
                </ul>
            </div>


            <!-- User Menu -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                        type="button" data-bs-toggle="dropdown"
                        title="{{ auth()->user()->name ?? __('app.Profile') }}">
                    <i class="bi bi-person-circle me-1"></i>
                    <span class="d-none d-md-inline">{{ auth()->user()->name ?? __('app.Profile') }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header d-flex align-items-center">
                        <i class="bi bi-person-circle me-2"></i>
                        <div>
                            <div class="fw-bold">{{ auth()->user()->name ?? __('app.user_label') }}</div>
                            <small class="text-muted">{{ auth()->user()->email ?? '' }}</small>
                        </div>
                    </h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('profile.index') }}">
                        <i class="bi bi-person me-2"></i>{{ __('app.Profile') }}
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('profile.settings') }}">
                        <i class="bi bi-gear me-2"></i>{{ __('app.Settings') }}
                    </a></li>
                    @hasPermission('view.reports')
                    <li><a class="dropdown-item" href="{{ route('reports.index') }}">
                        <i class="bi bi-graph-up me-2"></i>{{ __('app.Reports') }}
                    </a></li>
                    @endhasPermission
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>{{ __('app.Logout') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>