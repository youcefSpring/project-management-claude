<?php $__env->startSection('title', 'Blog'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">Academic Blog</h1>
                <p class="lead mb-4">
                    Thoughts on education, research insights, and academic perspectives.
                    Exploring ideas and sharing knowledge in my field of expertise.
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
                <form method="GET" action="<?php echo e(route('blog.index')); ?>" class="row g-3 align-items-end">
                    <!-- Search -->
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search Posts</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search"
                                   value="<?php echo e(request('search')); ?>" placeholder="Search titles, content...">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="col-md-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">All Categories</option>
                            <?php if(isset($categories)): ?>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->slug); ?>"
                                            <?php echo e(request('category') == $category->slug ? 'selected' : ''); ?>>
                                        <?php echo e($category->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <option value="research">Research</option>
                                <option value="teaching">Teaching</option>
                                <option value="technology">Technology</option>
                                <option value="academic-life">Academic Life</option>
                                <option value="conference">Conference</option>
                                <option value="publication">Publication</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div class="col-md-3">
                        <label for="sort" class="form-label">Sort By</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="latest" <?php echo e(request('sort') == 'latest' || !request('sort') ? 'selected' : ''); ?>>
                                Latest First
                            </option>
                            <option value="oldest" <?php echo e(request('sort') == 'oldest' ? 'selected' : ''); ?>>
                                Oldest First
                            </option>
                            <option value="popular" <?php echo e(request('sort') == 'popular' ? 'selected' : ''); ?>>
                                Most Popular
                            </option>
                            <option value="title" <?php echo e(request('sort') == 'title' ? 'selected' : ''); ?>>
                                Title A-Z
                            </option>
                        </select>
                    </div>

                    <!-- Filter Actions -->
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="<?php echo e(route('blog.index')); ?>" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Blog Posts -->
<section class="py-5 bg-white">
    <div class="container">
        <?php if(isset($posts) && $posts->count() > 0): ?>
            <!-- Results Summary -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Blog Posts</h3>
                        <span class="text-muted">
                            <?php echo e($posts->total()); ?> <?php echo e(Str::plural('post', $posts->total())); ?> found
                            <?php if(request('search')): ?>
                                for "<?php echo e(request('search')); ?>"
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Posts Grid -->
            <div class="row">
                <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <article class="card h-100 shadow-sm border-0">
                        <?php if($post->featured_image): ?>
                            <img src="<?php echo e(Storage::url($post->featured_image)); ?>"
                                 class="card-img-top" alt="<?php echo e($post->title); ?>"
                                 style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                 style="height: 200px;">
                                <i class="bi bi-file-text text-muted" style="font-size: 3rem;"></i>
                            </div>
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <!-- Meta Info -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <?php if($post->category): ?>
                                    <span class="badge bg-warning text-dark"><?php echo e($post->category->name); ?></span>
                                <?php endif; ?>
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>
                                    <?php echo e($post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y')); ?>

                                </small>
                            </div>

                            <!-- Title and Excerpt -->
                            <h5 class="card-title">
                                <a href="<?php echo e(route('blog.show', $post->slug)); ?>" class="text-decoration-none text-dark">
                                    <?php echo e($post->title); ?>

                                </a>
                            </h5>

                            <p class="card-text flex-grow-1">
                                <?php echo e($post->excerpt ?? Str::limit(strip_tags($post->content), 120)); ?>

                            </p>

                            <!-- Post Meta -->
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="d-flex align-items-center text-muted">
                                    <small>
                                        <i class="bi bi-eye me-1"></i><?php echo e($post->views_count ?? 0); ?> views
                                    </small>
                                    <?php if($post->reading_time): ?>
                                        <small class="ms-3">
                                            <i class="bi bi-clock me-1"></i><?php echo e($post->reading_time); ?> min read
                                        </small>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo e(route('blog.show', $post->slug)); ?>" class="btn btn-sm btn-outline-primary">
                                    Read More <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>

                            <!-- Tags -->
                            <?php if($post->tags && $post->tags->count() > 0): ?>
                                <div class="mt-3">
                                    <?php $__currentLoopData = $post->tags->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-light text-dark me-1">#<?php echo e($tag->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <?php echo e($posts->withQueryString()->links()); ?>

                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- No Posts Found -->
            <div class="row">
                <div class="col-12 text-center py-5">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-5">
                            <i class="bi bi-file-text text-muted" style="font-size: 4rem;"></i>
                            <h3 class="mt-3">No Blog Posts Found</h3>
                            <?php if(request()->hasAny(['search', 'category', 'sort'])): ?>
                                <p class="text-muted mb-4">
                                    No posts match your current filters. Try adjusting your search criteria.
                                </p>
                                <a href="<?php echo e(route('blog.index')); ?>" class="btn btn-primary">
                                    <i class="bi bi-arrow-left me-2"></i>View All Posts
                                </a>
                            <?php else: ?>
                                <p class="text-muted mb-4">
                                    I haven't published any blog posts yet. Check back soon for new content!
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

<!-- Featured Categories -->
<?php if(isset($categories) && $categories->count() > 0): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3>Explore by Category</h3>
                <p class="text-muted">Browse posts by topic area</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <?php $__currentLoopData = $categories->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
                <a href="<?php echo e(route('blog.index', ['category' => $category->slug])); ?>"
                   class="text-decoration-none">
                    <div class="card text-center h-100 shadow-sm border-0">
                        <div class="card-body py-3">
                            <i class="bi bi-folder text-primary" style="font-size: 2rem;"></i>
                            <h6 class="card-title mt-2 mb-1"><?php echo e($category->name); ?></h6>
                            <small class="text-muted"><?php echo e($category->posts_count ?? 0); ?> posts</small>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Newsletter Signup -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <h3 class="mb-3">Stay Updated</h3>
                <p class="mb-4">
                    Subscribe to get notified about new blog posts and academic updates.
                </p>
                <form method="POST" action="<?php echo e(route('newsletter.subscribe')); ?>" class="row g-2">
                    <?php echo csrf_field(); ?>
                    <div class="col-md-8">
                        <input type="email" class="form-control" name="email"
                               placeholder="Enter your email address" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-light w-100">
                            <i class="bi bi-envelope me-2"></i>Subscribe
                        </button>
                    </div>
                </form>
                <small class="mt-2 d-block opacity-75">
                    <i class="bi bi-shield-check me-1"></i>
                    Your email is safe and will never be shared.
                </small>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/public/blog/index.blade.php ENDPATH**/ ?>