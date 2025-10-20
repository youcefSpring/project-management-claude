<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Teacher Portfolio')); ?> <?php if (! empty(trim($__env->yieldContent('title')))): ?> - <?php echo $__env->yieldContent('title'); ?> <?php endif; ?></title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'Academic portfolio showcasing teaching, research, and professional achievements.'); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta_keywords', 'education, research, teaching, academic portfolio'); ?>">
    <meta name="author" content="<?php echo e(config('app.name', 'Teacher Portfolio')); ?>">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo e(config('app.name', 'Teacher Portfolio')); ?> <?php if (! empty(trim($__env->yieldContent('title')))): ?> - <?php echo $__env->yieldContent('title'); ?> <?php endif; ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('meta_description', 'Academic portfolio showcasing teaching, research, and professional achievements.'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(request()->url()); ?>">
    <?php if (! empty(trim($__env->yieldContent('og_image')))): ?>
        <meta property="og:image" content="<?php echo $__env->yieldContent('og_image'); ?>">
    <?php endif; ?>

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo e(config('app.name', 'Teacher Portfolio')); ?> <?php if (! empty(trim($__env->yieldContent('title')))): ?> - <?php echo $__env->yieldContent('title'); ?> <?php endif; ?>">
    <meta name="twitter:description" content="<?php echo $__env->yieldContent('meta_description', 'Academic portfolio showcasing teaching, research, and professional achievements.'); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --bs-primary: #2563eb;
            --bs-secondary: #64748b;
            --bs-success: #059669;
            --bs-info: #0891b2;
            --bs-warning: #d97706;
            --bs-danger: #dc2626;
        }

        body {
            font-family: 'Figtree', sans-serif;
            line-height: 1.6;
        }

        .navbar-brand {
            font-weight: 600;
        }

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
            transition: all 0.15s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }

        footer {
            background-color: #1f2937;
        }

        .text-muted {
            color: #6b7280 !important;
        }

        /* Academic specific styles */
        .citation {
            background-color: #f8f9fa;
            border-left: 4px solid var(--bs-primary);
            padding: 1rem;
            font-family: 'Georgia', serif;
            font-style: italic;
        }

        .badge-academic {
            background-color: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .academic-card {
            border-left: 4px solid var(--bs-primary);
        }

        .research-highlight {
            background: linear-gradient(45deg, #f8fafc, #e2e8f0);
            border-radius: 0.5rem;
            padding: 2rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-section {
                padding: 2rem 0;
            }

            .hero-section h1 {
                font-size: 2rem !important;
            }
        }

        /* Animation for cards */
        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--bs-primary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #1d4ed8;
        }
    </style>

    <?php echo $__env->yieldContent('styles'); ?>
</head>

<body class="bg-light">
    <!-- Navigation Header -->
    <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Breadcrumbs -->
    <?php echo $__env->make('partials.breadcrumbs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Flash Messages -->
    <?php echo $__env->make('partials.flash-messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Main Content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add fade-in animation to cards
            const cards = document.querySelectorAll('.card');
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in');
                    }
                });
            }, observerOptions);

            cards.forEach(card => {
                observer.observe(card);
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialize popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });

        // Global function for showing loading states
        function showLoading(element) {
            const originalContent = element.innerHTML;
            element.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Loading...';
            element.disabled = true;
            return originalContent;
        }

        function hideLoading(element, originalContent) {
            element.innerHTML = originalContent;
            element.disabled = false;
        }

        // Global function for handling form submissions
        function handleFormSubmit(form, button) {
            const originalContent = showLoading(button);

            // Simulate form processing (replace with actual AJAX if needed)
            setTimeout(function() {
                hideLoading(button, originalContent);
            }, 2000);
        }
    </script>

    <?php echo $__env->yieldContent('scripts'); ?>

    <!-- Schema.org structured data -->
    <?php if (! empty(trim($__env->yieldContent('structured_data')))): ?>
        <script type="application/ld+json">
            <?php echo $__env->yieldContent('structured_data'); ?>
        </script>
    <?php endif; ?>
</body>
</html><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/public/layouts/app.blade.php ENDPATH**/ ?>