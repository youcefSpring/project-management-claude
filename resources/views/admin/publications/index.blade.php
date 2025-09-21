@extends('layouts.admin')

@section('title', 'Publications')
@section('page-title', 'Publications')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">Manage Publications</h5>
                <p class="text-muted mb-0">Showcase your research papers, articles, and academic publications</p>
            </div>
            <a href="{{ route('admin.publications.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>New Publication
            </a>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.publications.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="journal" {{ request('type') == 'journal' ? 'selected' : '' }}>Journal Article</option>
                                <option value="conference" {{ request('type') == 'conference' ? 'selected' : '' }}>Conference Paper</option>
                                <option value="book" {{ request('type') == 'book' ? 'selected' : '' }}>Book</option>
                                <option value="book_chapter" {{ request('type') == 'book_chapter' ? 'selected' : '' }}>Book Chapter</option>
                                <option value="thesis" {{ request('type') == 'thesis' ? 'selected' : '' }}>Thesis</option>
                                <option value="report" {{ request('type') == 'report' ? 'selected' : '' }}>Report</option>
                                <option value="preprint" {{ request('type') == 'preprint' ? 'selected' : '' }}>Preprint</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="in_preparation" {{ request('status') == 'in_preparation' ? 'selected' : '' }}>In Preparation</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="year" class="form-control" placeholder="Publication Year" value="{{ request('year') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-outline-primary me-2">Filter</button>
                            <a href="{{ route('admin.publications.index') }}" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @if($publications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Publication Date</th>
                                    <th>Journal/Venue</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($publications as $publication)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-1">{{ $publication->title }}</h6>
                                            <small class="text-muted">
                                                @if($publication->authors)
                                                    {{ Str::limit($publication->authors, 80) }}
                                                @else
                                                    No authors specified
                                                @endif
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ ucwords(str_replace('_', ' ', $publication->type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($publication->status === 'published')
                                            <span class="badge bg-success">Published</span>
                                        @elseif($publication->status === 'accepted')
                                            <span class="badge bg-info">Accepted</span>
                                        @elseif($publication->status === 'under_review')
                                            <span class="badge bg-warning">Under Review</span>
                                        @elseif($publication->status === 'in_preparation')
                                            <span class="badge bg-secondary">In Preparation</span>
                                        @else
                                            <span class="badge bg-light text-dark">{{ ucfirst($publication->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($publication->publication_date)
                                            {{ $publication->publication_date->format('M Y') }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($publication->journal_name || $publication->venue)
                                            {{ $publication->journal_name ?: $publication->venue }}
                                        @else
                                            <span class="text-muted">Not specified</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.publications.show', $publication) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.publications.edit', $publication) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.publications.destroy', $publication) }}" class="d-inline">
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
                        {{ $publications->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-text text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 mb-2">No publications yet</h5>
                        <p class="text-muted mb-4">Start showcasing your research papers and academic publications</p>
                        <a href="{{ route('admin.publications.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-2"></i>Add Your First Publication
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection