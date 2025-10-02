@extends('layouts.admin')

@section('page-title', 'Contact Messages')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Contact Messages</h1>
        <p class="text-muted mb-0">Manage incoming contact form submissions</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#bulkActionModal">
            <i class="bi bi-list-check me-1"></i>Bulk Actions
        </button>
        <div class="btn-group" role="group">
            <button class="btn btn-outline-primary" onclick="markAllAsRead()">
                <i class="bi bi-eye me-1"></i>Mark All Read
            </button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary">{{ $statusCounts['unread'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Unread</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success">{{ $statusCounts['read'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Read</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-info">{{ $statusCounts['replied'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Replied</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-danger">{{ $statusCounts['spam'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Spam</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.contact.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text"
                       class="form-control"
                       id="search"
                       name="search"
                       placeholder="Search by name, email, or subject..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                    <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Replied</option>
                    <option value="spam" {{ request('status') === 'spam' ? 'selected' : '' }}>Spam</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="date_range" class="form-label">Date Range</label>
                <select class="form-select" id="date_range" name="date_range">
                    <option value="">All Time</option>
                    <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('date_range') === 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('date_range') === 'month' ? 'selected' : '' }}>This Month</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ route('admin.contact.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Messages Table -->
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                Messages ({{ $messages->total() }})
            </h5>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="selectAll">
                <label class="form-check-label" for="selectAll">
                    Select All
                </label>
            </div>
        </div>
    </div>

    @if($messages->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" id="selectAllHeader"></th>
                        <th>Contact Details</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $message)
                        <tr class="{{ $message->status === 'unread' ? 'table-warning' : '' }}">
                            <td>
                                <input type="checkbox" class="message-checkbox" value="{{ $message->id }}">
                            </td>
                            <td>
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="{{ route('admin.contact.show', $message) }}" class="text-decoration-none">
                                                {{ $message->name }}
                                            </a>
                                            @if($message->status === 'unread')
                                                <span class="badge bg-primary ms-1">New</span>
                                            @endif
                                        </h6>
                                        <p class="text-muted small mb-1">
                                            <i class="bi bi-envelope me-1"></i>{{ $message->email }}
                                        </p>
                                        <p class="text-muted small mb-0">
                                            {{ Str::limit($message->message, 80) }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $message->subject }}</span>
                            </td>
                            <td>
                                @if($message->status === 'unread')
                                    <span class="badge bg-warning">Unread</span>
                                @elseif($message->status === 'read')
                                    <span class="badge bg-success">Read</span>
                                @elseif($message->status === 'replied')
                                    <span class="badge bg-info">Replied</span>
                                @else
                                    <span class="badge bg-danger">Spam</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $message->created_at->format('M j, Y') }}<br>
                                    {{ $message->created_at->format('g:i A') }}
                                </small>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.contact.show', $message) }}">
                                                <i class="bi bi-eye me-2"></i>View
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        @if($message->status === 'unread')
                                            <li>
                                                <button class="dropdown-item" onclick="updateStatus({{ $message->id }}, 'read')">
                                                    <i class="bi bi-check me-2"></i>Mark as Read
                                                </button>
                                            </li>
                                        @endif
                                        @if($message->status !== 'replied')
                                            <li>
                                                <button class="dropdown-item" onclick="updateStatus({{ $message->id }}, 'replied')">
                                                    <i class="bi bi-reply me-2"></i>Mark as Replied
                                                </button>
                                            </li>
                                        @endif
                                        @if($message->status !== 'spam')
                                            <li>
                                                <button class="dropdown-item text-warning" onclick="updateStatus({{ $message->id }}, 'spam')">
                                                    <i class="bi bi-exclamation-triangle me-2"></i>Mark as Spam
                                                </button>
                                            </li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('admin.contact.destroy', $message) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" data-confirm-delete>
                                                    <i class="bi bi-trash me-2"></i>Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Showing {{ $messages->firstItem() }} to {{ $messages->lastItem() }} of {{ $messages->total() }} results
                    </small>
                    {{ $messages->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    @else
        <div class="card-body text-center py-5">
            <i class="bi bi-envelope text-muted mb-3" style="font-size: 3rem;"></i>
            @if(request()->hasAny(['search', 'status', 'date_range']))
                <h5 class="text-muted">No messages found</h5>
                <p class="text-muted mb-3">No messages match your current filters.</p>
                <a href="{{ route('admin.contact.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i>Clear Filters
                </a>
            @else
                <h5 class="text-muted">No messages yet</h5>
                <p class="text-muted mb-0">Contact form submissions will appear here.</p>
            @endif
        </div>
    @endif
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkActionForm">
                    @csrf
                    <div class="mb-3">
                        <label for="bulk_action" class="form-label">Select Action</label>
                        <select class="form-select" id="bulk_action" name="action" required>
                            <option value="">Choose an action...</option>
                            <option value="mark_read">Mark as Read</option>
                            <option value="mark_replied">Mark as Replied</option>
                            <option value="mark_spam">Mark as Spam</option>
                            <option value="delete" class="text-danger">Delete Messages</option>
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        This action will be applied to <span id="selectedCount">0</span> selected message(s).
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="executeBulkAction()">Execute Action</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select All functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        const selectAllHeader = document.getElementById('selectAllHeader');
        const messageCheckboxes = document.querySelectorAll('.message-checkbox');

        function updateSelectAll() {
            const checkedCount = document.querySelectorAll('.message-checkbox:checked').length;
            const totalCount = messageCheckboxes.length;

            selectAllCheckbox.checked = checkedCount === totalCount;
            selectAllHeader.checked = checkedCount === totalCount;

            // Update bulk action button
            document.getElementById('selectedCount').textContent = checkedCount;
        }

        [selectAllCheckbox, selectAllHeader].forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                messageCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateSelectAll();
            });
        });

        messageCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectAll);
        });

        // Auto-submit filters
        const statusSelect = document.getElementById('status');
        const dateRangeSelect = document.getElementById('date_range');

        [statusSelect, dateRangeSelect].forEach(select => {
            if (select) {
                select.addEventListener('change', function() {
                    this.form.submit();
                });
            }
        });
    });

    function updateStatus(messageId, status) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/contact/${messageId}/status`;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
        }

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);

        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);

        document.body.appendChild(form);
        form.submit();
    }

    function markAllAsRead() {
        if (confirm('Are you sure you want to mark all messages as read?')) {
            // Implementation would depend on backend route
            window.location.href = '{{ route("admin.contact.index") }}?mark_all_read=1';
        }
    }

    function executeBulkAction() {
        const action = document.getElementById('bulk_action').value;
        const selectedIds = Array.from(document.querySelectorAll('.message-checkbox:checked')).map(cb => cb.value);

        if (!action || selectedIds.length === 0) {
            alert('Please select an action and at least one message.');
            return;
        }

        const confirmMessage = action === 'delete'
            ? 'Are you sure you want to delete the selected messages? This action cannot be undone.'
            : `Are you sure you want to ${action.replace('_', ' ')} ${selectedIds.length} message(s)?`;

        if (!confirm(confirmMessage)) {
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';

        if (action === 'delete') {
            form.action = '{{ route("admin.contact.bulk-delete") }}';
        } else {
            form.action = '{{ route("admin.contact.bulk-status") }}';
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
        }

        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'message_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        if (action !== 'delete') {
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = action.replace('mark_', '');
            form.appendChild(statusInput);
        }

        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection