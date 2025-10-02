<?php $__env->startSection('title', $post->title); ?>

<?php $__env->startSection('content'); ?>
<!-- Post Header -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb text-white-50">
                <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>" class="text-white-50">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('blog.index')); ?>" class="text-white-50">Blog</a></li>
                <li class="breadcrumb-item active text-white"><?php echo e(Str::limit($post->title, 50)); ?></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-10">
                <h1 class="display-6 fw-bold mb-3"><?php echo e($post->title); ?></h1>

                <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                    <div class="d-flex align-items-center text-white-50">
                        <i class="bi bi-person-circle me-2"></i>
                        <span><?php echo e($post->user->name ?? 'Author'); ?></span>
                    </div>

                    <div class="d-flex align-items-center text-white-50">
                        <i class="bi bi-calendar me-2"></i>
                        <span><?php echo e($post->created_at->format('F j, Y')); ?></span>
                    </div>

                    <div class="d-flex align-items-center text-white-50">
                        <i class="bi bi-clock me-2"></i>
                        <span><?php echo e($post->reading_time ?? ceil(str_word_count(strip_tags($post->content)) / 200)); ?> min read</span>
                    </div>

                    <?php if($post->status === 'published'): ?>
                        <span class="badge bg-success fs-6">Published</span>
                    <?php else: ?>
                        <span class="badge bg-warning fs-6">Draft</span>
                    <?php endif; ?>
                </div>

                <?php if($post->excerpt): ?>
                    <p class="lead text-white-50 mb-0"><?php echo e($post->excerpt); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Post Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <article class="blog-post">
                    <!-- Featured Image -->
                    <?php if($post->featured_image): ?>
                        <div class="mb-4">
                            <img src="<?php echo e(asset('storage/' . $post->featured_image)); ?>"
                                 class="img-fluid rounded"
                                 alt="<?php echo e($post->title); ?>"
                                 style="width: 100%; max-height: 400px; object-fit: cover;">
                        </div>
                    <?php endif; ?>

                    <!-- Post Content -->
                    <div class="post-content">
                        <?php echo nl2br(e($post->content)); ?>

                    </div>

                    <!-- Tags -->
                    <?php if($post->tags && $post->tags->count() > 0): ?>
                        <div class="mt-5 pt-4 border-top">
                            <h6 class="text-muted mb-3">Tagged with:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <?php $__currentLoopData = $post->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('blog.index', ['tag' => $tag->slug])); ?>"
                                       class="badge bg-secondary text-decoration-none fs-6">
                                        <?php echo e($tag->name); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Share Buttons -->
                    <div class="mt-5 pt-4 border-top">
                        <h6 class="text-muted mb-3">Share this post:</h6>
                        <div class="d-flex gap-2">
                            <a href="https://twitter.com/intent/tweet?text=<?php echo e(urlencode($post->title)); ?>&url=<?php echo e(urlencode(request()->fullUrl())); ?>"
                               target="_blank"
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-twitter me-1"></i>Twitter
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(request()->fullUrl())); ?>"
                               target="_blank"
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-facebook me-1"></i>Facebook
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo e(urlencode(request()->fullUrl())); ?>"
                               target="_blank"
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-linkedin me-1"></i>LinkedIn
                            </a>
                            <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard()">
                                <i class="bi bi-link me-1"></i>Copy Link
                            </button>
                        </div>
                    </div>

                    <!-- Author Bio -->
                    <div class="mt-5 pt-4 border-top">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-person-circle text-muted me-3" style="font-size: 3rem;"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-2"><?php echo e($post->user->name ?? 'Author'); ?></h5>
                                        <p class="text-muted mb-3">
                                            <?php if($post->user->bio ?? null): ?>
                                                <?php echo e(Str::limit($post->user->bio, 200)); ?>

                                            <?php else: ?>
                                                Passionate about education, research, and technology. Sharing insights and experiences through writing and teaching.
                                            <?php endif; ?>
                                        </p>
                                        <div class="d-flex gap-2">
                                            <a href="<?php echo e(route('about')); ?>" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-person me-1"></i>About Me
                                            </a>
                                            <a href="<?php echo e(route('contact.show')); ?>" class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-envelope me-1"></i>Contact
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <?php if($previousPost || $nextPost): ?>
                        <div class="mt-5 pt-4 border-top">
                            <div class="row">
                                <?php if($previousPost): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-arrow-left text-muted me-2 mt-1"></i>
                                            <div>
                                                <small class="text-muted">Previous Post</small>
                                                <h6 class="mb-0">
                                                    <a href="<?php echo e(route('blog.show', $previousPost->slug)); ?>" class="text-decoration-none">
                                                        <?php echo e($previousPost->title); ?>

                                                    </a>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if($nextPost): ?>
                                    <div class="col-md-6 mb-3 text-md-end">
                                        <div class="d-flex align-items-start justify-content-md-end">
                                            <div class="text-md-end me-md-2">
                                                <small class="text-muted">Next Post</small>
                                                <h6 class="mb-0">
                                                    <a href="<?php echo e(route('blog.show', $nextPost->slug)); ?>" class="text-decoration-none">
                                                        <?php echo e($nextPost->title); ?>

                                                    </a>
                                                </h6>
                                            </div>
                                            <i class="bi bi-arrow-right text-muted ms-2 mt-1 d-none d-md-block"></i>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Post Meta -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-info-circle text-primary me-2"></i>
                            Post Information
                        </h5>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Published:</span>
                            <span class="fw-medium"><?php echo e($post->created_at->format('M j, Y')); ?></span>
                        </div>

                        <?php if($post->updated_at->gt($post->created_at)): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Updated:</span>
                                <span class="fw-medium"><?php echo e($post->updated_at->format('M j, Y')); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Reading Time:</span>
                            <span class="fw-medium"><?php echo e($post->reading_time ?? ceil(str_word_count(strip_tags($post->content)) / 200)); ?> min</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Words:</span>
                            <span class="fw-medium"><?php echo e(number_format(str_word_count(strip_tags($post->content)))); ?></span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Status:</span>
                            <?php if($post->status === 'published'): ?>
                                <span class="badge bg-success">Published</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Draft</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Table of Contents -->
                <?php if($post->content): ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-list-ul text-primary me-2"></i>
                                Table of Contents
                            </h5>
                            <div id="tableOfContents">
                                <p class="text-muted">Table of contents will be generated from headings in the post.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Recent Posts -->
                <?php if($recentPosts && $recentPosts->count() > 0): ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-clock-history text-primary me-2"></i>
                                Recent Posts
                            </h5>

                            <?php $__currentLoopData = $recentPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recentPost): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex align-items-start mb-3">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="<?php echo e(route('blog.show', $recentPost->slug)); ?>" class="text-decoration-none">
                                                <?php echo e(Str::limit($recentPost->title, 50)); ?>

                                            </a>
                                        </h6>
                                        <small class="text-muted"><?php echo e($recentPost->created_at->format('M j, Y')); ?></small>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <div class="text-center mt-3">
                                <a href="<?php echo e(route('blog.index')); ?>" class="btn btn-outline-primary btn-sm">
                                    View All Posts <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Related Posts -->
                <?php if($relatedPosts && $relatedPosts->count() > 0): ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-collection text-primary me-2"></i>
                                Related Posts
                            </h5>

                            <?php $__currentLoopData = $relatedPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedPost): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="mb-3">
                                    <h6 class="mb-1">
                                        <a href="<?php echo e(route('blog.show', $relatedPost->slug)); ?>" class="text-decoration-none">
                                            <?php echo e(Str::limit($relatedPost->title, 50)); ?>

                                        </a>
                                    </h6>
                                    <small class="text-muted"><?php echo e($relatedPost->created_at->format('M j, Y')); ?></small>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Subscribe -->
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">
                            <i class="bi bi-bell text-primary me-2"></i>
                            Stay Updated
                        </h5>
                        <p class="text-muted mb-3">
                            Interested in more content like this? Get in touch to stay updated
                            on new posts and research.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="<?php echo e(route('contact.show')); ?>" class="btn btn-primary">
                                <i class="bi bi-envelope me-1"></i>Contact Me
                            </a>
                            <a href="<?php echo e(route('blog.index')); ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-journal-text me-1"></i>More Posts
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .post-content {
        line-height: 1.8;
        font-size: 1.1rem;
        color: #374151;
    }

    .post-content h1,
    .post-content h2,
    .post-content h3,
    .post-content h4,
    .post-content h5,
    .post-content h6 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #1f2937;
        scroll-margin-top: 2rem;
    }

    .post-content p {
        margin-bottom: 1.5rem;
        text-align: justify;
    }

    .post-content blockquote {
        border-left: 4px solid #2563eb;
        padding-left: 1rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #6b7280;
        background-color: #f8fafc;
        padding: 1rem;
        border-radius: 0.375rem;
    }

    .post-content code {
        background-color: #f3f4f6;
        color: #dc2626;
        padding: 0.125rem 0.25rem;
        border-radius: 0.25rem;
        font-size: 0.9em;
    }

    .post-content pre {
        background-color: #1f2937;
        color: #f9fafb;
        padding: 1rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        margin: 1.5rem 0;
    }

    .post-content pre code {
        background-color: transparent;
        color: inherit;
        padding: 0;
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

    .blog-post img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1rem 0;
    }

    .table-of-contents {
        position: sticky;
        top: 2rem;
    }

    .table-of-contents a {
        color: #6b7280;
        text-decoration: none;
        padding: 0.25rem 0;
        display: block;
        border-left: 2px solid transparent;
        padding-left: 0.5rem;
    }

    .table-of-contents a:hover,
    .table-of-contents a.active {
        color: #2563eb;
        border-left-color: #2563eb;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Generate Table of Contents
        generateTableOfContents();

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
    });

    function generateTableOfContents() {
        const headings = document.querySelectorAll('.post-content h1, .post-content h2, .post-content h3, .post-content h4, .post-content h5, .post-content h6');
        const tocContainer = document.getElementById('tableOfContents');

        if (headings.length === 0) {
            tocContainer.innerHTML = '<p class="text-muted">No headings found in this post.</p>';
            return;
        }

        let tocHTML = '<ul class="list-unstyled table-of-contents">';
        headings.forEach((heading, index) => {
            const id = 'heading-' + index;
            heading.id = id;

            const level = parseInt(heading.tagName.charAt(1));
            const indent = (level - 1) * 1;

            tocHTML += `<li style="margin-left: ${indent}rem;">
                <a href="#${id}" class="toc-link">${heading.textContent}</a>
            </li>`;
        });
        tocHTML += '</ul>';

        tocContainer.innerHTML = tocHTML;
    }

    function copyToClipboard() {
        navigator.clipboard.writeText(window.location.href).then(function() {
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check me-1"></i>Copied!';
            btn.classList.add('btn-success');
            btn.classList.remove('btn-outline-secondary');

            setTimeout(function() {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
            }, 2000);
        }).catch(function() {
            alert('Failed to copy link to clipboard');
        });
    }

    // Update active TOC link on scroll
    window.addEventListener('scroll', function() {
        const headings = document.querySelectorAll('.post-content h1, .post-content h2, .post-content h3, .post-content h4, .post-content h5, .post-content h6');
        const tocLinks = document.querySelectorAll('.toc-link');

        let activeHeading = null;
        headings.forEach(heading => {
            const rect = heading.getBoundingClientRect();
            if (rect.top <= 100) {
                activeHeading = heading;
            }
        });

        tocLinks.forEach(link => link.classList.remove('active'));
        if (activeHeading) {
            const activeLink = document.querySelector(`a[href="#${activeHeading.id}"]`);
            if (activeLink) {
                activeLink.classList.add('active');
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/blog/show.blade.php ENDPATH**/ ?>