<?php $__env->startSection('title', $publication->title); ?>

<?php $__env->startSection('content'); ?>
<!-- Publication Header -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb text-white-50">
                <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>" class="text-white-50">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('publications.index')); ?>" class="text-white-50">Publications</a></li>
                <li class="breadcrumb-item active text-white"><?php echo e(Str::limit($publication->title, 50)); ?></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-10">
                <h1 class="display-6 fw-bold mb-3"><?php echo e($publication->title); ?></h1>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <?php if($publication->type === 'article'): ?>
                        <span class="badge bg-light text-dark fs-6">Journal Article</span>
                    <?php elseif($publication->type === 'conference_paper'): ?>
                        <span class="badge bg-light text-dark fs-6">Conference Paper</span>
                    <?php elseif($publication->type === 'book'): ?>
                        <span class="badge bg-light text-dark fs-6">Book</span>
                    <?php elseif($publication->type === 'thesis'): ?>
                        <span class="badge bg-light text-dark fs-6">Thesis</span>
                    <?php elseif($publication->type === 'report'): ?>
                        <span class="badge bg-light text-dark fs-6">Report</span>
                    <?php else: ?>
                        <span class="badge bg-light text-dark fs-6">Other</span>
                    <?php endif; ?>

                    <?php if($publication->status === 'published'): ?>
                        <span class="badge bg-success fs-6">Published</span>
                    <?php else: ?>
                        <span class="badge bg-warning fs-6">Draft</span>
                    <?php endif; ?>

                    <span class="badge bg-info fs-6"><?php echo e($publication->publication_date->format('Y')); ?></span>
                </div>

                <p class="lead mb-4"><strong>Authors:</strong> <?php echo e($publication->authors); ?></p>

                <?php if($publication->journal): ?>
                    <p class="text-white-50 mb-4">
                        <i class="bi bi-journal me-2"></i>
                        <strong>Published in:</strong> <?php echo e($publication->journal); ?>

                        <?php if($publication->volume || $publication->issue || $publication->pages): ?>
                            <span>
                                <?php if($publication->volume): ?>, Vol. <?php echo e($publication->volume); ?><?php endif; ?>
                                <?php if($publication->issue): ?>, Issue <?php echo e($publication->issue); ?><?php endif; ?>
                                <?php if($publication->pages): ?>, pp. <?php echo e($publication->pages); ?><?php endif; ?>
                            </span>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>

                <div class="d-flex flex-wrap gap-3">
                    <?php if($publication->publication_file_path): ?>
                        <a href="<?php echo e(route('publications.download', $publication)); ?>" class="btn btn-light btn-lg" target="_blank">
                            <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
                        </a>
                    <?php endif; ?>

                    <?php if($publication->external_url): ?>
                        <a href="<?php echo e($publication->external_url); ?>" target="_blank" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-link-45deg me-2"></i>External Link
                        </a>
                    <?php endif; ?>

                    <?php if($publication->doi): ?>
                        <a href="https://doi.org/<?php echo e($publication->doi); ?>" target="_blank" class="btn btn-outline-light">
                            <i class="bi bi-link me-2"></i>DOI
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Publication Details -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Abstract -->
                <?php if($publication->abstract): ?>
                    <div class="card mb-4">
                        <div class="card-body p-4">
                            <h2 class="h4 mb-3">
                                <i class="bi bi-file-text text-primary me-2"></i>
                                Abstract
                            </h2>
                            <div class="abstract-content">
                                <?php echo nl2br(e($publication->abstract)); ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Description -->
                <?php if($publication->description): ?>
                    <div class="card mb-4">
                        <div class="card-body p-4">
                            <h2 class="h4 mb-3">
                                <i class="bi bi-info-circle text-primary me-2"></i>
                                Description
                            </h2>
                            <div class="description-content">
                                <?php echo nl2br(e($publication->description)); ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Keywords -->
                <?php if($publication->keywords): ?>
                    <div class="card mb-4">
                        <div class="card-body p-4">
                            <h2 class="h4 mb-3">
                                <i class="bi bi-key text-primary me-2"></i>
                                Keywords
                            </h2>
                            <div class="d-flex flex-wrap gap-2">
                                <?php $__currentLoopData = explode(',', $publication->keywords); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-light text-dark border fs-6"><?php echo e(trim($keyword)); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Citation -->
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-3">
                            <i class="bi bi-quote text-primary me-2"></i>
                            Citation
                        </h2>

                        <!-- APA Style -->
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">APA Style:</h6>
                            <div class="bg-light p-3 rounded">
                                <small class="font-monospace">
                                    <?php echo e($publication->authors); ?> (<?php echo e($publication->publication_date->format('Y')); ?>). <?php echo e($publication->title); ?>.
                                    <?php if($publication->journal): ?>
                                        <em><?php echo e($publication->journal); ?></em><?php if($publication->volume): ?>, <?php echo e($publication->volume); ?><?php endif; ?>@if($publication->issue)>(<?php echo e($publication->issue); ?>)<?php endif; ?>@if($publication->pages), <?php echo e($publication->pages); ?><?php endif; ?>.
                                    <?php endif; ?>
                                    <?php if($publication->doi): ?> https://doi.org/<?php echo e($publication->doi); ?><?php endif; ?>
                                </small>
                            </div>
                        </div>

                        <!-- MLA Style -->
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">MLA Style:</h6>
                            <div class="bg-light p-3 rounded">
                                <small class="font-monospace">
                                    <?php echo e($publication->authors); ?>. "<?php echo e($publication->title); ?>."
                                    <?php if($publication->journal): ?>
                                        <em><?php echo e($publication->journal); ?></em><?php if($publication->volume): ?>, vol. <?php echo e($publication->volume); ?><?php endif; ?>@if($publication->issue), no. <?php echo e($publication->issue); ?><?php endif; ?>,
                                    <?php endif; ?>
                                    <?php echo e($publication->publication_date->format('Y')); ?><?php if($publication->pages): ?>, pp. <?php echo e($publication->pages); ?><?php endif; ?>.
                                    <?php if($publication->doi): ?> DOI: <?php echo e($publication->doi); ?>.<?php endif; ?>
                                </small>
                            </div>
                        </div>

                        <!-- BibTeX -->
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">BibTeX:</h6>
                            <div class="bg-light p-3 rounded">
                                <small class="font-monospace">
{{ $publication->type === 'article' ? 'article' : 'inproceedings' }}<?php echo e('{'); ?><?php echo e(Str::slug($publication->title)); ?>,<br>
&nbsp;&nbsp;title = <?php echo e('{'); ?><?php echo e($publication->title); ?><?php echo e('}'); ?>,<br>
&nbsp;&nbsp;author = <?php echo e('{'); ?><?php echo e($publication->authors); ?><?php echo e('}'); ?>,<br>
<?php if($publication->journal): ?>
&nbsp;&nbsp;<?php echo e($publication->type === 'article' ? 'journal' : 'booktitle'); ?> = <?php echo e('{'); ?><?php echo e($publication->journal); ?><?php echo e('}'); ?>,<br>
<?php endif; ?>
<?php if($publication->volume): ?>
&nbsp;&nbsp;volume = <?php echo e('{'); ?><?php echo e($publication->volume); ?><?php echo e('}'); ?>,<br>
<?php endif; ?>
<?php if($publication->issue): ?>
&nbsp;&nbsp;number = <?php echo e('{'); ?><?php echo e($publication->issue); ?><?php echo e('}'); ?>,<br>
<?php endif; ?>
<?php if($publication->pages): ?>
&nbsp;&nbsp;pages = <?php echo e('{'); ?><?php echo e($publication->pages); ?><?php echo e('}'); ?>,<br>
<?php endif; ?>
&nbsp;&nbsp;year = <?php echo e('{'); ?><?php echo e($publication->publication_date->format('Y')); ?><?php echo e('}'); ?>,<br>
<?php if($publication->doi): ?>
&nbsp;&nbsp;doi = <?php echo e('{'); ?><?php echo e($publication->doi); ?><?php echo e('}'); ?>,<br>
<?php endif; ?>
<?php echo e('}'); ?>

                                </small>
                            </div>
                        </div>

                        <button class="btn btn-outline-primary btn-sm" onclick="copyToClipboard('apa')">
                            <i class="bi bi-clipboard me-1"></i>Copy APA
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="copyToClipboard('mla')">
                            <i class="bi bi-clipboard me-1"></i>Copy MLA
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="copyToClipboard('bibtex')">
                            <i class="bi bi-clipboard me-1"></i>Copy BibTeX
                        </button>
                    </div>
                </div>

                <!-- Contact -->
                <div class="card">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-3">
                            <i class="bi bi-chat-dots text-primary me-2"></i>
                            Questions About This Research?
                        </h2>
                        <p class="text-muted mb-3">
                            Interested in discussing this research or exploring collaboration opportunities?
                            Feel free to reach out.
                        </p>
                        <a href="<?php echo e(route('contact.show')); ?>" class="btn btn-primary">
                            <i class="bi bi-envelope me-2"></i>Contact Me
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Publication Info -->
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-info-circle text-primary me-2"></i>
                            Publication Details
                        </h5>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Type:</span>
                            <span class="fw-medium"><?php echo e(ucwords(str_replace('_', ' ', $publication->type))); ?></span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Status:</span>
                            <?php if($publication->status === 'published'): ?>
                                <span class="badge bg-success">Published</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Draft</span>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Publication Date:</span>
                            <span class="fw-medium"><?php echo e($publication->publication_date->format('M j, Y')); ?></span>
                        </div>

                        <?php if($publication->citation_count && $publication->citation_count > 0): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Citations:</span>
                                <span class="fw-medium text-success"><?php echo e($publication->citation_count); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if($publication->isbn): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">ISBN:</span>
                                <span class="fw-medium small"><?php echo e($publication->isbn); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if($publication->doi): ?>
                            <div class="mb-2">
                                <span class="text-muted d-block">DOI:</span>
                                <a href="https://doi.org/<?php echo e($publication->doi); ?>" target="_blank" class="small text-decoration-none">
                                    <?php echo e($publication->doi); ?>

                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($publication->publication_file_path || $publication->external_url): ?>
                            <hr class="my-3">
                            <div class="d-grid gap-2">
                                <?php if($publication->publication_file_path): ?>
                                    <a href="<?php echo e(route('publications.download', $publication)); ?>" target="_blank" class="btn btn-primary">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>
                                        Download PDF
                                    </a>
                                <?php endif; ?>

                                <?php if($publication->external_url): ?>
                                    <a href="<?php echo e($publication->external_url); ?>" target="_blank" class="btn btn-outline-secondary">
                                        <i class="bi bi-link-45deg me-1"></i>
                                        External Link
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tags -->
                <?php if($publication->tags && $publication->tags->count() > 0): ?>
                    <div class="card mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">
                                <i class="bi bi-tags text-primary me-2"></i>
                                Tags
                            </h5>

                            <div class="d-flex flex-wrap gap-2">
                                <?php $__currentLoopData = $publication->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-secondary"><?php echo e($tag->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Author -->
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-person-badge text-primary me-2"></i>
                            Author
                        </h5>

                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-person-circle text-muted me-3" style="font-size: 2rem;"></i>
                            <div>
                                <h6 class="mb-0"><?php echo e($publication->user->name ?? 'Author'); ?></h6>
                                <small class="text-muted">Researcher</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="<?php echo e(route('contact.show')); ?>" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-envelope me-1"></i>Contact Author
                            </a>
                            <a href="<?php echo e(route('about')); ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-person me-1"></i>About Me
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Related Publications -->
                <?php if($relatedPublications && $relatedPublications->count() > 0): ?>
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">
                                <i class="bi bi-collection text-primary me-2"></i>
                                Related Publications
                            </h5>

                            <?php $__currentLoopData = $relatedPublications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="mb-3">
                                    <h6 class="mb-1">
                                        <a href="<?php echo e(route('publications.show', $related)); ?>" class="text-decoration-none">
                                            <?php echo e(Str::limit($related->title, 60)); ?>

                                        </a>
                                    </h6>
                                    <small class="text-muted"><?php echo e($related->publication_date->format('M Y')); ?></small>
                                    <?php if($related->type !== $publication->type): ?>
                                        <span class="badge bg-light text-dark border ms-1"><?php echo e(ucwords(str_replace('_', ' ', $related->type))); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <div class="text-center mt-3">
                                <a href="<?php echo e(route('publications.index')); ?>" class="btn btn-outline-primary btn-sm">
                                    View All Publications <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .abstract-content,
    .description-content {
        line-height: 1.7;
        font-size: 1.05rem;
        text-align: justify;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.5);
    }

    .breadcrumb-item a {
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        text-decoration: underline;
    }

    .card {
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .font-monospace {
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.9rem;
        line-height: 1.4;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    function copyToClipboard(type) {
        let text = '';
        const title = "<?php echo e($publication->title); ?>";
        const authors = "<?php echo e($publication->authors); ?>";
        const year = "<?php echo e($publication->publication_date->format('Y')); ?>";
        const journal = "<?php echo e($publication->journal ?? ''); ?>";
        const volume = "<?php echo e($publication->volume ?? ''); ?>";
        const issue = "<?php echo e($publication->issue ?? ''); ?>";
        const pages = "<?php echo e($publication->pages ?? ''); ?>";
        const doi = "<?php echo e($publication->doi ?? ''); ?>";

        if (type === 'apa') {
            text = `${authors} (${year}). ${title}.`;
            if (journal) {
                text += ` ${journal}`;
                if (volume) text += `, ${volume}`;
                if (issue) text += `(${issue})`;
                if (pages) text += `, ${pages}`;
                text += '.';
            }
            if (doi) text += ` https://doi.org/${doi}`;
        } else if (type === 'mla') {
            text = `${authors}. "${title}."`;
            if (journal) {
                text += ` ${journal}`;
                if (volume) text += `, vol. ${volume}`;
                if (issue) text += `, no. ${issue}`;
                text += ',';
            }
            text += ` ${year}`;
            if (pages) text += `, pp. ${pages}`;
            text += '.';
            if (doi) text += ` DOI: ${doi}.`;
        } else if (type === 'bibtex') {
            const slug = title.toLowerCase().replace(/[^a-z0-9]+/g, '');
            text = `@article{${slug},\n  title = {${title}},\n  author = {${authors}},`;
            if (journal) text += `\n  journal = {${journal}},`;
            if (volume) text += `\n  volume = {${volume}},`;
            if (issue) text += `\n  number = {${issue}},`;
            if (pages) text += `\n  pages = {${pages}},`;
            text += `\n  year = {${year}},`;
            if (doi) text += `\n  doi = {${doi}},`;
            text += '\n}';
        }

        navigator.clipboard.writeText(text).then(function() {
            // Show toast or alert
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check me-1"></i>Copied!';
            btn.classList.add('btn-success');
            btn.classList.remove('btn-outline-primary');

            setTimeout(function() {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-primary');
            }, 2000);
        }).catch(function() {
            alert('Failed to copy to clipboard');
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/publications/show.blade.php ENDPATH**/ ?>