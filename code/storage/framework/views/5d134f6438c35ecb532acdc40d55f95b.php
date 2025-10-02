<?php $__env->startSection('title', $publication->title ?? 'Publication Details'); ?>

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
                            <a href="<?php echo e(route('publications.index')); ?>" class="text-white text-decoration-none">Publications</a>
                        </li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">
                            <?php echo e(Str::limit($publication->title ?? 'Publication', 50)); ?>

                        </li>
                    </ol>
                </nav>

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

                <!-- Publication Title -->
                <h1 class="display-5 fw-bold mb-4"><?php echo e($publication->title ?? 'Publication Title'); ?></h1>

                <!-- Authors -->
                <?php if($publication->authors ?? false): ?>
                    <p class="lead mb-4">
                        <strong>Authors:</strong> <?php echo e($publication->authors); ?>

                    </p>
                <?php endif; ?>

                <!-- Publication Info -->
                <div class="row text-center">
                    <?php if($publication->published_date): ?>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="text-white-50">
                            <i class="bi bi-calendar" style="font-size: 1.5rem;"></i>
                            <div class="mt-2">
                                <strong class="text-white d-block"><?php echo e($publication->published_date->format('M Y')); ?></strong>
                                <small>Published</small>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($publication->citation_count): ?>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="text-white-50">
                            <i class="bi bi-quote" style="font-size: 1.5rem;"></i>
                            <div class="mt-2">
                                <strong class="text-white d-block"><?php echo e($publication->citation_count); ?></strong>
                                <small>Citations</small>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($publication->download_count): ?>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="text-white-50">
                            <i class="bi bi-download" style="font-size: 1.5rem;"></i>
                            <div class="mt-2">
                                <strong class="text-white d-block"><?php echo e($publication->download_count); ?></strong>
                                <small>Downloads</small>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($publication->impact_factor): ?>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="text-white-50">
                            <i class="bi bi-graph-up" style="font-size: 1.5rem;"></i>
                            <div class="mt-2">
                                <strong class="text-white d-block"><?php echo e($publication->impact_factor); ?></strong>
                                <small>Impact Factor</small>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Publication Content -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Publication Details -->
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="bi bi-info-circle me-2"></i>Publication Details</h4>
                    </div>
                    <div class="card-body p-4">
                        <!-- Journal/Conference Information -->
                        <?php if($publication->journal || $publication->conference): ?>
                            <div class="mb-4">
                                <?php if($publication->journal): ?>
                                    <h6>Journal Information</h6>
                                    <p class="mb-2">
                                        <strong>Journal:</strong> <em><?php echo e($publication->journal); ?></em>
                                    </p>
                                    <?php if($publication->volume): ?>
                                        <p class="mb-2"><strong>Volume:</strong> <?php echo e($publication->volume); ?></p>
                                    <?php endif; ?>
                                    <?php if($publication->issue): ?>
                                        <p class="mb-2"><strong>Issue:</strong> <?php echo e($publication->issue); ?></p>
                                    <?php endif; ?>
                                    <?php if($publication->pages): ?>
                                        <p class="mb-2"><strong>Pages:</strong> <?php echo e($publication->pages); ?></p>
                                    <?php endif; ?>
                                <?php elseif($publication->conference): ?>
                                    <h6>Conference Information</h6>
                                    <p class="mb-2">
                                        <strong>Conference:</strong> <em><?php echo e($publication->conference); ?></em>
                                    </p>
                                    <?php if($publication->conference_location): ?>
                                        <p class="mb-2"><strong>Location:</strong> <?php echo e($publication->conference_location); ?></p>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if($publication->publisher): ?>
                                    <p class="mb-2"><strong>Publisher:</strong> <?php echo e($publication->publisher); ?></p>
                                <?php endif; ?>

                                <?php if($publication->isbn): ?>
                                    <p class="mb-2"><strong>ISBN:</strong> <?php echo e($publication->isbn); ?></p>
                                <?php endif; ?>

                                <?php if($publication->doi): ?>
                                    <p class="mb-2">
                                        <strong>DOI:</strong>
                                        <a href="https://doi.org/<?php echo e($publication->doi); ?>" target="_blank" rel="noopener" class="text-decoration-none">
                                            <?php echo e($publication->doi); ?>

                                        </a>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Publication Actions -->
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <?php if($publication->pdf_file): ?>
                                <a href="<?php echo e(Storage::url($publication->pdf_file)); ?>" target="_blank" class="btn btn-danger">
                                    <i class="bi bi-file-pdf me-2"></i>Download PDF
                                </a>
                            <?php endif; ?>

                            <?php if($publication->doi): ?>
                                <a href="https://doi.org/<?php echo e($publication->doi); ?>" target="_blank" rel="noopener" class="btn btn-outline-secondary">
                                    <i class="bi bi-link me-2"></i>View on Publisher Site
                                </a>
                            <?php endif; ?>

                            <?php if($publication->url): ?>
                                <a href="<?php echo e($publication->url); ?>" target="_blank" rel="noopener" class="btn btn-outline-info">
                                    <i class="bi bi-globe me-2"></i>External Link
                                </a>
                            <?php endif; ?>

                            <button type="button" class="btn btn-outline-primary" onclick="copyToClipboard('<?php echo e(request()->url()); ?>')">
                                <i class="bi bi-share me-2"></i>Share
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Abstract -->
                <?php if($publication->abstract ?? false): ?>
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-file-text me-2"></i>Abstract</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="abstract-content">
                            <?php echo nl2br(e($publication->abstract)); ?>

                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Keywords -->
                <?php if($publication->keywords ?? false): ?>
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-warning text-white">
                        <h4 class="mb-0"><i class="bi bi-tags me-2"></i>Keywords</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap gap-2">
                            <?php $__currentLoopData = explode(',', $publication->keywords); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-light text-dark">#<?php echo e(trim($keyword)); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Bibtex Citation -->
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0"><i class="bi bi-quote me-2"></i>Citation</h4>
                    </div>
                    <div class="card-body p-4">
                        <!-- APA Citation -->
                        <div class="mb-3">
                            <h6>APA Format:</h6>
                            <div class="bg-light p-3 rounded">
                                <small class="font-monospace">
                                    <?php echo e($publication->authors ?? 'Author, A.'); ?>

                                    (<?php echo e($publication->published_date ? $publication->published_date->format('Y') : 'Year'); ?>).
                                    <?php echo e($publication->title); ?>.
                                    <?php if($publication->journal): ?>
                                        <em><?php echo e($publication->journal); ?></em><?php if($publication->volume): ?>, <?php echo e($publication->volume); ?><?php endif; ?>@if($publication->issue))(<?php echo e($publication->issue); ?>)<?php endif; ?>@if($publication->pages), <?php echo e($publication->pages); ?><?php endif; ?>.
                                    <?php elseif($publication->conference): ?>
                                        In <em><?php echo e($publication->conference); ?></em>.
                                    <?php endif; ?>
                                    <?php if($publication->doi): ?>
                                        https://doi.org/<?php echo e($publication->doi); ?>

                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>

                        <!-- BibTeX Citation -->
                        <?php if($publication->bibtex ?? false): ?>
                            <div class="mb-3">
                                <h6>BibTeX:</h6>
                                <div class="bg-dark text-light p-3 rounded">
                                    <pre class="mb-0"><code><?php echo e($publication->bibtex); ?></code></pre>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="copyToClipboard(`<?php echo e(addslashes($publication->bibtex)); ?>`)">
                                    <i class="bi bi-clipboard me-1"></i>Copy BibTeX
                                </button>
                            </div>
                        <?php else: ?>
                            <!-- Generate basic BibTeX -->
                            <div class="mb-3">
                                <h6>BibTeX:</h6>
                                <div class="bg-dark text-light p-3 rounded">
                                    <pre class="mb-0"><code>@article<?php echo e({ Str::slug($publication->title ?? 'publication') . $publication->published_date?->format('Y') ?? date('Y')); ?>,
  title=<?php echo e("{"); ?><?php echo e($publication->title ?? 'Publication Title'); ?><?php echo e("}"); ?>,
  author=<?php echo e("{"); ?><?php echo e($publication->authors ?? 'Author Name'); ?><?php echo e("}"); ?>,
  <?php if($publication->journal): ?>journal=<?php echo e("{"); ?><?php echo e($publication->journal); ?><?php echo e("}"); ?>,<?php endif; ?>
  <?php if($publication->volume): ?>volume=<?php echo e("{"); ?><?php echo e($publication->volume); ?><?php echo e("}"); ?>,<?php endif; ?>
  <?php if($publication->issue): ?>number=<?php echo e("{"); ?><?php echo e($publication->issue); ?><?php echo e("}"); ?>,<?php endif; ?>
  <?php if($publication->pages): ?>pages=<?php echo e("{"); ?><?php echo e($publication->pages); ?><?php echo e("}"); ?>,<?php endif; ?>
  year=<?php echo e("{"); ?><?php echo e($publication->published_date ? $publication->published_date->format('Y') : date('Y')); ?><?php echo e("}"); ?>,
  <?php if($publication->publisher): ?>publisher=<?php echo e("{"); ?><?php echo e($publication->publisher); ?><?php echo e("}"); ?>,<?php endif; ?>
  <?php if($publication->doi): ?>doi=<?php echo e("{"); ?><?php echo e($publication->doi); ?><?php echo e("}"); ?>,<?php endif; ?>
}</code></pre>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Related Publications -->
                <?php if(isset($relatedPublications) && $relatedPublications->count() > 0): ?>
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0"><i class="bi bi-journals me-2"></i>Related Publications</h4>
                    </div>
                    <div class="card-body p-0">
                        <?php $__currentLoopData = $relatedPublications->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedPub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-3 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                                <h6 class="mb-2">
                                    <a href="<?php echo e(route('publications.show', $relatedPub->slug)); ?>"
                                       class="text-decoration-none">
                                        <?php echo e($relatedPub->title); ?>

                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <?php echo e($relatedPub->published_date ? $relatedPub->published_date->format('Y') : 'Recent'); ?> •
                                    <?php echo e(ucfirst($relatedPub->type)); ?>

                                </small>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Publication Metrics -->
                <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 2rem;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Publication Metrics</h5>
                    </div>
                    <div class="card-body">
                        <?php if($publication->published_date): ?>
                        <div class="mb-3">
                            <strong>Publication Date:</strong><br>
                            <span class="text-muted"><?php echo e($publication->published_date->format('F j, Y')); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($publication->citation_count): ?>
                        <div class="mb-3">
                            <strong>Citations:</strong><br>
                            <span class="text-muted"><?php echo e($publication->citation_count); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($publication->download_count): ?>
                        <div class="mb-3">
                            <strong>Downloads:</strong><br>
                            <span class="text-muted"><?php echo e($publication->download_count); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($publication->impact_factor): ?>
                        <div class="mb-3">
                            <strong>Impact Factor:</strong><br>
                            <span class="text-muted"><?php echo e($publication->impact_factor); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($publication->quartile): ?>
                        <div class="mb-3">
                            <strong>Journal Quartile:</strong><br>
                            <span class="text-muted">Q<?php echo e($publication->quartile); ?></span>
                        </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <strong>Type:</strong><br>
                            <span class="text-muted"><?php echo e(ucfirst($publication->type ?? 'Publication')); ?></span>
                        </div>

                        <?php if($publication->language): ?>
                        <div class="mb-3">
                            <strong>Language:</strong><br>
                            <span class="text-muted"><?php echo e($publication->language); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Author Information -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-person me-2"></i>Author</h5>
                    </div>
                    <div class="card-body text-center">
                        <?php if($teacher->avatar ?? false): ?>
                            <img src="<?php echo e(Storage::url($teacher->avatar)); ?>"
                                 alt="<?php echo e($teacher->name ?? 'Author'); ?>"
                                 class="rounded-circle mb-3"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px;">
                                <i class="bi bi-person-circle text-muted" style="font-size: 2.5rem;"></i>
                            </div>
                        <?php endif; ?>

                        <h6 class="mb-2"><?php echo e($teacher->name ?? 'Dr. [Your Name]'); ?></h6>
                        <p class="text-muted mb-3"><?php echo e($teacher->title ?? 'Professor'); ?></p>

                        <div class="d-flex gap-2 justify-content-center">
                            <a href="<?php echo e(route('about')); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-person me-1"></i>Profile
                            </a>
                            <a href="<?php echo e(route('contact.show')); ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-envelope me-1"></i>Contact
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Share Publication -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="bi bi-share me-2"></i>Share This Publication</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="https://twitter.com/intent/tweet?url=<?php echo e(urlencode(request()->url())); ?>&text=<?php echo e(urlencode($publication->title)); ?>"
                               target="_blank" rel="noopener"
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-twitter me-2"></i>Share on Twitter
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo e(urlencode(request()->url())); ?>"
                               target="_blank" rel="noopener"
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-linkedin me-2"></i>Share on LinkedIn
                            </a>
                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="copyToClipboard('<?php echo e(request()->url()); ?>')">
                                <i class="bi bi-link me-2"></i>Copy Link
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Research Areas -->
                <?php if($publication->research_areas ?? false): ?>
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Research Areas</h5>
                    </div>
                    <div class="card-body">
                        <?php $__currentLoopData = explode(',', $publication->research_areas); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge bg-light text-dark me-1 mb-1"><?php echo e(trim($area)); ?></span>
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
            <?php if(isset($previousPublication)): ?>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-0">
                        <div class="card-body">
                            <small class="text-muted">Previous Publication</small>
                            <h6 class="mt-2">
                                <a href="<?php echo e(route('publications.show', $previousPublication->slug)); ?>"
                                   class="text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i><?php echo e(Str::limit($previousPublication->title, 60)); ?>

                                </a>
                            </h6>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(isset($nextPublication)): ?>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-0">
                        <div class="card-body text-end">
                            <small class="text-muted">Next Publication</small>
                            <h6 class="mt-2">
                                <a href="<?php echo e(route('publications.show', $nextPublication->slug)); ?>"
                                   class="text-decoration-none">
                                    <?php echo e(Str::limit($nextPublication->title, 60)); ?><i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-3">
            <a href="<?php echo e(route('publications.index')); ?>" class="btn btn-success">
                <i class="bi bi-grid me-2"></i>View All Publications
            </a>
        </div>
    </div>
</section>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/public/publications/show.blade.php ENDPATH**/ ?>