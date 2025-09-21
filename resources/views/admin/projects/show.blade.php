@extends('layouts.admin')

@section('page-title', 'View Project')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $project->title }}</h1>
        <p class="text-muted mb-0">Project Details</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Edit Project
        </a>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Projects
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Project Image -->
        @if($project->featured_image)
            <div class="card mb-4">
                <div class="card-body p-0">
                    <img src="{{ Storage::url($project->featured_image) }}"
                         alt="{{ $project->title }}"
                         class="img-fluid w-100"
                         style="max-height: 400px; object-fit: cover;">
                </div>
            </div>
        @endif

        <!-- Project Description -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-file-text text-primary me-2"></i>
                    Description
                </h5>
                <p class="card-text">{{ $project->description }}</p>
            </div>
        </div>

        <!-- Project Details -->
        @if($project->content)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-journal-text text-primary me-2"></i>
                        Project Details
                    </h5>
                    <div class="content">
                        {!! nl2br(e($project->content)) !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Technologies -->
        @if($project->technologies)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-gear text-primary me-2"></i>
                        Technologies Used
                    </h5>
                    <div class="content">
                        {!! nl2br(e($project->technologies)) !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Collaborators -->
        @if($project->collaborators)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-people text-primary me-2"></i>
                        Collaborators
                    </h5>
                    <div class="content">
                        {!! nl2br(e($project->collaborators)) !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Key Outcomes -->
        @if($project->key_outcomes)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-trophy text-primary me-2"></i>
                        Key Outcomes
                    </h5>
                    <div class="content">
                        {!! nl2br(e($project->key_outcomes)) !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-lightning text-primary me-2"></i>
                    Quick Actions
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    @if($project->project_url)
                        <a href="{{ $project->project_url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-link-45deg me-1"></i>View Project
                        </a>
                    @endif
                    @if($project->repository_url)
                        <a href="{{ $project->repository_url }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-github me-1"></i>View Repository
                        </a>
                    @endif
                    <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit Project
                    </a>
                    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm-delete>
                            <i class="bi bi-trash me-1"></i>Delete Project
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Project Info -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Project Information
                </h5>

                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <div>
                        @if($project->status === 'active')
                            <span class="badge bg-success fs-6">Active</span>
                        @elseif($project->status === 'completed')
                            <span class="badge bg-primary fs-6">Completed</span>
                        @elseif($project->status === 'on-hold')
                            <span class="badge bg-warning fs-6">On Hold</span>
                        @elseif($project->status === 'cancelled')
                            <span class="badge bg-danger fs-6">Cancelled</span>
                        @else
                            <span class="badge bg-secondary fs-6">{{ ucfirst($project->status) }}</span>
                        @endif
                    </div>
                </div>

                @if($project->type)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Project Type</label>
                        <div>{{ ucfirst($project->type) }}</div>
                    </div>
                @endif

                @if($project->start_date)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Start Date</label>
                        <div>{{ $project->start_date->format('F d, Y') }}</div>
                    </div>
                @endif

                @if($project->end_date)
                    <div class="mb-3">
                        <label class="form-label fw-bold">End Date</label>
                        <div>{{ $project->end_date->format('F d, Y') }}</div>
                    </div>
                @endif

                @if($project->funding_amount)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Funding Amount</label>
                        <div>${{ number_format($project->funding_amount, 2) }}</div>
                    </div>
                @endif

                @if($project->client_organization)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Client/Organization</label>
                        <div>{{ $project->client_organization }}</div>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fw-bold">Published</label>
                    <div>
                        @if($project->is_published)
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-secondary">Draft</span>
                        @endif
                    </div>
                </div>

                @if($project->is_featured)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Featured</label>
                        <div>
                            <span class="badge bg-warning">Featured Project</span>
                        </div>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fw-bold">Created</label>
                    <div>{{ $project->created_at->format('F d, Y \a\t g:i A') }}</div>
                </div>

                <div>
                    <label class="form-label fw-bold">Last Updated</label>
                    <div>{{ $project->updated_at->format('F d, Y \a\t g:i A') }}</div>
                </div>
            </div>
        </div>

        <!-- Project Links -->
        @if($project->project_url || $project->repository_url)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-link text-primary me-2"></i>
                        Project Links
                    </h5>

                    @if($project->project_url)
                        <div class="mb-2">
                            <a href="{{ $project->project_url }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-link-45deg me-1"></i>View Live Project
                            </a>
                        </div>
                    @endif

                    @if($project->repository_url)
                        <div class="mb-2">
                            <a href="{{ $project->repository_url }}" target="_blank" class="btn btn-outline-secondary btn-sm w-100">
                                <i class="bi bi-github me-1"></i>View Repository
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Tags -->
        @if($project->tags && $project->tags->count() > 0)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-tags text-primary me-2"></i>
                        Tags
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($project->tags as $tag)
                            <span class="badge bg-light text-dark">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistics (if this is a public project) -->
        @if($project->is_published)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-bar-chart text-primary me-2"></i>
                        Public Visibility
                    </h5>
                    <div class="text-center">
                        <p class="text-muted mb-2">This project is visible to the public</p>
                        <a href="{{ url('/projects/' . Str::slug($project->title)) }}" target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-eye me-1"></i>View Public Page
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .content {
        line-height: 1.6;
        color: #4a5568;
    }

    .content p {
        margin-bottom: 1rem;
    }

    .badge.fs-6 {
        font-size: 0.875rem !important;
    }
</style>
@endsection