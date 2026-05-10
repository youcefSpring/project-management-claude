@extends('layouts.sidebar')

@section('title', __('Create User'))
@section('page-title', __('Add New User'))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('User Information') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">{{ __('Primary Role') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">{{ __('Select a primary role') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                                    {{ $roleLabels[$role] }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('This will be the user\'s primary role for permissions and display.') }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Additional Roles') }} <span class="text-muted">({{ __('Optional') }})</span></label>
                        <div class="row">
                            @foreach($roles as $role)
                                @if($role !== 'admin')
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input additional-role" type="checkbox"
                                                   name="additional_roles[]" value="{{ $role }}"
                                                   id="additional_role_{{ $role }}"
                                                   {{ in_array($role, old('additional_roles', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="additional_role_{{ $role }}">
                                                {{ $roleLabels[$role] }}
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @error('additional_roles')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('User can have multiple roles. For example, a developer can also be a manager.') }}</div>
                    </div>

                    <div class="mb-3">
                        <label for="language" class="form-label">{{ __('Language') }}</label>
                        <select class="form-select @error('language') is-invalid @enderror" id="language" name="language">
                            <option value="en" {{ old('language', 'en') === 'en' ? 'selected' : '' }}>{{ __('English') }}</option>
                            <option value="fr" {{ old('language') === 'fr' ? 'selected' : '' }}>{{ __('French') }}</option>
                            <option value="ar" {{ old('language') === 'ar' ? 'selected' : '' }}>{{ __('Arabic') }}</option>
                        </select>
                        @error('language')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }} <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                        <input type="password" class="form-control"
                               id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>
                            {{ __('Create User') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Role Information') }}</h5>
            </div>
            <div class="card-body">
                <div id="role-info">
                    <p class="text-muted">{{ __('Select a role to see permissions and description.') }}</p>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Guidelines') }}</h5>
            </div>
            <div class="card-body">
                <h6>{{ __('Password Requirements') }}</h6>
                <ul class="small text-muted">
                    <li>{{ __('Minimum 8 characters') }}</li>
                    <li>{{ __('Mix of letters, numbers recommended') }}</li>
                    <li>{{ __('User will be able to change password later') }}</li>
                </ul>

                <h6 class="mt-3">{{ __('User Management') }}</h6>
                <ul class="small text-muted">
                    <li>{{ __('Email must be unique') }}</li>
                    <li>{{ __('Role can be changed later') }}</li>
                    <li>{{ __('Users receive account creation notification') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

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

// Update role information display
function updateRoleInfo() {
    const selectedRole = document.getElementById('role').value;
    const infoDiv = document.getElementById('role-info');

    if (selectedRole && roleDescriptions[selectedRole]) {
        const role = roleDescriptions[selectedRole];
        const permissionsList = role.permissions.map(p => `<li>${p}</li>`).join('');

        infoDiv.innerHTML = `
            <h6 class="text-primary">${role.title} (Primary)</h6>
            <p class="small">${role.description}</p>
            <h6 class="mt-3">{{ __('Primary Role Permissions') }}</h6>
            <ul class="small text-muted">
                ${permissionsList}
            </ul>
        `;
    } else {
        infoDiv.innerHTML = '<p class="text-muted">{{ __("Select a primary role to see permissions and description.") }}</p>';
    }
}

// Prevent selecting primary role as additional role
function updateAdditionalRoles() {
    const primaryRole = document.getElementById('role').value;
    const additionalCheckboxes = document.querySelectorAll('.additional-role');

    additionalCheckboxes.forEach(checkbox => {
        if (checkbox.value === primaryRole) {
            checkbox.checked = false;
            checkbox.disabled = true;
            checkbox.parentElement.classList.add('text-muted');
        } else {
            checkbox.disabled = false;
            checkbox.parentElement.classList.remove('text-muted');
        }
    });
}

document.getElementById('role').addEventListener('change', function() {
    updateRoleInfo();
    updateAdditionalRoles();
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateRoleInfo();
    updateAdditionalRoles();
});
</script>
@endsection