@extends('layouts.app')

@section('title', $user->name)
@section('page-title', $user->name)

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- User Profile -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Profile Information') }}</h5>
                <div>
                    @can('update', $user)
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>
                            {{ __('Edit') }}
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
                                <strong>{{ __('Email') }}:</strong>
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
                        {{ __('Managed Projects') }}
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
                                                <small class="text-muted">{{ $project->tasks->count() }} {{ __('tasks') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3">{{ __('No projects managed yet.') }}</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Tasks (for team members) -->
        @if($user->canWorkOnTasks())
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        {{ __('Assigned Tasks') }}
                        <span class="badge bg-secondary ms-2">{{ $user->assignedTasks->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($user->assignedTasks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Task') }}</th>
                                        <th>{{ __('Project') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Due Date') }}</th>
                                        <th>{{ __('Priority') }}</th>
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
                                                        <span class="badge bg-danger ms-1">{{ __('Overdue') }}</span>
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
                                    {{ __('View All Tasks') }}
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-muted text-center py-3">{{ __('No tasks assigned yet.') }}</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Recent Activity -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Recent Activity') }}</h5>
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
                    <p class="text-muted text-center py-3">{{ __('No recent activity.') }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- User Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @can('update', $user)
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-2"></i>
                            {{ __('Edit Profile') }}
                        </a>
                    @endcan

                    @if($user->canWorkOnTasks())
                        <a href="{{ route('tasks.index', ['assigned_to' => $user->id]) }}" class="btn btn-outline-success">
                            <i class="bi bi-list-task me-2"></i>
                            {{ __('View All Tasks') }}
                        </a>
                    @endif

                    @if($user->isManager())
                        <a href="{{ route('projects.index', ['manager_id' => $user->id]) }}" class="btn btn-outline-info">
                            <i class="bi bi-folder me-2"></i>
                            {{ __('View Projects') }}
                        </a>
                    @endif

                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>
                        {{ __('Back to Users') }}
                    </a>
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
                        <div class="stat-label">{{ __('Projects Managed') }}</div>
                    </div>
                @endif

                @if($user->canWorkOnTasks())
                    <div class="stat-item">
                        <div class="stat-value">{{ $stats['total_tasks_assigned'] }}</div>
                        <div class="stat-label">{{ __('Tasks Assigned') }}</div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-value">{{ $stats['completed_tasks'] }}</div>
                        <div class="stat-label">{{ __('Tasks Completed') }}</div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-value">{{ number_format($stats['total_time_logged'], 1) }}h</div>
                        <div class="stat-label">{{ __('Time Logged') }}</div>
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
                <h5 class="mb-0">{{ __('Role Information') }}</h5>
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
@endsection