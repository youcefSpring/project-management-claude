@extends('layouts.admin')

@section('page-title', 'Create Course')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Create New Course</h1>
        <p class="text-muted mb-0">Add a new course to your portfolio</p>
    </div>
    <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Courses
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <!-- Course Title -->
                        <div class="col-12">
                            <label for="title" class="form-label">Course Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Course Description -->
                        <div class="col-12">
                            <label for="description" class="form-label">Course Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status and Level -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="upcoming" {{ old('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="level" class="form-label">Level</label>
                            <select class="form-select @error('level') is-invalid @enderror" id="level" name="level">
                                <option value="">Select Level</option>
                                <option value="beginner" {{ old('level') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ old('level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                                <option value="graduate" {{ old('level') === 'graduate' ? 'selected' : '' }}>Graduate</option>
                            </select>
                            @error('level')
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
                                   value="{{ old('start_date') }}">
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
                                   value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Credits -->
                        <div class="col-md-6">
                            <label for="credits" class="form-label">Credits</label>
                            <input type="number"
                                   class="form-control @error('credits') is-invalid @enderror"
                                   id="credits"
                                   name="credits"
                                   min="1"
                                   max="10"
                                   value="{{ old('credits') }}">
                            @error('credits')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Syllabus File -->
                        <div class="col-md-6">
                            <label for="syllabus_file" class="form-label">Syllabus (PDF)</label>
                            <input type="file"
                                   class="form-control @error('syllabus_file') is-invalid @enderror"
                                   id="syllabus_file"
                                   name="syllabus_file"
                                   accept=".pdf">
                            @error('syllabus_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload a PDF syllabus file (max 10MB)</div>
                        </div>

                        <!-- Content -->
                        <div class="col-12">
                            <label for="content" class="form-label">Course Content</label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content"
                                      name="content"
                                      rows="6">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Detailed course description and overview</div>
                        </div>

                        <!-- Learning Objectives -->
                        <div class="col-12">
                            <label for="learning_objectives" class="form-label">Learning Objectives</label>
                            <textarea class="form-control @error('learning_objectives') is-invalid @enderror"
                                      id="learning_objectives"
                                      name="learning_objectives"
                                      rows="4">{{ old('learning_objectives') }}</textarea>
                            @error('learning_objectives')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Prerequisites -->
                        <div class="col-12">
                            <label for="prerequisites" class="form-label">Prerequisites</label>
                            <textarea class="form-control @error('prerequisites') is-invalid @enderror"
                                      id="prerequisites"
                                      name="prerequisites"
                                      rows="3">{{ old('prerequisites') }}</textarea>
                            @error('prerequisites')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Assessment Methods -->
                        <div class="col-12">
                            <label for="assessment_methods" class="form-label">Assessment Methods</label>
                            <textarea class="form-control @error('assessment_methods') is-invalid @enderror"
                                      id="assessment_methods"
                                      name="assessment_methods"
                                      rows="3">{{ old('assessment_methods') }}</textarea>
                            @error('assessment_methods')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Resources -->
                        <div class="col-12">
                            <label for="resources" class="form-label">Course Resources</label>
                            <textarea class="form-control @error('resources') is-invalid @enderror"
                                      id="resources"
                                      name="resources"
                                      rows="4">{{ old('resources') }}</textarea>
                            @error('resources')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Required textbooks, materials, and additional resources</div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between pt-3">
                                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-1"></i>Cancel
                                </a>
                                <div class="d-flex gap-2">
                                    <button type="submit" name="action" value="draft" class="btn btn-outline-primary">
                                        <i class="bi bi-file-earmark me-1"></i>Save as Draft
                                    </button>
                                    <button type="submit" name="action" value="publish" class="btn btn-primary">
                                        <i class="bi bi-check-lg me-1"></i>Create Course
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Publishing Options -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-gear text-primary me-2"></i>
                    Publishing Options
                </h5>

                <div class="mb-3">
                    <label class="form-label">Visibility</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="visibility" id="public" value="public" checked>
                        <label class="form-check-label" for="public">
                            <strong>Public</strong>
                            <small class="text-muted d-block">Visible to all visitors</small>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="visibility" id="private" value="private">
                        <label class="form-check-label" for="private">
                            <strong>Private</strong>
                            <small class="text-muted d-block">Only visible to you</small>
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">SEO Settings</label>
                    <input type="text"
                           class="form-control form-control-sm mb-2"
                           name="meta_title"
                           placeholder="Custom page title"
                           value="{{ old('meta_title') }}">
                    <textarea class="form-control form-control-sm"
                              name="meta_description"
                              rows="2"
                              placeholder="Meta description for search engines">{{ old('meta_description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Tips -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    Tips
                </h5>
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Use clear, descriptive titles for better discoverability
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Include detailed learning objectives to set expectations
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Upload a comprehensive syllabus PDF when available
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Specify prerequisites to help students prepare
                    </li>
                    <li>
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Set appropriate course level and credit hours
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-generate slug from title
        const titleInput = document.getElementById('title');
        const slugPreview = document.createElement('div');
        slugPreview.className = 'form-text text-muted';
        slugPreview.id = 'slug-preview';
        titleInput.parentNode.appendChild(slugPreview);

        function updateSlugPreview() {
            const slug = titleInput.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');

            if (slug) {
                slugPreview.innerHTML = `<i class="bi bi-link me-1"></i>URL: /courses/${slug}`;
            } else {
                slugPreview.innerHTML = '';
            }
        }

        titleInput.addEventListener('input', updateSlugPreview);
        updateSlugPreview();

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
            const submitButton = e.submitter;
            const originalText = submitButton.innerHTML;

            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Saving...';
            submitButton.disabled = true;

            // Re-enable after 10 seconds as fallback
            setTimeout(function() {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 10000);
        });

        // File size validation
        const syllabusInput = document.getElementById('syllabus_file');
        syllabusInput.addEventListener('change', function() {
            if (this.files[0]) {
                const fileSize = this.files[0].size / 1024 / 1024; // Size in MB
                if (fileSize > 10) {
                    this.setCustomValidity('File size must be less than 10MB');
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