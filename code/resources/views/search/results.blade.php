@extends('layouts.app')

@section('title', __('Search Results'))
@section('page-title', __('Search Results'))

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Search Header -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-1">{{ __('Search Results') }}</h4>
                        <p class="text-muted mb-0">
                            @if(request('q'))
                                {{ __('Results for') }}: "<strong>{{ request('q') }}</strong>"
                                @if(isset($totalResults))
                                    - {{ $totalResults }} {{ __('results found') }}
                                @endif
                            @else
                                {{ __('Enter a search term to find projects, tasks, and users') }}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('search.results') }}">
                            <div class="input-group">
                                <input type="text" class="form-control" name="q" value="{{ request('q') }}"
                                       placeholder="{{ __('Search...') }}" autofocus>
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(request('q'))
            <!-- Search Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="type-filter" class="form-label">{{ __('Type') }}</label>
                            <select class="form-select" id="type-filter" name="type">
                                <option value="">{{ __('All Types') }}</option>
                                <option value="projects" {{ request('type') === 'projects' ? 'selected' : '' }}>
                                    {{ __('Projects') }}
                                </option>
                                <option value="tasks" {{ request('type') === 'tasks' ? 'selected' : '' }}>
                                    {{ __('Tasks') }}
                                </option>
                                <option value="users" {{ request('type') === 'users' ? 'selected' : '' }}>
                                    {{ __('Users') }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status-filter" class="form-label">{{ __('Status') }}</label>
                            <select class="form-select" id="status-filter" name="status">
                                <option value="">{{ __('All Statuses') }}</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                    {{ __('Active') }}
                                </option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                    {{ __('Completed') }}
                                </option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                    {{ __('Pending') }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="sort-filter" class="form-label">{{ __('Sort By') }}</label>
                            <select class="form-select" id="sort-filter" name="sort">
                                <option value="relevance" {{ request('sort') === 'relevance' ? 'selected' : '' }}>
                                    {{ __('Relevance') }}
                                </option>
                                <option value="date" {{ request('sort') === 'date' ? 'selected' : '' }}>
                                    {{ __('Date') }}
                                </option>
                                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>
                                    {{ __('Name') }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-primary me-2" onclick="applyFilters()">
                                {{ __('Apply') }}
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                {{ __('Clear') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Results -->
            @if(isset($projects) && $projects->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-folder me-2"></i>
                            {{ __('Projects') }}
                            <span class="badge bg-primary ms-2">{{ $projects->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    @foreach($projects as $project)
                                        <tr>
                                            <td style="width: 60px;">
                                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-folder-fill"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <a href="{{ route('projects.show', $project) }}" class="fw-bold text-decoration-none">
                                                        {{ $project->title }}
                                                    </a>
                                                    @if($project->description)
                                                        <br>
                                                        <small class="text-muted">{{ Str::limit($project->description, 100) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td style="width: 120px;">
                                                <span class="badge bg-{{ $project->status === 'active' ? 'success' : ($project->status === 'completed' ? 'primary' : 'warning') }}">
                                                    {{ ucfirst($project->status) }}
                                                </span>
                                            </td>
                                            <td style="width: 150px;">
                                                <small class="text-muted">
                                                    @if($project->start_date)
                                                        {{ \Carbon\Carbon::parse($project->start_date)->format('M d, Y') }}
                                                    @else
                                                        {{ __('No start date') }}
                                                    @endif
                                                </small>
                                            </td>
                                            <td style="width: 100px;">
                                                <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-primary">
                                                    {{ __('View') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($tasks) && $tasks->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-check-square me-2"></i>
                            {{ __('Tasks') }}
                            <span class="badge bg-primary ms-2">{{ $tasks->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    @foreach($tasks as $task)
                                        <tr>
                                            <td style="width: 60px;">
                                                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center text-white"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-check-square-fill"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <a href="{{ route('tasks.show', $task) }}" class="fw-bold text-decoration-none">
                                                        {{ $task->title }}
                                                    </a>
                                                    @if($task->description)
                                                        <br>
                                                        <small class="text-muted">{{ Str::limit($task->description, 100) }}</small>
                                                    @endif
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="bi bi-folder me-1"></i>{{ $task->project->title }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td style="width: 120px;">
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'in_progress' => 'primary',
                                                        'completed' => 'success',
                                                        'cancelled' => 'secondary'
                                                    ];
                                                    $color = $statusColors[$task->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                            </td>
                                            <td style="width: 150px;">
                                                @if($task->due_date)
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                                    </small>
                                                @else
                                                    <small class="text-muted">{{ __('No due date') }}</small>
                                                @endif
                                            </td>
                                            <td style="width: 100px;">
                                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-primary">
                                                    {{ __('View') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($users) && $users->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-people me-2"></i>
                            {{ __('Users') }}
                            <span class="badge bg-primary ms-2">{{ $users->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td style="width: 60px;">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="fw-bold">{{ $user->name }}</span>
                                                    <br>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </td>
                                            <td style="width: 120px;">
                                                <span class="badge bg-info">
                                                    {{ ucfirst($user->role ?? 'User') }}
                                                </span>
                                            </td>
                                            <td style="width: 150px;">
                                                <small class="text-muted">
                                                    {{ __('Joined') }} {{ $user->created_at->format('M Y') }}
                                                </small>
                                            </td>
                                            <td style="width: 100px;">
                                                @if(auth()->user()->isAdmin())
                                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                                        {{ __('View') }}
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if((!isset($projects) || $projects->count() === 0) &&
                (!isset($tasks) || $tasks->count() === 0) &&
                (!isset($users) || $users->count() === 0))
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-search fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('No results found') }}</h5>
                        <p class="text-muted mb-4">
                            {{ __('Try adjusting your search terms or filters to find what you\'re looking for.') }}
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item border-0 py-1">
                                        <small class="text-muted">• {{ __('Check your spelling') }}</small>
                                    </div>
                                    <div class="list-group-item border-0 py-1">
                                        <small class="text-muted">• {{ __('Try more general terms') }}</small>
                                    </div>
                                    <div class="list-group-item border-0 py-1">
                                        <small class="text-muted">• {{ __('Use different keywords') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- No Search Query -->
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-search fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('Start searching') }}</h5>
                    <p class="text-muted">
                        {{ __('Enter a search term above to find projects, tasks, and users.') }}
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function applyFilters() {
    const params = new URLSearchParams(window.location.search);

    const type = document.getElementById('type-filter').value;
    const status = document.getElementById('status-filter').value;
    const sort = document.getElementById('sort-filter').value;

    if (type) params.set('type', type);
    else params.delete('type');

    if (status) params.set('status', status);
    else params.delete('status');

    if (sort) params.set('sort', sort);
    else params.delete('sort');

    window.location.search = params.toString();
}

function clearFilters() {
    const params = new URLSearchParams(window.location.search);

    params.delete('type');
    params.delete('status');
    params.delete('sort');

    window.location.search = params.toString();
}

// Auto-apply filters on change
document.addEventListener('DOMContentLoaded', function() {
    const filters = ['type-filter', 'status-filter', 'sort-filter'];

    filters.forEach(filterId => {
        const element = document.getElementById(filterId);
        if (element) {
            element.addEventListener('change', applyFilters);
        }
    });
});
</script>
@endpush