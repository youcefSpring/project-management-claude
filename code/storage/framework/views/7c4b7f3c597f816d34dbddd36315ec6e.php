<?php $__env->startSection('title', 'Courses'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">Courses & Teaching</h1>
                <p class="lead mb-4">
                    Explore the courses I teach and my approach to education. From foundational concepts to advanced topics,
                    I strive to create engaging and meaningful learning experiences for all students.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Search and Filters -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form method="GET" action="<?php echo e(route('courses.index')); ?>" class="row g-3 align-items-end">
                    <!-- Search -->
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search Courses</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search"
                                   value="<?php echo e(request('search')); ?>" placeholder="Search courses, descriptions...">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Level Filter -->
                    <div class="col-md-2">
                        <label for="level" class="form-label">Level</label>
                        <select class="form-select" id="level" name="level">
                            <option value="">All Levels</option>
                            <option value="undergraduate" <?php echo e(request('level') == 'undergraduate' ? 'selected' : ''); ?>>
                                Undergraduate
                            </option>
                            <option value="graduate" <?php echo e(request('level') == 'graduate' ? 'selected' : ''); ?>>
                                Graduate
                            </option>
                            <option value="doctoral" <?php echo e(request('level') == 'doctoral' ? 'selected' : ''); ?>>
                                Doctoral
                            </option>
                        </select>
                    </div>

                    <!-- Semester Filter -->
                    <div class="col-md-2">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-select" id="semester" name="semester">
                            <option value="">All Semesters</option>
                            <option value="fall" <?php echo e(request('semester') == 'fall' ? 'selected' : ''); ?>>Fall</option>
                            <option value="spring" <?php echo e(request('semester') == 'spring' ? 'selected' : ''); ?>>Spring</option>
                            <option value="summer" <?php echo e(request('semester') == 'summer' ? 'selected' : ''); ?>>Summer</option>
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div class="col-md-2">
                        <label for="sort" class="form-label">Sort By</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="code" <?php echo e(request('sort') == 'code' || !request('sort') ? 'selected' : ''); ?>>
                                Course Code
                            </option>
                            <option value="title" <?php echo e(request('sort') == 'title' ? 'selected' : ''); ?>>
                                Title A-Z
                            </option>
                            <option value="credits" <?php echo e(request('sort') == 'credits' ? 'selected' : ''); ?>>
                                Credits
                            </option>
                            <option value="level" <?php echo e(request('sort') == 'level' ? 'selected' : ''); ?>>
                                Level
                            </option>
                        </select>
                    </div>

                    <!-- Filter Actions -->
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Courses List -->
<section class="py-5 bg-white">
    <div class="container">
        <?php if(isset($courses) && $courses->count() > 0): ?>
            <!-- Results Summary -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Courses</h3>
                        <span class="text-muted">
                            <?php echo e($courses->total()); ?> <?php echo e(Str::plural('course', $courses->total())); ?> found
                            <?php if(request('search')): ?>
                                for "<?php echo e(request('search')); ?>"
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Courses Grid -->
            <div class="row">
                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <?php if($course->image): ?>
                            <img src="<?php echo e(Storage::url($course->image)); ?>"
                                 class="card-img-top" alt="<?php echo e($course->title); ?>"
                                 style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center text-white"
                                 style="height: 200px;">
                                <div class="text-center">
                                    <i class="bi bi-book" style="font-size: 3rem;"></i>
                                    <h4 class="mt-2"><?php echo e($course->course_code ?? 'COURSE'); ?></h4>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <!-- Course Meta -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge bg-primary"><?php echo e($course->course_code ?? 'COURSE 101'); ?></span>
                                    <?php if($course->level): ?>
                                        <span class="badge bg-secondary ms-1"><?php echo e(ucfirst($course->level)); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block"><?php echo e($course->credits ?? 3); ?> Credits</small>
                                    <?php if($course->semester && $course->year): ?>
                                        <small class="text-muted"><?php echo e(ucfirst($course->semester)); ?> <?php echo e($course->year); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Course Title -->
                            <h5 class="card-title">
                                <a href="<?php echo e(route('courses.show', $course->slug)); ?>" class="text-decoration-none text-dark">
                                    <?php echo e($course->title); ?>

                                </a>
                            </h5>

                            <!-- Course Description -->
                            <p class="card-text flex-grow-1">
                                <?php echo e(Str::limit($course->description, 120)); ?>

                            </p>

                            <!-- Course Features -->
                            <?php if($course->prerequisites || $course->meeting_times || $course->location): ?>
                                <div class="mb-3">
                                    <?php if($course->meeting_times): ?>
                                        <small class="text-muted d-block">
                                            <i class="bi bi-clock me-1"></i><?php echo e($course->meeting_times); ?>

                                        </small>
                                    <?php endif; ?>
                                    <?php if($course->location): ?>
                                        <small class="text-muted d-block">
                                            <i class="bi bi-geo-alt me-1"></i><?php echo e($course->location); ?>

                                        </small>
                                    <?php endif; ?>
                                    <?php if($course->prerequisites): ?>
                                        <small class="text-muted d-block">
                                            <i class="bi bi-list-check me-1"></i>Prerequisites: <?php echo e(Str::limit($course->prerequisites, 40)); ?>

                                        </small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Course Status -->
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div>
                                    <?php if($course->status === 'active'): ?>
                                        <span class="badge bg-success">Currently Teaching</span>
                                    <?php elseif($course->status === 'upcoming'): ?>
                                        <span class="badge bg-warning text-dark">Upcoming</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark">Previously Taught</span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo e(route('courses.show', $course->slug)); ?>" class="btn btn-outline-primary btn-sm">
                                    Learn More <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>

                            <!-- Learning Objectives Preview -->
                            <?php if($course->learning_objectives): ?>
                                <div class="mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <strong>Key Learning Outcomes:</strong>
                                        <?php echo e(Str::limit(strip_tags($course->learning_objectives), 100)); ?>

                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <?php echo e($courses->withQueryString()->links()); ?>

                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- No Courses Found -->
            <div class="row">
                <div class="col-12 text-center py-5">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-5">
                            <i class="bi bi-book text-muted" style="font-size: 4rem;"></i>
                            <h3 class="mt-3">No Courses Found</h3>
                            <?php if(request()->hasAny(['search', 'level', 'semester', 'sort'])): ?>
                                <p class="text-muted mb-4">
                                    No courses match your current filters. Try adjusting your search criteria.
                                </p>
                                <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-primary">
                                    <i class="bi bi-arrow-left me-2"></i>View All Courses
                                </a>
                            <?php else: ?>
                                <p class="text-muted mb-4">
                                    Course information will be available here. Check back for updates on current and upcoming courses.
                                </p>
                                <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">
                                    <i class="bi bi-house me-2"></i>Back to Home
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Course Statistics -->
<?php if(isset($courseStats)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3>Teaching Overview</h3>
                <p class="text-muted">A snapshot of my teaching experience</p>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-book-half text-primary" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1"><?php echo e($courseStats['total_courses'] ?? 0); ?></h3>
                        <p class="text-muted mb-0">Total Courses</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-people text-success" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1"><?php echo e($courseStats['total_students'] ?? 0); ?></h3>
                        <p class="text-muted mb-0">Students Taught</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-award text-warning" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1"><?php echo e($courseStats['years_teaching'] ?? 0); ?></h3>
                        <p class="text-muted mb-0">Years Teaching</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-star text-info" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1"><?php echo e($courseStats['avg_rating'] ?? '4.8'); ?></h3>
                        <p class="text-muted mb-0">Average Rating</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Teaching Philosophy -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h3 class="mb-4">Teaching Philosophy</h3>
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5">
                        <blockquote class="blockquote">
                            <p class="lead mb-4">
                                "I believe in creating an inclusive and engaging learning environment where students
                                are encouraged to think critically, ask questions, and explore new ideas. My goal is
                                to inspire lifelong learning and help students develop the skills they need to succeed
                                in their academic and professional endeavors."
                            </p>
                        </blockquote>
                        <div class="row mt-4">
                            <div class="col-md-4 mb-3">
                                <i class="bi bi-lightbulb text-primary" style="font-size: 2rem;"></i>
                                <h6 class="mt-2">Innovation</h6>
                                <small class="text-muted">Embracing new teaching methods and technologies</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <i class="bi bi-people text-success" style="font-size: 2rem;"></i>
                                <h6 class="mt-2">Inclusion</h6>
                                <small class="text-muted">Creating welcoming spaces for all learners</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <i class="bi bi-graph-up text-info" style="font-size: 2rem;"></i>
                                <h6 class="mt-2">Growth</h6>
                                <small class="text-muted">Fostering continuous learning and development</small>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="<?php echo e(route('about')); ?>" class="btn btn-primary me-2">
                                <i class="bi bi-person me-2"></i>Learn More About Me
                            </a>
                            <a href="<?php echo e(route('contact.show')); ?>" class="btn btn-outline-primary">
                                <i class="bi bi-envelope me-2"></i>Contact Me
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Course Levels Overview -->
<?php if(isset($courseLevels) && count($courseLevels) > 0): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3>Course Levels</h3>
                <p class="text-muted">Explore courses by academic level</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <?php $__currentLoopData = $courseLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                <a href="<?php echo e(route('courses.index', ['level' => $level])); ?>" class="text-decoration-none">
                    <div class="card text-center h-100 shadow-sm border-0">
                        <div class="card-body py-4">
                            <?php if($level === 'undergraduate'): ?>
                                <i class="bi bi-mortarboard text-primary" style="font-size: 2.5rem;"></i>
                            <?php elseif($level === 'graduate'): ?>
                                <i class="bi bi-award text-success" style="font-size: 2.5rem;"></i>
                            <?php else: ?>
                                <i class="bi bi-trophy text-warning" style="font-size: 2.5rem;"></i>
                            <?php endif; ?>
                            <h5 class="card-title mt-3 mb-2"><?php echo e(ucfirst($level)); ?></h5>
                            <p class="text-muted"><?php echo e($count); ?> <?php echo e(Str::plural('course', $count)); ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/public/courses/index.blade.php ENDPATH**/ ?>