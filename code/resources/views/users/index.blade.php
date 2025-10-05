@extends('layouts.app')

@section('title', __('Users'))
@section('page-title', __('User Management'))

@section('content')
<div class="row">
    <!-- Header Actions -->
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">{{ __('Users') }}</h2>
                <p class="text-muted mb-0">{{ __('Manage users and their roles') }}</p>
            </div>
            <div>
                @can('create', App\Models\User::class)
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus me-2"></i>
                    {{ __('Add New User') }}
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('users.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="role" class="form-label">{{ __('Role') }}</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">{{ __('All Roles') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>
                                    {{ $roleLabels[$role] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="search" class="form-label">{{ __('Search') }}</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="{{ __('Search users...') }}">
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="bi bi-search me-2"></i>
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>
                            {{ __('Clear') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Users List -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    {{ __('Users') }}
                    <span class="badge bg-secondary ms-2">{{ $users->total() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Projects') }}</th>
                                    <th>{{ __('Tasks') }}</th>
                                    <th>{{ __('Joined') }}</th>
                                    <th width="200">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3">
                                                    {{ substr($user->name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                                    @if($user->isAdmin())
                                                        <small class="text-primary">{{ __('Administrator') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown">
                                                    <span class="role-badge badge bg-{{ $user->isAdmin() ? 'danger' : ($user->isManager() ? 'warning' : 'primary') }}">
                                                        {{ $user->getRoleLabel() }}
                                                    </span>
                                                </button>
                                                @can('update', $user)
                                                <ul class="dropdown-menu">
                                                    @foreach($roles as $role)
                                                        @if($role !== $user->role)
                                                            <li>
                                                                <a class="dropdown-item" href="#"
                                                                   onclick="updateUserRole({{ $user->id }}, '{{ $role }}')">
                                                                    {{ $roleLabels[$role] }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                                @endcan
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->isManager())
                                                <span class="badge bg-info">{{ $user->managedProjects->count() }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->canWorkOnTasks())
                                                <span class="badge bg-success">{{ $user->assignedTasks->count() }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @can('update', $user)
                                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endcan
                                                @can('delete', $user)
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-people fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('No users found') }}</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['role', 'search']))
                                {{ __('Try adjusting your filters or') }}
                                <a href="{{ route('users.index') }}">{{ __('clear all filters') }}</a>
                            @else
                                {{ __('Get started by adding your first user.') }}
                            @endif
                        </p>
                        @can('create', App\Models\User::class)
                            <a href="{{ route('users.create') }}" class="btn btn-primary">
                                <i class="bi bi-person-plus me-2"></i>
                                {{ __('Add First User') }}
                            </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

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
                <p><strong>{{ __('User:') }}</strong> <span id="deleteUserName"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Delete User') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    text-transform: uppercase;
}
</style>

<script>
function updateUserRole(userId, newRole) {
    if (confirm('{{ __("Are you sure you want to change this user\'s role?") }}')) {
        fetch(`/users/${userId}/role`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                role: newRole
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || '{{ __("Error updating user role") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("Error updating user role") }}');
        });
    }
}

function deleteUser(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteForm').action = `/users/${userId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection