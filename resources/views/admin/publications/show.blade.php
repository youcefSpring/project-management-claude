@extends('layouts.admin')

@section('page-title', 'View Publication')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $publication->title }}</h1>
        <p class="text-muted mb-0">Publication Details</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.publications.edit', $publication) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Edit Publication
        </a>
        <a href="{{ route('admin.publications.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Publications
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Publication Title and Authors -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title h4">{{ $publication->title }}</h2>
                @if($publication->authors)
                    <p class="text-muted mb-3">
                        <strong>Authors:</strong> {{ $publication->authors }}
                    </p>
                @endif

                <!-- Publication Details -->
                <div class="row g-3">
                    @if($publication->journal_name || $publication->venue)
                        <div class="col-md-6">
                            <strong>Journal/Venue:</strong><br>
                            {{ $publication->journal_name ?: $publication->venue }}
                        </div>
                    @endif

                    @if($publication->year || $publication->publication_date)
                        <div class="col-md-6">
                            <strong>Publication Year:</strong><br>
                            @if($publication->publication_date)
                                {{ $publication->publication_date->format('Y') }}
                                @if($publication->publication_date->format('M d') !== 'Jan 01')
                                    ({{ $publication->publication_date->format('M d, Y') }})
                                @endif
                            @else
                                {{ $publication->year }}
                            @endif
                        </div>
                    @endif

                    @if($publication->volume || $publication->issue || $publication->pages)
                        <div class="col-md-6">
                            <strong>Volume/Issue/Pages:</strong><br>
                            @if($publication->volume)Vol. {{ $publication->volume }}@endif
                            @if($publication->issue), Issue {{ $publication->issue }}@endif
                            @if($publication->pages), pp. {{ $publication->pages }}@endif
                        </div>
                    @endif

                    @if($publication->doi)
                        <div class="col-md-6">
                            <strong>DOI:</strong><br>
                            <a href="https://doi.org/{{ $publication->doi }}" target="_blank" class="text-decoration-none">
                                {{ $publication->doi }} <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Abstract -->
        @if($publication->abstract)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-file-text text-primary me-2"></i>
                        Abstract
                    </h5>
                    <div class="content">
                        {!! nl2br(e($publication->abstract)) !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Keywords -->
        @if($publication->keywords)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-tags text-primary me-2"></i>
                        Keywords
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(explode(',', $publication->keywords) as $keyword)
                            <span class="badge bg-light text-dark">{{ trim($keyword) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Notes -->
        @if($publication->notes)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-journal-text text-primary me-2"></i>
                        Notes
                    </h5>
                    <div class="content">
                        {!! nl2br(e($publication->notes)) !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Citation -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-quote text-primary me-2"></i>
                    Citation
                </h5>
                <div class="bg-light p-3 rounded">
                    <div id="citation-text" class="font-monospace small">
                        @if($publication->authors){{ $publication->authors }}@endif
                        @if($publication->year || $publication->publication_date) ({{ $publication->publication_date ? $publication->publication_date->format('Y') : $publication->year }}).@endif
                        @if($publication->title) {{ $publication->title }}.@endif
                        @if($publication->journal_name || $publication->venue) <em>{{ $publication->journal_name ?: $publication->venue }}</em>@endif
                        @if($publication->volume), {{ $publication->volume }}@endif
                        @if($publication->issue)({{ $publication->issue }})@endif
                        @if($publication->pages), {{ $publication->pages }}@endif
                        @if($publication->doi). https://doi.org/{{ $publication->doi }}@endif
                    </div>
                    <button class="btn btn-outline-secondary btn-sm mt-2" onclick="copyCitation()">
                        <i class="bi bi-clipboard me-1"></i>Copy Citation
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-lightning text-primary me-2"></i>
                    Quick Actions
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    @if($publication->url)
                        <a href="{{ $publication->url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-link-45deg me-1"></i>View Publication
                        </a>
                    @endif
                    @if($publication->doi)
                        <a href="https://doi.org/{{ $publication->doi }}" target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-search me-1"></i>View DOI
                        </a>
                    @endif
                    @if($publication->pdf_file)
                        <a href="{{ Storage::url($publication->pdf_file) }}" target="_blank" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-file-pdf me-1"></i>Download PDF
                        </a>
                    @endif
                    <a href="{{ route('admin.publications.edit', $publication) }}" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit Publication
                    </a>
                    <form method="POST" action="{{ route('admin.publications.destroy', $publication) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm-delete>
                            <i class="bi bi-trash me-1"></i>Delete Publication
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Publication Info -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Publication Information
                </h5>

                <div class="mb-3">
                    <label class="form-label fw-bold">Type</label>
                    <div>
                        <span class="badge bg-light text-dark fs-6">
                            {{ ucwords(str_replace('_', ' ', $publication->type)) }}
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <div>
                        @if($publication->status === 'published')
                            <span class="badge bg-success fs-6">Published</span>
                        @elseif($publication->status === 'accepted')
                            <span class="badge bg-info fs-6">Accepted</span>
                        @elseif($publication->status === 'under_review')
                            <span class="badge bg-warning fs-6">Under Review</span>
                        @elseif($publication->status === 'in_preparation')
                            <span class="badge bg-secondary fs-6">In Preparation</span>
                        @else
                            <span class="badge bg-light text-dark fs-6">{{ ucfirst($publication->status) }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Published</label>
                    <div>
                        @if($publication->is_published)
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-secondary">Draft</span>
                        @endif
                    </div>
                </div>

                @if($publication->is_featured)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Featured</label>
                        <div>
                            <span class="badge bg-warning">Featured Publication</span>
                        </div>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fw-bold">Created</label>
                    <div>{{ $publication->created_at->format('F d, Y \a\t g:i A') }}</div>
                </div>

                <div>
                    <label class="form-label fw-bold">Last Updated</label>
                    <div>{{ $publication->updated_at->format('F d, Y \a\t g:i A') }}</div>
                </div>
            </div>
        </div>

        <!-- Files and Links -->
        @if($publication->pdf_file || $publication->url || $publication->doi)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-link text-primary me-2"></i>
                        Files & Links
                    </h5>

                    @if($publication->pdf_file)
                        <div class="mb-2">
                            <a href="{{ Storage::url($publication->pdf_file) }}" target="_blank" class="btn btn-outline-success btn-sm w-100">
                                <i class="bi bi-file-pdf me-1"></i>Download PDF
                            </a>
                        </div>
                    @endif

                    @if($publication->url)
                        <div class="mb-2">
                            <a href="{{ $publication->url }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-link-45deg me-1"></i>View Publication
                            </a>
                        </div>
                    @endif

                    @if($publication->doi)
                        <div class="mb-2">
                            <a href="https://doi.org/{{ $publication->doi }}" target="_blank" class="btn btn-outline-info btn-sm w-100">
                                <i class="bi bi-search me-1"></i>View DOI
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Statistics (if this is a public publication) -->
        @if($publication->is_published)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-bar-chart text-primary me-2"></i>
                        Public Visibility
                    </h5>
                    <div class="text-center">
                        <p class="text-muted mb-2">This publication is visible to the public</p>
                        <a href="{{ url('/publications/' . Str::slug($publication->title)) }}" target="_blank" class="btn btn-outline-info btn-sm">
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

    #citation-text {
        line-height: 1.5;
        word-break: break-word;
    }
</style>
@endsection

@section('scripts')
<script>
    function copyCitation() {
        const citationText = document.getElementById('citation-text').textContent;

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(citationText).then(function() {
                showCopyFeedback();
            }).catch(function(err) {
                console.error('Failed to copy: ', err);
                fallbackCopyTextToClipboard(citationText);
            });
        } else {
            fallbackCopyTextToClipboard(citationText);
        }
    }

    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;

        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showCopyFeedback();
            }
        } catch (err) {
            console.error('Failed to copy: ', err);
        }

        document.body.removeChild(textArea);
    }

    function showCopyFeedback() {
        const button = document.querySelector('button[onclick="copyCitation()"]');
        const originalText = button.innerHTML;

        button.innerHTML = '<i class="bi bi-check me-1"></i>Copied!';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');

        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
    }
</script>
@endsection