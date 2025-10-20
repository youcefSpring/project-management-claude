<?php $__env->startSection('page-title', 'Edit Project'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Edit Project</h1>
        <p class="text-muted mb-0">Update project information and details</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.projects.show', $project)); ?>" class="btn btn-outline-info">
            <i class="bi bi-eye me-1"></i>View Project
        </a>
        <a href="<?php echo e(route('admin.projects.index')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Projects
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="<?php echo e(route('admin.projects.update', $project)); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

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
                                   value="<?php echo e(old('title', $project->title)); ?>"
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
                                      required><?php echo e(old('description', $project->description)); ?></textarea>
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
                                <option value="active" <?php echo e(old('status', $project->status) === 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="completed" <?php echo e(old('status', $project->status) === 'completed' ? 'selected' : ''); ?>>Completed</option>
                                <option value="on-hold" <?php echo e(old('status', $project->status) === 'on-hold' ? 'selected' : ''); ?>>On Hold</option>
                                <option value="cancelled" <?php echo e(old('status', $project->status) === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
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
                                <option value="research" <?php echo e(old('type', $project->type) === 'research' ? 'selected' : ''); ?>>Research</option>
                                <option value="academic" <?php echo e(old('type', $project->type) === 'academic' ? 'selected' : ''); ?>>Academic</option>
                                <option value="publication" <?php echo e(old('type', $project->type) === 'publication' ? 'selected' : ''); ?>>Publication</option>
                                <option value="collaboration" <?php echo e(old('type', $project->type) === 'collaboration' ? 'selected' : ''); ?>>Collaboration</option>
                                <option value="teaching" <?php echo e(old('type', $project->type) === 'teaching' ? 'selected' : ''); ?>>Teaching</option>
                                <option value="consulting" <?php echo e(old('type', $project->type) === 'consulting' ? 'selected' : ''); ?>>Consulting</option>
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
                                   value="<?php echo e(old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '')); ?>">
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
                                   value="<?php echo e(old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '')); ?>">
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
                                   value="<?php echo e(old('funding_amount', $project->funding_amount)); ?>">
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
                                   value="<?php echo e(old('client_organization', $project->client_organization)); ?>">
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
                                   value="<?php echo e(old('project_url', $project->project_url)); ?>">
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
                                   value="<?php echo e(old('repository_url', $project->repository_url)); ?>">
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
                            <?php if($project->featured_image): ?>
                                <div class="mb-2">
                                    <img src="<?php echo e(Storage::url($project->featured_image)); ?>"
                                         alt="Current featured image"
                                         class="img-thumbnail"
                                         style="max-width: 200px; max-height: 150px;">
                                    <p class="form-text mb-2">Current image (will be replaced if new image is uploaded)</p>
                                </div>
                            <?php endif; ?>
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
                            <div class="form-text">Upload a new image to replace the current one (max 5MB)</div>
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
                                      rows="8"><?php echo e(old('content', $project->content)); ?></textarea>
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
                                      rows="3"><?php echo e(old('technologies', $project->technologies)); ?></textarea>
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
                                      rows="3"><?php echo e(old('collaborators', $project->collaborators)); ?></textarea>
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
                                      rows="4"><?php echo e(old('key_outcomes', $project->key_outcomes)); ?></textarea>
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
                                <a href="<?php echo e(route('admin.projects.show', $project)); ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Update Project
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
                                           <?php echo e(in_array($tag->id, old('tags', $project->tags->pluck('id')->toArray())) ? 'checked' : ''); ?>>
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
                        <input class="form-check-input" type="radio" name="is_published" id="published" value="1" <?php echo e(old('is_published', $project->is_published) == '1' ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="published">
                            <strong>Published</strong>
                            <small class="text-muted d-block">Visible to all visitors</small>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_published" id="draft" value="0" <?php echo e(old('is_published', $project->is_published) == '0' ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="draft">
                            <strong>Draft</strong>
                            <small class="text-muted d-block">Only visible to you</small>
                        </label>
                    </div>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" <?php echo e(old('is_featured', $project->is_featured) ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="is_featured">
                        <strong>Featured Project</strong>
                        <small class="text-muted d-block">Highlight this project on homepage</small>
                    </label>
                </div>
            </div>
        </div>

        <!-- Project Info -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Project Info
                </h5>

                <div class="mb-2">
                    <small class="text-muted">Created:</small>
                    <div><?php echo e($project->created_at->format('F d, Y \a\t g:i A')); ?></div>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Last Updated:</small>
                    <div><?php echo e($project->updated_at->format('F d, Y \a\t g:i A')); ?></div>
                </div>

                <?php if($project->is_published): ?>
                    <div class="mt-3">
                        <a href="<?php echo e(url('/projects/' . Str::slug($project->title))); ?>" target="_blank" class="btn btn-outline-info btn-sm w-100">
                            <i class="bi bi-eye me-1"></i>View Public Page
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="card border-danger">
            <div class="card-body">
                <h5 class="card-title text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Danger Zone
                </h5>
                <p class="card-text small text-muted">
                    Permanently delete this project. This action cannot be undone.
                </p>
                <form method="POST" action="<?php echo e(route('admin.projects.destroy', $project)); ?>" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100" data-confirm-delete>
                        <i class="bi bi-trash me-1"></i>Delete Project
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

            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Updating...';
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/admin/projects/edit.blade.php ENDPATH**/ ?>