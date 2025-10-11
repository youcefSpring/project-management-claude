@extends('layouts.app')

@section('title', $user->name)
@section('page-title', $user->name)

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- User Profile -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('app.profile') }}</h5>
                <div>
                    @can('update', $user)
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>
                            {{ __('app.edit') }}
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="avatar-large mb-3">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <h6>{{ $user->name }}</h6>
                        <span class="badge bg-{{ $user->isAdmin() ? 'danger' : ($user->isManager() ? 'warning' : 'primary') }} fs-6">
                            {{ $user->getRoleLabel() }}
                        </span>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-sm-6">
                                <strong>{{ __('app.email') }}:</strong>
                                <p>{{ $user->email }}</p>
                            </div>
                            <div class="col-sm-6">
                                <strong>{{ __('Language') }}:</strong>
                                <p>
                                    @switch($user->language)
                                        @case('en')
                                            {{ __('English') }}
                                            @break
                                        @case('fr')
                                            {{ __('French') }}
                                            @break
                                        @case('ar')
                                            {{ __('Arabic') }}
                                            @break
                                        @default
                                            {{ ucfirst($user->language) }}
                                    @endswitch
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <strong>{{ __('Member Since') }}:</strong>
                                <p>{{ $user->created_at->format('F d, Y') }}</p>
                            </div>
                            <div class="col-sm-6">
                                <strong>{{ __('Last Updated') }}:</strong>
                                <p>{{ $user->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects (for managers) -->
        @if($user->isManager())
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        {{ __('app.projects.title') }}
                        <span class="badge bg-secondary ms-2">{{ $user->managedProjects->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($user->managedProjects->count() > 0)
                        <div class="row">
                            @foreach($user->managedProjects as $project)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-start border-{{ $project->status === 'active' ? 'success' : ($project->status === 'completed' ? 'primary' : 'warning') }} border-3">
                                        <div class="card-body p-3">
                                            <h6 class="card-title">
                                                <a href="{{ route('projects.show', $project) }}" class="text-decoration-none">
                                                    {{ $project->title }}
                                                </a>
                                            </h6>
                                            <p class="card-text small text-muted">{{ Str::limit($project->description, 80) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-{{ $project->status === 'active' ? 'success' : ($project->status === 'completed' ? 'primary' : 'warning') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                                </span>
                                                <small class="text-muted">{{ $project->tasks->count() }} {{ __('app.tasks.title') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3">{{ __('app.projects.no_projects') }}</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Tasks (for team members) -->
        @if($user->canWorkOnTasks())
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        {{ __('app.tasks.title') }}
                        <span class="badge bg-secondary ms-2">{{ $user->assignedTasks->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($user->assignedTasks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('app.tasks.title') }}</th>
                                        <th>{{ __('app.projects.title') }}</th>
                                        <th>{{ __('app.status') }}</th>
                                        <th>{{ __('app.tasks.due_date') }}</th>
                                        <th>{{ __('app.tasks.priority') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->assignedTasks->take(10) as $task)
                                        <tr>
                                            <td>
                                                <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none">
                                                    {{ $task->title }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('projects.show', $task->project) }}" class="text-decoration-none">
                                                    {{ $task->project->title }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($task->due_date)
                                                    @php
                                                        $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                                    @endphp
                                                    {{ $dueDate->format('M d, Y') }}
                                                    @if($dueDate->isPast() && $task->status !== 'completed')
                                                        <span class="badge bg-danger ms-1">{{ __('app.tasks.overdue') }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">{{ __('No due date') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $task->priority === 'urgent' ? 'danger' : ($task->priority === 'high' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($user->assignedTasks->count() > 10)
                            <div class="text-center">
                                <a href="{{ route('tasks.index', ['assigned_to' => $user->id]) }}" class="btn btn-outline-primary">
                                    {{ __('app.tasks.title') }}
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-muted text-center py-3">{{ __('app.tasks.no_tasks') }}</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Recent Activity -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.dashboard.recent_activity') }}</h5>
            </div>
            <div class="card-body">
                @if($user->taskNotes->count() > 0 || $user->timeEntries->count() > 0)
                    <div class="timeline">
                        @php
                            $activities = collect();

                            // Add recent comments
                            foreach($user->taskNotes->take(5) as $note) {
                                $activities->push([
                                    'type' => 'comment',
                                    'date' => $note->created_at,
                                    'title' => 'Commented on task',
                                    'description' => $note->task->title,
                                    'content' => Str::limit($note->content, 100),
                                    'link' => route('tasks.show', $note->task)
                                ]);
                            }

                            // Add recent time entries
                            foreach($user->timeEntries->take(5) as $entry) {
                                $activities->push([
                                    'type' => 'time',
                                    'date' => $entry->created_at,
                                    'title' => 'Logged time',
                                    'description' => $entry->task->title,
                                    'content' => number_format($entry->duration_hours, 1) . 'h',
                                    'link' => route('tasks.show', $entry->task)
                                ]);
                            }

                            $activities = $activities->sortByDesc('date')->take(10);
                        @endphp

                        @foreach($activities as $activity)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ $activity['type'] === 'comment' ? 'primary' : 'success' }}">
                                    <i class="bi bi-{{ $activity['type'] === 'comment' ? 'chat-text' : 'clock' }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">{{ $activity['title'] }}</h6>
                                    <p class="timeline-text">
                                        <a href="{{ $activity['link'] }}" class="text-decoration-none">{{ $activity['description'] }}</a>
                                        @if($activity['content'])
                                            <br><small class="text-muted">{{ $activity['content'] }}</small>
                                        @endif
                                    </p>
                                    <small class="text-muted">{{ $activity['date']->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3">{{ __('app.dashboard.no_recent_activity') }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- User Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @can('update', $user)
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-2"></i>
                            {{ __('app.profile') }}
                        </a>
                    @endcan

                    @if($user->canWorkOnTasks())
                        <a href="{{ route('tasks.index', ['assigned_to' => $user->id]) }}" class="btn btn-outline-success">
                            <i class="bi bi-list-task me-2"></i>
                            {{ __('app.tasks.title') }}
                        </a>
                    @endif

                    @if($user->isManager())
                        <a href="{{ route('projects.index', ['manager_id' => $user->id]) }}" class="btn btn-outline-info">
                            <i class="bi bi-folder me-2"></i>
                            {{ __('app.projects.title') }}
                        </a>
                    @endif

                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>
                        {{ __('app.users.title') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Productivity Charts -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.productivity.title') }}</h5>
            </div>
            <div class="card-body">
                <!-- Daily Hours Chart -->
                <div class="mb-4">
                    <h6>{{ __('app.productivity.daily_hours') }}</h6>
                    <canvas id="dailyHoursChart" height="100"></canvas>
                </div>

                <!-- Task Completion Chart -->
                <div class="mb-4">
                    <h6>{{ __('app.productivity.completion_rate') }}</h6>
                    <canvas id="taskCompletionChart" height="100"></canvas>
                </div>

                <!-- Work Pattern Chart -->
                <div class="mb-3">
                    <h6>{{ __('app.productivity.work_patterns') }}</h6>
                    <canvas id="workPatternChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Statistics') }}</h5>
            </div>
            <div class="card-body">
                @if($user->isManager())
                    <div class="stat-item">
                        <div class="stat-value">{{ $stats['total_projects_managed'] }}</div>
                        <div class="stat-label">{{ __('app.projects.title') }}</div>
                    </div>
                @endif

                @if($user->canWorkOnTasks())
                    <div class="stat-item">
                        <div class="stat-value">{{ $stats['total_tasks_assigned'] }}</div>
                        <div class="stat-label">{{ __('app.tasks.title') }}</div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-value">{{ $stats['completed_tasks'] }}</div>
                        <div class="stat-label">{{ __('app.dashboard.completed_tasks') }}</div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-value">{{ number_format($stats['total_time_logged'], 1) }}h</div>
                        <div class="stat-label">{{ __('app.dashboard.time_logged') }}</div>
                    </div>
                @endif

                <div class="stat-item">
                    <div class="stat-value">{{ $stats['total_comments'] }}</div>
                    <div class="stat-label">{{ __('Comments Made') }}</div>
                </div>
            </div>
        </div>

        <!-- Role Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.users.role') }}</h5>
            </div>
            <div class="card-body">
                <h6 class="text-primary">{{ $user->getRoleLabel() }}</h6>
                <p class="small">
                    @switch($user->role)
                        @case('admin')
                            {{ __('Full system access. Can manage all users, projects, and system settings.') }}
                            @break
                        @case('manager')
                            {{ __('Can manage projects and teams. Has access to reports and project oversight.') }}
                            @break
                        @case('developer')
                            {{ __('Technical team member who works on development tasks.') }}
                            @break
                        @case('designer')
                            {{ __('Creative team member who works on design-related tasks.') }}
                            @break
                        @case('tester')
                            {{ __('Quality assurance team member who tests and validates work.') }}
                            @break
                        @case('hr')
                            {{ __('HR team member with access to user management and reports.') }}
                            @break
                        @case('accountant')
                            {{ __('Financial team member with access to time tracking and billing reports.') }}
                            @break
                        @case('client')
                            {{ __('External client with limited access to view project progress.') }}
                            @break
                        @default
                            {{ __('General team member with basic access.') }}
                    @endswitch
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background-color: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 24px;
    text-transform: uppercase;
    margin: 0 auto;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
    border-left: 2px solid #e9ecef;
}

.timeline-item:last-child {
    border-left: none;
}

.timeline-marker {
    position: absolute;
    left: -31px;
    top: 5px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
}

.timeline-content {
    padding-left: 20px;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 14px;
}

.timeline-text {
    margin-bottom: 5px;
    font-size: 13px;
}

.stat-item {
    text-align: center;
    padding: 15px 0;
    border-bottom: 1px solid #f8f9fa;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-value {
    font-size: 24px;
    font-weight: bold;
    color: #007bff;
}

.stat-label {
    font-size: 12px;
    text-transform: uppercase;
    color: #6c757d;
    margin-top: 5px;
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Productivity data from backend
    const productivityData = @json($productivityData ?? []);

    // Default data if no backend data available
    const defaultData = {
        dailyHours: {
            labels: @json(array_map(function($i) { return now()->subDays(6-$i)->format('M d'); }, range(0, 6))),
            data: @json($user->timeEntries->where('created_at', '>=', now()->subDays(7))->groupBy(function($entry) {
                return $entry->created_at->format('Y-m-d');
            })->map(function($entries) {
                return $entries->sum('duration_hours');
            })->values()->toArray() ?: [2, 4, 6, 3, 5, 7, 4])
        },
        taskCompletion: {
            labels: ['{{ __("app.tasks.completed") }}', '{{ __("app.tasks.in_progress") }}', '{{ __("app.tasks.pending") }}'],
            data: [
                {{ $user->assignedTasks->where('status', 'completed')->count() }},
                {{ $user->assignedTasks->where('status', 'in_progress')->count() }},
                {{ $user->assignedTasks->where('status', 'pending')->count() }}
            ]
        },
        workPattern: {
            labels: ['6 AM', '9 AM', '12 PM', '3 PM', '6 PM', '9 PM'],
            data: @json($user->timeEntries->groupBy(function($entry) {
                return $entry->created_at->format('H');
            })->map(function($entries, $hour) {
                return $entries->count();
            })->values()->slice(6, 6)->toArray() ?: [1, 8, 6, 4, 2, 1])
        }
    };

    // Common chart options
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
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
        }
    };

    // Daily Hours Chart
    const dailyHoursCtx = document.getElementById('dailyHoursChart').getContext('2d');
    new Chart(dailyHoursCtx, {
        type: 'line',
        data: {
            labels: defaultData.dailyHours.labels,
            datasets: [{
                label: '{{ __("app.time.hours") }}',
                data: defaultData.dailyHours.data,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + 'h {{ __("app.time.logged") }}';
                        }
                    }
                }
            }
        }
    });

    // Task Completion Chart
    const taskCompletionCtx = document.getElementById('taskCompletionChart').getContext('2d');
    new Chart(taskCompletionCtx, {
        type: 'doughnut',
        data: {
            labels: defaultData.taskCompletion.labels,
            datasets: [{
                data: defaultData.taskCompletion.data,
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
                        padding: 15
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((context.parsed * 100) / total);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Work Pattern Chart
    const workPatternCtx = document.getElementById('workPatternChart').getContext('2d');
    new Chart(workPatternCtx, {
        type: 'bar',
        data: {
            labels: defaultData.workPattern.labels,
            datasets: [{
                label: '{{ __("app.productivity.peak_hours") }}',
                data: defaultData.workPattern.data,
                backgroundColor: 'rgba(40, 167, 69, 0.8)',
                borderColor: '#28a745',
                borderWidth: 1
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '{{ __("app.tasks.title") }}: ' + context.parsed.y;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
