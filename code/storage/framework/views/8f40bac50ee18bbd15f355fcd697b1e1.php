<?php $__env->startSection('title', 'Contact Me'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">Get In Touch</h1>
                <p class="lead mb-4">
                    I welcome collaboration opportunities, academic discussions, and inquiries from students and colleagues.
                    Whether you're interested in my research, courses, or potential partnerships, I'd love to hear from you.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form and Info -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <!-- Contact Form -->
            <div class="col-lg-8 mb-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-envelope-fill me-2"></i>Send Me a Message</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('contact.store')); ?>" method="POST" class="needs-validation" novalidate>
                            <?php echo csrf_field(); ?>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="name" name="name" value="<?php echo e(old('name')); ?>" required>
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
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="email" name="email" value="<?php echo e(old('email')); ?>" required>
                                    <?php $__errorArgs = ['email'];
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
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="phone" name="phone" value="<?php echo e(old('phone')); ?>">
                                    <?php $__errorArgs = ['phone'];
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
                                    <label for="subject_type" class="form-label">Subject Type <span class="text-danger">*</span></label>
                                    <select class="form-select <?php $__errorArgs = ['subject_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="subject_type" name="subject_type" required>
                                        <option value="">Select a subject type</option>
                                        <option value="general_inquiry" <?php echo e(old('subject_type') == 'general_inquiry' ? 'selected' : ''); ?>>General Inquiry</option>
                                        <option value="collaboration" <?php echo e(old('subject_type') == 'collaboration' ? 'selected' : ''); ?>>Research Collaboration</option>
                                        <option value="course_info" <?php echo e(old('subject_type') == 'course_info' ? 'selected' : ''); ?>>Course Information</option>
                                        <option value="student_inquiry" <?php echo e(old('subject_type') == 'student_inquiry' ? 'selected' : ''); ?>>Student Inquiry</option>
                                        <option value="media_interview" <?php echo e(old('subject_type') == 'media_interview' ? 'selected' : ''); ?>>Media/Interview Request</option>
                                        <option value="speaking_engagement" <?php echo e(old('subject_type') == 'speaking_engagement' ? 'selected' : ''); ?>>Speaking Engagement</option>
                                        <option value="other" <?php echo e(old('subject_type') == 'other' ? 'selected' : ''); ?>>Other</option>
                                    </select>
                                    <?php $__errorArgs = ['subject_type'];
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
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="subject" name="subject" value="<?php echo e(old('subject')); ?>" required>
                                <?php $__errorArgs = ['subject'];
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

                            <div class="mb-4">
                                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          id="message" name="message" rows="6" required
                                          placeholder="Please provide details about your inquiry..."><?php echo e(old('message')); ?></textarea>
                                <?php $__errorArgs = ['message'];
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

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send me-2"></i>Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-4">
                <!-- Direct Contact -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <?php if($teacher->email ?? false): ?>
                            <div class="mb-3">
                                <h6><i class="bi bi-envelope text-primary me-2"></i>Email</h6>
                                <a href="mailto:<?php echo e($teacher->email); ?>" class="text-decoration-none">
                                    <?php echo e($teacher->email); ?>

                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($teacher->phone ?? false): ?>
                            <div class="mb-3">
                                <h6><i class="bi bi-telephone text-primary me-2"></i>Phone</h6>
                                <a href="tel:<?php echo e($teacher->phone); ?>" class="text-decoration-none">
                                    <?php echo e($teacher->phone); ?>

                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($teacher->office_location ?? false): ?>
                            <div class="mb-3">
                                <h6><i class="bi bi-geo-alt text-primary me-2"></i>Office</h6>
                                <p class="mb-0"><?php echo e($teacher->office_location); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if($teacher->office_hours ?? false): ?>
                            <div class="mb-3">
                                <h6><i class="bi bi-clock text-primary me-2"></i>Office Hours</h6>
                                <p class="mb-0"><?php echo nl2br(e($teacher->office_hours)); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Response Time -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-clock-fill me-2"></i>Response Time</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>General Inquiries:</strong> 1-2 business days
                        </p>
                        <p class="mb-3">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Student Questions:</strong> Same day (during business hours)
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Research Collaborations:</strong> 2-3 business days
                        </p>
                    </div>
                </div>

                <!-- Social Links -->
                <?php if(($teacher->website ?? false) || ($teacher->linkedin_url ?? false) || ($teacher->twitter_url ?? false)): ?>
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="bi bi-share me-2"></i>Connect With Me</h5>
                    </div>
                    <div class="card-body">
                        <?php if($teacher->website ?? false): ?>
                            <div class="mb-2">
                                <a href="<?php echo e($teacher->website); ?>" target="_blank" rel="noopener"
                                   class="text-decoration-none">
                                    <i class="bi bi-globe text-primary me-2"></i>Personal Website
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($teacher->linkedin_url ?? false): ?>
                            <div class="mb-2">
                                <a href="<?php echo e($teacher->linkedin_url); ?>" target="_blank" rel="noopener"
                                   class="text-decoration-none">
                                    <i class="bi bi-linkedin text-primary me-2"></i>LinkedIn
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($teacher->twitter_url ?? false): ?>
                            <div class="mb-2">
                                <a href="<?php echo e($teacher->twitter_url); ?>" target="_blank" rel="noopener"
                                   class="text-decoration-none">
                                    <i class="bi bi-twitter text-primary me-2"></i>Twitter
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($teacher->google_scholar_url ?? false): ?>
                            <div class="mb-2">
                                <a href="<?php echo e($teacher->google_scholar_url); ?>" target="_blank" rel="noopener"
                                   class="text-decoration-none">
                                    <i class="bi bi-mortarboard text-primary me-2"></i>Google Scholar
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2>Frequently Asked Questions</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq1">
                                How quickly do you respond to emails?
                            </button>
                        </h3>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                I aim to respond to all emails within 1-2 business days. Student inquiries during
                                office hours typically receive same-day responses. For urgent matters, please
                                indicate this in your subject line.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq2">
                                Do you accept research collaboration proposals?
                            </button>
                        </h3>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, I'm always interested in meaningful research collaborations. Please include
                                details about the proposed project, timeline, and how it aligns with my research
                                interests when you contact me.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq3">
                                Are you available for speaking engagements?
                            </button>
                        </h3>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                I regularly accept speaking invitations for conferences, seminars, and academic
                                events. Please provide details about the event, audience, and preferred topics
                                when reaching out.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq4">
                                Can I schedule a meeting outside of office hours?
                            </button>
                        </h3>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Absolutely! While I maintain regular office hours, I'm happy to schedule
                                appointments at mutually convenient times. Please suggest a few preferred
                                time slots in your message.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/public/contact.blade.php ENDPATH**/ ?>