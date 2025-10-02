@extends('layouts.app')

@section('title', $project->title)
@section('page-title', $project->title)

@section('content')
<div class="row">
    <!-- Project Header -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <h1 class="mb-0 me-3">{{ $project->title }}</h1>
                            <span class="badge status-{{ $project->status }} status-badge">
                                {{ ucfirst($project->status) }}
                            </span>
                        </div>
                        <p class="text-muted mb-3">{{ $project->description }}</p>

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <i class="bi bi-person-fill me-2 text-primary"></i>
                                <strong>{{ __('Manager') }}:</strong> {{ $project->manager->name }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="bi bi-calendar me-2 text-primary"></i>
                                <strong>{{ __('Start Date') }}:</strong> {{ $project->start_date->format('M d, Y') }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="bi bi-calendar-check me-2 text-primary"></i>
                                <strong>{{ __('End Date') }}:</strong> {{ $project->end_date->format('M d, Y') }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="bi bi-clock me-2 text-primary"></i>
                                <strong>{{ __('Total Hours') }}:</strong> {{ $stats['total_hours'] }}h
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 text-center">
                        <!-- Progress Chart -->
                        <div class="position-relative d-inline-block mb-3">
                            <canvas id="progressChart" width="150" height="150"></canvas>
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <h3 class="mb-0">{{ $stats['progress_percentage'] }}%</h3>
                                <small class="text-muted">{{ __('Complete') }}</small>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col-6">
                                <div class="fw-bold text-primary fs-4">{{ $stats['total_tasks'] }}</div>
                                <small class="text-muted">{{ __('Total Tasks') }}</small>
                            </div>
                            <div class="col-6">
                                <div class="fw-bold text-success fs-4">{{ $stats['completed_tasks'] }}</div>
                                <small class="text-muted">{{ __('Completed') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                <div class="mt-3 pt-3 border-top">
                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary me-2">
                        <i class="bi bi-pencil me-2"></i>{{ __('Edit Project') }}
                    </a>
                    <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}" class="btn btn-outline-success me-2">
                        <i class="bi bi-plus-circle me-2"></i>{{ __('Add Task') }}
                    </a>
                    <button class="btn btn-outline-warning" onclick="changeProjectStatus()">
                        <i class="bi bi-arrow-repeat me-2"></i>{{ __('Change Status') }}
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tasks Section -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-check2-square me-2"></i>
                    {{ __('Project Tasks') }}
                </h5>
                <div>
                    <button class="btn btn-sm btn-outline-secondary me-2" onclick="refreshTasks()">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>{{ __('Add Task') }}
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <!-- Task Filters -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <select class="form-select form-select-sm" id="task-status-filter">
                            <option value="">{{ __('All Tasks') }}</option>
                            <option value="à_faire">{{ __('To Do') }}</option>
                            <option value="en_cours">{{ __('In Progress') }}</option>
                            <option value="fait">{{ __('Done') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control form-control-sm" id="task-search"
                               placeholder="{{ __('Search tasks...') }}">
                    </div>
                </div>

                <!-- Tasks List -->
                <div id="tasks-container">
                    <!-- Tasks will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Team Members -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>
                    {{ __('Team Members') }}
                </h5>
            </div>
            <div class="card-body">
                <div id="team-members">
                    <!-- Team members will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-activity me-2"></i>
                    {{ __('Recent Activity') }}
                </h5>
            </div>
            <div class="card-body">
                <div id="project-activity">
                    <!-- Activity will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    initProgressChart();
    loadProjectTasks();
    loadTeamMembers();
    loadProjectActivity();

    // Task filters
    document.getElementById('task-status-filter').addEventListener('change', loadProjectTasks);

    let searchTimeout;
    document.getElementById('task-search').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadProjectTasks, 500);
    });
});

function initProgressChart() {
    const ctx = document.getElementById('progressChart').getContext('2d');
    const progress = {{ $stats['progress_percentage'] }};

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [progress, 100 - progress],
                backgroundColor: ['#198754', '#e9ecef'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                }
            },
            responsive: false,
            maintainAspectRatio: false
        }
    });
}

function loadProjectTasks() {
    const statusFilter = document.getElementById('task-status-filter').value;
    const searchFilter = document.getElementById('task-search').value;

    const params = new URLSearchParams({
        project_id: {{ $project->id }},
        status: statusFilter,
        search: searchFilter
    });

    axios.get(`/ajax/tasks?${params}`)
        .then(response => {
            renderTasks(response.data.data);
        })
        .catch(error => console.error('Failed to load tasks:', error));
}

