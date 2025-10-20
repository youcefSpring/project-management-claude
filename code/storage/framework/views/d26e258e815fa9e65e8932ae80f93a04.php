<!-- Footer -->
<footer class="text-white py-4 mt-5" style="background-color: #1f2937;">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5><?php echo e(config('app.name', 'Teacher Portfolio')); ?></h5>
                <p class="text-light mb-0">Sharing knowledge through teaching, research, and development.</p>

                <?php if(isset($teacher) && $teacher->bio): ?>
                    <p class="text-light small mt-2"><?php echo e(Str::limit($teacher->bio, 120)); ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="mb-3">
                    <h6 class="text-light">Quick Links</h6>
                    <div class="d-flex flex-column flex-md-row justify-content-md-end gap-2">
                        <a href="<?php echo e(route('about')); ?>" class="text-light text-decoration-none">
                            <i class="bi bi-person me-1"></i>About
                        </a>
                        <a href="<?php echo e(route('courses.index')); ?>" class="text-light text-decoration-none">
                            <i class="bi bi-book me-1"></i>Courses
                        </a>
                        <a href="<?php echo e(route('publications.index')); ?>" class="text-light text-decoration-none">
                            <i class="bi bi-journal-text me-1"></i>Publications
                        </a>
                        <a href="<?php echo e(route('contact.show')); ?>" class="text-light text-decoration-none">
                            <i class="bi bi-envelope me-1"></i>Contact
                        </a>
                    </div>
                </div>

                <div class="mb-2">
                    <a href="<?php echo e(route('contact.show')); ?>" class="text-light text-decoration-none me-3">
                        <i class="bi bi-envelope"></i> Contact
                    </a>
                    <a href="<?php echo e(route('download-cv')); ?>" class="text-light text-decoration-none">
                        <i class="bi bi-download"></i> Download CV
                    </a>
                </div>

                <?php if(isset($teacher)): ?>
                    <?php if($teacher->linkedin_url || $teacher->website || $teacher->google_scholar_url): ?>
                        <div class="mb-2">
                            <?php if($teacher->website): ?>
                                <a href="<?php echo e($teacher->website); ?>" target="_blank" rel="noopener" class="text-light me-3">
                                    <i class="bi bi-globe"></i>
                                </a>
                            <?php endif; ?>
                            <?php if($teacher->linkedin_url): ?>
                                <a href="<?php echo e($teacher->linkedin_url); ?>" target="_blank" rel="noopener" class="text-light me-3">
                                    <i class="bi bi-linkedin"></i>
                                </a>
                            <?php endif; ?>
                            <?php if($teacher->google_scholar_url): ?>
                                <a href="<?php echo e($teacher->google_scholar_url); ?>" target="_blank" rel="noopener" class="text-light me-3">
                                    <i class="bi bi-mortarboard"></i>
                                </a>
                            <?php endif; ?>
                            <?php if($teacher->orcid_id): ?>
                                <a href="https://orcid.org/<?php echo e($teacher->orcid_id); ?>" target="_blank" rel="noopener" class="text-light">
                                    <i class="bi bi-person-badge"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <small class="text-light">
                    © <?php echo e(date('Y')); ?> <?php echo e(config('app.name', 'Teacher Portfolio')); ?>. All rights reserved.
                </small>
            </div>
        </div>
    </div>
</footer><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/partials/footer.blade.php ENDPATH**/ ?>