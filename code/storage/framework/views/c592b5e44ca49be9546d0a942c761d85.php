<!-- Navigation Header -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
            <i class="bi bi-mortarboard me-2"></i>
            <?php echo e(config('app.name', 'Teacher Portfolio')); ?>

        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>" href="<?php echo e(route('home')); ?>">
                        <i class="bi bi-house me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('about') ? 'active' : ''); ?>" href="<?php echo e(route('about')); ?>">
                        <i class="bi bi-person me-1"></i>About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('courses.*') ? 'active' : ''); ?>" href="<?php echo e(route('courses.index')); ?>">
                        <i class="bi bi-book me-1"></i>Courses
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('projects.*') ? 'active' : ''); ?>" href="<?php echo e(route('projects.index')); ?>">
                        <i class="bi bi-code-slash me-1"></i>Projects
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('publications.*') ? 'active' : ''); ?>" href="<?php echo e(route('publications.index')); ?>">
                        <i class="bi bi-journal-text me-1"></i>Publications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('blog.*') ? 'active' : ''); ?>" href="<?php echo e(route('blog.index')); ?>">
                        <i class="bi bi-pencil-square me-1"></i>Blog
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('contact.*') ? 'active' : ''); ?>" href="<?php echo e(route('contact.show')); ?>">
                        <i class="bi bi-envelope me-1"></i>Contact
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <?php if(auth()->guard()->check()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i><?php echo e(Auth::user()->name); ?>

                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo e(route('admin.dashboard')); ?>">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('login')); ?>">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('download-cv')); ?>">
                        <i class="bi bi-download me-1"></i>Download CV
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/partials/header.blade.php ENDPATH**/ ?>