function renderTasks(tasks) {
    const container = document.getElementById('tasks-container');

    if (tasks.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="bi bi-check2-square fs-2"></i>
                <p class="mt-2">{{ __('No tasks found') }}</p>
            </div>
        `;
        return;
    }

    container.innerHTML = tasks.map(task => `
        <div class="card border-start border-${getTaskColor(task.status)} border-3 mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="card-title mb-1">
                            <a href="/tasks/${task.id}" class="text-decoration-none">${task.title}</a>
                        </h6>
                        <p class="text-muted small mb-2">${task.description || ''}</p>

                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <span class="badge status-${task.status} status-badge">
                                ${getStatusText(task.status)}
                            </span>
                            ${task.assigned_user ? `
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-person me-1"></i>${task.assigned_user.name}
                                </span>
                            ` : ''}
                            ${task.due_date ? `
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-calendar me-1"></i>${formatDate(task.due_date)}
                                </span>
                            ` : ''}
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/tasks/${task.id}">
                                <i class="bi bi-eye me-2"></i>{{ __('View') }}
                            </a></li>
                            <li><a class="dropdown-item" href="/tasks/${task.id}/edit">
                                <i class="bi bi-pencil me-2"></i>{{ __('Edit') }}
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="quickStatusChange(${task.id})">
                                <i class="bi bi-arrow-repeat me-2"></i>{{ __('Change Status') }}
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function loadTeamMembers() {
    // This would load team members assigned to tasks in this project
    const container = document.getElementById('team-members');
    container.innerHTML = `
        <div class="placeholder-glow">
            <div class="d-flex align-items-center mb-3">
                <div class="placeholder bg-primary rounded-circle me-2" style="width: 32px; height: 32px;"></div>
                <span class="placeholder col-6"></span>
            </div>
            <div class="d-flex align-items-center mb-3">
                <div class="placeholder bg-success rounded-circle me-2" style="width: 32px; height: 32px;"></div>
                <span class="placeholder col-5"></span>
            </div>
        </div>
    `;

    // Simulate loading team members
    setTimeout(() => {
        container.innerHTML = `
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                     style="width: 32px; height: 32px;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div>
                    <div class="fw-bold">{{ $project->manager->name }}</div>
                    <small class="text-muted">{{ __('Project Manager') }}</small>
                </div>
            </div>
            @foreach($project->tasks->where('assigned_to', '!=', null)->unique('assigned_to') as $task)
            <div class="d-flex align-items-center mb-3">
                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                     style="width: 32px; height: 32px;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div>
                    <div class="fw-bold">{{ $task->assignedUser->name ?? __('Unassigned') }}</div>
                    <small class="text-muted">{{ __('Team Member') }}</small>
                </div>
            </div>
            @endforeach
        `;
    }, 1000);
}

function loadProjectActivity() {
    const container = document.getElementById('project-activity');
    container.innerHTML = `
        <div class="placeholder-glow">
            <div class="d-flex mb-3">
                <div class="placeholder bg-primary rounded-circle me-2" style="width: 24px; height: 24px;"></div>
                <div class="flex-grow-1">
                    <span class="placeholder col-8"></span>
                    <span class="placeholder col-4"></span>
                </div>
            </div>
        </div>
    `;

    // This would load actual project activity from the API
    setTimeout(() => {
        container.innerHTML = `
            <div class="small">
                <div class="d-flex mb-3">
                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                         style="width: 24px; height: 24px;">
                        <i class="bi bi-check" style="font-size: 0.7rem;"></i>
                    </div>
                    <div>
                        <div>Task completed by John</div>
                        <small class="text-muted">2 hours ago</small>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                         style="width: 24px; height: 24px;">
                        <i class="bi bi-plus" style="font-size: 0.7rem;"></i>
                    </div>
                    <div>
                        <div>New task added</div>
                        <small class="text-muted">5 hours ago</small>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                         style="width: 24px; height: 24px;">
                        <i class="bi bi-clock" style="font-size: 0.7rem;"></i>
                    </div>
                    <div>
                        <div>Time logged by Sarah</div>
                        <small class="text-muted">1 day ago</small>
                    </div>
                </div>
            </div>
        `;
    }, 1200);
}

function refreshTasks() {
    loadProjectTasks();
}

function quickStatusChange(taskId) {
    // This would open a quick status change modal
    console.log('Change status for task:', taskId);
}

function changeProjectStatus() {
    // This would open a project status change modal
    console.log('Change project status');
}

function getTaskColor(status) {
    const colors = {
        'à_faire': 'warning',
        'en_cours': 'info',
        'fait': 'success'
    };
    return colors[status] || 'secondary';
}

function getStatusText(status) {
    const statuses = {
        'à_faire': '{{ __("To Do") }}',
        'en_cours': '{{ __("In Progress") }}',
        'fait': '{{ __("Done") }}'
    };
    return statuses[status] || status;
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString();
}
</script>
@endpush