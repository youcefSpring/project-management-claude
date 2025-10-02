<?php $__env->startSection('page-title', 'Edit Publication'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Edit Publication</h1>
        <p class="text-muted mb-0">Update publication information and details</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.publications.show', $publication)); ?>" class="btn btn-outline-info">
            <i class="bi bi-eye me-1"></i>View Publication
        </a>
        <a href="<?php echo e(route('admin.publications.index')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Publications
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="<?php echo e(route('admin.publications.update', $publication)); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="row g-3">
                        <!-- Publication Title -->
                        <div class="col-12">
                            <label for="title" class="form-label">Publication Title <span class="text-danger">*</span></label>
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
                                   value="<?php echo e(old('title', $publication->title)); ?>"
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

                        <!-- Authors -->
                        <div class="col-12">
                            <label for="authors" class="form-label">Authors <span class="text-danger">*</span></label>
                            <textarea class="form-control <?php $__errorArgs = ['authors'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      id="authors"
                                      name="authors"
                                      rows="2"
                                      required><?php echo e(old('authors', $publication->authors)); ?></textarea>
                            <?php $__errorArgs = ['authors'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">List all authors in proper citation format (e.g., "Smith, J., Doe, A., & Johnson, M.")</div>
                        </div>

                        <!-- Type and Status -->
                        <div class="col-md-6">
                            <label for="type" class="form-label">Publication Type <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="journal" <?php echo e(old('type', $publication->type) === 'journal' ? 'selected' : ''); ?>>Journal Article</option>
                                <option value="conference" <?php echo e(old('type', $publication->type) === 'conference' ? 'selected' : ''); ?>>Conference Paper</option>
                                <option value="book" <?php echo e(old('type', $publication->type) === 'book' ? 'selected' : ''); ?>>Book</option>
                                <option value="book_chapter" <?php echo e(old('type', $publication->type) === 'book_chapter' ? 'selected' : ''); ?>>Book Chapter</option>
                                <option value="thesis" <?php echo e(old('type', $publication->type) === 'thesis' ? 'selected' : ''); ?>>Thesis</option>
                                <option value="report" <?php echo e(old('type', $publication->type) === 'report' ? 'selected' : ''); ?>>Report</option>
                                <option value="preprint" <?php echo e(old('type', $publication->type) === 'preprint' ? 'selected' : ''); ?>>Preprint</option>
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

                        <div class="col-md-6">
                            <label for="status" class="form-label">Publication Status</label>
                            <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="status" name="status">
                                <option value="published" <?php echo e(old('status', $publication->status) === 'published' ? 'selected' : ''); ?>>Published</option>
                                <option value="accepted" <?php echo e(old('status', $publication->status) === 'accepted' ? 'selected' : ''); ?>>Accepted</option>
                                <option value="under_review" <?php echo e(old('status', $publication->status) === 'under_review' ? 'selected' : ''); ?>>Under Review</option>
                                <option value="in_preparation" <?php echo e(old('status', $publication->status) === 'in_preparation' ? 'selected' : ''); ?>>In Preparation</option>
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

                        <!-- Journal/Venue Information -->
                        <div class="col-md-6">
                            <label for="journal_name" class="form-label">Journal/Conference Name</label>
                            <input type="text"
                                   class="form-control <?php $__errorArgs = ['journal_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="journal_name"
                                   name="journal_name"
                                   value="<?php echo e(old('journal_name', $publication->journal_name)); ?>">
                            <?php $__errorArgs = ['journal_name'];
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
                            <label for="venue" class="form-label">Venue/Publisher</label>
                            <input type="text"
                                   class="form-control <?php $__errorArgs = ['venue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="venue"
                                   name="venue"
                                   value="<?php echo e(old('venue', $publication->venue)); ?>">
                            <?php $__errorArgs = ['venue'];
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

                        <!-- Publication Details -->
                        <div class="col-md-4">
                            <label for="volume" class="form-label">Volume</label>
                            <input type="text"
                                   class="form-control <?php $__errorArgs = ['volume'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="volume"
                                   name="volume"
                                   value="<?php echo e(old('volume', $publication->volume)); ?>">
                            <?php $__errorArgs = ['volume'];
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

                        <div class="col-md-4">
                            <label for="issue" class="form-label">Issue</label>
                            <input type="text"
                                   class="form-control <?php $__errorArgs = ['issue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="issue"
                                   name="issue"
                                   value="<?php echo e(old('issue', $publication->issue)); ?>">
                            <?php $__errorArgs = ['issue'];
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

                        <div class="col-md-4">
                            <label for="pages" class="form-label">Pages</label>
                            <input type="text"
                                   class="form-control <?php $__errorArgs = ['pages'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="pages"
                                   name="pages"
                                   value="<?php echo e(old('pages', $publication->pages)); ?>"
                                   placeholder="e.g., 123-145">
                            <?php $__errorArgs = ['pages'];
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

                        <!-- Publication Date -->
                        <div class="col-md-6">
                            <label for="publication_date" class="form-label">Publication Date</label>
                            <input type="date"
                                   class="form-control <?php $__errorArgs = ['publication_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="publication_date"
                                   name="publication_date"
                                   value="<?php echo e(old('publication_date', $publication->publication_date ? $publication->publication_date->format('Y-m-d') : '')); ?>">
                            <?php $__errorArgs = ['publication_date'];
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
                            <label for="year" class="form-label">Publication Year</label>
                            <input type="number"
                                   class="form-control <?php $__errorArgs = ['year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="year"
                                   name="year"
                                   min="1900"
                                   max="<?php echo e(date('Y') + 5); ?>"
                                   value="<?php echo e(old('year', $publication->year)); ?>">
                            <?php $__errorArgs = ['year'];
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

                        <!-- DOI and URLs -->
                        <div class="col-md-6">
                            <label for="doi" class="form-label">DOI</label>
                            <input type="text"
                                   class="form-control <?php $__errorArgs = ['doi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="doi"
                                   name="doi"
                                   value="<?php echo e(old('doi', $publication->doi)); ?>"
                                   placeholder="e.g., 10.1000/182">
                            <?php $__errorArgs = ['doi'];
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
                            <label for="url" class="form-label">Publication URL</label>
                            <input type="url"
                                   class="form-control <?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="url"
                                   name="url"
                                   value="<?php echo e(old('url', $publication->url)); ?>">
                            <?php $__errorArgs = ['url'];
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

                        <!-- PDF File -->
                        <div class="col-12">
                            <label for="pdf_file" class="form-label">PDF File</label>
                            <?php if($publication->pdf_file): ?>
                                <div class="mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-file-pdf text-danger"></i>
                                        <span>Current PDF: <?php echo e(basename($publication->pdf_file)); ?></span>
                                        <a href="<?php echo e(Storage::url($publication->pdf_file)); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </div>
                                    <p class="form-text mb-2">Upload a new PDF to replace the current one</p>
                                </div>
                            <?php endif; ?>
                            <input type="file"
                                   class="form-control <?php $__errorArgs = ['pdf_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="pdf_file"
                                   name="pdf_file"
                                   accept=".pdf">
                            <?php $__errorArgs = ['pdf_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Upload a new PDF file to replace the current one (max 20MB)</div>
                        </div>

                        <!-- Abstract -->
                        <div class="col-12">
                            <label for="abstract" class="form-label">Abstract</label>
                            <textarea class="form-control <?php $__errorArgs = ['abstract'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      id="abstract"
                                      name="abstract"
                                      rows="6"><?php echo e(old('abstract', $publication->abstract)); ?></textarea>
                            <?php $__errorArgs = ['abstract'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Publication abstract or summary</div>
                        </div>

                        <!-- Keywords -->
                        <div class="col-12">
                            <label for="keywords" class="form-label">Keywords</label>
                            <textarea class="form-control <?php $__errorArgs = ['keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      id="keywords"
                                      name="keywords"
                                      rows="2"><?php echo e(old('keywords', $publication->keywords)); ?></textarea>
                            <?php $__errorArgs = ['keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Comma-separated keywords or research topics</div>
                        </div>

                        <!-- Notes -->
                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      id="notes"
                                      name="notes"
                                      rows="3"><?php echo e(old('notes', $publication->notes)); ?></textarea>
                            <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Additional notes or comments about this publication</div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between pt-3">
                                <a href="<?php echo e(route('admin.publications.show', $publication)); ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Update Publication
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Visibility Settings -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-eye text-primary me-2"></i>
                    Visibility
                </h5>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_published" id="published" value="1" <?php echo e(old('is_published', $publication->is_published) == '1' ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="published">
                            <strong>Published</strong>
                            <small class="text-muted d-block">Visible to all visitors</small>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_published" id="draft" value="0" <?php echo e(old('is_published', $publication->is_published) == '0' ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="draft">
                            <strong>Draft</strong>
                            <small class="text-muted d-block">Only visible to you</small>
                        </label>
                    </div>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" <?php echo e(old('is_featured', $publication->is_featured) ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="is_featured">
                        <strong>Featured Publication</strong>
                        <small class="text-muted d-block">Highlight this publication</small>
                    </label>
                </div>
            </div>
        </div>

        <!-- Citation Preview -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-quote text-primary me-2"></i>
                    Citation Preview
                </h5>
                <div id="citation-preview" class="small text-muted">
                    <em>Citation will appear here as you update the form</em>
                </div>
            </div>
        </div>

        <!-- Publication Info -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Publication Info
                </h5>

                <div class="mb-2">
                    <small class="text-muted">Created:</small>
                    <div><?php echo e($publication->created_at->format('F d, Y \a\t g:i A')); ?></div>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Last Updated:</small>
                    <div><?php echo e($publication->updated_at->format('F d, Y \a\t g:i A')); ?></div>
                </div>

                <?php if($publication->is_published): ?>
                    <div class="mt-3">
                        <a href="<?php echo e(url('/publications/' . Str::slug($publication->title))); ?>" target="_blank" class="btn btn-outline-info btn-sm w-100">
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
                    Permanently delete this publication. This action cannot be undone.
                </p>
                <form method="POST" action="<?php echo e(route('admin.publications.destroy', $publication)); ?>" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100" data-confirm-delete>
                        <i class="bi bi-trash me-1"></i>Delete Publication
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
        // Auto-generate year from publication date
        const publicationDateInput = document.getElementById('publication_date');
        const yearInput = document.getElementById('year');

        publicationDateInput.addEventListener('change', function() {
            if (this.value) {
                const date = new Date(this.value);
                yearInput.value = date.getFullYear();
                updateCitationPreview();
            }
        });

        // Citation preview update
        function updateCitationPreview() {
            const title = document.getElementById('title').value;
            const authors = document.getElementById('authors').value;
            const year = document.getElementById('year').value;
            const journal = document.getElementById('journal_name').value;
            const volume = document.getElementById('volume').value;
            const issue = document.getElementById('issue').value;
            const pages = document.getElementById('pages').value;
            const doi = document.getElementById('doi').value;

            let citation = '';

            if (authors) citation += authors;
            if (year) citation += ` (${year}).`;
            if (title) citation += ` ${title}.`;
            if (journal) citation += ` <em>${journal}</em>`;
            if (volume) citation += `, ${volume}`;
            if (issue) citation += `(${issue})`;
            if (pages) citation += `, ${pages}`;
            if (doi) citation += `. https://doi.org/${doi}`;

            document.getElementById('citation-preview').innerHTML = citation || '<em>Citation will appear here as you update the form</em>';
        }

        // Add event listeners for real-time preview
        ['title', 'authors', 'year', 'journal_name', 'volume', 'issue', 'pages', 'doi'].forEach(function(id) {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('input', updateCitationPreview);
            }
        });

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
        const pdfFileInput = document.getElementById('pdf_file');
        pdfFileInput.addEventListener('change', function() {
            if (this.files[0]) {
                const fileSize = this.files[0].size / 1024 / 1024; // Size in MB
                if (fileSize > 20) {
                    this.setCustomValidity('File size must be less than 20MB');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                }
            }
        });

        // Initialize citation preview
        updateCitationPreview();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/admin/publications/edit.blade.php ENDPATH**/ ?>