@extends('layouts.app')

@section('title', __('Timesheet'))
@section('page-title', __('Timesheet Management'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    {{ __('Timesheet Entries') }}
                </h5>
                <div>
                    <a href="{{ route('timesheet.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>{{ __('Add Time Entry') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="search-input" placeholder="{{ __('Search...') }}">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="project-filter">
                            <option value="">{{ __('All Projects') }}</option>
                            @foreach($projects ?? [] as $project)
                                <option value="{{ $project->id }}">{{ $project->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="user-filter">
                            <option value="">{{ __('All Users') }}</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="date-from" placeholder="{{ __('From Date') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="date-to" placeholder="{{ __('To Date') }}">
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-outline-secondary" onclick="clearFilters()">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>

                <!-- Timesheet Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="timesheet-table">
                        <thead class="table-dark">
                            <tr>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Project') }}</th>
                                <th>{{ __('Task') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Hours') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="timesheet-tbody">
                            @forelse($timesheets ?? [] as $timesheet)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ \Carbon\Carbon::parse($timesheet->date)->format('M d, Y') }}</span>
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($timesheet->date)->format('l') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                 style="width: 32px; height: 32px;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $timesheet->user->name ?? __('Unknown') }}</div>
                                                <small class="text-muted">{{ $timesheet->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                 style="width: 24px; height: 24px;">
                                                <i class="bi bi-folder-fill" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <span>{{ $timesheet->project->title ?? __('No Project') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($timesheet->task)
                                            <span class="badge bg-info">{{ $timesheet->task->title }}</span>
                                        @else
                                            <span class="text-muted">{{ __('General') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $timesheet->description }}">
                                            {{ $timesheet->description ?: __('No description') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $timesheet->hours }}h</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger'
                                            ];
                                            $color = $statusColors[$timesheet->status ?? 'pending'] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ ucfirst($timesheet->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('timesheet.show', $timesheet->id) }}">
                                                        <i class="bi bi-eye me-2"></i>{{ __('View') }}
                                                    </a>
                                                </li>
                                                @if(auth()->user()->id === $timesheet->user_id || auth()->user()->isAdmin())
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('timesheet.edit', $timesheet->id) }}">
                                                            <i class="bi bi-pencil me-2"></i>{{ __('Edit') }}
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(auth()->user()->isManager() || auth()->user()->isAdmin())
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="changeStatus({{ $timesheet->id }}, 'approved')">
                                                            <i class="bi bi-check-circle me-2 text-success"></i>{{ __('Approve') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="changeStatus({{ $timesheet->id }}, 'rejected')">
                                                            <i class="bi bi-x-circle me-2 text-danger"></i>{{ __('Reject') }}
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(auth()->user()->id === $timesheet->user_id || auth()->user()->isAdmin())
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="#" onclick="deleteEntry({{ $timesheet->id }})">
                                                            <i class="bi bi-trash me-2"></i>{{ __('Delete') }}
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-clock-history fs-2"></i>
                                            <p class="mt-2">{{ __('No timesheet entries found') }}</p>
                                            <a href="{{ route('timesheet.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-1"></i>{{ __('Add Your First Entry') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($timesheets) && $timesheets->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $timesheets->links() }}
                    </div>
                @endif

                <!-- Summary Section -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h5 class="card-title text-primary">{{ $summary['total_hours'] ?? 0 }}h</h5>
                                <p class="card-text">{{ __('Total Hours') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h5 class="card-title text-success">{{ $summary['approved_hours'] ?? 0 }}h</h5>
                                <p class="card-text">{{ __('Approved Hours') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <h5 class="card-title text-warning">{{ $summary['pending_hours'] ?? 0 }}h</h5>
                                <p class="card-text">{{ __('Pending Hours') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <h5 class="card-title text-danger">{{ $summary['rejected_hours'] ?? 0 }}h</h5>
                                <p class="card-text">{{ __('Rejected Hours') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    setupFilters();
});

function setupFilters() {
    const searchInput = document.getElementById('search-input');
    const projectFilter = document.getElementById('project-filter');
    const userFilter = document.getElementById('user-filter');
    const dateFrom = document.getElementById('date-from');
    const dateTo = document.getElementById('date-to');

    let filterTimeout;

    function applyFilters() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            const params = new URLSearchParams(window.location.search);

            if (searchInput.value) params.set('search', searchInput.value);
            else params.delete('search');

            if (projectFilter.value) params.set('project', projectFilter.value);
            else params.delete('project');

            if (userFilter.value) params.set('user', userFilter.value);
            else params.delete('user');

            if (dateFrom.value) params.set('date_from', dateFrom.value);
            else params.delete('date_from');

            if (dateTo.value) params.set('date_to', dateTo.value);
            else params.delete('date_to');

            const newUrl = window.location.pathname + '?' + params.toString();
            window.location.href = newUrl;
        }, 500);
    }

    searchInput.addEventListener('input', applyFilters);
    projectFilter.addEventListener('change', applyFilters);
    userFilter.addEventListener('change', applyFilters);
    dateFrom.addEventListener('change', applyFilters);
    dateTo.addEventListener('change', applyFilters);

    // Set current values from URL params
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('search')) searchInput.value = urlParams.get('search');
    if (urlParams.get('project')) projectFilter.value = urlParams.get('project');
    if (urlParams.get('user')) userFilter.value = urlParams.get('user');
    if (urlParams.get('date_from')) dateFrom.value = urlParams.get('date_from');
    if (urlParams.get('date_to')) dateTo.value = urlParams.get('date_to');
}

function clearFilters() {
    document.getElementById('search-input').value = '';
    document.getElementById('project-filter').value = '';
    document.getElementById('user-filter').value = '';
    document.getElementById('date-from').value = '';
    document.getElementById('date-to').value = '';
    window.location.href = window.location.pathname;
}

function changeStatus(timesheetId, status) {
    if (confirm(`{{ __('Are you sure you want to change the status?') }}`)) {
        axios.post(`/timesheet/${timesheetId}/status`, {
            status: status,
            _token: '{{ csrf_token() }}'
        })
        .then(response => {
            if (response.data.success) {
                location.reload();
            } else {
                alert('{{ __('Error updating status') }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __('Error updating status') }}');
        });
    }
}

function deleteEntry(timesheetId) {
    if (confirm(`{{ __('Are you sure you want to delete this entry?') }}`)) {
        axios.delete(`/timesheet/${timesheetId}`, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (response.data.success) {
                location.reload();
            } else {
                alert('{{ __('Error deleting entry') }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __('Error deleting entry') }}');
        });
    }
}
</script>
@endpush