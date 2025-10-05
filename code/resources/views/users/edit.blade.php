@extends('layouts.app')

@section('title', __('Edit User'))
@section('page-title', __('Edit User: :name', ['name' => $user->name]))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('User Information') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">{{ __('Role') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>
                                    {{ $roleLabels[$role] }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($user->isAdmin() && \App\Models\User::where('role', 'admin')->count() <= 1)
                            <div class="form-text text-warning">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                {{ __('This is the last administrator. Role change may restrict system access.') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="language" class="form-label">{{ __('Language') }}</label>
                        <select class="form-select @error('language') is-invalid @enderror" id="language" name="language">
                            <option value="en" {{ old('language', $user->language) === 'en' ? 'selected' : '' }}>{{ __('English') }}</option>
                            <option value="fr" {{ old('language', $user->language) === 'fr' ? 'selected' : '' }}>{{ __('French') }}</option>
                            <option value="ar" {{ old('language', $user->language) === 'ar' ? 'selected' : '' }}>{{ __('Arabic') }}</option>
                        </select>
                        @error('language')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h6>{{ __('Change Password') }} <small class="text-muted">({{ __('leave blank to keep current password') }})</small></h6>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('New Password') }}</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('Confirm New Password') }}</label>
                        <input type="password" class="form-control"
                               id="password_confirmation" name="password_confirmation">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ __('Update User') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('User Actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('users.show', $user) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>
                        {{ __('View Profile') }}
                    </a>

                    @if($user->canWorkOnTasks())
                        <a href="{{ route('tasks.index', ['assigned_to' => $user->id]) }}" class="btn btn-outline-success">
                            <i class="bi bi-list-task me-2"></i>
                            {{ __('View Tasks') }}
                        </a>
                    @endif

                    @if($user->isManager())
                        <a href="{{ route('projects.index', ['manager_id' => $user->id]) }}" class="btn btn-outline-info">
                            <i class="bi bi-folder me-2"></i>
                            {{ __('View Projects') }}
                        </a>
                    @endif

                    @can('delete', $user)
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i>
                            {{ __('Delete User') }}
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Current Role') }}</h5>
            </div>
            <div class="card-body">
                <div id="current-role-info">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('User Statistics') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>{{ __('Member Since') }}:</strong>
                    <span class="float-end">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                @if($user->isManager())
                    <div class="mb-2">
                        <strong>{{ __('Projects Managed') }}:</strong>
                        <span class="float-end">{{ $user->managedProjects->count() }}</span>
                    </div>
                @endif
                @if($user->canWorkOnTasks())
                    <div class="mb-2">
                        <strong>{{ __('Tasks Assigned') }}:</strong>
                        <span class="float-end">{{ $user->assignedTasks->count() }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>{{ __('Time Logged') }}:</strong>
                        <span class="float-end">{{ number_format($user->timeEntries->sum('duration_hours') ?? 0, 1) }}h</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@can('delete', $user)
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Delete User') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete this user?') }}</p>
                <p class="text-danger">
                    <strong>{{ __('Warning:') }}</strong>
                    {{ __('This action cannot be undone.') }}
                </p>
                <p><strong>{{ __('User:') }}</strong> {{ $user->name }}</p>
                @if($user->managedProjects->count() > 0 || $user->assignedTasks->count() > 0 || $user->timeEntries->count() > 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ __('This user has associated data that will prevent deletion:') }}
                        <ul class="mb-0 mt-2">
                            @if($user->managedProjects->count() > 0)
                                <li>{{ __(':count managed projects', ['count' => $user->managedProjects->count()]) }}</li>
                            @endif
                            @if($user->assignedTasks->count() > 0)
                                <li>{{ __(':count assigned tasks', ['count' => $user->assignedTasks->count()]) }}</li>
                            @endif
                            @if($user->timeEntries->count() > 0)
                                <li>{{ __(':count time entries', ['count' => $user->timeEntries->count()]) }}</li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <form method="POST" action="{{ route('users.destroy', $user) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Delete User') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan

<script>
const roleDescriptions = {
    'admin': {
        title: 'Administrator',
        description: 'Full system access. Can manage all users, projects, and system settings.',
        permissions: ['Manage all users', 'Manage all projects', 'View all reports', 'System configuration']
    },
    'manager': {
        title: 'Project Manager',
        description: 'Can manage projects and teams. Has access to reports and project oversight.',
        permissions: ['Create/manage projects', 'Assign tasks', 'View reports', 'Manage team members']
    },
    'developer': {
        title: 'Developer',
        description: 'Technical team member who works on development tasks.',
        permissions: ['Work on assigned tasks', 'Log time entries', 'Comment on tasks', 'View project details']
    },
    'designer': {
        title: 'Designer',
        description: 'Creative team member who works on design-related tasks.',
        permissions: ['Work on design tasks', 'Log time entries', 'Comment on tasks', 'View project details']
    },
    'tester': {
        title: 'QA Tester',
        description: 'Quality assurance team member who tests and validates work.',
        permissions: ['Work on testing tasks', 'Log time entries', 'Report bugs', 'View project details']
    },
    'hr': {
        title: 'Human Resources',
        description: 'HR team member with access to user management and reports.',
        permissions: ['View user reports', 'Access HR features', 'Limited project visibility']
    },
    'accountant': {
        title: 'Accountant',
        description: 'Financial team member with access to time tracking and billing reports.',
        permissions: ['View time reports', 'Access billing information', 'Financial reports']
    },
    'client': {
        title: 'Client',
        description: 'External client with limited access to view project progress.',
        permissions: ['View assigned projects', 'View progress reports', 'Limited task visibility']
    },
    'member': {
        title: 'Member',
        description: 'General team member with basic access.',
        permissions: ['Work on assigned tasks', 'Log time entries', 'Basic project access']
    }
};

function updateRoleInfo() {
    const selectedRole = document.getElementById('role').value;
    const infoDiv = document.getElementById('current-role-info');

    if (selectedRole && roleDescriptions[selectedRole]) {
        const role = roleDescriptions[selectedRole];
        const permissionsList = role.permissions.map(p => `<li>${p}</li>`).join('');

        infoDiv.innerHTML = `
            <h6 class="text-primary">${role.title}</h6>
            <p class="small">${role.description}</p>
            <h6 class="mt-3">{{ __('Permissions') }}</h6>
            <ul class="small text-muted">
                ${permissionsList}
            </ul>
        `;
    }
}

document.getElementById('role').addEventListener('change', updateRoleInfo);

// Initialize on page load
document.addEventListener('DOMContentLoaded', updateRoleInfo);
</script>
@endsection