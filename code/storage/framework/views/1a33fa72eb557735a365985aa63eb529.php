<?php $__env->startSection('page-title', 'Create Project'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Create New Project</h1>
        <p class="text-muted mb-0">Add a new research or academic project to your portfolio</p>
    </div>
    <a href="<?php echo e(route('admin.projects.index')); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Projects
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="<?php echo e(route('admin.projects.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="row g-3">
                        <!-- Project Title -->
                        <div class="col-12">
                            <label for="title" class="form-label">Project Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="title"
                                   name="title"
                                   value="<?php echo e(old('title')); ?>"
                                   required>
                            <?php $__errorArgs = ['title'];
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

                        <!-- Project Description -->
                        <div class="col-12">
                            <label for="description" class="form-label">Project Description <span class="text-danger">*</span></label>
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
                                      rows="4"
                                      required><?php echo e(old('description')); ?></textarea>
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
                        </div>

                        <!-- Status and Type -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="status" name="status">
                                <option value="active" <?php echo e(old('status') === 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="completed" <?php echo e(old('status') === 'completed' ? 'selected' : ''); ?>>Completed</option>
                                <option value="on-hold" <?php echo e(old('status') === 'on-hold' ? 'selected' : ''); ?>>On Hold</option>
                                <option value="cancelled" <?php echo e(old('status') === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                            </select>
                            <?php $__errorArgs = ['status'];
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

                        <div class="col-md-6">
                            <label for="type" class="form-label">Project Type</label>
                            <select class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="type" name="type">
                                <option value="">Select Type</option>
                                <option value="research" <?php echo e(old('type') === 'research' ? 'selected' : ''); ?>>Research</option>
                                <option value="academic" <?php echo e(old('type') === 'academic' ? 'selected' : ''); ?>>Academic</option>
                                <option value="publication" <?php echo e(old('type') === 'publication' ? 'selected' : ''); ?>>Publication</option>
                                <option value="collaboration" <?php echo e(old('type') === 'collaboration' ? 'selected' : ''); ?>>Collaboration</option>
                                <option value="teaching" <?php echo e(old('type') === 'teaching' ? 'selected' : ''); ?>>Teaching</option>
                                <option value="consulting" <?php echo e(old('type') === 'consulting' ? 'selected' : ''); ?>>Consulting</option>
                            </select>
                            <?php $__errorArgs = ['type'];
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

                        <!-- Dates -->
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date"
                                   class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="start_date"
                                   name="start_date"
                                   value="<?php echo e(old('start_date')); ?>">
                            <?php $__errorArgs = ['start_date'];
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

                        <div class="col-md-6">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date"
                                   class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="end_date"
                                   name="end_date"
                                   value="<?php echo e(old('end_date')); ?>">
                            <?php $__errorArgs = ['end_date'];
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

                        <!-- Funding and Client -->
                        <div class="col-md-6">
                            <label for="funding_amount" class="form-label">Funding Amount ($)</label>
                            <input type="number"
                                   class="form-control <?php $__errorArgs = ['funding_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="funding_amount"
                                   name="funding_amount"
                                   min="0"
                                   step="0.01"
                                   value="<?php echo e(old('funding_amount')); ?>">
                            <?php $__errorArgs = ['funding_amount'];
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

                        <div class="col-md-6">
                            <label for="client_organization" class="form-label">Client/Organization</label>
                            <input type="text"
                                   class="form-control <?php $__errorArgs = ['client_organization'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="client_organization"
                                   name="client_organization"
                                   value="<?php echo e(old('client_organization')); ?>">
                            <?php $__errorArgs = ['client_organization'];
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

                        <!-- URL Links -->
                        <div class="col-md-6">
                            <label for="project_url" class="form-label">Project URL</label>
                            <input type="url"
                                   class="form-control <?php $__errorArgs = ['project_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="project_url"
                                   name="project_url"
                                   value="<?php echo e(old('project_url')); ?>">
                            <?php $__errorArgs = ['project_url'];
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

                        <div class="col-md-6">
                            <label for="repository_url" class="form-label">Repository URL</label>
                            <input type="url"
                                   class="form-control <?php $__errorArgs = ['repository_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="repository_url"
                                   name="repository_url"
                                   value="<?php echo e(old('repository_url')); ?>">
                            <?php $__errorArgs = ['repository_url'];
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

                        <!-- Featured Image -->
                        <div class="col-12">
                            <label for="featured_image" class="form-label">Featured Image</label>
                            <input type="file"
                                   class="form-control <?php $__errorArgs = ['featured_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="featured_image"
                                   name="featured_image"
                                   accept="image/*">
                            <?php $__errorArgs = ['featured_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Upload an image to represent your project (max 5MB)</div>
                        </div>

                        <!-- Detailed Content -->
                        <div class="col-12">
                            <label for="content" class="form-label">Project Details</label>
                            <textarea class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      id="content"
                                      name="content"
                                      rows="8"><?php echo e(old('content')); ?></textarea>
                            <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Detailed description of the project methodology, goals, and outcomes</div>
                        </div>

                        <!-- Technologies -->
                        <div class="col-12">
                            <label for="technologies" class="form-label">Technologies Used</label>
                            <textarea class="form-control <?php $__errorArgs = ['technologies'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      id="technologies"
                                      name="technologies"
                                      rows="3"><?php echo e(old('technologies')); ?></textarea>
                            <?php $__errorArgs = ['technologies'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">List the technologies, tools, or methodologies used in this project</div>
                        </div>

                        <!-- Collaborators -->
                        <div class="col-12">
                            <label for="collaborators" class="form-label">Collaborators</label>
                            <textarea class="form-control <?php $__errorArgs = ['collaborators'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      id="collaborators"
                                      name="collaborators"
                                      rows="3"><?php echo e(old('collaborators')); ?></textarea>
                            <?php $__errorArgs = ['collaborators'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">List project collaborators, co-investigators, or team members</div>
                        </div>

                        <!-- Key Outcomes -->
                        <div class="col-12">
                            <label for="key_outcomes" class="form-label">Key Outcomes</label>
                            <textarea class="form-control <?php $__errorArgs = ['key_outcomes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      id="key_outcomes"
                                      name="key_outcomes"
                                      rows="4"><?php echo e(old('key_outcomes')); ?></textarea>
                            <?php $__errorArgs = ['key_outcomes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Summary of key findings, deliverables, or achievements</div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between pt-3">
                                <a href="<?php echo e(route('admin.projects.index')); ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Create Project
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Tags -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-tags text-primary me-2"></i>
                    Tags
                </h5>
                <div class="row g-2">
                    <?php if(isset($tags) && $tags->count() > 0): ?>
                        <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="tags[]" value="<?php echo e($tag->id); ?>"
                                           id="tag_<?php echo e($tag->id); ?>"
                                           <?php echo e(in_array($tag->id, old('tags', [])) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="tag_<?php echo e($tag->id); ?>">
                                        <?php echo e($tag->name); ?>

                                    </label>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="col-12">
                            <p class="text-muted small mb-0">No tags available. Tags can be managed through the admin panel.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Visibility Settings -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-eye text-primary me-2"></i>
                    Visibility
                </h5>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_published" id="published" value="1" <?php echo e(old('is_published', '1') == '1' ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="published">
                            <strong>Published</strong>
                            <small class="text-muted d-block">Visible to all visitors</small>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_published" id="draft" value="0" <?php echo e(old('is_published') == '0' ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="draft">
                            <strong>Draft</strong>
                            <small class="text-muted d-block">Only visible to you</small>
                        </label>
                    </div>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" <?php echo e(old('is_featured') ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="is_featured">
                        <strong>Featured Project</strong>
                        <small class="text-muted d-block">Highlight this project on homepage</small>
                    </label>
                </div>
            </div>
        </div>

        <!-- Tips -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    Tips
                </h5>
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Use clear, descriptive titles that reflect the project's purpose
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Include key outcomes and findings to showcase impact
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Add relevant tags to help visitors find related projects
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Upload a high-quality featured image to make projects visually appealing
                    </li>
                    <li>
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Include links to repositories or live demos when available
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-generate slug from title
        const titleInput = document.getElementById('title');
        const slugPreview = document.createElement('div');
        slugPreview.className = 'form-text text-muted';
        slugPreview.id = 'slug-preview';
        titleInput.parentNode.appendChild(slugPreview);

        function updateSlugPreview() {
            const slug = titleInput.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');

            if (slug) {
                slugPreview.innerHTML = `<i class="bi bi-link me-1"></i>URL: /projects/${slug}`;
            } else {
                slugPreview.innerHTML = '';
            }
        }

        titleInput.addEventListener('input', updateSlugPreview);
        updateSlugPreview();

        // Date validation
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        function validateDates() {
            if (startDateInput.value && endDateInput.value) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);

                if (endDate < startDate) {
                    endDateInput.setCustomValidity('End date must be after start date');
                } else {
                    endDateInput.setCustomValidity('');
                }
            }
        }

        startDateInput.addEventListener('change', validateDates);
        endDateInput.addEventListener('change', validateDates);

        // Form submission handling
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Creating...';
            submitButton.disabled = true;

            // Re-enable after 10 seconds as fallback
            setTimeout(function() {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 10000);
        });

        // File size validation
        const featuredImageInput = document.getElementById('featured_image');
        featuredImageInput.addEventListener('change', function() {
            if (this.files[0]) {
                const fileSize = this.files[0].size / 1024 / 1024; // Size in MB
                if (fileSize > 5) {
                    this.setCustomValidity('File size must be less than 5MB');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                }
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/admin/projects/create.blade.php ENDPATH**/ ?>