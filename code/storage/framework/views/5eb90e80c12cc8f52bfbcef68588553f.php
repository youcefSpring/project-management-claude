<?php $__env->startSection('title', __('Dashboard')); ?>
<?php $__env->startSection('page-title', __('Dashboard')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Stats Cards -->
    <div class="col-12">
        <div class="row" id="stats-container">
            <!-- Loading skeleton -->
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="placeholder-glow">
                                    <span class="placeholder col-6"></span>
                                    <span class="placeholder col-4"></span>
                                </div>
                            </div>
                            <div class="placeholder bg-primary rounded-circle" style="width: 60px; height: 60px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="placeholder-glow">
                                    <span class="placeholder col-6"></span>
                                    <span class="placeholder col-4"></span>
                                </div>
                            </div>
                            <div class="placeholder bg-success rounded-circle" style="width: 60px; height: 60px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="placeholder-glow">
                                    <span class="placeholder col-6"></span>
                                    <span class="placeholder col-4"></span>
                                </div>
                            </div>
                            <div class="placeholder bg-warning rounded-circle" style="width: 60px; height: 60px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="placeholder-glow">
                                    <span class="placeholder col-6"></span>
                                    <span class="placeholder col-4"></span>
                                </div>
                            </div>
                            <div class="placeholder bg-info rounded-circle" style="width: 60px; height: 60px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="col-lg-8">
        <!-- Recent Activity -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-activity me-2"></i>
                    <?php echo e(__('Recent Activity')); ?>

                </h5>
                <button class="btn btn-sm btn-outline-secondary" onclick="refreshActivity()">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <div id="activity-container">
                    <!-- Loading placeholder -->
                    <div class="d-flex mb-3">
                        <div class="placeholder-glow flex-shrink-0 me-3">
                            <div class="placeholder bg-primary rounded-circle" style="width: 40px; height: 40px;"></div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="placeholder-glow">
                                <span class="placeholder col-8"></span>
                                <span class="placeholder col-4"></span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="placeholder-glow flex-shrink-0 me-3">
                            <div class="placeholder bg-success rounded-circle" style="width: 40px; height: 40px;"></div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="placeholder-glow">
                                <span class="placeholder col-6"></span>
                                <span class="placeholder col-5"></span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="placeholder-glow flex-shrink-0 me-3">
                            <div class="placeholder bg-warning rounded-circle" style="width: 40px; height: 40px;"></div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="placeholder-glow">
                                <span class="placeholder col-7"></span>
                                <span class="placeholder col-3"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Tasks -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-check2-square me-2"></i>
                    <?php echo e(__('My Tasks')); ?>

                </h5>
                <div>
                    <button class="btn btn-sm btn-outline-secondary me-2" onclick="refreshTasks()">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                    <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-sm btn-primary">
                        <?php echo e(__('View All')); ?>

                    </a>
                </div>
            </div>
            <div class="card-body">
                <div id="tasks-container">
                    <!-- Loading placeholder -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card border-start border-warning border-3">
                                <div class="card-body">
                                    <div class="placeholder-glow">
                                        <span class="placeholder col-8"></span>
                                        <span class="placeholder col-5"></span>
                                        <span class="placeholder col-6"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-start border-info border-3">
                                <div class="card-body">
                                    <div class="placeholder-glow">
                                        <span class="placeholder col-7"></span>
                                        <span class="placeholder col-4"></span>
                                        <span class="placeholder col-6"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-start border-success border-3">
                                <div class="card-body">
                                    <div class="placeholder-glow">
                                        <span class="placeholder col-6"></span>
                                        <span class="placeholder col-5"></span>
                                        <span class="placeholder col-7"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    <?php echo e(__('Quick Actions')); ?>

                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                    <a href="<?php echo e(route('projects.create')); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle me-2"></i>
                        <?php echo e(__('New Project')); ?>

                    </a>
                    <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-outline-success">
                        <i class="bi bi-plus-square me-2"></i>
                        <?php echo e(__('New Task')); ?>

                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('timesheet.create')); ?>" class="btn btn-outline-info">
                        <i class="bi bi-clock me-2"></i>
                        <?php echo e(__('Log Time')); ?>

                    </a>
                    <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                    <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-outline-warning">
                        <i class="bi bi-graph-up me-2"></i>
                        <?php echo e(__('View Reports')); ?>

                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-bell me-2"></i>
                    <?php echo e(__('Notifications')); ?>

                </h5>
                <span class="badge bg-primary" id="notification-badge" style="display: none;">0</span>
            </div>
            <div class="card-body">
                <div id="notifications-container">
                    <div class="text-center text-muted">
                        <i class="bi bi-bell-slash fs-2"></i>
                        <p class="mt-2"><?php echo e(__('No notifications')); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Tracking Widget -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-stopwatch me-2"></i>
                    <?php echo e(__('Time Tracking')); ?>

                </h5>
            </div>
            <div class="card-body">
                <div id="time-tracking-widget">
                    <div class="text-center">
                        <div class="fs-4 fw-bold text-primary" id="current-time">00:00:00</div>
                        <p class="text-muted"><?php echo e(__('Today\'s Total')); ?></p>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-success" id="start-timer" onclick="startTimer()">
                            <i class="bi bi-play-circle me-2"></i>
                            <?php echo e(__('Start Timer')); ?>

                        </button>
                        <button class="btn btn-danger d-none" id="stop-timer" onclick="stopTimer()">
                            <i class="bi bi-stop-circle me-2"></i>
                            <?php echo e(__('Stop Timer')); ?>

                        </button>
                    </div>

                    <div class="mt-3">
                        <small class="text-muted"><?php echo e(__('This week: ')); ?></small>
                        <span id="week-total" class="fw-bold">0h 0m</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load dashboard data
    loadDashboardStats();
    loadRecentActivity();
    loadMyTasks();
    loadNotifications();
    initTimeTracking();

    // Refresh data every 30 seconds
    setInterval(() => {
        loadDashboardStats();
        loadNotifications();
    }, 30000);

    // Refresh time tracking every second
    setInterval(updateTimeDisplay, 1000);
});

