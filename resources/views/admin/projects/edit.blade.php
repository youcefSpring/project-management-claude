@extends('layouts.admin')

@section('page-title', 'Edit Project')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Edit Project</h1>
        <p class="text-muted mb-0">Update project information and details</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-outline-info">
            <i class="bi bi-eye me-1"></i>View Project
        </a>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Projects
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Project Title -->
                        <div class="col-12">
                            <label for="title" class="form-label">Project Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $project->title) }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Project Description -->
                        <div class="col-12">
                            <label for="description" class="form-label">Project Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      required>{{ old('description', $project->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status and Type -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="active" {{ old('status', $project->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="on-hold" {{ old('status', $project->status) === 'on-hold' ? 'selected' : '' }}>On Hold</option>
                                <option value="cancelled" {{ old('status', $project->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="type" class="form-label">Project Type</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                <option value="">Select Type</option>
                                <option value="research" {{ old('type', $project->type) === 'research' ? 'selected' : '' }}>Research</option>
                                <option value="academic" {{ old('type', $project->type) === 'academic' ? 'selected' : '' }}>Academic</option>
                                <option value="publication" {{ old('type', $project->type) === 'publication' ? 'selected' : '' }}>Publication</option>
                                <option value="collaboration" {{ old('type', $project->type) === 'collaboration' ? 'selected' : '' }}>Collaboration</option>
                                <option value="teaching" {{ old('type', $project->type) === 'teaching' ? 'selected' : '' }}>Teaching</option>
                                <option value="consulting" {{ old('type', $project->type) === 'consulting' ? 'selected' : '' }}>Consulting</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dates -->
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   id="start_date"
                                   name="start_date"
                                   value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   id="end_date"
                                   name="end_date"
                                   value="{{ old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Funding and Client -->
                        <div class="col-md-6">
                            <label for="funding_amount" class="form-label">Funding Amount ($)</label>
                            <input type="number"
                                   class="form-control @error('funding_amount') is-invalid @enderror"
                                   id="funding_amount"
                                   name="funding_amount"
                                   min="0"
                                   step="0.01"
                                   value="{{ old('funding_amount', $project->funding_amount) }}">
                            @error('funding_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="client_organization" class="form-label">Client/Organization</label>
                            <input type="text"
                                   class="form-control @error('client_organization') is-invalid @enderror"
                                   id="client_organization"
                                   name="client_organization"
                                   value="{{ old('client_organization', $project->client_organization) }}">
                            @error('client_organization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- URL Links -->
                        <div class="col-md-6">
                            <label for="project_url" class="form-label">Project URL</label>
                            <input type="url"
                                   class="form-control @error('project_url') is-invalid @enderror"
                                   id="project_url"
                                   name="project_url"
                                   value="{{ old('project_url', $project->project_url) }}">
                            @error('project_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="repository_url" class="form-label">Repository URL</label>
                            <input type="url"
                                   class="form-control @error('repository_url') is-invalid @enderror"
                                   id="repository_url"
                                   name="repository_url"
                                   value="{{ old('repository_url', $project->repository_url) }}">
                            @error('repository_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Featured Image -->
                        <div class="col-12">
                            <label for="featured_image" class="form-label">Featured Image</label>
                            @if($project->featured_image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($project->featured_image) }}"
                                         alt="Current featured image"
                                         class="img-thumbnail"
                                         style="max-width: 200px; max-height: 150px;">
                                    <p class="form-text mb-2">Current image (will be replaced if new image is uploaded)</p>
                                </div>
                            @endif
                            <input type="file"
                                   class="form-control @error('featured_image') is-invalid @enderror"
                                   id="featured_image"
                                   name="featured_image"
                                   accept="image/*">
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload a new image to replace the current one (max 5MB)</div>
                        </div>

                        <!-- Detailed Content -->
                        <div class="col-12">
                            <label for="content" class="form-label">Project Details</label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content"
                                      name="content"
                                      rows="8">{{ old('content', $project->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Detailed description of the project methodology, goals, and outcomes</div>
                        </div>

                        <!-- Technologies -->
                        <div class="col-12">
                            <label for="technologies" class="form-label">Technologies Used</label>
                            <textarea class="form-control @error('technologies') is-invalid @enderror"
                                      id="technologies"
                                      name="technologies"
                                      rows="3">{{ old('technologies', $project->technologies) }}</textarea>
                            @error('technologies')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">List the technologies, tools, or methodologies used in this project</div>
                        </div>

                        <!-- Collaborators -->
                        <div class="col-12">
                            <label for="collaborators" class="form-label">Collaborators</label>
                            <textarea class="form-control @error('collaborators') is-invalid @enderror"
                                      id="collaborators"
                                      name="collaborators"
                                      rows="3">{{ old('collaborators', $project->collaborators) }}</textarea>
                            @error('collaborators')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">List project collaborators, co-investigators, or team members</div>
                        </div>

                        <!-- Key Outcomes -->
                        <div class="col-12">
                            <label for="key_outcomes" class="form-label">Key Outcomes</label>
                            <textarea class="form-control @error('key_outcomes') is-invalid @enderror"
                                      id="key_outcomes"
                                      name="key_outcomes"
                                      rows="4">{{ old('key_outcomes', $project->key_outcomes) }}</textarea>
                            @error('key_outcomes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Summary of key findings, deliverables, or achievements</div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between pt-3">
                                <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Update Project
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Tags -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-tags text-primary me-2"></i>
                    Tags
                </h5>
                <div class="row g-2">
                    @if(isset($tags) && $tags->count() > 0)
                        @foreach($tags as $tag)
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="tags[]" value="{{ $tag->id }}"
                                           id="tag_{{ $tag->id }}"
                                           {{ in_array($tag->id, old('tags', $project->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tag_{{ $tag->id }}">
                                        {{ $tag->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <p class="text-muted small mb-0">No tags available. <a href="{{ route('admin.tags.create') }}">Create tags</a> to organize your projects.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Visibility Settings -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-eye text-primary me-2"></i>
                    Visibility
                </h5>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_published" id="published" value="1" {{ old('is_published', $project->is_published) == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="published">
                            <strong>Published</strong>
                            <small class="text-muted d-block">Visible to all visitors</small>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_published" id="draft" value="0" {{ old('is_published', $project->is_published) == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="draft">
                            <strong>Draft</strong>
                            <small class="text-muted d-block">Only visible to you</small>
                        </label>
                    </div>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $project->is_featured) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_featured">
                        <strong>Featured Project</strong>
                        <small class="text-muted d-block">Highlight this project on homepage</small>
                    </label>
                </div>
            </div>
        </div>

        <!-- Project Info -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Project Info
                </h5>

                <div class="mb-2">
                    <small class="text-muted">Created:</small>
                    <div>{{ $project->created_at->format('F d, Y \a\t g:i A') }}</div>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Last Updated:</small>
                    <div>{{ $project->updated_at->format('F d, Y \a\t g:i A') }}</div>
                </div>

                @if($project->is_published)
                    <div class="mt-3">
                        <a href="{{ url('/projects/' . Str::slug($project->title)) }}" target="_blank" class="btn btn-outline-info btn-sm w-100">
                            <i class="bi bi-eye me-1"></i>View Public Page
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="card border-danger">
            <div class="card-body">
                <h5 class="card-title text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Danger Zone
                </h5>
                <p class="card-text small text-muted">
                    Permanently delete this project. This action cannot be undone.
                </p>
                <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100" data-confirm-delete>
                        <i class="bi bi-trash me-1"></i>Delete Project
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date validation
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        function validateDates() {
            if (startDateInput.value && endDateInput.value) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);

                if (endDate < startDate) {
                    endDateInput.setCustomValidity('End date must be after start date');
                } else {
                    endDateInput.setCustomValidity('');
                }
            }
        }

        startDateInput.addEventListener('change', validateDates);
        endDateInput.addEventListener('change', validateDates);

        // Form submission handling
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Updating...';
            submitButton.disabled = true;

            // Re-enable after 10 seconds as fallback
            setTimeout(function() {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 10000);
        });

        // File size validation
        const featuredImageInput = document.getElementById('featured_image');
        featuredImageInput.addEventListener('change', function() {
            if (this.files[0]) {
                const fileSize = this.files[0].size / 1024 / 1024; // Size in MB
                if (fileSize > 5) {
                    this.setCustomValidity('File size must be less than 5MB');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                }
            }
        });
    });
</script>
@endsection