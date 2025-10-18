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
                <?php if(auth()->user() && auth()->user()->organization): ?>
                    <?php echo e(auth()->user()->organization->name); ?>

                <?php else: ?>
                    <?php echo e(__('app.organization')); ?>

                <?php endif; ?>
            </h6>
        </div>

        <!-- Navigation Menu -->
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>"
                   href="<?php echo e(route('dashboard')); ?>">
                    <i class="bi bi-speedometer2"></i>
                    <?php echo e(__('app.Dashboard')); ?>

                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('projects.*') ? 'active' : ''); ?>"
                   href="<?php echo e(route('projects.index')); ?>">
                    <i class="bi bi-folder"></i>
                    <?php echo e(__('app.nav.projects')); ?>

                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('tasks.*') ? 'active' : ''); ?>"
                   href="<?php echo e(route('tasks.index')); ?>">
                    <i class="bi bi-check2-square"></i>
                    <?php echo e(__('app.nav.tasks')); ?>

                </a>
            </li>

            <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'view.timetracking')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('timesheet.*') ? 'active' : ''); ?>"
                   href="<?php echo e(route('timesheet.index')); ?>">
                    <i class="bi bi-clock"></i>
                    <?php echo e(__('app.nav.timesheet')); ?>

                </a>
            </li>
            <?php endif; ?>

            <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'view.reports')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>"
                   href="<?php echo e(route('reports.index')); ?>">
                    <i class="bi bi-graph-up"></i>
                    <?php echo e(__('app.nav.reports')); ?>

                </a>
            </li>
            <?php endif; ?>

            <?php if (\Illuminate\Support\Facades\Blade::check('hasPermission', 'view.users')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>"
                   href="<?php echo e(route('users.index')); ?>">
                    <i class="bi bi-people"></i>
                    <?php echo e(__('app.User Management')); ?>

                </a>
            </li>
            <?php endif; ?>

            

            <hr class="my-3 text-light">

            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('profile.*') ? 'active' : ''); ?>"
                   href="<?php echo e(route('profile.index')); ?>">
                    <i class="bi bi-person"></i>
                    <?php echo e(__('app.Profile')); ?>

                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('help')); ?>">
                    <i class="bi bi-question-circle"></i>
                    <?php echo e(__('app.Help')); ?>

                </a>
            </li>

            <!-- Language Switcher -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="sidebarLanguageDropdown"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-globe"></i>
                    <?php echo e(__('app.preferred_language')); ?>

                </a>
                <ul class="dropdown-menu dropdown-menu-dark" style="margin-left: 1rem;">
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
            </li>
        </ul>

        <!-- Logout -->
        <div class="mt-auto">
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    <?php echo e(__('app.logout')); ?>

                </button>
            </form>
        </div>
    </div>
</nav><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/components/sidebar.blade.php ENDPATH**/ ?>