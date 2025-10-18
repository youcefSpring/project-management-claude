<?php
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
?>

<!-- Top Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <!-- Mobile Toggle -->
        <button class="btn btn-outline-secondary d-lg-none" type="button" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>

        <!-- Page Title -->
        <div class="navbar-brand mb-0 h1 ms-2">
            <?php echo $__env->yieldContent('page-title', __('app.Dashboard')); ?>
        </div>

        <!-- Right Side -->
        <div class="d-flex align-items-center">
            <!-- Search -->
            <form class="d-flex me-3" action="<?php echo e(route('search.results')); ?>" method="GET">
                <div class="input-group">
                    <input class="form-control form-control-sm" type="search"
                           placeholder="<?php echo e(__('app.Search...')); ?>" name="q" value="<?php echo e(request('q')); ?>">
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
                    <?php
                        $notificationService = app(\App\Services\NotificationService::class);
                        $user = auth()->user();
                        $headerNotifications = $user ? $notificationService->getUserNotifications($user, 5) : collect();
                        $unreadCount = $user ? $notificationService->getUnreadCount($user) : 0;
                    ?>
                    <?php if($unreadCount > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo e($unreadCount); ?>

                        </span>
                    <?php endif; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="width: 350px; max-height: 400px; overflow-y: auto;">
                    <li><h6 class="dropdown-header d-flex align-items-center">
                        <i class="bi bi-bell me-2"></i><?php echo e(__('app.Notifications')); ?>

                    </h6></li>
                    <?php if(count($headerNotifications) > 0): ?>
                        <?php $__currentLoopData = $headerNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a class="dropdown-item notification-item" href="#">
                                    <div class="notification-content">
                                        <div class="notification-title">
                                            <?php echo e($notification['title'] ?? $notification['message'] ?? 'Notification'); ?>

                                        </div>
                                        <div class="notification-time">
                                            <?php echo e(\Carbon\Carbon::parse($notification['created_at'])->diffForHumans()); ?>

                                        </div>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <li>
                            <div class="dropdown-item-text text-muted text-center py-3">
                                <i class="bi bi-bell-slash mb-2 d-block" style="font-size: 1.5rem;"></i>
                                <?php echo e(__('app.No notifications')); ?>

                            </div>
                        </li>
                    <?php endif; ?>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-center fw-bold" href="#">
                            <i class="bi bi-arrow-right me-2"></i><?php echo e(__('app.View all')); ?>

                        </a>
                    </li>
                </ul>
            </div>

            <!-- Language Switcher -->
            <div class="dropdown me-2">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                        type="button" data-bs-toggle="dropdown"
                        title="<?php echo e(__('app.preferred_language')); ?>">
                    <i class="bi bi-globe me-1"></i>
                    <span class="d-none d-sm-inline"><?php echo e(LaravelLocalization::getCurrentLocaleName()); ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end language-dropdown">
                    <li><h6 class="dropdown-header d-flex align-items-center">
                        <i class="bi bi-translate me-2"></i><?php echo e(__('app.preferred_language')); ?>

                    </h6></li>
                    <?php $__currentLoopData = LaravelLocalization::getSupportedLocales(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $localeCode => $properties): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a class="dropdown-item <?php echo e(app()->getLocale() == $localeCode ? 'active' : ''); ?>"
                               rel="alternate"
                               hreflang="<?php echo e($localeCode); ?>"
                               href="<?php echo e(LaravelLocalization::getLocalizedURL($localeCode, null, [], true)); ?>">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-flag me-2"></i>
                                    <span class="flex-grow-1"><?php echo e($properties['native']); ?></span>
                                    <?php if(app()->getLocale() == $localeCode): ?>
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>

            <!-- User Menu -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                        type="button" data-bs-toggle="dropdown"
                        title="<?php echo e(auth()->user()->name ?? __('app.Profile')); ?>">
                    <i class="bi bi-person-circle me-1"></i>
                    <span class="d-none d-md-inline"><?php echo e(auth()->user()->name ?? __('app.Profile')); ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header d-flex align-items-center">
                        <i class="bi bi-person-circle me-2"></i>
                        <div>
                            <div class="fw-bold"><?php echo e(auth()->user()->name ?? __('app.user_label')); ?></div>
                            <small class="text-muted"><?php echo e(auth()->user()->email ?? ''); ?></small>
                        </div>
                    </h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?php echo e(route('profile.index')); ?>">
                        <i class="bi bi-person me-2"></i><?php echo e(__('app.Profile')); ?>

                    </a></li>
                    <li><a class="dropdown-item" href="<?php echo e(route('profile.settings')); ?>">
                        <i class="bi bi-gear me-2"></i><?php echo e(__('app.Settings')); ?>

                    </a></li>
                    <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'view.reports')): ?>
                    <li><a class="dropdown-item" href="<?php echo e(route('reports.index')); ?>">
                        <i class="bi bi-graph-up me-2"></i><?php echo e(__('app.Reports')); ?>

                    </a></li>
                    <?php endif; ?>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i><?php echo e(__('app.Logout')); ?>

                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/components/navbar.blade.php ENDPATH**/ ?>