function loadDashboardStats() {
    axios.get('/ajax/dashboard/stats')
        .then(response => {
            const stats = response.data;
            renderStatsCards(stats);
        })
        .catch(error => console.error('Failed to load stats:', error));
}

function renderStatsCards(stats) {
    const container = document.getElementById('stats-container');
    container.innerHTML = `
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">${stats.projects_count || 0}</h3>
                            <p class="mb-0"><?php echo e(__('Projects')); ?></p>
                        </div>
                        <div class="opacity-75">
                            <i class="bi bi-folder fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">${stats.tasks_count || 0}</h3>
                            <p class="mb-0"><?php echo e(__('Total Tasks')); ?></p>
                        </div>
                        <div class="opacity-75">
                            <i class="bi bi-check2-square fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">${stats.my_tasks || 0}</h3>
                            <p class="mb-0"><?php echo e(__('My Tasks')); ?></p>
                        </div>
                        <div class="opacity-75">
                            <i class="bi bi-person-check fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">${stats.total_hours_this_week || 0}h</h3>
                            <p class="mb-0"><?php echo e(__('This Week')); ?></p>
                        </div>
                        <div class="opacity-75">
                            <i class="bi bi-clock fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function loadRecentActivity() {
    axios.get('/ajax/dashboard/recent-activity')
        .then(response => {
            const activities = response.data.data;
            renderRecentActivity(activities);
        })
        .catch(error => console.error('Failed to load activity:', error));
}

function renderRecentActivity(activities) {
    const container = document.getElementById('activity-container');

    if (activities.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted">
                <i class="bi bi-activity fs-2"></i>
                <p class="mt-2"><?php echo e(__('No recent activity')); ?></p>
            </div>
        `;
        return;
    }

    container.innerHTML = activities.map(activity => `
        <div class="d-flex mb-3">
            <div class="flex-shrink-0 me-3">
                <div class="bg-${getActivityColor(activity.type)} rounded-circle d-flex align-items-center justify-content-center text-white"
                     style="width: 40px; height: 40px;">
                    <i class="bi ${getActivityIcon(activity.type)}"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <p class="mb-1">${activity.description}</p>
                <small class="text-muted">${formatTime(activity.created_at)}</small>
            </div>
        </div>
    `).join('');
}

function loadMyTasks() {
    const params = new URLSearchParams({
        assigned_to: window.userId,
        limit: 6
    });

    axios.get(`/ajax/tasks?${params}`)
        .then(response => {
            const tasks = response.data.data;
            renderMyTasks(tasks);
        })
        .catch(error => console.error('Failed to load tasks:', error));
}

