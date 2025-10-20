<?php $__env->startSection('title', $project->title ?? 'Project Details'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('home')); ?>" class="text-white text-decoration-none">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('projects.index')); ?>" class="text-white text-decoration-none">Projects</a>
                        </li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">
                            <?php echo e(Str::limit($project->title ?? 'Project', 50)); ?>

                        </li>
                    </ol>
                </nav>

                <!-- Project Meta -->
                <div class="d-flex flex-wrap gap-2 mb-3">
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
                        <span class="badge bg-light text-dark"><?php echo e(ucfirst($project->type)); ?></span>
                    <?php endif; ?>

                    <?php if($project->funding_source): ?>
                        <span class="badge bg-info"><?php echo e($project->funding_source); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Project Title -->
                <h1 class="display-5 fw-bold mb-4"><?php echo e($project->title ?? 'Project Title'); ?></h1>

                <!-- Project Summary -->
                <?php if($project->summary ?? false): ?>
                    <p class="lead mb-4"><?php echo e($project->summary); ?></p>
                <?php endif; ?>

                <!-- Quick Info -->
                <div class="row text-center">
                    <?php if($project->start_date): ?>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="text-white-50">
                            <i class="bi bi-calendar-event" style="font-size: 1.5rem;"></i>
                            <div class="mt-2">
                                <strong class="text-white d-block"><?php echo e(is_string($project->start_date) ? \Carbon\Carbon::parse($project->start_date)->format('M Y') : $project->start_date->format('M Y')); ?></strong>
                                <small>Started</small>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($project->duration): ?>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="text-white-50">
                            <i class="bi bi-clock" style="font-size: 1.5rem;"></i>
                            <div class="mt-2">
                                <strong class="text-white d-block"><?php echo e($project->duration); ?></strong>
                                <small>Duration</small>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($project->funding_amount): ?>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="text-white-50">
                            <i class="bi bi-currency-dollar" style="font-size: 1.5rem;"></i>
                            <div class="mt-2">
                                <strong class="text-white d-block">$<?php echo e(number_format($project->funding_amount)); ?></strong>
                                <small>Funding</small>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($project->collaborators): ?>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="text-white-50">
                            <i class="bi bi-people" style="font-size: 1.5rem;"></i>
                            <div class="mt-2">
                                <strong class="text-white d-block"><?php echo e(count(explode(',', $project->collaborators))); ?></strong>
                                <small>Collaborators</small>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Project Content -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Project Image -->
                <?php if($project->featured_image ?? false): ?>
                <div class="mb-5">
                    <img src="<?php echo e(Storage::url($project->featured_image)); ?>"
                         class="img-fluid rounded shadow-sm" alt="<?php echo e($project->title); ?>"
                         style="width: 100%; height: 300px; object-fit: cover;">
                </div>
                <?php endif; ?>

                <!-- Project Overview -->
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0"><i class="bi bi-info-circle me-2"></i>Project Overview</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if($project->description ?? false): ?>
                            <div class="project-description">
                                <?php echo nl2br(e($project->description)); ?>

                            </div>
                        <?php else: ?>
                            <p>This research project addresses important questions in the field through innovative methodologies and collaborative approaches. The work aims to advance understanding and provide practical solutions to real-world challenges.</p>

                            <p>Our team employs cutting-edge techniques and interdisciplinary perspectives to explore complex problems and generate meaningful insights that contribute to both academic knowledge and practical applications.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Objectives -->
                <?php if($project->objectives ?? false): ?>
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="bi bi-bullseye me-2"></i>Research Objectives</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="project-objectives">
                            <?php echo $project->objectives; ?>

                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Methodology -->
                <?php if($project->methodology ?? false): ?>
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-warning text-white">
                        <h4 class="mb-0"><i class="bi bi-gear me-2"></i>Methodology</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="project-methodology">
                            <?php echo $project->methodology; ?>

                        </div>
                    </div>
                </div>
                <?php else: ?>
                <!-- Sample Methodology -->
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-warning text-white">
                        <h4 class="mb-0"><i class="bi bi-gear me-2"></i>Research Approach</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-warning me-3">1</span>
                                    <div>
                                        <h6>Literature Review</h6>
                                        <small class="text-muted">Comprehensive analysis of existing research</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-warning me-3">2</span>
                                    <div>
                                        <h6>Data Collection</h6>
                                        <small class="text-muted">Gathering and organizing research data</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-warning me-3">3</span>
                                    <div>
                                        <h6>Analysis & Modeling</h6>
                                        <small class="text-muted">Statistical analysis and predictive modeling</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-warning me-3">4</span>
                                    <div>
                                        <h6>Validation & Testing</h6>
                                        <small class="text-muted">Verification of results and conclusions</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Results/Findings -->
                <?php if($project->results ?? false): ?>
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-graph-up me-2"></i>Results & Findings</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="project-results">
                            <?php echo $project->results; ?>

                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Publications -->
                <?php if($project->publications ?? false): ?>
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0"><i class="bi bi-journal-text me-2"></i>Related Publications</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="project-publications">
                            <?php echo $project->publications; ?>

                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Project Gallery -->
                <?php if($project->gallery ?? false): ?>
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-dark text-white">
                        <h4 class="mb-0"><i class="bi bi-images me-2"></i>Project Gallery</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <?php $__currentLoopData = json_decode($project->gallery, true) ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4 mb-3">
                                    <img src="<?php echo e(Storage::url($image['path'])); ?>"
                                         class="img-fluid rounded shadow-sm"
                                         alt="<?php echo e($image['caption'] ?? 'Project Image'); ?>"
                                         style="height: 200px; width: 100%; object-fit: cover;">
                                    <?php if($image['caption'] ?? false): ?>
                                        <small class="text-muted d-block mt-2"><?php echo e($image['caption']); ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Project Information -->
                <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 2rem;">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Project Details</h5>
                    </div>
                    <div class="card-body">
                        <?php if($project->start_date): ?>
                        <div class="mb-3">
                            <strong>Start Date:</strong><br>
                            <span class="text-muted"><?php echo e(is_string($project->start_date) ? \Carbon\Carbon::parse($project->start_date)->format('F j, Y') : $project->start_date->format('F j, Y')); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($project->end_date): ?>
                        <div class="mb-3">
                            <strong>End Date:</strong><br>
                            <span class="text-muted"><?php echo e(is_string($project->end_date) ? \Carbon\Carbon::parse($project->end_date)->format('F j, Y') : $project->end_date->format('F j, Y')); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($project->duration): ?>
                        <div class="mb-3">
                            <strong>Duration:</strong><br>
                            <span class="text-muted"><?php echo e($project->duration); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($project->status): ?>
                        <div class="mb-3">
                            <strong>Status:</strong><br>
                            <span class="text-muted"><?php echo e(ucfirst($project->status)); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($project->funding_source): ?>
                        <div class="mb-3">
                            <strong>Funding Source:</strong><br>
                            <span class="text-muted"><?php echo e($project->funding_source); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($project->funding_amount): ?>
                        <div class="mb-3">
                            <strong>Funding Amount:</strong><br>
                            <span class="text-muted">$<?php echo e(number_format($project->funding_amount)); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($project->location): ?>
                        <div class="mb-3">
                            <strong>Location:</strong><br>
                            <span class="text-muted"><?php echo e($project->location); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Team Members -->
                <?php if($project->collaborators ?? false): ?>
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Research Team</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <?php if($teacher->avatar ?? false): ?>
                                <img src="<?php echo e(Storage::url($teacher->avatar)); ?>"
                                     alt="<?php echo e($teacher->name ?? 'Principal Investigator'); ?>"
                                     class="rounded-circle mb-2"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            <?php endif; ?>
                            <h6 class="mb-1"><?php echo e($teacher->name ?? 'Dr. [Your Name]'); ?></h6>
                            <small class="text-muted">Principal Investigator</small>
                        </div>

                        <h6 class="mt-4 mb-3">Collaborators:</h6>
                        <?php $__currentLoopData = explode(',', $project->collaborators); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collaborator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-2">
                                <i class="bi bi-person text-success me-2"></i><?php echo e(trim($collaborator)); ?>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Technologies -->
                <?php if($project->technologies ?? false): ?>
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Technologies & Tools</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <?php $__currentLoopData = explode(',', $project->technologies); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tech): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-light text-dark"><?php echo e(trim($tech)); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Keywords -->
                <?php if($project->keywords ?? false): ?>
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-tags me-2"></i>Keywords</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <?php $__currentLoopData = explode(',', $project->keywords); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-light text-dark">#<?php echo e(trim($keyword)); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Project Links -->
                <?php if($project->website_url || $project->repository_url || $project->demo_url): ?>
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-link me-2"></i>Project Links</h5>
                    </div>
                    <div class="card-body">
                        <?php if($project->website_url): ?>
                            <div class="mb-2">
                                <a href="<?php echo e($project->website_url); ?>" target="_blank" rel="noopener"
                                   class="text-decoration-none">
                                    <i class="bi bi-globe text-primary me-2"></i>Project Website
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($project->repository_url): ?>
                            <div class="mb-2">
                                <a href="<?php echo e($project->repository_url); ?>" target="_blank" rel="noopener"
                                   class="text-decoration-none">
                                    <i class="bi bi-github text-primary me-2"></i>Code Repository
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($project->demo_url): ?>
                            <div class="mb-2">
                                <a href="<?php echo e($project->demo_url); ?>" target="_blank" rel="noopener"
                                   class="text-decoration-none">
                                    <i class="bi bi-play-circle text-primary me-2"></i>Live Demo
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Related Projects -->
                <?php if(isset($relatedProjects) && $relatedProjects->count() > 0): ?>
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-collection me-2"></i>Related Projects</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php $__currentLoopData = $relatedProjects->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedProject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-3 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                                <h6 class="mb-2">
                                    <a href="<?php echo e(route('projects.show', $relatedProject->slug)); ?>"
                                       class="text-decoration-none">
                                        <?php echo e($relatedProject->title); ?>

                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <?php echo e(ucfirst($relatedProject->status)); ?> • <?php echo e($relatedProject->start_date?->format('Y')); ?>

                                </small>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Navigation -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <?php if(isset($previousProject)): ?>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-0">
                        <div class="card-body">
                            <small class="text-muted">Previous Project</small>
                            <h6 class="mt-2">
                                <a href="<?php echo e(route('projects.show', $previousProject->slug)); ?>"
                                   class="text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i><?php echo e($previousProject->title); ?>

                                </a>
                            </h6>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(isset($nextProject)): ?>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-0">
                        <div class="card-body text-end">
                            <small class="text-muted">Next Project</small>
                            <h6 class="mt-2">
                                <a href="<?php echo e(route('projects.show', $nextProject->slug)); ?>"
                                   class="text-decoration-none">
                                    <?php echo e($nextProject->title); ?><i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-3">
            <a href="<?php echo e(route('projects.index')); ?>" class="btn btn-info text-white">
                <i class="bi bi-grid me-2"></i>View All Projects
            </a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/public/projects/show.blade.php ENDPATH**/ ?>