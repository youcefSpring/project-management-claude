@extends('layouts.admin')

@section('title', 'Edit Blog Post')
@section('page-title', 'Edit Blog Post')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">Edit Blog Post</h5>
                <p class="text-muted mb-0">{{ $blogPost->title }}</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('admin.blog.show', $blogPost) }}" class="btn btn-outline-info">
                    <i class="bi bi-eye me-2"></i>View Post
                </a>
                <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Posts
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.blog.update', $blogPost) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title', $blogPost->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                       id="slug" name="slug" value="{{ old('slug', $blogPost->slug) }}">
                                <div class="form-text">URL-friendly version of the title</div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="excerpt" class="form-label">Excerpt</label>
                                <textarea class="form-control @error('excerpt') is-invalid @enderror"
                                          id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $blogPost->excerpt) }}</textarea>
                                <div class="form-text">Brief summary of the post (optional)</div>
                                @error('excerpt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Content *</label>
                                <textarea class="form-control @error('content') is-invalid @enderror"
                                          id="content" name="content" rows="15" required>{{ old('content', $blogPost->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Publish Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="is_published" class="form-label">Status</label>
                                <select class="form-select @error('is_published') is-invalid @enderror"
                                        id="is_published" name="is_published">
                                    <option value="0" {{ old('is_published', $blogPost->is_published) == '0' ? 'selected' : '' }}>Draft</option>
                                    <option value="1" {{ old('is_published', $blogPost->is_published) == '1' ? 'selected' : '' }}>Published</option>
                                </select>
                                @error('is_published')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="published_at" class="form-label">Publish Date</label>
                                <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror"
                                       id="published_at" name="published_at"
                                       value="{{ old('published_at', $blogPost->published_at ? $blogPost->published_at->format('Y-m-d\TH:i') : '') }}">
                                <div class="form-text">Leave empty to publish immediately</div>
                                @error('published_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="is_featured" class="form-check-label">
                                    <input type="checkbox" class="form-check-input @error('is_featured') is-invalid @enderror"
                                           id="is_featured" name="is_featured" value="1"
                                           {{ old('is_featured', $blogPost->is_featured) ? 'checked' : '' }}>
                                    Featured Post
                                </label>
                                @error('is_featured')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Featured Image</h6>
                        </div>
                        <div class="card-body">
                            @if($blogPost->featured_image)
                                <div class="mb-3">
                                    <img src="{{ Storage::url($blogPost->featured_image) }}"
                                         alt="Current featured image"
                                         class="img-fluid rounded"
                                         style="max-height: 200px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_featured_image" id="remove_featured_image">
                                        <label class="form-check-label" for="remove_featured_image">
                                            Remove current image
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <input type="file" class="form-control @error('featured_image') is-invalid @enderror"
                                       id="featured_image" name="featured_image" accept="image/*">
                                <div class="form-text">
                                    @if($blogPost->featured_image)
                                        Upload a new image to replace the current one
                                    @else
                                        Recommended: 1200x600px
                                    @endif
                                </div>
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Tags</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                @foreach($tags as $tag)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               id="tag_{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}"
                                               {{ in_array($tag->id, old('tags', $blogPost->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tag_{{ $tag->id }}">
                                            {{ $tag->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Update Post
                        </button>
                        <a href="{{ route('admin.blog.show', $blogPost) }}" class="btn btn-outline-secondary">
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
});
</script>
@endsection
@endsection