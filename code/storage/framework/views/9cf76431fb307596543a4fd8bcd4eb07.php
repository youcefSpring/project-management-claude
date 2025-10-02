<?php $__env->startSection('title', 'Blog'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold">Blog</h1>
                <p class="lead mb-0">Thoughts, insights, and reflections on teaching, research, and technology.</p>
            </div>
            <div class="col-lg-4 text-center">
                <i class="bi bi-pencil-square" style="font-size: 6rem; opacity: 0.7;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Blog Content -->
<section class="py-5">
    <div class="container">
        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form method="GET" action="<?php echo e(route('blog.index')); ?>" class="d-flex gap-2">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search blog posts by title or content..."
                           value="<?php echo e(request('search')); ?>">
                    <select name="tag" class="form-select" style="max-width: 200px;">
                        <option value="">All Tags</option>
                        <?php $__currentLoopData = $availableTags ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tag->slug); ?>" <?php echo e(request('tag') === $tag->slug ? 'selected' : ''); ?>>
                                <?php echo e($tag->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search"></i>
                    </button>
                    <?php if(request('search') || request('tag')): ?>
                        <a href="<?php echo e(route('blog.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="text-muted"><?php echo e($posts->total()); ?> post(s) found</span>
            </div>
        </div>

        <?php if(request('search') || request('tag')): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <?php if(request('search')): ?>
                    Showing results for "<strong><?php echo e(request('search')); ?></strong>"
                <?php endif; ?>
                <?php if(request('tag')): ?>
                    <?php if(request('search')): ?> with tag <?php else: ?> Showing posts tagged <?php endif; ?>
                    <strong><?php echo e($availableTags->where('slug', request('tag'))->first()?->name ?? request('tag')); ?></strong>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Blog Posts -->
        <?php if($posts->count() > 0): ?>
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <article class="card blog-card mb-4">
                            <?php if($post->featured_image): ?>
                                <div class="blog-image-container">
                                    <img src="<?php echo e(asset('storage/' . $post->featured_image)); ?>"
                                         class="card-img-top blog-image"
                                         alt="<?php echo e($post->title); ?>"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                    <div class="blog-placeholder" style="display: none;">
                                        <i class="bi bi-file-text text-muted"></i>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i>
                                        <?php echo e($post->created_at->format('M j, Y')); ?>

                                        <?php if($post->created_at->diffInDays() <= 7): ?>
                                            <span class="badge bg-warning ms-2">New</span>
                                        <?php endif; ?>
                                    </small>

                                    <?php if($post->status === 'published'): ?>
                                        <span class="badge bg-success">Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Draft</span>
                                    <?php endif; ?>
                                </div>

                                <h2 class="h4 card-title mb-3">
                                    <a href="<?php echo e(route('blog.show', $post->slug)); ?>" class="text-decoration-none">
                                        <?php echo e($post->title); ?>

                                    </a>
                                </h2>

                                <?php if($post->excerpt): ?>
                                    <p class="card-text text-muted mb-3">
                                        <?php echo e($post->excerpt); ?>

                                    </p>
                                <?php else: ?>
                                    <p class="card-text text-muted mb-3">
                                        <?php echo e(Str::limit(strip_tags($post->content), 200)); ?>

                                    </p>
                                <?php endif; ?>

                                <!-- Tags -->
                                <?php if($post->tags && $post->tags->count() > 0): ?>
                                    <div class="mb-3">
                                        <?php $__currentLoopData = $post->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e(route('blog.index', ['tag' => $tag->slug])); ?>"
                                               class="badge bg-secondary text-decoration-none me-1">
                                                <?php echo e($tag->name); ?>

                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="<?php echo e(route('blog.show', $post->slug)); ?>" class="btn btn-primary">
                                        <i class="bi bi-book-open me-1"></i>Read More
                                    </a>

                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        <?php echo e($post->reading_time ?? ceil(str_word_count(strip_tags($post->content)) / 200)); ?> min read
                                    </small>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Pagination -->
                    <?php if($posts->hasPages()): ?>
                        <div class="d-flex justify-content-center">
                            <?php echo e($posts->appends(request()->query())->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Search Widget -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-search text-primary me-2"></i>
                                Search Posts
                            </h5>
                            <form method="GET" action="<?php echo e(route('blog.index')); ?>">
                                <div class="input-group">
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="Search..."
                                           value="<?php echo e(request('search')); ?>">
                                    <button class="btn btn-outline-primary" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Recent Posts -->
                    <?php if($recentPosts ?? null): ?>
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
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Popular Tags -->
                    <?php if($popularTags ?? null): ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-tags text-primary me-2"></i>
                                    Popular Tags
                                </h5>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php $__currentLoopData = $popularTags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(route('blog.index', ['tag' => $tag->slug])); ?>"
                                           class="badge bg-light text-dark border text-decoration-none">
                                            <?php echo e($tag->name); ?>

                                            <?php if($tag->posts_count ?? null): ?>
                                                <span class="text-muted">(<?php echo e($tag->posts_count); ?>)</span>
                                            <?php endif; ?>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Archive -->
                    <?php if($archive ?? null): ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-archive text-primary me-2"></i>
                                    Archive
                                </h5>
                                <?php $__currentLoopData = $archive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <a href="<?php echo e(route('blog.index', ['month' => $month->month, 'year' => $month->year])); ?>"
                                           class="text-decoration-none">
                                            <?php echo e(\Carbon\Carbon::create($month->year, $month->month)->format('F Y')); ?>

                                        </a>
                                        <span class="badge bg-light text-dark"><?php echo e($month->count); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- About -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-person text-primary me-2"></i>
                                About This Blog
                            </h5>
                            <p class="text-muted mb-3">
                                Welcome to my blog where I share insights on education, technology,
                                research, and life as an academic. Join the conversation and feel
                                free to share your thoughts.
                            </p>
                            <div class="d-grid gap-2">
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
        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-5">
                <i class="bi bi-pencil-square text-muted mb-3" style="font-size: 4rem;"></i>

                <?php if(request('search') || request('tag')): ?>
                    <h3 class="h4 text-muted mb-3">No posts found</h3>
                    <p class="text-muted mb-4">
                        No blog posts match your search criteria. Try adjusting your filters.
                    </p>
                    <a href="<?php echo e(route('blog.index')); ?>" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-1"></i>View All Posts
                    </a>
                <?php else: ?>
                    <h3 class="h4 text-muted mb-3">No blog posts yet</h3>
                    <p class="text-muted mb-4">
                        Blog posts will appear here once they are published. Check back soon!
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">
                            <i class="bi bi-house me-1"></i>Back to Home
                        </a>
                        <a href="<?php echo e(route('publications.index')); ?>" class="btn btn-outline-primary">
                            <i class="bi bi-journal-text me-1"></i>View Publications
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .blog-card {
        border: 1px solid #e5e7eb;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .blog-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .blog-image-container {
        height: 250px;
        overflow: hidden;
        position: relative;
    }

    .blog-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .blog-card:hover .blog-image {
        transform: scale(1.05);
    }

    .blog-placeholder {
        height: 250px;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
    }

    .card-title a {
        color: inherit;
        transition: color 0.3s ease;
    }

    .card-title a:hover {
        color: #2563eb;
    }

    .badge {
        font-size: 0.75rem;
        transition: all 0.3s ease;
    }

    .badge:hover {
        transform: scale(1.05);
    }

    article {
        scroll-margin-top: 2rem;
    }

    .sidebar .card {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/blog/index.blade.php ENDPATH**/ ?>