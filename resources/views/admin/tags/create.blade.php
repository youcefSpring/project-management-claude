@extends('layouts.admin')

@section('page-title', 'Create Tag')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Create New Tag</h1>
        <p class="text-muted mb-0">Add a new tag to organize your content</p>
    </div>
    <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Tags
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.tags.store') }}">
                    @csrf

                    <div class="row g-3">
                        <!-- Tag Name -->
                        <div class="col-12">
                            <label for="name" class="form-label">Tag Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="e.g., Web Development, Research, Laravel">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tag Slug -->
                        <div class="col-12">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   id="slug"
                                   name="slug"
                                   value="{{ old('slug') }}"
                                   placeholder="Auto-generated from name">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Leave empty to auto-generate from tag name. Must be URL-friendly (lowercase, hyphens only).
                                <span id="slug-preview" class="text-primary fw-bold"></span>
                            </div>
                        </div>

                        <!-- Tag Description -->
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Brief description of what this tag represents">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional description to help you and others understand this tag's purpose</div>
                        </div>

                        <!-- Tag Color -->
                        <div class="col-md-6">
                            <label for="color" class="form-label">Tag Color</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color"
                                       class="form-control form-control-color @error('color') is-invalid @enderror"
                                       id="color"
                                       name="color"
                                       value="{{ old('color', '#6c757d') }}"
                                       style="width: 60px;">
                                <input type="text"
                                       class="form-control @error('color') is-invalid @enderror"
                                       id="color-hex"
                                       name="color_hex"
                                       value="{{ old('color', '#6c757d') }}"
                                       pattern="^#[0-9a-fA-F]{6}$"
                                       placeholder="#6c757d">
                                <button type="button" class="btn btn-outline-secondary" onclick="randomColor()">
                                    <i class="bi bi-shuffle"></i>
                                </button>
                            </div>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Choose a color to visually identify this tag</div>
                        </div>

                        <!-- Tag Preview -->
                        <div class="col-md-6">
                            <label class="form-label">Preview</label>
                            <div class="d-flex align-items-center gap-2">
                                <span id="tag-preview" class="badge" style="background-color: #6c757d; color: white;">
                                    Sample Tag
                                </span>
                                <small class="text-muted">This is how your tag will appear</small>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between pt-3">
                                <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Create Tag
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Tag Usage Guide -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-question-circle text-primary me-2"></i>
                    Tag Usage Guide
                </h5>
                <div class="small">
                    <div class="mb-3">
                        <strong>What are tags?</strong>
                        <p class="mb-2">Tags help categorize and organize your content, making it easier for visitors to find related posts, projects, and courses.</p>
                    </div>

                    <div class="mb-3">
                        <strong>Best Practices:</strong>
                        <ul class="mb-0">
                            <li>Use descriptive, specific names</li>
                            <li>Keep names concise (1-3 words)</li>
                            <li>Use consistent naming conventions</li>
                            <li>Avoid overly generic terms</li>
                            <li>Check for existing similar tags</li>
                        </ul>
                    </div>

                    <div>
                        <strong>Examples:</strong>
                        <div class="d-flex flex-wrap gap-1 mt-2">
                            <span class="badge bg-primary">Laravel</span>
                            <span class="badge bg-success">Web Development</span>
                            <span class="badge bg-info">Research</span>
                            <span class="badge bg-warning">Machine Learning</span>
                            <span class="badge bg-secondary">JavaScript</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Color Presets -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-palette text-primary me-2"></i>
                    Color Presets
                </h5>
                <div class="row g-2">
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 color-preset" data-color="#0d6efd">
                            <div class="rounded-circle mx-auto" style="width: 20px; height: 20px; background-color: #0d6efd;"></div>
                            Blue
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 color-preset" data-color="#198754">
                            <div class="rounded-circle mx-auto" style="width: 20px; height: 20px; background-color: #198754;"></div>
                            Green
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 color-preset" data-color="#dc3545">
                            <div class="rounded-circle mx-auto" style="width: 20px; height: 20px; background-color: #dc3545;"></div>
                            Red
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 color-preset" data-color="#ffc107">
                            <div class="rounded-circle mx-auto" style="width: 20px; height: 20px; background-color: #ffc107;"></div>
                            Yellow
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 color-preset" data-color="#6f42c1">
                            <div class="rounded-circle mx-auto" style="width: 20px; height: 20px; background-color: #6f42c1;"></div>
                            Purple
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 color-preset" data-color="#fd7e14">
                            <div class="rounded-circle mx-auto" style="width: 20px; height: 20px; background-color: #fd7e14;"></div>
                            Orange
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Existing Tags -->
        @if(isset($existingTags) && $existingTags->count() > 0)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-tags text-primary me-2"></i>
                        Existing Tags
                    </h5>
                    <div class="mb-2">
                        <small class="text-muted">{{ $existingTags->count() }} tags already created</small>
                    </div>
                    <div class="d-flex flex-wrap gap-1 max-height-200" style="max-height: 200px; overflow-y: auto;">
                        @foreach($existingTags->take(20) as $tag)
                            <span class="badge"
                                  style="background-color: {{ $tag->color ?? '#6c757d' }}; color: white;"
                                  title="{{ $tag->description ?? $tag->name }}">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                        @if($existingTags->count() > 20)
                            <small class="text-muted">and {{ $existingTags->count() - 20 }} more...</small>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const slugPreview = document.getElementById('slug-preview');
        const colorInput = document.getElementById('color');
        const colorHexInput = document.getElementById('color-hex');
        const tagPreview = document.getElementById('tag-preview');

        // Auto-generate slug from name
        function updateSlug() {
            if (!slugInput.dataset.manual && nameInput.value) {
                const slug = nameInput.value
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim('-');

                slugInput.value = slug;
                slugPreview.textContent = slug ? `URL: /tags/${slug}` : '';
            }
            updatePreview();
        }

        // Update preview
        function updatePreview() {
            const name = nameInput.value || 'Sample Tag';
            const color = colorInput.value || '#6c757d';

            tagPreview.textContent = name;
            tagPreview.style.backgroundColor = color;
            tagPreview.style.color = getContrastColor(color);
        }

        // Get contrast color (white or black) based on background
        function getContrastColor(hexColor) {
            const r = parseInt(hexColor.substr(1, 2), 16);
            const g = parseInt(hexColor.substr(3, 2), 16);
            const b = parseInt(hexColor.substr(5, 2), 16);
            const yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;
            return (yiq >= 128) ? 'black' : 'white';
        }

        // Sync color inputs
        function syncColorInputs(source) {
            if (source === 'picker') {
                colorHexInput.value = colorInput.value;
            } else if (source === 'hex') {
                if (/^#[0-9a-fA-F]{6}$/.test(colorHexInput.value)) {
                    colorInput.value = colorHexInput.value;
                }
            }
            updatePreview();
        }

        // Event listeners
        nameInput.addEventListener('input', updateSlug);

        slugInput.addEventListener('input', function() {
            this.dataset.manual = 'true';
            updatePreview();
        });

        colorInput.addEventListener('input', function() {
            syncColorInputs('picker');
        });

        colorHexInput.addEventListener('input', function() {
            syncColorInputs('hex');
        });

        // Color preset buttons
        document.querySelectorAll('.color-preset').forEach(function(button) {
            button.addEventListener('click', function() {
                const color = this.dataset.color;
                colorInput.value = color;
                colorHexInput.value = color;
                updatePreview();
            });
        });

        // Random color function
        window.randomColor = function() {
            const colors = ['#0d6efd', '#198754', '#dc3545', '#ffc107', '#6f42c1', '#fd7e14', '#20c997', '#e91e63', '#795548', '#607d8b'];
            const randomColor = colors[Math.floor(Math.random() * colors.length)];
            colorInput.value = randomColor;
            colorHexInput.value = randomColor;
            updatePreview();
        };

        // Form submission handling
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Creating...';
            submitButton.disabled = true;

            // Re-enable after 10 seconds as fallback
            setTimeout(function() {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 10000);
        });

        // Initialize preview
        updateSlug();
        updatePreview();
    });
</script>
@endsection

@section('styles')
<style>
    .form-control-color {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }

    .color-preset {
        padding: 0.5rem;
        border: 1px solid #dee2e6;
    }

    .color-preset:hover {
        background-color: #f8f9fa;
    }

    .max-height-200 {
        max-height: 200px;
        overflow-y: auto;
    }

    #tag-preview {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
    }
</style>
@endsection