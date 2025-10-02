@extends('layouts.admin')

@section('page-title', 'Create Publication')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Add New Publication</h1>
        <p class="text-muted mb-0">Add a research paper, article, or academic publication to your portfolio</p>
    </div>
    <a href="{{ route('admin.publications.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Publications
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.publications.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <!-- Publication Title -->
                        <div class="col-12">
                            <label for="title" class="form-label">Publication Title <span class="text-danger">*</span></label>
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

                        <!-- Authors -->
                        <div class="col-12">
                            <label for="authors" class="form-label">Authors <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('authors') is-invalid @enderror"
                                      id="authors"
                                      name="authors"
                                      rows="2"
                                      required>{{ old('authors') }}</textarea>
                            @error('authors')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">List all authors in proper citation format (e.g., "Smith, J., Doe, A., & Johnson, M.")</div>
                        </div>

                        <!-- Type and Status -->
                        <div class="col-md-6">
                            <label for="type" class="form-label">Publication Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="journal" {{ old('type') === 'journal' ? 'selected' : '' }}>Journal Article</option>
                                <option value="conference" {{ old('type') === 'conference' ? 'selected' : '' }}>Conference Paper</option>
                                <option value="book" {{ old('type') === 'book' ? 'selected' : '' }}>Book</option>
                                <option value="book_chapter" {{ old('type') === 'book_chapter' ? 'selected' : '' }}>Book Chapter</option>
                                <option value="thesis" {{ old('type') === 'thesis' ? 'selected' : '' }}>Thesis</option>
                                <option value="report" {{ old('type') === 'report' ? 'selected' : '' }}>Report</option>
                                <option value="preprint" {{ old('type') === 'preprint' ? 'selected' : '' }}>Preprint</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Publication Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="accepted" {{ old('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="under_review" {{ old('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="in_preparation" {{ old('status') === 'in_preparation' ? 'selected' : '' }}>In Preparation</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Journal/Venue Information -->
                        <div class="col-md-6">
                            <label for="journal_name" class="form-label">Journal/Conference Name</label>
                            <input type="text"
                                   class="form-control @error('journal_name') is-invalid @enderror"
                                   id="journal_name"
                                   name="journal_name"
                                   value="{{ old('journal_name') }}">
                            @error('journal_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="venue" class="form-label">Venue/Publisher</label>
                            <input type="text"
                                   class="form-control @error('venue') is-invalid @enderror"
                                   id="venue"
                                   name="venue"
                                   value="{{ old('venue') }}">
                            @error('venue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Publication Details -->
                        <div class="col-md-4">
                            <label for="volume" class="form-label">Volume</label>
                            <input type="text"
                                   class="form-control @error('volume') is-invalid @enderror"
                                   id="volume"
                                   name="volume"
                                   value="{{ old('volume') }}">
                            @error('volume')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="issue" class="form-label">Issue</label>
                            <input type="text"
                                   class="form-control @error('issue') is-invalid @enderror"
                                   id="issue"
                                   name="issue"
                                   value="{{ old('issue') }}">
                            @error('issue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="pages" class="form-label">Pages</label>
                            <input type="text"
                                   class="form-control @error('pages') is-invalid @enderror"
                                   id="pages"
                                   name="pages"
                                   value="{{ old('pages') }}"
                                   placeholder="e.g., 123-145">
                            @error('pages')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Publication Date -->
                        <div class="col-md-6">
                            <label for="publication_date" class="form-label">Publication Date</label>
                            <input type="date"
                                   class="form-control @error('publication_date') is-invalid @enderror"
                                   id="publication_date"
                                   name="publication_date"
                                   value="{{ old('publication_date') }}">
                            @error('publication_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="year" class="form-label">Publication Year</label>
                            <input type="number"
                                   class="form-control @error('year') is-invalid @enderror"
                                   id="year"
                                   name="year"
                                   min="1900"
                                   max="{{ date('Y') + 5 }}"
                                   value="{{ old('year', date('Y')) }}">
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DOI and URLs -->
                        <div class="col-md-6">
                            <label for="doi" class="form-label">DOI</label>
                            <input type="text"
                                   class="form-control @error('doi') is-invalid @enderror"
                                   id="doi"
                                   name="doi"
                                   value="{{ old('doi') }}"
                                   placeholder="e.g., 10.1000/182">
                            @error('doi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="url" class="form-label">Publication URL</label>
                            <input type="url"
                                   class="form-control @error('url') is-invalid @enderror"
                                   id="url"
                                   name="url"
                                   value="{{ old('url') }}">
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- PDF File -->
                        <div class="col-12">
                            <label for="pdf_file" class="form-label">PDF File</label>
                            <input type="file"
                                   class="form-control @error('pdf_file') is-invalid @enderror"
                                   id="pdf_file"
                                   name="pdf_file"
                                   accept=".pdf">
                            @error('pdf_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload the publication PDF file (max 20MB)</div>
                        </div>

                        <!-- Abstract -->
                        <div class="col-12">
                            <label for="abstract" class="form-label">Abstract</label>
                            <textarea class="form-control @error('abstract') is-invalid @enderror"
                                      id="abstract"
                                      name="abstract"
                                      rows="6">{{ old('abstract') }}</textarea>
                            @error('abstract')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Publication abstract or summary</div>
                        </div>

                        <!-- Keywords -->
                        <div class="col-12">
                            <label for="keywords" class="form-label">Keywords</label>
                            <textarea class="form-control @error('keywords') is-invalid @enderror"
                                      id="keywords"
                                      name="keywords"
                                      rows="2">{{ old('keywords') }}</textarea>
                            @error('keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Comma-separated keywords or research topics</div>
                        </div>

                        <!-- Notes -->
                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes"
                                      name="notes"
                                      rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Additional notes or comments about this publication</div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between pt-3">
                                <a href="{{ route('admin.publications.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Create Publication
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Visibility Settings -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-eye text-primary me-2"></i>
                    Visibility
                </h5>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_published" id="published" value="1" {{ old('is_published', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="published">
                            <strong>Published</strong>
                            <small class="text-muted d-block">Visible to all visitors</small>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_published" id="draft" value="0" {{ old('is_published') == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="draft">
                            <strong>Draft</strong>
                            <small class="text-muted d-block">Only visible to you</small>
                        </label>
                    </div>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_featured">
                        <strong>Featured Publication</strong>
                        <small class="text-muted d-block">Highlight this publication</small>
                    </label>
                </div>
            </div>
        </div>

        <!-- Citation Preview -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-quote text-primary me-2"></i>
                    Citation Preview
                </h5>
                <div id="citation-preview" class="small text-muted">
                    <em>Citation will appear here as you fill in the form</em>
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
                        Include the DOI for easier reference and citation
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Write a comprehensive abstract to attract readers
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Use relevant keywords to improve discoverability
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Upload the PDF for easy access by visitors
                    </li>
                    <li>
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Ensure author names follow proper academic formatting
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
        // Auto-generate year from publication date
        const publicationDateInput = document.getElementById('publication_date');
        const yearInput = document.getElementById('year');

        publicationDateInput.addEventListener('change', function() {
            if (this.value) {
                const date = new Date(this.value);
                yearInput.value = date.getFullYear();
                updateCitationPreview();
            }
        });

        // Citation preview update
        function updateCitationPreview() {
            const title = document.getElementById('title').value;
            const authors = document.getElementById('authors').value;
            const year = document.getElementById('year').value;
            const journal = document.getElementById('journal_name').value;
            const volume = document.getElementById('volume').value;
            const issue = document.getElementById('issue').value;
            const pages = document.getElementById('pages').value;
            const doi = document.getElementById('doi').value;

            let citation = '';

            if (authors) citation += authors;
            if (year) citation += ` (${year}).`;
            if (title) citation += ` ${title}.`;
            if (journal) citation += ` <em>${journal}</em>`;
            if (volume) citation += `, ${volume}`;
            if (issue) citation += `(${issue})`;
            if (pages) citation += `, ${pages}`;
            if (doi) citation += `. https://doi.org/${doi}`;

            document.getElementById('citation-preview').innerHTML = citation || '<em>Citation will appear here as you fill in the form</em>';
        }

        // Add event listeners for real-time preview
        ['title', 'authors', 'year', 'journal_name', 'volume', 'issue', 'pages', 'doi'].forEach(function(id) {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('input', updateCitationPreview);
            }
        });

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

        // File size validation
        const pdfFileInput = document.getElementById('pdf_file');
        pdfFileInput.addEventListener('change', function() {
            if (this.files[0]) {
                const fileSize = this.files[0].size / 1024 / 1024; // Size in MB
                if (fileSize > 20) {
                    this.setCustomValidity('File size must be less than 20MB');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                }
            }
        });

        // Initialize citation preview
        updateCitationPreview();
    });
</script>
@endsection