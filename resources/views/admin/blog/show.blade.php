@extends('layouts.admin')

@section('title', $blogPost->title)
@section('page-title', 'View Blog Post')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">{{ $blogPost->title }}</h5>
                <p class="text-muted mb-0">
                    @if($blogPost->is_published)
                        <span class="badge bg-success me-2">Published</span>
                    @else
                        <span class="badge bg-warning me-2">Draft</span>
                    @endif
                    @if($blogPost->published_at)
                        {{ $blogPost->published_at->format('M d, Y \a\t g:i A') }}
                    @else
                        Created {{ $blogPost->created_at->format('M d, Y \a\t g:i A') }}
                    @endif
                </p>
            </div>
            <div class="btn-group">
                <a href="{{ route('admin.blog.edit', $blogPost) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Post
                </a>
                <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Posts
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    @if($blogPost->featured_image)
                        <div class="card-header p-0">
                            <img src="{{ Storage::url($blogPost->featured_image) }}"
                                 alt="{{ $blogPost->title }}"
                                 class="img-fluid w-100"
                                 style="max-height: 400px; object-fit: cover;">
                        </div>
                    @endif
                    <div class="card-body">
                        @if($blogPost->excerpt)
                            <div class="alert alert-light border-start border-primary border-4 mb-4">
                                <h6 class="mb-2">Excerpt:</h6>
                                <p class="mb-0 fst-italic">{{ $blogPost->excerpt }}</p>
                            </div>
                        @endif

                        <div class="content">
                            {!! nl2br(e($blogPost->content)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Post Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <div>
                                @if($blogPost->is_published)
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-warning">Draft</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Slug</label>
                            <div class="text-muted">{{ $blogPost->slug }}</div>
                        </div>

                        @if($blogPost->published_at)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Published Date</label>
                                <div>{{ $blogPost->published_at->format('F j, Y \a\t g:i A') }}</div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Created</label>
                            <div>{{ $blogPost->created_at->format('F j, Y \a\t g:i A') }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Last Updated</label>
                            <div>{{ $blogPost->updated_at->format('F j, Y \a\t g:i A') }}</div>
                        </div>

                        @if($blogPost->is_featured)
                            <div class="mb-3">
                                <span class="badge bg-primary">
                                    <i class="bi bi-star me-1"></i>Featured Post
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                @if($blogPost->tags->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Tags</h6>
                        </div>
                        <div class="card-body">
                            @foreach($blogPost->tags as $tag)
                                <span class="badge bg-light text-dark me-2 mb-2">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if($blogPost->is_published)
                                <a href="{{ route('public.blog.show', $blogPost->slug) }}"
                                   class="btn btn-outline-primary" target="_blank">
                                    <i class="bi bi-eye me-2"></i>View on Site
                                </a>
                            @endif

                            <a href="{{ route('admin.blog.edit', $blogPost) }}"
                               class="btn btn-primary">
                                <i class="bi bi-pencil me-2"></i>Edit Post
                            </a>

                            <form method="POST" action="{{ route('admin.blog.destroy', $blogPost) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-outline-danger w-100"
                                        data-confirm-delete>
                                    <i class="bi bi-trash me-2"></i>Delete Post
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection