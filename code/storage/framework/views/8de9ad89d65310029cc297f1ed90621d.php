<?php $__env->startSection('title', 'Research Projects'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">Research Projects</h1>
                <p class="lead mb-4">
                    Explore my current and past research initiatives. From innovative studies to collaborative efforts,
                    these projects represent my commitment to advancing knowledge and understanding in my field.
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
                <form method="GET" action="<?php echo e(route('projects.index')); ?>" class="row g-3 align-items-end">
                    <!-- Search -->
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search Projects</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search"
                                   value="<?php echo e(request('search')); ?>" placeholder="Search titles, descriptions...">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>
                                Active
                            </option>
                            <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>
                                Completed
                            </option>
                            <option value="planning" <?php echo e(request('status') == 'planning' ? 'selected' : ''); ?>>
                                Planning
                            </option>
                            <option value="on-hold" <?php echo e(request('status') == 'on-hold' ? 'selected' : ''); ?>>
                                On Hold
                            </option>
                        </select>
                    </div>

                    <!-- Type Filter -->
                    <div class="col-md-2">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">All Types</option>
                            <option value="research" <?php echo e(request('type') == 'research' ? 'selected' : ''); ?>>Research</option>
                            <option value="collaboration" <?php echo e(request('type') == 'collaboration' ? 'selected' : ''); ?>>Collaboration</option>
                            <option value="consulting" <?php echo e(request('type') == 'consulting' ? 'selected' : ''); ?>>Consulting</option>
                            <option value="grant" <?php echo e(request('type') == 'grant' ? 'selected' : ''); ?>>Grant-funded</option>
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div class="col-md-2">
                        <label for="sort" class="form-label">Sort By</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="latest" <?php echo e(request('sort') == 'latest' || !request('sort') ? 'selected' : ''); ?>>
                                Latest First
                            </option>
                            <option value="oldest" <?php echo e(request('sort') == 'oldest' ? 'selected' : ''); ?>>
                                Oldest First
                            </option>
                            <option value="title" <?php echo e(request('sort') == 'title' ? 'selected' : ''); ?>>
                                Title A-Z
                            </option>
                            <option value="status" <?php echo e(request('sort') == 'status' ? 'selected' : ''); ?>>
                                Status
                            </option>
                        </select>
                    </div>

                    <!-- Filter Actions -->
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="<?php echo e(route('projects.index')); ?>" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Projects List -->
<section class="py-5 bg-white">
    <div class="container">
        <?php if(isset($projects) && $projects->count() > 0): ?>
            <!-- Results Summary -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Research Projects</h3>
                        <span class="text-muted">
                            <?php echo e($projects->total()); ?> <?php echo e(Str::plural('project', $projects->total())); ?> found
                            <?php if(request('search')): ?>
                                for "<?php echo e(request('search')); ?>"
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Projects Grid -->
            <div class="row">
                <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <?php if($project->featured_image): ?>
                            <img src="<?php echo e(Storage::url($project->featured_image)); ?>"
                                 class="card-img-top" alt="<?php echo e($project->title); ?>"
                                 style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-info d-flex align-items-center justify-content-center text-white"
                                 style="height: 200px;">
                                <i class="bi bi-lightbulb" style="font-size: 3rem;"></i>
                            </div>
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <!-- Project Meta -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <?php if($project->status === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php elseif($project->status === 'completed'): ?>
                                        <span class="badge bg-primary">Completed</span>
                                    <?php elseif($project->status === 'planning'): ?>
                                        <span class="badge bg-warning text-dark">Planning</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">On Hold</span>
                                    <?php endif; ?>

                                    <?php if($project->type): ?>
                                        <span class="badge bg-light text-dark ms-1"><?php echo e(ucfirst($project->type)); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-end">
                                    <?php if($project->start_date): ?>
                                        <small class="text-muted d-block"><?php echo e(is_string($project->start_date) ? \Carbon\Carbon::parse($project->start_date)->format('Y') : $project->start_date->format('Y')); ?></small>
                                    <?php endif; ?>
                                    <?php if($project->funding_amount): ?>
                                        <small class="text-muted">$<?php echo e(number_format($project->funding_amount)); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Project Title -->
                            <h5 class="card-title">
                                <a href="<?php echo e(route('projects.show', $project->slug)); ?>" class="text-decoration-none text-dark">
                                    <?php echo e($project->title); ?>

                                </a>
                            </h5>

                            <!-- Project Description -->
                            <p class="card-text flex-grow-1">
                                <?php echo e(Str::limit($project->description, 120)); ?>

                            </p>

                            <!-- Project Details -->
                            <div class="mb-3">
                                <?php if($project->duration): ?>
                                    <small class="text-muted d-block">
                                        <i class="bi bi-clock me-1"></i>Duration: <?php echo e($project->duration); ?>

                                    </small>
                                <?php endif; ?>
                                <?php if($project->collaborators): ?>
                                    <small class="text-muted d-block">
                                        <i class="bi bi-people me-1"></i><?php echo e(count(explode(',', $project->collaborators))); ?> Collaborators
                                    </small>
                                <?php endif; ?>
                                <?php if($project->funding_source): ?>
                                    <small class="text-muted d-block">
                                        <i class="bi bi-bank me-1"></i><?php echo e($project->funding_source); ?>

                                    </small>
                                <?php endif; ?>
                            </div>

                            <!-- Action Button -->
                            <div class="mt-auto">
                                <a href="<?php echo e(route('projects.show', $project->slug)); ?>" class="btn btn-outline-info w-100">
                                    View Project <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>

                            <!-- Technologies/Keywords -->
                            <?php if($project->technologies || $project->keywords): ?>
                                <div class="mt-3 pt-3 border-top">
                                    <?php if($project->technologies): ?>
                                        <?php $__currentLoopData = array_slice(explode(',', $project->technologies), 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tech): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-light text-dark me-1"><?php echo e(trim($tech)); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php elseif($project->keywords): ?>
                                        <?php $__currentLoopData = array_slice(explode(',', $project->keywords), 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-light text-dark me-1"><?php echo e(trim($keyword)); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
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
                        <?php echo e($projects->withQueryString()->links()); ?>

                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- No Projects Found -->
            <div class="row">
                <div class="col-12 text-center py-5">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-5">
                            <i class="bi bi-lightbulb text-muted" style="font-size: 4rem;"></i>
                            <h3 class="mt-3">No Projects Found</h3>
                            <?php if(request()->hasAny(['search', 'status', 'type', 'sort'])): ?>
                                <p class="text-muted mb-4">
                                    No projects match your current filters. Try adjusting your search criteria.
                                </p>
                                <a href="<?php echo e(route('projects.index')); ?>" class="btn btn-primary">
                                    <i class="bi bi-arrow-left me-2"></i>View All Projects
                                </a>
                            <?php else: ?>
                                <p class="text-muted mb-4">
                                    Project information will be available here. Check back for updates on current and upcoming research.
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

<!-- Project Statistics -->
<?php if(isset($projectStats)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3>Research Overview</h3>
                <p class="text-muted">A snapshot of my research activities</p>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-lightbulb-fill text-info" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1"><?php echo e($projectStats['total_projects'] ?? 0); ?></h3>
                        <p class="text-muted mb-0">Total Projects</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-play-circle text-success" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1"><?php echo e($projectStats['active_projects'] ?? 0); ?></h3>
                        <p class="text-muted mb-0">Active Projects</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-currency-dollar text-warning" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1">$<?php echo e(number_format($projectStats['total_funding'] ?? 0)); ?>K</h3>
                        <p class="text-muted mb-0">Total Funding</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-people text-primary" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1"><?php echo e($projectStats['collaborators'] ?? 0); ?></h3>
                        <p class="text-muted mb-0">Collaborators</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Research Areas -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h3 class="mb-4">Research Focus Areas</h3>
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5">
                        <p class="lead mb-4">
                            My research spans multiple interconnected areas, focusing on innovative solutions
                            and interdisciplinary approaches to complex challenges.
                        </p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-arrow-right text-info me-3"></i>
                                    <span>Computational Methods & Algorithms</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-arrow-right text-info me-3"></i>
                                    <span>Data Analysis & Machine Learning</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-arrow-right text-info me-3"></i>
                                    <span>Educational Technology & Innovation</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-arrow-right text-info me-3"></i>
                                    <span>Collaborative Research Networks</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="<?php echo e(route('publications.index')); ?>" class="btn btn-info text-white me-2">
                                <i class="bi bi-journal-text me-2"></i>View Publications
                            </a>
                            <a href="<?php echo e(route('contact.show')); ?>" class="btn btn-outline-info">
                                <i class="bi bi-envelope me-2"></i>Collaborate With Me
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Project Types -->
<?php if(isset($projectTypes) && count($projectTypes) > 0): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3>Project Categories</h3>
                <p class="text-muted">Explore projects by type and focus area</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <?php $__currentLoopData = $projectTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                <a href="<?php echo e(route('projects.index', ['type' => $type])); ?>" class="text-decoration-none">
                    <div class="card text-center h-100 shadow-sm border-0">
                        <div class="card-body py-4">
                            <?php if($type === 'research'): ?>
                                <i class="bi bi-search text-info" style="font-size: 2.5rem;"></i>
                            <?php elseif($type === 'collaboration'): ?>
                                <i class="bi bi-people text-success" style="font-size: 2.5rem;"></i>
                            <?php elseif($type === 'consulting'): ?>
                                <i class="bi bi-briefcase text-warning" style="font-size: 2.5rem;"></i>
                            <?php else: ?>
                                <i class="bi bi-award text-primary" style="font-size: 2.5rem;"></i>
                            <?php endif; ?>
                            <h5 class="card-title mt-3 mb-2"><?php echo e(ucfirst($type)); ?></h5>
                            <p class="text-muted"><?php echo e($count); ?> <?php echo e(Str::plural('project', $count)); ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h3 class="mb-4">Interested in Collaboration?</h3>
                <p class="lead mb-4">
                    I'm always open to new research opportunities and collaborative projects.
                    Whether you're a fellow researcher, industry partner, or student interested in getting involved,
                    I'd love to hear from you.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="<?php echo e(route('contact.show')); ?>" class="btn btn-light btn-lg">
                        <i class="bi bi-envelope me-2"></i>Contact Me
                    </a>
                    <a href="<?php echo e(route('publications.index')); ?>" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-journal-text me-2"></i>View Publications
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/public/projects/index.blade.php ENDPATH**/ ?>