@extends('layouts.admin')

@section('title', 'Tags')
@section('page-title', 'Tags')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">Manage Tags</h5>
                <p class="text-muted mb-0">Organize your content with tags and categories</p>
            </div>
            <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>New Tag
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Total Tags</h6>
                            <div class="stats-number">{{ $tags->total() }}</div>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-tags" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Most Used</h6>
                            <div class="text-sm">
                                @if(isset($mostUsedTag))
                                    <strong>{{ $mostUsedTag->name }}</strong><br>
                                    <small class="text-muted">{{ $mostUsedTag->usage_count }} items</small>
                                @else
                                    <small class="text-muted">No usage data</small>
                                @endif
                            </div>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Blog Tags</h6>
                            <div class="stats-number">{{ $blogTagsCount ?? 0 }}</div>
                        </div>
                        <div class="text-info">
                            <i class="bi bi-pencil-square" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Project Tags</h6>
                            <div class="stats-number">{{ $projectTagsCount ?? 0 }}</div>
                        </div>
                        <div class="text-warning">
                            <i class="bi bi-code-slash" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.tags.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search tags..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="sort" class="form-select">
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Sort by Date</option>
                                <option value="usage" {{ request('sort') == 'usage' ? 'selected' : '' }}>Sort by Usage</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="order" class="form-select">
                                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-outline-primary me-2">Search</button>
                            <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @if($tags->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Description</th>
                                    <th>Usage Count</th>
                                    <th>Created</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tags as $tag)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle me-2"
                                                 style="width: 20px; height: 20px; background-color: {{ $tag->color ?? '#6c757d' }};"></div>
                                            <div>
                                                <h6 class="mb-0">{{ $tag->name }}</h6>
                                                @if($tag->color)
                                                    <small class="text-muted">{{ $tag->color }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="text-muted">{{ $tag->slug }}</code>
                                    </td>
                                    <td>
                                        @if($tag->description)
                                            {{ Str::limit($tag->description, 50) }}
                                        @else
                                            <span class="text-muted">No description</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $tag->posts_count + $tag->projects_count + $tag->courses_count ?? 0 }} items
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $tag->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.tags.edit', $tag) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete"
                                                        data-confirm-delete
                                                        {{ ($tag->posts_count + $tag->projects_count + $tag->courses_count ?? 0) > 0 ? 'data-has-items=true' : '' }}>
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

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $tags->firstItem() }} to {{ $tags->lastItem() }} of {{ $tags->total() }} results
                        </div>
                        <div>
                            {{ $tags->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-tags text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 mb-2">No tags yet</h5>
                        <p class="text-muted mb-4">Start organizing your content with tags</p>
                        <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-2"></i>Create Your First Tag
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bulk Actions (if there are tags) -->
        @if($tags->count() > 0)
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-gear text-primary me-2"></i>
                        Bulk Actions
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('admin.tags.index', ['sort' => 'usage', 'order' => 'desc']) }}"
                               class="btn btn-outline-info btn-sm w-100">
                                <i class="bi bi-sort-numeric-down me-1"></i>Show Most Used Tags
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('admin.tags.index', ['unused' => 'true']) }}"
                               class="btn btn-outline-warning btn-sm w-100">
                                <i class="bi bi-exclamation-triangle me-1"></i>Show Unused Tags
                            </a>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="bulkDeleteUnused()">
                                <i class="bi bi-trash me-1"></i>Delete Unused Tags
                            </button>
                        </div>
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
        // Enhanced delete confirmation for tags with items
        const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                const hasItems = this.dataset.hasItems === 'true';

                let message = 'Are you sure you want to delete this tag?';
                if (hasItems) {
                    message = 'This tag is currently being used by posts, projects, or courses. Deleting it will remove the tag from all associated content. Are you sure you want to continue?';
                }

                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });
    });

    function bulkDeleteUnused() {
        if (confirm('Are you sure you want to delete all unused tags? This action cannot be undone.')) {
            // Create a form dynamically to handle bulk delete
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.tags.bulk-delete-unused") }}';

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection

@section('styles')
<style>
    .text-sm {
        font-size: 0.875rem;
    }

    .stats-card .stats-number {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--admin-primary);
    }

    code {
        background-color: #f8f9fa;
        padding: 0.2rem 0.4rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }
</style>
@endsection