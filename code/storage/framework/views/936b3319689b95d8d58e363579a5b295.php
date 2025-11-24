<!-- Horizontal Sidebar Navigation -->
<nav class="sidebar d-flex flex-column flex-md-row align-items-center p-3 shadow-sm">
    <!-- Organization Name / Brand -->
    <div class="d-flex align-items-center mb-2 mb-md-0 me-md-auto text-white text-decoration-none">
        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-2"
             style="width: 40px; height: 40px; min-width: 40px;">
            <i class="bi bi-building text-primary" style="font-size: 1.2rem;"></i>
        </div>
        <h6 class="mb-0 text-white fw-bold text-truncate" style="max-width: 200px;">
            <?php if(auth()->user() && auth()->user()->organization): ?>
                <?php echo e(auth()->user()->organization->name); ?>

            <?php else: ?>
                <?php echo e(__('app.organization')); ?>

            <?php endif; ?>
        </h6>
    </div>

    <!-- Navigation Menu -->
    <ul class="nav nav-pills flex-row justify-content-center flex-wrap gap-1 my-2 my-md-0 mx-md-3">
        <li class="nav-item">
            <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>"
               href="<?php echo e(route('dashboard')); ?>" title="<?php echo e(__('app.Dashboard')); ?>">
                <i class="bi bi-speedometer2"></i>
                <span class="d-none d-lg-inline"><?php echo e(__('app.Dashboard')); ?></span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo e(request()->routeIs('projects.*') ? 'active' : ''); ?>"
               href="<?php echo e(route('projects.index')); ?>" title="<?php echo e(__('app.nav.projects')); ?>">
                <i class="bi bi-folder"></i>
                <span class="d-none d-lg-inline"><?php echo e(__('app.nav.projects')); ?></span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo e(request()->routeIs('tasks.*') ? 'active' : ''); ?>"
               href="<?php echo e(route('tasks.index')); ?>" title="<?php echo e(__('app.nav.tasks')); ?>">
                <i class="bi bi-check2-square"></i>
                <span class="d-none d-lg-inline"><?php echo e(__('app.nav.tasks')); ?></span>
            </a>
        </li>

        <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'view.timetracking')): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e(request()->routeIs('timesheet.*') ? 'active' : ''); ?>"
               href="<?php echo e(route('timesheet.index')); ?>" title="<?php echo e(__('app.nav.timesheet')); ?>">
                <i class="bi bi-clock"></i>
                <span class="d-none d-lg-inline"><?php echo e(__('app.nav.timesheet')); ?></span>
            </a>
        </li>
        <?php endif; ?>

        <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'view.reports')): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>"
               href="<?php echo e(route('reports.index')); ?>" title="<?php echo e(__('app.nav.reports')); ?>">
                <i class="bi bi-graph-up"></i>
                <span class="d-none d-lg-inline"><?php echo e(__('app.nav.reports')); ?></span>
            </a>
        </li>
        <?php endif; ?>

        <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'view.users')): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>"
               href="<?php echo e(route('users.index')); ?>" title="<?php echo e(__('app.User Management')); ?>">
                <i class="bi bi-people"></i>
                <span class="d-none d-lg-inline"><?php echo e(__('app.User Management')); ?></span>
            </a>
        </li>
        <?php endif; ?>

        <li class="nav-item">
            <a class="nav-link <?php echo e(request()->routeIs('profile.*') ? 'active' : ''); ?>"
               href="<?php echo e(route('profile.index')); ?>" title="<?php echo e(__('app.Profile')); ?>">
                <i class="bi bi-person"></i>
                <span class="d-none d-lg-inline"><?php echo e(__('app.Profile')); ?></span>
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
                <?php
                    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
                ?>
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
                                    <i class="bi bi-check-circle-fill text-success ms-2"></i>
                                <?php endif; ?>
                            </div>
                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>

        <!-- Logout -->
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-outline-light btn-sm" title="<?php echo e(__('app.logout')); ?>">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
</nav><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/components/sidebar.blade.php ENDPATH**/ ?>