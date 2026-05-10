@extends('layouts.admin')

@section('title', 'Projects')
@section('page-title', 'Projects')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">Manage Projects</h5>
                <p class="text-muted mb-0">Showcase your research and academic projects</p>
            </div>
            <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>New Project
            </a>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.projects.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select name="tag" class="form-select">
                                <option value="">All Tags</option>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="on-hold" {{ request('status') == 'on-hold' ? 'selected' : '' }}>On Hold</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-outline-primary me-2">Filter</button>
                            <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @if($projects->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>Tags</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $project)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($project->featured_image)
                                                <img src="{{ Storage::url($project->featured_image) }}"
                                                     alt="{{ $project->title }}"
                                                     class="rounded me-3"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $project->title }}</h6>
                                                <small class="text-muted">{{ Str::limit($project->description, 60) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($project->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($project->status === 'completed')
                                            <span class="badge bg-primary">Completed</span>
                                        @elseif($project->status === 'on-hold')
                                            <span class="badge bg-warning">On Hold</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($project->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($project->start_date)
                                            {{ is_string($project->start_date) ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : $project->start_date->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        @foreach($project->tags as $tag)
                                            <span class="badge bg-light text-dark me-1">{{ $tag->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.projects.show', $project) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.projects.edit', $project) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete"
                                                        data-confirm-delete>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $projects->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-code-slash text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 mb-2">No projects yet</h5>
                        <p class="text-muted mb-4">Start showcasing your research and academic projects</p>
                        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-2"></i>Create Your First Project
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection