@extends('layouts.navbar')

@section('title', __('Help'))
@section('page-title', __('Help & Documentation'))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Getting Started') }}</h5>
            </div>
            <div class="card-body">
                <h6>{{ __('Welcome to Project Management System') }}</h6>
                <p>{{ __('This system helps you manage projects, tasks, and time tracking efficiently.') }}</p>

                <h6 class="mt-4">{{ __('Key Features') }}</h6>
                <ul>
                    <li>{{ __('Project Management - Create and manage projects') }}</li>
                    <li>{{ __('Task Management - Assign and track tasks') }}</li>
                    <li>{{ __('Time Tracking - Log time spent on tasks') }}</li>
                    <li>{{ __('Reports - Generate detailed reports') }}</li>
                    <li>{{ __('Team Collaboration - Work together efficiently') }}</li>
                </ul>

                <h6 class="mt-4">{{ __('Quick Actions') }}</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h6 class="card-title">{{ __('Create a Project') }}</h6>
                                <p class="card-text">{{ __('Start by creating a new project to organize your work.') }}</p>
                                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">{{ __('Create Project') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-body">
                                <h6 class="card-title">{{ __('Log Time') }}</h6>
                                <p class="card-text">{{ __('Track time spent on your tasks.') }}</p>
                                <a href="{{ route('timesheet.create') }}" class="btn btn-success btn-sm">{{ __('Log Time') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('User Guide') }}</h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="helpAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingProjects">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProjects">
                                {{ __('Managing Projects') }}
                            </button>
                        </h2>
                        <div id="collapseProjects" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <p>{{ __('Projects are the main containers for organizing work. Here\'s how to manage them:') }}</p>
                                <ul>
                                    <li>{{ __('Create projects with clear titles and descriptions') }}</li>
                                    <li>{{ __('Set start and end dates') }}</li>
                                    <li>{{ __('Assign project managers') }}</li>
                                    <li>{{ __('Track project progress through the dashboard') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTasks">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTasks">
                                {{ __('Working with Tasks') }}
                            </button>
                        </h2>
                        <div id="collapseTasks" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <p>{{ __('Tasks break down projects into manageable work items:') }}</p>
                                <ul>
                                    <li>{{ __('Create tasks within projects') }}</li>
                                    <li>{{ __('Assign tasks to team members') }}</li>
                                    <li>{{ __('Set due dates and priorities') }}</li>
                                    <li>{{ __('Update task status as work progresses') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTime">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTime">
                                {{ __('Time Tracking') }}
                            </button>
                        </h2>
                        <div id="collapseTime" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <p>{{ __('Track time spent on tasks for accurate project reporting:') }}</p>
                                <ul>
                                    <li>{{ __('Log time entries for specific tasks') }}</li>
                                    <li>{{ __('Add descriptions to explain the work done') }}</li>
                                    <li>{{ __('View time summaries in reports') }}</li>
                                    <li>{{ __('Use time data for project planning') }}</li>
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
                <h5 class="mb-0">{{ __('Contact Support') }}</h5>
            </div>
            <div class="card-body">
                <p>{{ __('Need additional help? Contact our support team.') }}</p>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="alert('Feature coming soon!')">
                        <i class="bi bi-envelope me-2"></i>
                        {{ __('Email Support') }}
                    </button>
                    <button class="btn btn-outline-info" onclick="alert('Feature coming soon!')">
                        <i class="bi bi-chat-dots me-2"></i>
                        {{ __('Live Chat') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Keyboard Shortcuts') }}</h5>
            </div>
            <div class="card-body">
                <small>
                    <strong>Ctrl + /</strong> - {{ __('Show this help') }}<br>
                    <strong>Ctrl + N</strong> - {{ __('New task') }}<br>
                    <strong>Ctrl + D</strong> - {{ __('Dashboard') }}<br>
                    <strong>Ctrl + T</strong> - {{ __('Time tracking') }}<br>
                </small>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('System Information') }}</h5>
            </div>
            <div class="card-body">
                <small>
                    <strong>{{ __('Version') }}:</strong> 1.0.0<br>
                    <strong>{{ __('Last Updated') }}:</strong> {{ now()->format('M d, Y') }}<br>
                    <strong>{{ __('User Role') }}:</strong> {{ ucfirst(auth()->user()->role) }}<br>
                </small>
            </div>
        </div>
    </div>
</div>
@endsection