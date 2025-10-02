<?php $__env->startSection('page-title', 'View Publication'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1"><?php echo e($publication->title); ?></h1>
        <p class="text-muted mb-0">Publication Details</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.publications.edit', $publication)); ?>" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Edit Publication
        </a>
        <a href="<?php echo e(route('admin.publications.index')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Publications
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Publication Title and Authors -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title h4"><?php echo e($publication->title); ?></h2>
                <?php if($publication->authors): ?>
                    <p class="text-muted mb-3">
                        <strong>Authors:</strong> <?php echo e($publication->authors); ?>

                    </p>
                <?php endif; ?>

                <!-- Publication Details -->
                <div class="row g-3">
                    <?php if($publication->journal_name || $publication->venue): ?>
                        <div class="col-md-6">
                            <strong>Journal/Venue:</strong><br>
                            <?php echo e($publication->journal_name ?: $publication->venue); ?>

                        </div>
                    <?php endif; ?>

                    <?php if($publication->year || $publication->publication_date): ?>
                        <div class="col-md-6">
                            <strong>Publication Year:</strong><br>
                            <?php if($publication->publication_date): ?>
                                <?php echo e($publication->publication_date->format('Y')); ?>

                                <?php if($publication->publication_date->format('M d') !== 'Jan 01'): ?>
                                    (<?php echo e($publication->publication_date->format('M d, Y')); ?>)
                                <?php endif; ?>
                            <?php else: ?>
                                <?php echo e($publication->year); ?>

                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if($publication->volume || $publication->issue || $publication->pages): ?>
                        <div class="col-md-6">
                            <strong>Volume/Issue/Pages:</strong><br>
                            <?php if($publication->volume): ?>Vol. <?php echo e($publication->volume); ?><?php endif; ?>
                            <?php if($publication->issue): ?>, Issue <?php echo e($publication->issue); ?><?php endif; ?>
                            <?php if($publication->pages): ?>, pp. <?php echo e($publication->pages); ?><?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if($publication->doi): ?>
                        <div class="col-md-6">
                            <strong>DOI:</strong><br>
                            <a href="https://doi.org/<?php echo e($publication->doi); ?>" target="_blank" class="text-decoration-none">
                                <?php echo e($publication->doi); ?> <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Abstract -->
        <?php if($publication->abstract): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-file-text text-primary me-2"></i>
                        Abstract
                    </h5>
                    <div class="content">
                        <?php echo nl2br(e($publication->abstract)); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Keywords -->
        <?php if($publication->keywords): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-tags text-primary me-2"></i>
                        Keywords
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        <?php $__currentLoopData = explode(',', $publication->keywords); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge bg-light text-dark"><?php echo e(trim($keyword)); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Notes -->
        <?php if($publication->notes): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-journal-text text-primary me-2"></i>
                        Notes
                    </h5>
                    <div class="content">
                        <?php echo nl2br(e($publication->notes)); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Citation -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-quote text-primary me-2"></i>
                    Citation
                </h5>
                <div class="bg-light p-3 rounded">
                    <div id="citation-text" class="font-monospace small">
                        <?php if($publication->authors): ?><?php echo e($publication->authors); ?><?php endif; ?>
                        <?php if($publication->year || $publication->publication_date): ?> (<?php echo e($publication->publication_date ? $publication->publication_date->format('Y') : $publication->year); ?>).<?php endif; ?>
                        <?php if($publication->title): ?> <?php echo e($publication->title); ?>.<?php endif; ?>
                        <?php if($publication->journal_name || $publication->venue): ?> <em><?php echo e($publication->journal_name ?: $publication->venue); ?></em><?php endif; ?>
                        <?php if($publication->volume): ?>, <?php echo e($publication->volume); ?><?php endif; ?>
                        <?php if($publication->issue): ?>(<?php echo e($publication->issue); ?>)<?php endif; ?>
                        <?php if($publication->pages): ?>, <?php echo e($publication->pages); ?><?php endif; ?>
                        <?php if($publication->doi): ?>. https://doi.org/<?php echo e($publication->doi); ?><?php endif; ?>
                    </div>
                    <button class="btn btn-outline-secondary btn-sm mt-2" onclick="copyCitation()">
                        <i class="bi bi-clipboard me-1"></i>Copy Citation
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-lightning text-primary me-2"></i>
                    Quick Actions
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    <?php if($publication->url): ?>
                        <a href="<?php echo e($publication->url); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-link-45deg me-1"></i>View Publication
                        </a>
                    <?php endif; ?>
                    <?php if($publication->doi): ?>
                        <a href="https://doi.org/<?php echo e($publication->doi); ?>" target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-search me-1"></i>View DOI
                        </a>
                    <?php endif; ?>
                    <?php if($publication->pdf_file): ?>
                        <a href="<?php echo e(Storage::url($publication->pdf_file)); ?>" target="_blank" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-file-pdf me-1"></i>Download PDF
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('admin.publications.edit', $publication)); ?>" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit Publication
                    </a>
                    <form method="POST" action="<?php echo e(route('admin.publications.destroy', $publication)); ?>" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm-delete>
                            <i class="bi bi-trash me-1"></i>Delete Publication
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Publication Info -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Publication Information
                </h5>

                <div class="mb-3">
                    <label class="form-label fw-bold">Type</label>
                    <div>
                        <span class="badge bg-light text-dark fs-6">
                            <?php echo e(ucwords(str_replace('_', ' ', $publication->type))); ?>

                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <div>
                        <?php if($publication->status === 'published'): ?>
                            <span class="badge bg-success fs-6">Published</span>
                        <?php elseif($publication->status === 'accepted'): ?>
                            <span class="badge bg-info fs-6">Accepted</span>
                        <?php elseif($publication->status === 'under_review'): ?>
                            <span class="badge bg-warning fs-6">Under Review</span>
                        <?php elseif($publication->status === 'in_preparation'): ?>
                            <span class="badge bg-secondary fs-6">In Preparation</span>
                        <?php else: ?>
                            <span class="badge bg-light text-dark fs-6"><?php echo e(ucfirst($publication->status)); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Published</label>
                    <div>
                        <?php if($publication->is_published): ?>
                            <span class="badge bg-success">Published</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Draft</span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($publication->is_featured): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Featured</label>
                        <div>
                            <span class="badge bg-warning">Featured Publication</span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label fw-bold">Created</label>
                    <div><?php echo e($publication->created_at->format('F d, Y \a\t g:i A')); ?></div>
                </div>

                <div>
                    <label class="form-label fw-bold">Last Updated</label>
                    <div><?php echo e($publication->updated_at->format('F d, Y \a\t g:i A')); ?></div>
                </div>
            </div>
        </div>

        <!-- Files and Links -->
        <?php if($publication->pdf_file || $publication->url || $publication->doi): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-link text-primary me-2"></i>
                        Files & Links
                    </h5>

                    <?php if($publication->pdf_file): ?>
                        <div class="mb-2">
                            <a href="<?php echo e(Storage::url($publication->pdf_file)); ?>" target="_blank" class="btn btn-outline-success btn-sm w-100">
                                <i class="bi bi-file-pdf me-1"></i>Download PDF
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if($publication->url): ?>
                        <div class="mb-2">
                            <a href="<?php echo e($publication->url); ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-link-45deg me-1"></i>View Publication
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if($publication->doi): ?>
                        <div class="mb-2">
                            <a href="https://doi.org/<?php echo e($publication->doi); ?>" target="_blank" class="btn btn-outline-info btn-sm w-100">
                                <i class="bi bi-search me-1"></i>View DOI
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Statistics (if this is a public publication) -->
        <?php if($publication->is_published): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-bar-chart text-primary me-2"></i>
                        Public Visibility
                    </h5>
                    <div class="text-center">
                        <p class="text-muted mb-2">This publication is visible to the public</p>
                        <a href="<?php echo e(url('/publications/' . Str::slug($publication->title))); ?>" target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-eye me-1"></i>View Public Page
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .content {
        line-height: 1.6;
        color: #4a5568;
    }

    .content p {
        margin-bottom: 1rem;
    }

    .badge.fs-6 {
        font-size: 0.875rem !important;
    }

    #citation-text {
        line-height: 1.5;
        word-break: break-word;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    function copyCitation() {
        const citationText = document.getElementById('citation-text').textContent;

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(citationText).then(function() {
                showCopyFeedback();
            }).catch(function(err) {
                console.error('Failed to copy: ', err);
                fallbackCopyTextToClipboard(citationText);
            });
        } else {
            fallbackCopyTextToClipboard(citationText);
        }
    }

    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;

        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showCopyFeedback();
            }
        } catch (err) {
            console.error('Failed to copy: ', err);
        }

        document.body.removeChild(textArea);
    }

    function showCopyFeedback() {
        const button = document.querySelector('button[onclick="copyCitation()"]');
        const originalText = button.innerHTML;

        button.innerHTML = '<i class="bi bi-check me-1"></i>Copied!';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');

        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/admin/publications/show.blade.php ENDPATH**/ ?>