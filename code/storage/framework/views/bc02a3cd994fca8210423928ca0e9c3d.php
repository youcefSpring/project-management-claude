<?php $__env->startSection('title', 'Publications'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold">Publications</h1>
                <p class="lead mb-0">Explore my research publications, academic papers, and scholarly contributions.</p>
            </div>
            <div class="col-lg-4 text-center">
                <i class="bi bi-journal-text" style="font-size: 6rem; opacity: 0.7;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Publications Content -->
<section class="py-5">
    <div class="container">
        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form method="GET" action="<?php echo e(route('publications.index')); ?>" class="d-flex gap-2">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search by title, authors, journal, or keywords..."
                           value="<?php echo e(request('search')); ?>">
                    <select name="type" class="form-select" style="max-width: 200px;">
                        <option value="">All Types</option>
                        <option value="article" <?php echo e(request('type') === 'article' ? 'selected' : ''); ?>>Journal Articles</option>
                        <option value="conference_paper" <?php echo e(request('type') === 'conference_paper' ? 'selected' : ''); ?>>Conference Papers</option>
                        <option value="book" <?php echo e(request('type') === 'book' ? 'selected' : ''); ?>>Books</option>
                        <option value="thesis" <?php echo e(request('type') === 'thesis' ? 'selected' : ''); ?>>Thesis</option>
                        <option value="report" <?php echo e(request('type') === 'report' ? 'selected' : ''); ?>>Reports</option>
                        <option value="other" <?php echo e(request('type') === 'other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search"></i>
                    </button>
                    <?php if(request('search') || request('type')): ?>
                        <a href="<?php echo e(route('publications.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="sort" id="newest" <?php echo e(!request('sort') || request('sort') === 'newest' ? 'checked' : ''); ?>>
                    <label class="btn btn-outline-secondary btn-sm" for="newest">Newest</label>

                    <input type="radio" class="btn-check" name="sort" id="oldest" <?php echo e(request('sort') === 'oldest' ? 'checked' : ''); ?>>
                    <label class="btn btn-outline-secondary btn-sm" for="oldest">Oldest</label>

                    <input type="radio" class="btn-check" name="sort" id="citations" <?php echo e(request('sort') === 'citations' ? 'checked' : ''); ?>>
                    <label class="btn btn-outline-secondary btn-sm" for="citations">Citations</label>
                </div>
            </div>
        </div>

        <?php if(request('search') || request('type')): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <?php if(request('search')): ?>
                    Showing results for "<strong><?php echo e(request('search')); ?></strong>"
                <?php endif; ?>
                <?php if(request('type')): ?>
                    <?php if(request('search')): ?> in <?php endif; ?>
                    <strong><?php echo e(ucwords(str_replace('_', ' ', request('type')))); ?></strong>
                <?php endif; ?>
                - <?php echo e($publications->total()); ?> publication(s) found
            </div>
        <?php endif; ?>

        <!-- Publications List -->
        <?php if($publications->count() > 0): ?>
            <div class="row g-4">
                <?php $__currentLoopData = $publications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $publication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-12">
                        <div class="card publication-card">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-2">
                                                    <a href="<?php echo e(route('publications.show', $publication)); ?>" class="text-decoration-none">
                                                        <?php echo e($publication->title); ?>

                                                    </a>
                                                </h5>

                                                <div class="d-flex flex-wrap gap-2 mb-2">
                                                    <?php if($publication->type === 'article'): ?>
                                                        <span class="badge bg-primary">Journal Article</span>
                                                    <?php elseif($publication->type === 'conference_paper'): ?>
                                                        <span class="badge bg-success">Conference Paper</span>
                                                    <?php elseif($publication->type === 'book'): ?>
                                                        <span class="badge bg-warning">Book</span>
                                                    <?php elseif($publication->type === 'thesis'): ?>
                                                        <span class="badge bg-info">Thesis</span>
                                                    <?php elseif($publication->type === 'report'): ?>
                                                        <span class="badge bg-secondary">Report</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-dark">Other</span>
                                                    <?php endif; ?>

                                                    <?php if($publication->status === 'published'): ?>
                                                        <span class="badge bg-success">Published</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">Draft</span>
                                                    <?php endif; ?>
                                                </div>

                                                <p class="text-muted mb-2">
                                                    <strong>Authors:</strong> <?php echo e($publication->authors); ?>

                                                </p>

                                                <?php if($publication->journal): ?>
                                                    <p class="text-muted mb-2">
                                                        <strong>Published in:</strong> <?php echo e($publication->journal); ?>

                                                        <?php if($publication->volume || $publication->issue || $publication->pages): ?>
                                                            <span class="text-secondary">
                                                                <?php if($publication->volume): ?>, Vol. <?php echo e($publication->volume); ?><?php endif; ?>
                                                                <?php if($publication->issue): ?>, Issue <?php echo e($publication->issue); ?><?php endif; ?>
                                                                <?php if($publication->pages): ?>, pp. <?php echo e($publication->pages); ?><?php endif; ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </p>
                                                <?php endif; ?>

                                                <?php if($publication->abstract): ?>
                                                    <p class="text-muted">
                                                        <?php echo e(Str::limit($publication->abstract, 200)); ?>

                                                    </p>
                                                <?php elseif($publication->description): ?>
                                                    <p class="text-muted">
                                                        <?php echo e(Str::limit($publication->description, 200)); ?>

                                                    </p>
                                                <?php endif; ?>

                                                <!-- Keywords -->
                                                <?php if($publication->keywords): ?>
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <strong>Keywords:</strong>
                                                            <?php $__currentLoopData = explode(',', $publication->keywords); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <span class="badge bg-light text-dark border me-1"><?php echo e(trim($keyword)); ?></span>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </small>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Tags -->
                                                <?php if($publication->tags && $publication->tags->count() > 0): ?>
                                                    <div class="mt-2">
                                                        <?php $__currentLoopData = $publication->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <span class="badge bg-secondary me-1"><?php echo e($tag->name); ?></span>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 text-md-end">
                                        <div class="publication-meta">
                                            <div class="mb-3">
                                                <div class="text-muted small mb-1">Publication Date</div>
                                                <div class="fw-bold"><?php echo e($publication->publication_date->format('M j, Y')); ?></div>
                                            </div>

                                            <?php if($publication->citation_count && $publication->citation_count > 0): ?>
                                                <div class="mb-3">
                                                    <div class="text-muted small mb-1">Citations</div>
                                                    <div class="fw-bold text-success"><?php echo e($publication->citation_count); ?></div>
                                                </div>
                                            <?php endif; ?>

                                            <?php if($publication->doi): ?>
                                                <div class="mb-3">
                                                    <div class="text-muted small mb-1">DOI</div>
                                                    <div class="small">
                                                        <a href="https://doi.org/<?php echo e($publication->doi); ?>" target="_blank" class="text-decoration-none">
                                                            <?php echo e(Str::limit($publication->doi, 20)); ?>

                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <div class="d-grid gap-2">
                                                <a href="<?php echo e(route('publications.show', $publication)); ?>" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-eye me-1"></i>View Details
                                                </a>

                                                <?php if($publication->publication_file_path): ?>
                                                    <a href="<?php echo e(route('publications.download', $publication)); ?>" class="btn btn-outline-secondary btn-sm" target="_blank">
                                                        <i class="bi bi-file-earmark-pdf me-1"></i>Download PDF
                                                    </a>
                                                <?php endif; ?>

                                                <?php if($publication->external_url): ?>
                                                    <a href="<?php echo e($publication->external_url); ?>" target="_blank" class="btn btn-outline-info btn-sm">
                                                        <i class="bi bi-link-45deg me-1"></i>External Link
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <?php if($publications->hasPages()): ?>
                <div class="d-flex justify-content-center mt-5">
                    <?php echo e($publications->appends(request()->query())->links()); ?>

                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-5">
                <i class="bi bi-journal-text text-muted mb-3" style="font-size: 4rem;"></i>

                <?php if(request('search') || request('type')): ?>
                    <h3 class="h4 text-muted mb-3">No publications found</h3>
                    <p class="text-muted mb-4">
                        No publications match your search criteria. Try adjusting your filters.
                    </p>
                    <a href="<?php echo e(route('publications.index')); ?>" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-1"></i>View All Publications
                    </a>
                <?php else: ?>
                    <h3 class="h4 text-muted mb-3">No publications available yet</h3>
                    <p class="text-muted">
                        Research publications and academic papers will be available here once added.
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Research Areas Info -->
        <?php if($publications->count() > 0): ?>
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-lightbulb text-primary me-2"></i>
                                Research Areas
                            </h5>
                            <div class="row g-4">
                                <div class="col-md-3">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-cpu text-primary me-2 mt-1"></i>
                                        <div>
                                            <h6 class="mb-1">Computer Science</h6>
                                            <small class="text-muted">Software engineering and systems</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-graph-up text-success me-2 mt-1"></i>
                                        <div>
                                            <h6 class="mb-1">Data Science</h6>
                                            <small class="text-muted">Analytics and machine learning</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-mortarboard text-info me-2 mt-1"></i>
                                        <div>
                                            <h6 class="mb-1">Educational Technology</h6>
                                            <small class="text-muted">Learning systems and pedagogy</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-gear text-warning me-2 mt-1"></i>
                                        <div>
                                            <h6 class="mb-1">Applied Research</h6>
                                            <small class="text-muted">Industry applications and solutions</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .publication-card {
        border: 1px solid #e5e7eb;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .publication-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .publication-meta {
        border-left: 1px solid #e5e7eb;
        padding-left: 1rem;
    }

    .badge {
        font-size: 0.75rem;
    }

    .btn-check:checked + .btn {
        background-color: #2563eb;
        border-color: #2563eb;
        color: white;
    }

    @media (max-width: 768px) {
        .publication-meta {
            border-left: none;
            border-top: 1px solid #e5e7eb;
            padding-left: 0;
            padding-top: 1rem;
            margin-top: 1rem;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle sort buttons
        const sortButtons = document.querySelectorAll('input[name="sort"]');
        sortButtons.forEach(function(button) {
            button.addEventListener('change', function() {
                const url = new URL(window.location);
                if (this.id === 'newest') {
                    url.searchParams.delete('sort');
                } else {
                    url.searchParams.set('sort', this.id);
                }
                window.location.href = url.toString();
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/publications/index.blade.php ENDPATH**/ ?>