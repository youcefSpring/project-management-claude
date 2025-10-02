@extends('layouts.admin')

@section('title', 'Edit Course')
@section('page-title', 'Edit Course')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">Edit Course</h5>
                <p class="text-muted mb-0">{{ $course->title }}</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-outline-info">
                    <i class="bi bi-eye me-2"></i>View Course
                </a>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Courses
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Course Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title', $course->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                       id="slug" name="slug" value="{{ old('slug', $course->slug) }}">
                                <div class="form-text">URL-friendly version of the title</div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="course_code" class="form-label">Course Code</label>
                                        <input type="text" class="form-control @error('course_code') is-invalid @enderror"
                                               id="course_code" name="course_code" value="{{ old('course_code', $course->course_code) }}">
                                        <div class="form-text">e.g., CS101, MATH201</div>
                                        @error('course_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="credits" class="form-label">Credits</label>
                                        <input type="number" class="form-control @error('credits') is-invalid @enderror"
                                               id="credits" name="credits" value="{{ old('credits', $course->credits) }}" min="1" max="10">
                                        @error('credits')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="5" required>{{ old('description', $course->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="objectives" class="form-label">Learning Objectives</label>
                                <textarea class="form-control @error('objectives') is-invalid @enderror"
                                          id="objectives" name="objectives" rows="4">{{ old('objectives', $course->objectives) }}</textarea>
                                <div class="form-text">List the main learning outcomes (one per line)</div>
                                @error('objectives')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="prerequisites" class="form-label">Prerequisites</label>
                                <textarea class="form-control @error('prerequisites') is-invalid @enderror"
                                          id="prerequisites" name="prerequisites" rows="3">{{ old('prerequisites', $course->prerequisites) }}</textarea>
                                <div class="form-text">Required knowledge or courses before taking this course</div>
                                @error('prerequisites')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="syllabus_content" class="form-label">Syllabus Content</label>
                                <textarea class="form-control @error('syllabus_content') is-invalid @enderror"
                                          id="syllabus_content" name="syllabus_content" rows="10">{{ old('syllabus_content', $course->syllabus_content) }}</textarea>
                                <div class="form-text">Detailed course content, topics covered, schedule, etc.</div>
                                @error('syllabus_content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Course Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="level" class="form-label">Level</label>
                                <select class="form-select @error('level') is-invalid @enderror" id="level" name="level">
                                    <option value="">Select Level</option>
                                    <option value="undergraduate" {{ old('level', $course->level) === 'undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                    <option value="graduate" {{ old('level', $course->level) === 'graduate' ? 'selected' : '' }}>Graduate</option>
                                    <option value="phd" {{ old('level', $course->level) === 'phd' ? 'selected' : '' }}>PhD</option>
                                    <option value="continuing_education" {{ old('level', $course->level) === 'continuing_education' ? 'selected' : '' }}>Continuing Education</option>
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control @error('department') is-invalid @enderror"
                                       id="department" name="department" value="{{ old('department', $course->department) }}">
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                               id="start_date" name="start_date"
                                               value="{{ old('start_date', $course->start_date ? $course->start_date->format('Y-m-d') : '') }}">
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                               id="end_date" name="end_date"
                                               value="{{ old('end_date', $course->end_date ? $course->end_date->format('Y-m-d') : '') }}">
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="is_active" class="form-label">Status</label>
                                <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                                    <option value="1" {{ old('is_active', $course->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active', $course->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input @error('is_featured') is-invalid @enderror"
                                           type="checkbox" id="is_featured" name="is_featured" value="1"
                                           {{ old('is_featured', $course->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Featured Course
                                    </label>
                                </div>
                                @error('is_featured')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Course Image</h6>
                        </div>
                        <div class="card-body">
                            @if($course->image)
                                <div class="mb-3">
                                    <img src="{{ Storage::url($course->image) }}"
                                         alt="Current course image"
                                         class="img-fluid rounded"
                                         style="max-height: 200px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image">
                                        <label class="form-check-label" for="remove_image">
                                            Remove current image
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                       id="image" name="image" accept="image/*">
                                <div class="form-text">
                                    @if($course->image)
                                        Upload a new image to replace the current one
                                    @else
                                        Recommended: 800x400px
                                    @endif
                                </div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Attachments</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="syllabus_file" class="form-label">Syllabus PDF</label>
                                @if($course->syllabus_file)
                                    <div class="mb-2">
                                        <a href="{{ Storage::url($course->syllabus_file) }}"
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-pdf me-1"></i>View Current Syllabus
                                        </a>
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="remove_syllabus" id="remove_syllabus">
                                            <label class="form-check-label" for="remove_syllabus">
                                                Remove current syllabus
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('syllabus_file') is-invalid @enderror"
                                       id="syllabus_file" name="syllabus_file" accept=".pdf">
                                @error('syllabus_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Update Course
                        </button>
                        <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from title (only if slug is empty)
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const originalSlug = slugInput.value;

    titleInput.addEventListener('input', function() {
        if (!slugInput.dataset.manual && !originalSlug) {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        }
    });

    slugInput.addEventListener('input', function() {
        this.dataset.manual = 'true';
    });

    // Validate date range
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    function validateDates() {
        if (startDate.value && endDate.value && startDate.value > endDate.value) {
            endDate.setCustomValidity('End date must be after start date');
        } else {
            endDate.setCustomValidity('');
        }
    }

    startDate.addEventListener('change', validateDates);
    endDate.addEventListener('change', validateDates);
});
</script>
@endsection
@endsection