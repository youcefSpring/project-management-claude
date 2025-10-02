<?php $__env->startSection('page-title', 'Edit Tag'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Edit Tag</h1>
        <p class="text-muted mb-0">Update tag information and settings</p>
    </div>
    <a href="<?php echo e(route('admin.tags.index')); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Tags
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="<?php echo e(route('admin.tags.update', $tag)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="row g-3">
                        <!-- Tag Name -->
                        <div class="col-12">
                            <label for="name" class="form-label">Tag Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="name"
                                   name="name"
                                   value="<?php echo e(old('name', $tag->name)); ?>"
                                   required
                                   placeholder="e.g., Web Development, Research, Laravel">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Tag Slug -->
                        <div class="col-12">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text"
                                   class="form-control <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="slug"
                                   name="slug"
                                   value="<?php echo e(old('slug', $tag->slug)); ?>"
                                   placeholder="Auto-generated from name">
                            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">
                                Must be URL-friendly (lowercase, hyphens only).
                                <span id="slug-preview" class="text-primary fw-bold"></span>
                                <?php if($tag->slug): ?>
                                    <br><strong>Current URL:</strong> <code>/tags/<?php echo e($tag->slug); ?></code>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Tag Description -->
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Brief description of what this tag represents"><?php echo e(old('description', $tag->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Optional description to help you and others understand this tag's purpose</div>
                        </div>

                        <!-- Tag Color -->
                        <div class="col-md-6">
                            <label for="color" class="form-label">Tag Color</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color"
                                       class="form-control form-control-color <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="color"
                                       name="color"
                                       value="<?php echo e(old('color', $tag->color ?? '#6c757d')); ?>"
                                       style="width: 60px;">
                                <input type="text"
                                       class="form-control <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="color-hex"
                                       name="color_hex"
                                       value="<?php echo e(old('color', $tag->color ?? '#6c757d')); ?>"
                                       pattern="^#[0-9a-fA-F]{6}$"
                                       placeholder="#6c757d">
                                <button type="button" class="btn btn-outline-secondary" onclick="randomColor()">
                                    <i class="bi bi-shuffle"></i>
                                </button>
                            </div>
                            <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Choose a color to visually identify this tag</div>
                        </div>

                        <!-- Tag Preview -->
                        <div class="col-md-6">
                            <label class="form-label">Preview</label>
                            <div class="d-flex align-items-center gap-2">
                                <span id="tag-preview" class="badge" style="background-color: <?php echo e($tag->color ?? '#6c757d'); ?>; color: white;">
                                    <?php echo e($tag->name); ?>

                                </span>
                                <small class="text-muted">This is how your tag will appear</small>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between pt-3">
                                <a href="<?php echo e(route('admin.tags.index')); ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Update Tag
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Tag Statistics -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-bar-chart text-primary me-2"></i>
                    Tag Usage Statistics
                </h5>

                <?php
                    $totalUsage = ($tag->posts_count ?? 0) + ($tag->projects_count ?? 0) + ($tag->courses_count ?? 0);
                ?>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold">Total Usage</span>
                        <span class="badge bg-primary"><?php echo e($totalUsage); ?> items</span>
                    </div>
                </div>

                <div class="mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Blog Posts</span>
                        <span class="badge bg-light text-dark"><?php echo e($tag->posts_count ?? 0); ?></span>
                    </div>
                </div>

                <div class="mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Projects</span>
                        <span class="badge bg-light text-dark"><?php echo e($tag->projects_count ?? 0); ?></span>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Courses</span>
                        <span class="badge bg-light text-dark"><?php echo e($tag->courses_count ?? 0); ?></span>
                    </div>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Created:</small>
                    <div><?php echo e($tag->created_at->format('F d, Y \a\t g:i A')); ?></div>
                </div>

                <div>
                    <small class="text-muted">Last Updated:</small>
                    <div><?php echo e($tag->updated_at->format('F d, Y \a\t g:i A')); ?></div>
                </div>
            </div>
        </div>

        <!-- Color Presets -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-palette text-primary me-2"></i>
                    Color Presets
                </h5>
                <div class="row g-2">
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 color-preset" data-color="#0d6efd">
                            <div class="rounded-circle mx-auto" style="width: 20px; height: 20px; background-color: #0d6efd;"></div>
                            Blue
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 color-preset" data-color="#198754">
                            <div class="rounded-circle mx-auto" style="width: 20px; height: 20px; background-color: #198754;"></div>
                            Green
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 color-preset" data-color="#dc3545">
                            <div class="rounded-circle mx-auto" style="width: 20px; height: 20px; background-color: #dc3545;"></div>
                            Red
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 color-preset" data-color="#ffc107">
                            <div class="rounded-circle mx-auto" style="width: 20px; height: 20px; background-color: #ffc107;"></div>
                            Yellow
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 color-preset" data-color="#6f42c1">
                            <div class="rounded-circle mx-auto" style="width: 20px; height: 20px; background-color: #6f42c1;"></div>
                            Purple
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 color-preset" data-color="#fd7e14">
                            <div class="rounded-circle mx-auto" style="width: 20px; height: 20px; background-color: #fd7e14;"></div>
                            Orange
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Content -->
        <?php if($totalUsage > 0): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-link text-primary me-2"></i>
                        Related Content
                    </h5>

                    <?php if(isset($tag->posts) && $tag->posts->count() > 0): ?>
                        <div class="mb-3">
                            <h6 class="text-muted">Recent Blog Posts</h6>
                            <?php $__currentLoopData = $tag->posts->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small><?php echo e(Str::limit($post->title, 30)); ?></small>
                                    <a href="<?php echo e(route('admin.blog.edit', $post)); ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($tag->posts->count() > 3): ?>
                                <small class="text-muted">and <?php echo e($tag->posts->count() - 3); ?> more...</small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($tag->projects) && $tag->projects->count() > 0): ?>
                        <div class="mb-3">
                            <h6 class="text-muted">Recent Projects</h6>
                            <?php $__currentLoopData = $tag->projects->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small><?php echo e(Str::limit($project->title, 30)); ?></small>
                                    <a href="<?php echo e(route('admin.projects.edit', $project)); ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($tag->projects->count() > 3): ?>
                                <small class="text-muted">and <?php echo e($tag->projects->count() - 3); ?> more...</small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($tag->courses) && $tag->courses->count() > 0): ?>
                        <div>
                            <h6 class="text-muted">Recent Courses</h6>
                            <?php $__currentLoopData = $tag->courses->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small><?php echo e(Str::limit($course->title, 30)); ?></small>
                                    <a href="<?php echo e(route('admin.courses.edit', $course)); ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($tag->courses->count() > 3): ?>
                                <small class="text-muted">and <?php echo e($tag->courses->count() - 3); ?> more...</small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Danger Zone -->
        <div class="card border-danger">
            <div class="card-body">
                <h5 class="card-title text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Danger Zone
                </h5>

                <?php if($totalUsage > 0): ?>
                    <div class="alert alert-warning">
                        <small>
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            This tag is currently used by <strong><?php echo e($totalUsage); ?> items</strong>.
                            Deleting it will remove the tag from all associated content.
                        </small>
                    </div>
                <?php endif; ?>

                <p class="card-text small text-muted">
                    Permanently delete this tag. This action cannot be undone.
                </p>
                <form method="POST" action="<?php echo e(route('admin.tags.destroy', $tag)); ?>" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100" data-confirm-delete>
                        <i class="bi bi-trash me-1"></i>Delete Tag
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const slugPreview = document.getElementById('slug-preview');
        const colorInput = document.getElementById('color');
        const colorHexInput = document.getElementById('color-hex');
        const tagPreview = document.getElementById('tag-preview');

        // Update slug preview
        function updateSlugPreview() {
            const slug = slugInput.value;
            if (slug) {
                slugPreview.textContent = `New URL: /tags/${slug}`;
            } else {
                slugPreview.textContent = '';
            }
            updatePreview();
        }

        // Update preview
        function updatePreview() {
            const name = nameInput.value || '<?php echo e($tag->name); ?>';
            const color = colorInput.value || '#6c757d';

            tagPreview.textContent = name;
            tagPreview.style.backgroundColor = color;
            tagPreview.style.color = getContrastColor(color);
        }

        // Get contrast color (white or black) based on background
        function getContrastColor(hexColor) {
            const r = parseInt(hexColor.substr(1, 2), 16);
            const g = parseInt(hexColor.substr(3, 2), 16);
            const b = parseInt(hexColor.substr(5, 2), 16);
            const yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;
            return (yiq >= 128) ? 'black' : 'white';
        }

        // Sync color inputs
        function syncColorInputs(source) {
            if (source === 'picker') {
                colorHexInput.value = colorInput.value;
            } else if (source === 'hex') {
                if (/^#[0-9a-fA-F]{6}$/.test(colorHexInput.value)) {
                    colorInput.value = colorHexInput.value;
                }
            }
            updatePreview();
        }

        // Event listeners
        nameInput.addEventListener('input', updatePreview);
        slugInput.addEventListener('input', updateSlugPreview);

        colorInput.addEventListener('input', function() {
            syncColorInputs('picker');
        });

        colorHexInput.addEventListener('input', function() {
            syncColorInputs('hex');
        });

        // Color preset buttons
        document.querySelectorAll('.color-preset').forEach(function(button) {
            button.addEventListener('click', function() {
                const color = this.dataset.color;
                colorInput.value = color;
                colorHexInput.value = color;
                updatePreview();
            });
        });

        // Random color function
        window.randomColor = function() {
            const colors = ['#0d6efd', '#198754', '#dc3545', '#ffc107', '#6f42c1', '#fd7e14', '#20c997', '#e91e63', '#795548', '#607d8b'];
            const randomColor = colors[Math.floor(Math.random() * colors.length)];
            colorInput.value = randomColor;
            colorHexInput.value = randomColor;
            updatePreview();
        };

        // Form submission handling
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Updating...';
            submitButton.disabled = true;

            // Re-enable after 10 seconds as fallback
            setTimeout(function() {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 10000);
        });

        // Delete confirmation
        const deleteButton = document.querySelector('[data-confirm-delete]');
        if (deleteButton) {
            deleteButton.addEventListener('click', function(e) {
                const totalUsage = <?php echo e($totalUsage); ?>;
                let message = 'Are you sure you want to delete this tag?';

                if (totalUsage > 0) {
                    message = `This tag is currently used by ${totalUsage} items. Deleting it will remove the tag from all associated content. Are you sure you want to continue?`;
                }

                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        }

        // Initialize preview
        updateSlugPreview();
        updatePreview();
    });
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .form-control-color {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }

    .color-preset {
        padding: 0.5rem;
        border: 1px solid #dee2e6;
    }

    .color-preset:hover {
        background-color: #f8f9fa;
    }

    #tag-preview {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
    }

    .btn-sm {
        padding: 0.25rem 0.375rem;
        font-size: 0.75rem;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/admin/tags/edit.blade.php ENDPATH**/ ?>