function renderMyTasks(tasks) {
    const container = document.getElementById('tasks-container');

    if (tasks.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted">
                <i class="bi bi-check2-square fs-2"></i>
                <p class="mt-2"><?php echo e(__('No tasks assigned')); ?></p>
                <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-outline-primary">
                    <?php echo e(__('Browse Available Tasks')); ?>

                </a>
            </div>
        `;
        return;
    }

    const tasksByStatus = {
        'à_faire': tasks.filter(t => t.status === 'à_faire'),
        'en_cours': tasks.filter(t => t.status === 'en_cours'),
        'fait': tasks.filter(t => t.status === 'fait')
    };

    container.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <h6 class="text-warning"><?php echo e(__('To Do')); ?></h6>
                ${tasksByStatus['à_faire'].map(task => renderTaskCard(task, 'warning')).join('')}
            </div>
            <div class="col-md-4">
                <h6 class="text-info"><?php echo e(__('In Progress')); ?></h6>
                ${tasksByStatus['en_cours'].map(task => renderTaskCard(task, 'info')).join('')}
            </div>
            <div class="col-md-4">
                <h6 class="text-success"><?php echo e(__('Done')); ?></h6>
                ${tasksByStatus['fait'].map(task => renderTaskCard(task, 'success')).join('')}
            </div>
        </div>
    `;
}

function renderTaskCard(task, color) {
    return `
        <div class="card border-start border-${color} border-3 mb-2">
            <div class="card-body p-2">
                <h6 class="card-title mb-1">
                    <a href="/tasks/${task.id}" class="text-decoration-none">${task.title}</a>
                </h6>
                <small class="text-muted">${task.project?.title || ''}</small>
                ${task.due_date ? `<br><small class="text-muted"><?php echo e(__('Due')); ?>: ${formatDate(task.due_date)}</small>` : ''}
            </div>
        </div>
    `;
}

function refreshActivity() {
    loadRecentActivity();
}

function refreshTasks() {
    loadMyTasks();
}

// Time Tracking Functions
let timerInterval = null;
let startTime = null;

function initTimeTracking() {
    updateTimeDisplay();
}

function updateTimeDisplay() {
    // This would typically fetch actual time data from the server
    const now = new Date();
    document.getElementById('current-time').textContent = now.toLocaleTimeString();
}

function startTimer() {
    startTime = new Date();
    document.getElementById('start-timer').classList.add('d-none');
    document.getElementById('stop-timer').classList.remove('d-none');

    timerInterval = setInterval(() => {
        const elapsed = new Date() - startTime;
        const hours = Math.floor(elapsed / 3600000);
        const minutes = Math.floor((elapsed % 3600000) / 60000);
        const seconds = Math.floor((elapsed % 60000) / 1000);

        document.getElementById('current-time').textContent =
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }, 1000);

    showSuccess('<?php echo e(__("Timer started")); ?>');
}

function stopTimer() {
    if (timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
    }

    document.getElementById('start-timer').classList.remove('d-none');
    document.getElementById('stop-timer').classList.add('d-none');

    if (startTime) {
        const elapsed = new Date() - startTime;
        const hours = Math.floor(elapsed / 3600000);
        const minutes = Math.floor((elapsed % 3600000) / 60000);

        showSuccess(`<?php echo e(__("Timer stopped. Elapsed time")); ?>: ${hours}h ${minutes}m`);

        // Here you would typically save the time entry to the server
        // showTimeEntryModal(startTime, new Date());
    }
}

// Utility functions
function getActivityColor(type) {
    const colors = {
        'project_created': 'primary',
        'task_completed': 'success',
        'task_assigned': 'info',
        'time_logged': 'warning',
        'note_added': 'secondary'
    };
    return colors[type] || 'secondary';
}

function getActivityIcon(type) {
    const icons = {
        'project_created': 'bi-folder-plus',
        'task_completed': 'bi-check-circle',
        'task_assigned': 'bi-person-plus',
        'time_logged': 'bi-clock',
        'note_added': 'bi-chat-dots'
    };
    return icons[type] || 'bi-circle';
}

function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;

    if (diff < 60000) return '<?php echo e(__("Just now")); ?>';
    if (diff < 3600000) return Math.floor(diff / 60000) + '<?php echo e(__("m ago")); ?>';
    if (diff < 86400000) return Math.floor(diff / 3600000) + '<?php echo e(__("h ago")); ?>';
    return Math.floor(diff / 86400000) + '<?php echo e(__("d ago")); ?>';
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString();
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/charikatec/Desktop/my docs/Laravel Apps/project-management-claude/code/resources/views/dashboard/index.blade.php ENDPATH**/ ?>