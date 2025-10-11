<?php $__env->startSection('title', __('app.profile.title')); ?>
<?php $__env->startSection('page-title', __('app.profile.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('app.profile.profile_information')); ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong><?php echo e(__('app.profile.name')); ?>:</strong>
                        <p><?php echo e(auth()->user()->name); ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong><?php echo e(__('app.profile.email')); ?>:</strong>
                        <p><?php echo e(auth()->user()->email); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong><?php echo e(__('app.profile.role')); ?>:</strong>
                        <p>
                            <span class="badge bg-<?php echo e(auth()->user()->role === 'admin' ? 'danger' : (auth()->user()->role === 'manager' ? 'warning' : 'info')); ?>">
                                <?php switch(auth()->user()->role):
                                    case ('admin'): ?> <?php echo e(__('app.users.admin')); ?> <?php break; ?>
                                    <?php case ('manager'): ?> <?php echo e(__('app.users.manager')); ?> <?php break; ?>
                                    <?php case ('developer'): ?> <?php echo e(__('app.users.developer')); ?> <?php break; ?>
                                    <?php case ('designer'): ?> <?php echo e(__('app.users.designer')); ?> <?php break; ?>
                                    <?php case ('tester'): ?> <?php echo e(__('app.users.tester')); ?> <?php break; ?>
                                    <?php case ('hr'): ?> <?php echo e(__('app.users.hr')); ?> <?php break; ?>
                                    <?php case ('accountant'): ?> <?php echo e(__('app.users.accountant')); ?> <?php break; ?>
                                    <?php case ('client'): ?> <?php echo e(__('app.users.client')); ?> <?php break; ?>
                                    <?php case ('member'): ?> <?php echo e(__('app.users.member')); ?> <?php break; ?>
                                    <?php default: ?> <?php echo e(ucfirst(auth()->user()->role)); ?>

                                <?php endswitch; ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong><?php echo e(__('Member Since')); ?>:</strong>
                        <p><?php echo e(auth()->user()->created_at->format('M d, Y')); ?></p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="<?php echo e(route('profile.settings')); ?>" class="btn btn-primary">
                        <i class="bi bi-gear me-2"></i>
                        <?php echo e(__('Edit Profile')); ?>

                    </a>
                </div>
            </div>
        </div>

        <!-- My Productivity Charts -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('app.productivity.overview')); ?></h5>
            </div>
            <div class="card-body">
                <!-- Weekly Hours Chart -->
                <div class="mb-4">
                    <h6><?php echo e(__('app.productivity.weekly_summary')); ?></h6>
                    <canvas id="weeklyHoursChart" height="100"></canvas>
                </div>

                <!-- Task Progress Chart -->
                <div class="mb-3">
                    <h6><?php echo e(__('app.productivity.tasks_completed')); ?></h6>
                    <canvas id="taskProgressChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo e(__('Quick Stats')); ?></h5>
            </div>
            <div class="card-body">
                <?php
                    $user = auth()->user();
                    $myTasks = \App\Models\Task::where('assigned_to', $user->id)->count();
                    $completedTasks = \App\Models\Task::where('assigned_to', $user->id)->where('status', 'completed')->count();
                    $totalTimeThisMonth = \App\Models\TimeEntry::where('user_id', $user->id)
                        ->whereBetween('start_time', [now()->startOfMonth(), now()->endOfMonth()])
                        ->sum('duration_hours');
                ?>

                <div class="mb-3">
                    <strong><?php echo e(__('My Tasks')); ?>:</strong>
                    <span class="float-end"><?php echo e($myTasks); ?></span>
                </div>
                <div class="mb-3">
                    <strong><?php echo e(__('Completed Tasks')); ?>:</strong>
                    <span class="float-end"><?php echo e($completedTasks); ?></span>
                </div>
                <div class="mb-3">
                    <strong><?php echo e(__('Time This Month')); ?>:</strong>
                    <span class="float-end"><?php echo e(number_format($totalTimeThisMonth, 1)); ?>h</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const user = <?php echo json_encode(auth()->user(), 15, 512) ?>;

    // Get last 7 days for weekly chart
    const last7Days = <?php echo json_encode(array_map(function($i) {
        return now()->subDays(6-$i)->format('M d');
    }, range(0, 6))) ?>;

    // Get time entries for last 7 days
    const weeklyHours = <?php echo json_encode(
        auth()->user()->timeEntries()
            ->where('start_time', '>=', now()->subDays(7))
            ->get()
            ->groupBy(function($entry) {
                return $entry->start_time->format('Y-m-d');
            })
            ->map(function($entries) {
                return $entries->sum('duration_hours');
            })
            ->values()
            ->toArray()) ?> || [0, 0, 0, 0, 0, 0, 0];

    // Task completion data
    const taskData = [
        <?php echo e(auth()->user()->assignedTasks()->where('status', 'completed')->count()); ?>,
        <?php echo e(auth()->user()->assignedTasks()->where('status', 'in_progress')->count()); ?>,
        <?php echo e(auth()->user()->assignedTasks()->where('status', 'pending')->count()); ?>

    ];

    // Common chart options
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    };

    // Weekly Hours Chart
    const weeklyCtx = document.getElementById('weeklyHoursChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: last7Days,
            datasets: [{
                label: '<?php echo e(__("app.time.hours")); ?>',
                data: weeklyHours,
                backgroundColor: 'rgba(0, 123, 255, 0.6)',
                borderColor: '#007bff',
                borderWidth: 1
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + 'h <?php echo e(__("app.time.logged")); ?>';
                        }
                    }
                }
            }
        }
    });

    // Task Progress Chart
    const taskCtx = document.getElementById('taskProgressChart').getContext('2d');
    new Chart(taskCtx, {
        type: 'doughnut',
        data: {
            labels: [
                '<?php echo e(__("app.tasks.completed")); ?>',
                '<?php echo e(__("app.tasks.in_progress")); ?>',
                '<?php echo e(__("app.tasks.pending")); ?>'
            ],
            datasets: [{
                data: taskData,
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#6c757d'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 10,
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((context.parsed * 100) / total) : 0;
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/profile/index.blade.php ENDPATH**/ ?>