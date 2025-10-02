<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">
                    Welcome to My Academic Portfolio
                </h1>
                <p class="lead mb-4">
                    <?php echo e($teacher->bio ?? 'Dedicated educator and researcher passionate about sharing knowledge and advancing understanding in my field through innovative teaching methods and cutting-edge research.'); ?>

                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="<?php echo e(route('about')); ?>" class="btn btn-light btn-lg">
                        <i class="bi bi-person me-2"></i>Learn More About Me
                    </a>
                    <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-book me-2"></i>View My Courses
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <?php if($teacher->avatar ?? false): ?>
                    <img src="<?php echo e(Storage::url($teacher->avatar)); ?>"
                         alt="<?php echo e($teacher->name ?? 'Teacher'); ?>"
                         class="img-fluid rounded-circle shadow-lg"
                         style="max-width: 300px; max-height: 300px; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-white rounded-circle mx-auto shadow-lg d-flex align-items-center justify-content-center"
                         style="width: 250px; height: 250px;">
                        <i class="bi bi-person-circle text-primary" style="font-size: 8rem;"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Quick Stats -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-book text-primary" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1"><?php echo e($coursesCount ?? 0); ?></h3>
                        <p class="text-muted mb-0">Courses Taught</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-journal-text text-success" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1"><?php echo e($publicationsCount ?? 0); ?></h3>
                        <p class="text-muted mb-0">Publications</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-code-slash text-info" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1"><?php echo e($projectsCount ?? 0); ?></h3>
                        <p class="text-muted mb-0">Research Projects</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-pencil-square text-warning" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1"><?php echo e($blogPostsCount ?? 0); ?></h3>
                        <p class="text-muted mb-0">Blog Posts</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Content -->
<?php if(isset($featuredCourses) && $featuredCourses->count() > 0): ?>
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-center mb-4">Featured Courses</h2>
            </div>
        </div>
        <div class="row">
            <?php $__currentLoopData = $featuredCourses->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <?php if($course->image): ?>
                        <img src="<?php echo e(Storage::url($course->image)); ?>"
                             class="card-img-top"
                             alt="<?php echo e($course->title); ?>"
                             style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-primary"><?php echo e($course->course_code ?? 'Course'); ?></span>
                            <small class="text-muted"><?php echo e($course->credits ?? 3); ?> Credits</small>
                        </div>
                        <h5 class="card-title"><?php echo e($course->title); ?></h5>
                        <p class="card-text flex-grow-1"><?php echo e(Str::limit($course->description, 150)); ?></p>
                        <a href="<?php echo e(route('courses.show', $course->slug)); ?>" class="btn btn-primary">
                            Learn More <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="text-center">
            <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-outline-primary">
                View All Courses <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Recent Publications -->
<?php if(isset($recentPublications) && $recentPublications->count() > 0): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-center mb-4">Recent Publications</h2>
            </div>
        </div>
        <div class="row">
            <?php $__currentLoopData = $recentPublications->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $publication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-success"><?php echo e(ucfirst($publication->type ?? 'Article')); ?></span>
                            <small class="text-muted"><?php echo e($publication->published_date?->format('Y') ?? 'Recent'); ?></small>
                        </div>
                        <h5 class="card-title"><?php echo e($publication->title); ?></h5>
                        <?php if($publication->journal): ?>
                            <p class="text-muted mb-2"><em><?php echo e($publication->journal); ?></em></p>
                        <?php endif; ?>
                        <p class="card-text"><?php echo e(Str::limit($publication->abstract ?? $publication->description, 200)); ?></p>
                        <a href="<?php echo e(route('publications.show', $publication->slug)); ?>" class="btn btn-outline-success">
                            Read More <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="text-center">
            <a href="<?php echo e(route('publications.index')); ?>" class="btn btn-outline-success">
                View All Publications <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Recent Projects -->
<?php if(isset($recentProjects) && $recentProjects->count() > 0): ?>
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-center mb-4">Recent Projects</h2>
            </div>
        </div>
        <div class="row">
            <?php $__currentLoopData = $recentProjects->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <?php if($project->featured_image): ?>
                        <img src="<?php echo e(Storage::url($project->featured_image)); ?>"
                             class="card-img-top"
                             alt="<?php echo e($project->title); ?>"
                             style="height: 180px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <?php if($project->status === 'active'): ?>
                                <span class="badge bg-success">Active</span>
                            <?php elseif($project->status === 'completed'): ?>
                                <span class="badge bg-primary">Completed</span>
                            <?php endif; ?>
                            <?php if($project->start_date): ?>
                                <small class="text-muted"><?php echo e($project->start_date->format('Y')); ?></small>
                            <?php endif; ?>
                        </div>
                        <h5 class="card-title"><?php echo e($project->title); ?></h5>
                        <p class="card-text flex-grow-1"><?php echo e(Str::limit($project->description, 120)); ?></p>
                        <a href="<?php echo e(route('projects.show', $project->slug)); ?>" class="btn btn-info text-white">
                            View Project <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="text-center">
            <a href="<?php echo e(route('projects.index')); ?>" class="btn btn-outline-info">
                View All Projects <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Recent Blog Posts -->
<?php if(isset($recentBlogPosts) && $recentBlogPosts->count() > 0): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-center mb-4">Latest from the Blog</h2>
            </div>
        </div>
        <div class="row">
            <?php $__currentLoopData = $recentBlogPosts->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <?php if($post->featured_image): ?>
                        <img src="<?php echo e(Storage::url($post->featured_image)); ?>"
                             class="card-img-top"
                             alt="<?php echo e($post->title); ?>"
                             style="height: 180px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <small class="text-muted mb-2">
                            <i class="bi bi-calendar me-1"></i>
                            <?php echo e($post->published_at?->format('M d, Y') ?? $post->created_at->format('M d, Y')); ?>

                        </small>
                        <h5 class="card-title"><?php echo e($post->title); ?></h5>
                        <p class="card-text flex-grow-1">
                            <?php echo e($post->excerpt ?? Str::limit(strip_tags($post->content), 120)); ?>

                        </p>
                        <a href="<?php echo e(route('blog.show', $post->slug)); ?>" class="btn btn-warning text-white">
                            Read More <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="text-center">
            <a href="<?php echo e(route('blog.index')); ?>" class="btn btn-outline-warning">
                View All Posts <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Contact CTA -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="mb-4">Get in Touch</h2>
                <p class="lead mb-4">
                    Interested in collaboration, have questions about my research, or want to discuss academic opportunities?
                    I'd love to hear from you.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="<?php echo e(route('contact.show')); ?>" class="btn btn-light btn-lg">
                        <i class="bi bi-envelope me-2"></i>Contact Me
                    </a>
                    <a href="<?php echo e(route('download-cv')); ?>" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-download me-2"></i>Download CV
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/public/home.blade.php ENDPATH**/ ?>