<?php $__env->startSection('title', 'Contact Message'); ?>
<?php $__env->startSection('page-title', 'Contact Message'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">Contact Message</h5>
                <p class="text-muted mb-0">From: <?php echo e($contactMessage->name); ?></p>
            </div>
            <div class="btn-group">
                <a href="<?php echo e(route('admin.contact.index')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Messages
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><?php echo e($contactMessage->subject ?: 'No Subject'); ?></h6>
                            <div>
                                <?php if($contactMessage->status === 'unread'): ?>
                                    <span class="badge bg-primary">Unread</span>
                                <?php elseif($contactMessage->status === 'read'): ?>
                                    <span class="badge bg-info">Read</span>
                                <?php elseif($contactMessage->status === 'replied'): ?>
                                    <span class="badge bg-success">Replied</span>
                                <?php elseif($contactMessage->status === 'archived'): ?>
                                    <span class="badge bg-secondary">Archived</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="message-content">
                            <?php echo nl2br(e($contactMessage->message)); ?>

                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">Quick Reply</h6>
                    </div>
                    <div class="card-body">
                        <form id="replyForm">
                            <div class="mb-3">
                                <label for="reply_message" class="form-label">Reply Message</label>
                                <textarea class="form-control" id="reply_message" name="reply_message" rows="5"
                                          placeholder="Type your reply here..."></textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-secondary me-2" onclick="window.open('mailto:<?php echo e($contactMessage->email); ?>')">
                                    <i class="bi bi-envelope me-2"></i>Open Email Client
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-2"></i>Send Reply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Message Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">From</label>
                            <div><?php echo e($contactMessage->name); ?></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <div>
                                <a href="mailto:<?php echo e($contactMessage->email); ?>" class="text-decoration-none">
                                    <?php echo e($contactMessage->email); ?>

                                </a>
                            </div>
                        </div>

                        <?php if($contactMessage->phone): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Phone</label>
                                <div>
                                    <a href="tel:<?php echo e($contactMessage->phone); ?>" class="text-decoration-none">
                                        <?php echo e($contactMessage->phone); ?>

                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Received</label>
                            <div><?php echo e($contactMessage->created_at->format('F j, Y \a\t g:i A')); ?></div>
                            <small class="text-muted"><?php echo e($contactMessage->created_at->diffForHumans()); ?></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <div>
                                <form method="POST" action="<?php echo e(route('admin.contact.update-status', $contactMessage)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="unread" <?php echo e($contactMessage->status === 'unread' ? 'selected' : ''); ?>>Unread</option>
                                        <option value="read" <?php echo e($contactMessage->status === 'read' ? 'selected' : ''); ?>>Read</option>
                                        <option value="replied" <?php echo e($contactMessage->status === 'replied' ? 'selected' : ''); ?>>Replied</option>
                                        <option value="archived" <?php echo e($contactMessage->status === 'archived' ? 'selected' : ''); ?>>Archived</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary"
                                    onclick="window.open('mailto:<?php echo e($contactMessage->email); ?>?subject=Re: <?php echo e(urlencode($contactMessage->subject ?: 'Your message')); ?>')">
                                <i class="bi bi-envelope me-2"></i>Reply via Email
                            </button>

                            <?php if($contactMessage->phone): ?>
                                <button type="button" class="btn btn-outline-info"
                                        onclick="window.open('tel:<?php echo e($contactMessage->phone); ?>')">
                                    <i class="bi bi-telephone me-2"></i>Call
                                </button>
                            <?php endif; ?>

                            <form method="POST" action="<?php echo e(route('admin.contact.update-status', $contactMessage)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <input type="hidden" name="status" value="<?php echo e($contactMessage->status === 'archived' ? 'read' : 'archived'); ?>">
                                <button type="submit" class="btn btn-outline-warning w-100">
                                    <i class="bi bi-archive me-2"></i>
                                    <?php echo e($contactMessage->status === 'archived' ? 'Unarchive' : 'Archive'); ?>

                                </button>
                            </form>

                            <form method="POST" action="<?php echo e(route('admin.contact.destroy', $contactMessage)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-outline-danger w-100"
                                        data-confirm-delete>
                                    <i class="bi bi-trash me-2"></i>Delete Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark message as read when viewing (if it's unread)
    <?php if($contactMessage->status === 'unread'): ?>
        fetch("<?php echo e(route('admin.contact.update-status', $contactMessage)); ?>", {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: 'read' })
        });
    <?php endif; ?>

    // Handle reply form
    document.getElementById('replyForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const message = document.getElementById('reply_message').value;

        if (message.trim()) {
            // Open email client with pre-filled content
            const subject = encodeURIComponent('Re: <?php echo e($contactMessage->subject ?: "Your message"); ?>');
            const body = encodeURIComponent(message + '\n\n---\nOriginal message:\n<?php echo e(addslashes($contactMessage->message)); ?>');
            const mailto = `mailto:<?php echo e($contactMessage->email); ?>?subject=${subject}&body=${body}`;

            window.open(mailto);

            // Update status to replied
            fetch("<?php echo e(route('admin.contact.update-status', $contactMessage)); ?>", {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: 'replied' })
            }).then(() => {
                window.location.reload();
            });
        } else {
            alert('Please enter a reply message.');
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/admin/contact/show.blade.php ENDPATH**/ ?>