@extends('layouts.sidebar')

@section('title', __('app.users.edit'))
@section('page-title', __('app.users.edit_user_name', ['name' => $user->name]))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.users.user_information') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('app.full_name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('app.email_address') }} <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">{{ __('app.users.role') }} <span class="text-danger">*</span></label>
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
                                {{ __('app.users.last_admin_warning') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="language" class="form-label">{{ __('app.profile_settings.language') }}</label>
                        <select class="form-select @error('language') is-invalid @enderror" id="language" name="language">
                            <option value="en" {{ old('language', $user->language) === 'en' ? 'selected' : '' }}>{{ __('app.users.language_english') }}</option>
                            <option value="fr" {{ old('language', $user->language) === 'fr' ? 'selected' : '' }}>{{ __('app.users.language_french') }}</option>
                            <option value="ar" {{ old('language', $user->language) === 'ar' ? 'selected' : '' }}>{{ __('app.users.language_arabic') }}</option>
                        </select>
                        @error('language')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h6>{{ __('app.profile_settings.change_password') }} <small class="text-muted">({{ __('app.users.password_leave_blank') }})</small></h6>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('app.profile_settings.new_password') }}</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('app.users.confirm_new_password') }}</label>
                        <input type="password" class="form-control"
                               id="password_confirmation" name="password_confirmation">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            {{ __('app.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ __('app.users.update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.users.user_actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('users.show', $user) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>
                        {{ __('app.users.view_profile') }}
                    </a>

                    @if($user->canWorkOnTasks())
                        <a href="{{ route('tasks.index', ['assigned_to' => $user->id]) }}" class="btn btn-outline-success">
                            <i class="bi bi-list-task me-2"></i>
                            {{ __('app.users.view_tasks') }}
                        </a>
                    @endif

                    @if($user->isManager())
                        <a href="{{ route('projects.index', ['manager_id' => $user->id]) }}" class="btn btn-outline-info">
                            <i class="bi bi-folder me-2"></i>
                            {{ __('app.users.view_projects') }}
                        </a>
                    @endif

                    @can('delete', $user)
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i>
                            {{ __('app.users.delete') }}
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.users.current_role') }}</h5>
            </div>
            <div class="card-body">
                <div id="current-role-info">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.users.user_statistics') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>{{ __('app.users.member_since') }}:</strong>
                    <span class="float-end">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                @if($user->isManager())
                    <div class="mb-2">
                        <strong>{{ __('app.users.projects_managed') }}:</strong>
                        <span class="float-end">{{ $user->managedProjects->count() }}</span>
                    </div>
                @endif
                @if($user->canWorkOnTasks())
                    <div class="mb-2">
                        <strong>{{ __('app.users.tasks_assigned') }}:</strong>
                        <span class="float-end">{{ $user->assignedTasks->count() }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>{{ __('app.users.time_logged') }}:</strong>
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
                <h5 class="modal-title">{{ __('app.users.delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('app.users.confirm_delete') }}</p>
                <p class="text-danger">
                    <strong>{{ __('app.warning') }}:</strong>
                    {{ __('app.users.delete_warning') }}
                </p>
                <p><strong>{{ __('app.user_label') }}:</strong> {{ $user->name }}</p>
                @if($user->managedProjects->count() > 0 || $user->assignedTasks->count() > 0 || $user->timeEntries->count() > 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ __('app.users.delete_has_data') }}
                        <ul class="mb-0 mt-2">
                            @if($user->managedProjects->count() > 0)
                                <li>{{ __('app.users.count_managed_projects', ['count' => $user->managedProjects->count()]) }}</li>
                            @endif
                            @if($user->assignedTasks->count() > 0)
                                <li>{{ __('app.users.count_assigned_tasks', ['count' => $user->assignedTasks->count()]) }}</li>
                            @endif
                            @if($user->timeEntries->count() > 0)
                                <li>{{ __('app.users.count_time_entries', ['count' => $user->timeEntries->count()]) }}</li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                <form method="POST" action="{{ route('users.destroy', $user) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('app.users.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan

<script>
const roleDescriptions = {
    'admin': {
        title: '{{ __("app.users.admin") }}',
        description: '{{ __("app.users.admin_desc") }}',
        permissions: [
            '{{ __("app.users.admin_perm1") }}',
            '{{ __("app.users.admin_perm2") }}',
            '{{ __("app.users.admin_perm3") }}',
            '{{ __("app.users.admin_perm4") }}'
        ]
    },
    'manager': {
        title: '{{ __("app.users.manager") }}',
        description: '{{ __("app.users.manager_desc") }}',
        permissions: [
            '{{ __("app.users.manager_perm1") }}',
            '{{ __("app.users.manager_perm2") }}',
            '{{ __("app.users.manager_perm3") }}',
            '{{ __("app.users.manager_perm4") }}'
        ]
    },
    'developer': {
        title: '{{ __("app.users.developer") }}',
        description: '{{ __("app.users.developer_desc") }}',
        permissions: [
            '{{ __("app.users.developer_perm1") }}',
            '{{ __("app.users.developer_perm2") }}',
            '{{ __("app.users.developer_perm3") }}',
            '{{ __("app.users.developer_perm4") }}'
        ]
    },
    'designer': {
        title: '{{ __("app.users.designer") }}',
        description: '{{ __("app.users.designer_desc") }}',
        permissions: [
            '{{ __("app.users.designer_perm1") }}',
            '{{ __("app.users.designer_perm2") }}',
            '{{ __("app.users.designer_perm3") }}',
            '{{ __("app.users.designer_perm4") }}'
        ]
    },
    'tester': {
        title: '{{ __("app.users.tester") }}',
        description: '{{ __("app.users.tester_desc") }}',
        permissions: [
            '{{ __("app.users.tester_perm1") }}',
            '{{ __("app.users.tester_perm2") }}',
            '{{ __("app.users.tester_perm3") }}',
            '{{ __("app.users.tester_perm4") }}'
        ]
    },
    'hr': {
        title: '{{ __("app.users.hr") }}',
        description: '{{ __("app.users.hr_desc") }}',
        permissions: [
            '{{ __("app.users.hr_perm1") }}',
            '{{ __("app.users.hr_perm2") }}',
            '{{ __("app.users.hr_perm3") }}'
        ]
    },
    'accountant': {
        title: '{{ __("app.users.accountant") }}',
        description: '{{ __("app.users.accountant_desc") }}',
        permissions: [
            '{{ __("app.users.accountant_perm1") }}',
            '{{ __("app.users.accountant_perm2") }}',
            '{{ __("app.users.accountant_perm3") }}'
        ]
    },
    'client': {
        title: '{{ __("app.users.client") }}',
        description: '{{ __("app.users.client_desc") }}',
        permissions: [
            '{{ __("app.users.client_perm1") }}',
            '{{ __("app.users.client_perm2") }}',
            '{{ __("app.users.client_perm3") }}'
        ]
    },
    'member': {
        title: '{{ __("app.users.member") }}',
        description: '{{ __("app.users.member_desc") }}',
        permissions: [
            '{{ __("app.users.member_perm1") }}',
            '{{ __("app.users.member_perm2") }}',
            '{{ __("app.users.member_perm3") }}'
        ]
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
            <h6 class="mt-3">{{ __('app.users.permissions') }}</h6>
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