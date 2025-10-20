<?php $__env->startSection('title', __('Help')); ?>
<?php $__env->startSection('page-title', __('Help & Documentation')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Getting Started')); ?></h5>
            </div>
            <div class="card-body">
                <h6><?php echo e(__('Welcome to Project Management System')); ?></h6>
                <p><?php echo e(__('This system helps you manage projects, tasks, and time tracking efficiently.')); ?></p>

                <h6 class="mt-4"><?php echo e(__('Key Features')); ?></h6>
                <ul>
                    <li><?php echo e(__('Project Management - Create and manage projects')); ?></li>
                    <li><?php echo e(__('Task Management - Assign and track tasks')); ?></li>
                    <li><?php echo e(__('Time Tracking - Log time spent on tasks')); ?></li>
                    <li><?php echo e(__('Reports - Generate detailed reports')); ?></li>
                    <li><?php echo e(__('Team Collaboration - Work together efficiently')); ?></li>
                </ul>

                <h6 class="mt-4"><?php echo e(__('Quick Actions')); ?></h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h6 class="card-title"><?php echo e(__('Create a Project')); ?></h6>
                                <p class="card-text"><?php echo e(__('Start by creating a new project to organize your work.')); ?></p>
                                <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                                    <a href="<?php echo e(route('projects.create')); ?>" class="btn btn-primary btn-sm"><?php echo e(__('Create Project')); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-body">
                                <h6 class="card-title"><?php echo e(__('Log Time')); ?></h6>
                                <p class="card-text"><?php echo e(__('Track time spent on your tasks.')); ?></p>
                                <a href="<?php echo e(route('timesheet.create')); ?>" class="btn btn-success btn-sm"><?php echo e(__('Log Time')); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('User Guide')); ?></h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="helpAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingProjects">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProjects">
                                <?php echo e(__('Managing Projects')); ?>

                            </button>
                        </h2>
                        <div id="collapseProjects" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <p><?php echo e(__('Projects are the main containers for organizing work. Here\'s how to manage them:')); ?></p>
                                <ul>
                                    <li><?php echo e(__('Create projects with clear titles and descriptions')); ?></li>
                                    <li><?php echo e(__('Set start and end dates')); ?></li>
                                    <li><?php echo e(__('Assign project managers')); ?></li>
                                    <li><?php echo e(__('Track project progress through the dashboard')); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTasks">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTasks">
                                <?php echo e(__('Working with Tasks')); ?>

                            </button>
                        </h2>
                        <div id="collapseTasks" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <p><?php echo e(__('Tasks break down projects into manageable work items:')); ?></p>
                                <ul>
                                    <li><?php echo e(__('Create tasks within projects')); ?></li>
                                    <li><?php echo e(__('Assign tasks to team members')); ?></li>
                                    <li><?php echo e(__('Set due dates and priorities')); ?></li>
                                    <li><?php echo e(__('Update task status as work progresses')); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTime">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTime">
                                <?php echo e(__('Time Tracking')); ?>

                            </button>
                        </h2>
                        <div id="collapseTime" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <p><?php echo e(__('Track time spent on tasks for accurate project reporting:')); ?></p>
                                <ul>
                                    <li><?php echo e(__('Log time entries for specific tasks')); ?></li>
                                    <li><?php echo e(__('Add descriptions to explain the work done')); ?></li>
                                    <li><?php echo e(__('View time summaries in reports')); ?></li>
                                    <li><?php echo e(__('Use time data for project planning')); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Contact Support')); ?></h5>
            </div>
            <div class="card-body">
                <p><?php echo e(__('Need additional help? Contact our support team.')); ?></p>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="alert('Feature coming soon!')">
                        <i class="bi bi-envelope me-2"></i>
                        <?php echo e(__('Email Support')); ?>

                    </button>
                    <button class="btn btn-outline-info" onclick="alert('Feature coming soon!')">
                        <i class="bi bi-chat-dots me-2"></i>
                        <?php echo e(__('Live Chat')); ?>

                    </button>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Keyboard Shortcuts')); ?></h5>
            </div>
            <div class="card-body">
                <small>
                    <strong>Ctrl + /</strong> - <?php echo e(__('Show this help')); ?><br>
                    <strong>Ctrl + N</strong> - <?php echo e(__('New task')); ?><br>
                    <strong>Ctrl + D</strong> - <?php echo e(__('Dashboard')); ?><br>
                    <strong>Ctrl + T</strong> - <?php echo e(__('Time tracking')); ?><br>
                </small>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('System Information')); ?></h5>
            </div>
            <div class="card-body">
                <small>
                    <strong><?php echo e(__('Version')); ?>:</strong> 1.0.0<br>
                    <strong><?php echo e(__('Last Updated')); ?>:</strong> <?php echo e(now()->format('M d, Y')); ?><br>
                    <strong><?php echo e(__('User Role')); ?>:</strong> <?php echo e(ucfirst(auth()->user()->role)); ?><br>
                </small>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/Terminé/project-management-claude/code/resources/views/help/index.blade.php ENDPATH**/ ?>