<?php $__env->startSection('title', 'Publications'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">Publications & Research</h1>
                <p class="lead mb-4">
                    Explore my scholarly contributions to the academic community. From peer-reviewed articles to conference proceedings,
                    these publications represent my ongoing research and commitment to advancing knowledge in my field.
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
                <form method="GET" action="<?php echo e(route('publications.index')); ?>" class="row g-3 align-items-end">
                    <!-- Search -->
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search Publications</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search"
                                   value="<?php echo e(request('search')); ?>" placeholder="Search titles, abstracts...">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Type Filter -->
                    <div class="col-md-2">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">All Types</option>
                            <option value="journal" <?php echo e(request('type') == 'journal' ? 'selected' : ''); ?>>
                                Journal Article
                            </option>
                            <option value="conference" <?php echo e(request('type') == 'conference' ? 'selected' : ''); ?>>
                                Conference Paper
                            </option>
                            <option value="book" <?php echo e(request('type') == 'book' ? 'selected' : ''); ?>>
                                Book
                            </option>
                            <option value="chapter" <?php echo e(request('type') == 'chapter' ? 'selected' : ''); ?>>
                                Book Chapter
                            </option>
                            <option value="thesis" <?php echo e(request('type') == 'thesis' ? 'selected' : ''); ?>>
                                Thesis/Dissertation
                            </option>
                            <option value="preprint" <?php echo e(request('type') == 'preprint' ? 'selected' : ''); ?>>
                                Preprint
                            </option>
                        </select>
                    </div>

                    <!-- Year Filter -->
                    <div class="col-md-2">
                        <label for="year" class="form-label">Year</label>
                        <select class="form-select" id="year" name="year">
                            <option value="">All Years</option>
                            <?php for($y = date('Y'); $y >= date('Y') - 10; $y--): ?>
                                <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>>
                                    <?php echo e($y); ?>

                                </option>
                            <?php endfor; ?>
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
                            <option value="citations" <?php echo e(request('sort') == 'citations' ? 'selected' : ''); ?>>
                                Most Cited
                            </option>
                        </select>
                    </div>

                    <!-- Filter Actions -->
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="<?php echo e(route('publications.index')); ?>" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Publications List -->
<section class="py-5 bg-white">
    <div class="container">
        <?php if(isset($publications) && $publications->count() > 0): ?>
            <!-- Results Summary -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Publications</h3>
                        <span class="text-muted">
                            <?php echo e($publications->total()); ?> <?php echo e(Str::plural('publication', $publications->total())); ?> found
                            <?php if(request('search')): ?>
                                for "<?php echo e(request('search')); ?>"
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Publications List -->
            <div class="row">
                <?php $__currentLoopData = $publications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $publication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <div class="row">
                                <!-- Publication Info -->
                                <div class="col-lg-9">
                                    <!-- Publication Meta -->
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <?php if($publication->type === 'journal'): ?>
                                            <span class="badge bg-success">Journal Article</span>
                                        <?php elseif($publication->type === 'conference'): ?>
                                            <span class="badge bg-info">Conference Paper</span>
                                        <?php elseif($publication->type === 'book'): ?>
                                            <span class="badge bg-warning text-dark">Book</span>
                                        <?php elseif($publication->type === 'chapter'): ?>
                                            <span class="badge bg-secondary">Book Chapter</span>
                                        <?php elseif($publication->type === 'thesis'): ?>
                                            <span class="badge bg-primary">Thesis</span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark"><?php echo e(ucfirst($publication->type ?? 'Publication')); ?></span>
                                        <?php endif; ?>

                                        <?php if($publication->peer_reviewed): ?>
                                            <span class="badge bg-warning text-dark">Peer Reviewed</span>
                                        <?php endif; ?>

                                        <?php if($publication->open_access): ?>
                                            <span class="badge bg-primary">Open Access</span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Title -->
                                    <h5 class="card-title mb-3">
                                        <a href="<?php echo e(route('publications.show', $publication->slug)); ?>" class="text-decoration-none text-dark">
                                            <?php echo e($publication->title); ?>

                                        </a>
                                    </h5>

                                    <!-- Authors -->
                                    <?php if($publication->authors): ?>
                                        <p class="text-muted mb-2">
                                            <strong>Authors:</strong> <?php echo e($publication->authors); ?>

                                        </p>
                                    <?php endif; ?>

                                    <!-- Journal/Conference Info -->
                                    <div class="mb-3">
                                        <?php if($publication->journal): ?>
                                            <p class="text-muted mb-1">
                                                <em><?php echo e($publication->journal); ?></em>
                                                <?php if($publication->volume || $publication->issue): ?>
                                                    <?php if($publication->volume): ?>, Vol. <?php echo e($publication->volume); ?><?php endif; ?>
                                                    <?php if($publication->issue): ?>, Issue <?php echo e($publication->issue); ?><?php endif; ?>
                                                <?php endif; ?>
                                                <?php if($publication->pages): ?>, pp. <?php echo e($publication->pages); ?><?php endif; ?>
                                            </p>
                                        <?php elseif($publication->conference): ?>
                                            <p class="text-muted mb-1">
                                                <em><?php echo e($publication->conference); ?></em>
                                                <?php if($publication->conference_location): ?>, <?php echo e($publication->conference_location); ?><?php endif; ?>
                                            </p>
                                        <?php elseif($publication->publisher): ?>
                                            <p class="text-muted mb-1">
                                                <em><?php echo e($publication->publisher); ?></em>
                                            </p>
                                        <?php endif; ?>

                                        <?php if($publication->published_date): ?>
                                            <p class="text-muted mb-1">
                                                <i class="bi bi-calendar me-1"></i>
                                                <?php echo e($publication->published_date->format('F Y')); ?>

                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Abstract -->
                                    <?php if($publication->abstract): ?>
                                        <p class="card-text">
                                            <?php echo e(Str::limit($publication->abstract, 200)); ?>

                                        </p>
                                    <?php endif; ?>

                                    <!-- Keywords -->
                                    <?php if($publication->keywords): ?>
                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <strong>Keywords:</strong>
                                                <?php $__currentLoopData = explode(',', $publication->keywords); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge bg-light text-dark me-1"><?php echo e(trim($keyword)); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </small>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Links -->
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="<?php echo e(route('publications.show', $publication->slug)); ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>

                                        <?php if($publication->pdf_file): ?>
                                            <a href="<?php echo e(Storage::url($publication->pdf_file)); ?>" target="_blank" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-file-pdf me-1"></i>PDF
                                            </a>
                                        <?php endif; ?>

                                        <?php if($publication->doi): ?>
                                            <a href="https://doi.org/<?php echo e($publication->doi); ?>" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-link me-1"></i>DOI
                                            </a>
                                        <?php endif; ?>

                                        <?php if($publication->url): ?>
                                            <a href="<?php echo e($publication->url); ?>" target="_blank" rel="noopener" class="btn btn-outline-info btn-sm">
                                                <i class="bi bi-globe me-1"></i>URL
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Metrics -->
                                <div class="col-lg-3 text-end">
                                    <?php if($publication->citation_count || $publication->download_count): ?>
                                        <div class="mt-3">
                                            <?php if($publication->citation_count): ?>
                                                <div class="mb-2">
                                                    <small class="text-muted">Citations</small>
                                                    <h6 class="mb-0"><?php echo e($publication->citation_count); ?></h6>
                                                </div>
                                            <?php endif; ?>

                                            <?php if($publication->download_count): ?>
                                                <div class="mb-2">
                                                    <small class="text-muted">Downloads</small>
                                                    <h6 class="mb-0"><?php echo e($publication->download_count); ?></h6>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($publication->impact_factor): ?>
                                        <div class="mt-3">
                                            <small class="text-muted">Impact Factor</small>
                                            <h6 class="mb-0"><?php echo e($publication->impact_factor); ?></h6>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <?php echo e($publications->withQueryString()->links()); ?>

                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- No Publications Found -->
            <div class="row">
                <div class="col-12 text-center py-5">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-5">
                            <i class="bi bi-journal-text text-muted" style="font-size: 4rem;"></i>
                            <h3 class="mt-3">No Publications Found</h3>
                            <?php if(request()->hasAny(['search', 'type', 'year', 'sort'])): ?>
                                <p class="text-muted mb-4">
                                    No publications match your current filters. Try adjusting your search criteria.
                                </p>
                                <a href="<?php echo e(route('publications.index')); ?>" class="btn btn-primary">
                                    <i class="bi bi-arrow-left me-2"></i>View All Publications
                                </a>
                            <?php else: ?>
                                <p class="text-muted mb-4">
                                    Publication information will be available here. Check back for updates on recent research publications.
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

<!-- Publication Statistics -->
<?php if(isset($publicationStats)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3>Publication Metrics</h3>
                <p class="text-muted">Impact and reach of my research publications</p>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-journal-text text-success" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1"><?php echo e($publicationStats['total_publications'] ?? 0); ?></h3>
                        <p class="text-muted mb-0">Total Publications</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-quote text-primary" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1"><?php echo e($publicationStats['total_citations'] ?? 0); ?></h3>
                        <p class="text-muted mb-0">Total Citations</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-graph-up text-warning" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1"><?php echo e($publicationStats['h_index'] ?? 0); ?></h3>
                        <p class="text-muted mb-0">H-Index</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-award text-info" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-3 mb-1"><?php echo e($publicationStats['peer_reviewed'] ?? 0); ?></h3>
                        <p class="text-muted mb-0">Peer Reviewed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Research Impact -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h3 class="mb-4">Research Impact & Contribution</h3>
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5">
                        <blockquote class="blockquote">
                            <p class="lead mb-4">
                                "My research aims to bridge theoretical knowledge with practical applications,
                                contributing to both academic understanding and real-world solutions that benefit
                                society and advance the field."
                            </p>
                        </blockquote>
                        <div class="row mt-4">
                            <div class="col-md-4 mb-3">
                                <i class="bi bi-lightbulb text-success" style="font-size: 2rem;"></i>
                                <h6 class="mt-2">Innovation</h6>
                                <small class="text-muted">Developing novel approaches and methodologies</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                                <h6 class="mt-2">Collaboration</h6>
                                <small class="text-muted">Working with international research networks</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <i class="bi bi-globe text-info" style="font-size: 2rem;"></i>
                                <h6 class="mt-2">Impact</h6>
                                <small class="text-muted">Creating meaningful change in the field</small>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="<?php echo e(route('projects.index')); ?>" class="btn btn-success me-2">
                                <i class="bi bi-lightbulb me-2"></i>View Research Projects
                            </a>
                            <a href="<?php echo e(route('contact.show')); ?>" class="btn btn-outline-success">
                                <i class="bi bi-envelope me-2"></i>Collaborate With Me
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Publication Types -->
<?php if(isset($publicationTypes) && count($publicationTypes) > 0): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3>Publication Types</h3>
                <p class="text-muted">Explore publications by category</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <?php $__currentLoopData = $publicationTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
                <a href="<?php echo e(route('publications.index', ['type' => $type])); ?>" class="text-decoration-none">
                    <div class="card text-center h-100 shadow-sm border-0">
                        <div class="card-body py-3">
                            <?php if($type === 'journal'): ?>
                                <i class="bi bi-journal text-success" style="font-size: 2rem;"></i>
                            <?php elseif($type === 'conference'): ?>
                                <i class="bi bi-people text-info" style="font-size: 2rem;"></i>
                            <?php elseif($type === 'book'): ?>
                                <i class="bi bi-book text-warning" style="font-size: 2rem;"></i>
                            <?php else: ?>
                                <i class="bi bi-file-text text-primary" style="font-size: 2rem;"></i>
                            <?php endif; ?>
                            <h6 class="card-title mt-2 mb-1"><?php echo e(ucfirst($type)); ?></h6>
                            <small class="text-muted"><?php echo e($count); ?> publications</small>
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
                <h3 class="mb-4">Stay Updated on My Research</h3>
                <p class="lead mb-4">
                    Follow my latest publications and research findings. Subscribe to get notified about
                    new publications and research updates directly in your inbox.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="<?php echo e(route('contact.show')); ?>" class="btn btn-light btn-lg">
                        <i class="bi bi-envelope me-2"></i>Contact Me
                    </a>
                    <?php if($teacher->google_scholar_url ?? false): ?>
                        <a href="<?php echo e($teacher->google_scholar_url); ?>" target="_blank" rel="noopener" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-mortarboard me-2"></i>Google Scholar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/public/publications/index.blade.php ENDPATH**/ ?>