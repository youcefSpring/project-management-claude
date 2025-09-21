@extends('layouts.admin')

@section('title', 'Blog Posts')
@section('page-title', 'Blog Posts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">Manage Blog Posts</h5>
                <p class="text-muted mb-0">Create and manage your blog posts</p>
            </div>
            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>New Post
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                @if($posts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Published Date</th>
                                    <th>Tags</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($posts as $post)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($post->featured_image)
                                                <img src="{{ Storage::url($post->featured_image) }}"
                                                     alt="{{ $post->title }}"
                                                     class="rounded me-3"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $post->title }}</h6>
                                                <small class="text-muted">{{ Str::limit($post->excerpt, 60) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($post->is_published)
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($post->published_at)
                                            {{ $post->published_at->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Not published</span>
                                        @endif
                                    </td>
                                    <td>
                                        @foreach($post->tags as $tag)
                                            <span class="badge bg-light text-dark me-1">{{ $tag->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.blog.show', $post) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.blog.edit', $post) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" class="d-inline">
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
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-pencil-square text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 mb-2">No blog posts yet</h5>
                        <p class="text-muted mb-4">Start creating engaging content for your audience</p>
                        <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-2"></i>Create Your First Post
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection