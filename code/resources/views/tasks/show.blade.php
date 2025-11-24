@extends('layouts.sidebar')

@section('title', $task->title)
@section('page-title', $task->title)

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('app.tasks.title') }}</h5>
                <div>
                    <div class="dropdown">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="taskActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear me-1"></i>
                            {{ __('app.actions') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="taskActionsDropdown">
                            @can('update', $task)
                                <li>
                                    <a href="{{ route('tasks.edit', $task) }}" class="dropdown-item">
                                        <i class="bi bi-pencil me-2 text-primary"></i>
                                        {{ __('app.edit') }}
                                    </a>
                                </li>
                                @if($task->status === 'pending')
                                    <li>
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#startTaskModal">
                                            <i class="bi bi-play me-2 text-warning"></i>
                                            {{ __('app.tasks.start_task') }}
                                        </button>
                                    </li>
                                @endif

                                @if($task->status === 'in_progress')
                                    <li>
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#completeTaskModal">
                                            <i class="bi bi-check-circle me-2 text-success"></i>
                                            {{ __('app.tasks.mark_complete') }}
                                        </button>
                                    </li>
                                @endif

                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changePriorityModal">
                                        <i class="bi bi-exclamation-circle me-2 text-info"></i>
                                        {{ __('app.tasks.change_priority') }}
                                    </button>
                                </li>

                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                                        <i class="bi bi-arrow-repeat me-2 text-secondary"></i>
                                        {{ __('app.tasks.change_status') }}
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endcan

                            <li>
                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addCommentModal">
                                    <i class="bi bi-chat-plus me-2 text-primary"></i>
                                    {{ __('app.tasks.add_comment') }}
                                </button>
                            </li>

                            <li>
                                <a href="{{ route('timesheet.create', ['task_id' => $task->id]) }}" class="dropdown-item">
                                    <i class="bi bi-clock me-2 text-success"></i>
                                    {{ __('app.time.log_time') }}
                                </a>
                            </li>

                            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addInterventionModal">
                                        <i class="bi bi-megaphone me-2 text-warning"></i>
                                        {{ __('app.tasks.add_intervention') }}
                                    </button>
                                </li>
                            @endif

                            @can('delete', $task)
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="bi bi-trash me-2"></i>
                                        {{ __('app.delete') }}
                                    </button>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Task Details Table -->
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ __('app.status') }}</th>
                <th>{{ __('app.tasks.priority') }}</th>
                <th>{{ __('app.tasks.assigned_to') }}</th>
                <th>{{ __('app.tasks.due_date') }}</th>
                <th>{{ __('app.description') }}</th>
                <th>{{ __('app.tasks.project') }}</th>
                <th>{{ __('app.time.total_time') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <span class="badge bg-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'warning' : 'secondary') }} fs-6">
                        @switch($task->status)
                            @case('pending') {{ __('app.tasks.pending') }} @break
                            @case('in_progress') {{ __('app.tasks.in_progress') }} @break
                            @case('completed') {{ __('app.tasks.completed') }} @break
                            @case('cancelled') {{ __('app.tasks.cancelled') }} @break
                            @default {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        @endswitch
                    </span>
                </td>
                <td>
                    <span class="badge bg-{{ $task->priority === 'urgent' ? 'danger' : ($task->priority === 'high' ? 'warning' : ($task->priority === 'medium' ? 'info' : 'secondary')) }} fs-6">
                        @switch($task->priority)
                            @case('low') {{ __('app.tasks.low') }} @break
                            @case('medium') {{ __('app.tasks.medium') }} @break
                            @case('high') {{ __('app.tasks.high') }} @break
                            @case('urgent') {{ __('app.tasks.urgent') }} @break
                            @default {{ ucfirst($task->priority) }}
                        @endswitch
                    </span>
                </td>
                <td>
                    @if($task->assignedUser)
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2" style="width: 24px; height: 24px; font-size: 0.8rem; display:inline-block;">
                            {{ substr($task->assignedUser->name, 0, 1) }}
                        </div>
                        <span class="fw-bold">{{ $task->assignedUser->name }}</span>
                    @else
                        <span class="text-muted">{{ __('app.unassigned') }}</span>
                    @endif
                </td>
                <td>
                    @php
                        $dueDate = $task->due_date ? (is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date) : null;
                        $isOverdue = $dueDate && $dueDate->isPast() && $task->status !== 'completed';
                        $isDueToday = $dueDate && $dueDate->isToday();
                    @endphp
                    @if($dueDate)
                        <span class="fw-bold {{ $isOverdue ? 'text-danger' : ($isDueToday ? 'text-warning' : '') }}">
                            {{ $dueDate->format('M d, Y') }}
                        </span>
                        @if($isOverdue)
                            <span class="badge bg-danger ms-1">{{ __('app.tasks.overdue') }}</span>
                        @elseif($isDueToday)
                            <span class="badge bg-warning ms-1">{{ __('app.tasks.due_today') }}</span>
                        @endif
                    @else
                        <span class="text-muted">{{ __('app.tasks.no_due_date') }}</span>
                    @endif
                </td>
                <td>
                    @if($task->description)
                        <p class="text-muted mb-0">{{ $task->description }}</p>
                    @else
                        <span class="text-muted">{{ __('app.tasks.no_description') }}</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('projects.show', $task->project) }}" class="d-flex align-items-center text-decoration-none text-dark">
                        <div class="bg-success rounded p-2 me-2 text-white">
                            <i class="bi bi-folder"></i>
                        </div>
                        <div>
                            <div class="fw-bold">{{ $task->project->title }}</div>
                            <small class="text-muted">{{ __('app.projects.view_details') }}</small>
                        </div>
                    </a>
                </td>
                <td class="d-flex align-items-center">
                    <i class="bi bi-clock me-2 text-primary"></i>
                    <span class="fw-bold fs-5">{{ number_format($task->timeEntries->sum('duration_hours') ?? 0, 1) }}h</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
            </div>
        </div>

        <!-- Task Interventions & Comments -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-chat-text me-2"></i>
                    {{ __('app.tasks.interventions_comments') }}
                </h5>
                <div class="btn-group btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="noteFilter" id="filterAll" autocomplete="off" checked>
                    <label class="btn btn-outline-primary" for="filterAll">{{ __('app.all') }}</label>

                    <input type="radio" class="btn-check" name="noteFilter" id="filterComments" autocomplete="off">
                    <label class="btn btn-outline-primary" for="filterComments">{{ __('app.notes.comments') }}</label>

                    <input type="radio" class="btn-check" name="noteFilter" id="filterStatus" autocomplete="off">
                    <label class="btn btn-outline-primary" for="filterStatus">{{ __('app.notes.status_changes') }}</label>

                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                        <input type="radio" class="btn-check" name="noteFilter" id="filterInternal" autocomplete="off">
                        <label class="btn btn-outline-primary" for="filterInternal">{{ __('app.notes.internal') }}</label>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($task->notes && $task->notes->count() > 0)
                    <div id="notesList" class="mb-4">
                        @foreach($task->notes as $note)
                            <div class="note-item border rounded p-3 mb-3"
                                 data-type="{{ $note->type }}"
                                 data-internal="{{ $note->is_internal ? 'true' : 'false' }}">

                                <!-- Note Header -->
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi {{ $note->icon ?? 'bi-chat-text' }} me-2 text-{{ $note->type === 'intervention' ? 'warning' : 'muted' }}"></i>
                                            <strong class="{{ $note->user->isAdmin() ? 'text-danger' : ($note->user->isManager() ? 'text-warning' : '') }}">
                                                {{ $note->user->name }}
                                                @if($note->user->isAdmin())
                                                    <span class="badge bg-danger ms-1">{{ __('app.roles.admin') }}</span>
                                                @elseif($note->user->isManager())
                                                    <span class="badge bg-warning ms-1">{{ __('app.roles.manager') }}</span>
                                                @endif
                                            </strong>
                                            <small class="text-muted ms-2">{{ $note->created_at->diffForHumans() }}</small>
                                            @if($note->is_internal ?? false)
                                                <span class="badge bg-secondary ms-2">
                                                    <i class="bi bi-eye-slash me-1"></i>{{ __('app.notes.internal') }}
                                                </span>
                                            @endif
                                            <span class="badge bg-light text-dark ms-2">
                                                @switch($note->type ?? 'comment')
                                                    @case('comment') {{ __('app.notes.comment') }} @break
                                                    @case('status_change') {{ __('app.notes.status_change') }} @break
                                                    @case('intervention') {{ __('app.notes.intervention') }} @break
                                                    @case('attachment') {{ __('app.notes.attachment') }} @break
                                                    @default {{ ucfirst($note->type ?? 'comment') }}
                                                @endswitch
                                            </span>
                                        </div>

                                        <!-- Note Content -->
                                        <div id="note-content-{{ $note->id }}">
                                            @if($note->content)
                                                <div class="note-content mb-2">
                                                    {!! nl2br(e($note->content)) !!}
                                                </div>
                                            @endif

                                            <!-- Attachments -->
                                            @if($note->hasAttachments())
                                                <div class="note-attachments mt-2">
                                                    <div class="row g-2">
                                                        @foreach($note->image_attachments as $attachment)
                                                            <div class="col-md-3">
                                                                <div class="attachment-item">
                                                                    <a href="{{ $attachment['versions']['original']['url'] ?? '#' }}"
                                                                       target="_blank"
                                                                       data-bs-toggle="modal"
                                                                       data-bs-target="#imageModal{{ $loop->parent->index }}_{{ $loop->index }}">
                                                                        <img src="{{ $attachment['versions']['thumbnail']['url'] ?? '#' }}"
                                                                             class="img-thumbnail w-100"
                                                                             style="height: 80px; object-fit: cover;"
                                                                             alt="{{ $attachment['original_name'] ?? 'Attachment' }}">
                                                                    </a>
                                                                    <small class="text-muted d-block mt-1">
                                                                        {{ $attachment['original_name'] ?? 'Image' }}
                                                                    </small>
                                                                </div>

                                                                <!-- Image Modal -->
                                                                <div class="modal fade" id="imageModal{{ $loop->parent->index }}_{{ $loop->index }}" tabindex="-1">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">{{ $attachment['original_name'] ?? 'Image' }}</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                            </div>
                                                                            <div class="modal-body text-center">
                                                                                <img src="{{ $attachment['versions']['large']['url'] ?? $attachment['versions']['original']['url'] }}"
                                                                                     class="img-fluid"
                                                                                     alt="{{ $attachment['original_name'] ?? 'Attachment' }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Edit Form (Hidden by default) -->
                                        <div id="note-edit-form-{{ $note->id }}" style="display: none;">
                                            <form method="POST" action="{{ route('tasks.notes.update', $note) }}">
                                                @csrf
                                                @method('PUT')
                                                <textarea class="form-control mb-2" name="content" rows="3">{{ $note->content }}</textarea>
                                                <button type="submit" class="btn btn-sm btn-primary">{{ __('app.update') }}</button>
                                                <button type="button" class="btn btn-sm btn-secondary ms-1" onclick="toggleEdit({{ $note->id }})">{{ __('app.cancel') }}</button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Note Actions -->
                                    <div class="ms-3">
                                        @if($note->canBeEditedBy(auth()->user()))
                                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" onclick="toggleEdit({{ $note->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        @endif
                                        @if($note->canBeDeletedBy(auth()->user()))
                                            <form method="POST" action="{{ route('tasks.notes.destroy', $note) }}" style="display: inline;" onsubmit="return confirm('{{ __('app.messages.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-chat-text fs-1 mb-3"></i>
                        <p>{{ __('app.notes.no_comments_yet') }}</p>
                    </div>
                @endif

                <!-- Add New Comment/Intervention Form -->
                @if($task->canBeViewedBy(auth()->user()))
                    <div class="card bg-light">
                        <div class="card-body">
                            <form method="POST" action="{{ route('tasks.notes.store', $task) }}" enctype="multipart/form-data" id="commentForm">
                                @csrf

                                <!-- Comment Type Selection -->
                                @can('createIntervention', $task)
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('app.notes.type') }}</label>
                                            <select class="form-select" name="type" id="noteType">
                                                <option value="comment">{{ __('app.notes.comment') }}</option>
                                                <option value="intervention">{{ __('app.notes.intervention') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" name="is_internal" id="isInternal">
                                                <label class="form-check-label" for="isInternal">
                                                    <i class="bi bi-eye-slash me-1"></i>
                                                    {{ __('app.notes.internal_note') }}
                                                    <small class="text-muted d-block">{{ __('app.notes.internal_note_help') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Comment Content -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">
                                        <span id="contentLabel">{{ __('app.comments.add_comment') }}</span>
                                    </label>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                              id="content" name="content" rows="3"
                                              placeholder="{{ __('app.notes.write_comment_placeholder') }}">{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Photo Attachments -->
                                <div class="mb-3">
                                    <label for="attachments" class="form-label">
                                        <i class="bi bi-camera me-1"></i>
                                        {{ __('app.notes.attach_photos') }}
                                    </label>
                                    <input type="file" class="form-control" id="attachments" name="attachments[]"
                                           multiple accept="image/*" onchange="previewImages(this)">
                                    <small class="text-muted">{{ __('app.notes.photo_help') }}</small>

                                    <!-- Image Preview -->
                                    <div id="imagePreview" class="mt-2"></div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send me-1"></i>
                                        <span id="submitText">{{ __('app.comments.add_comment') }}</span>
                                    </button>
                                    <small class="text-muted">
                                        {{ __('app.notes.stakeholders_notified') }}
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Time Entries -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('app.time.title') }}</h5>
                <a href="{{ route('timesheet.create', ['task_id' => $task->id]) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    {{ __('app.time.log_time') }}
                </a>
            </div>
            <div class="card-body">
                @if($task->timeEntries && $task->timeEntries->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('app.date') }}</th>
                                    <th>{{ __('app.users.title') }}</th>
                                    <th>{{ __('app.time.duration') }}</th>
                                    <th>{{ __('app.description') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($task->timeEntries as $entry)
                                    <tr>
                                        <td>
                                            @if($entry->start_time)
                                                {{ is_string($entry->start_time) ? \Carbon\Carbon::parse($entry->start_time)->format('M d, Y') : $entry->start_time->format('M d, Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $entry->user->name }}</td>
                                        <td>{{ number_format($entry->duration_hours, 1) }}h</td>
                                        <td>{{ $entry->comment ?? $entry->description ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <strong>{{ __('app.time.total_time') }}:</strong>
                        {{ number_format($task->timeEntries->sum('duration_hours'), 1) }}h
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-clock text-muted fs-2"></i>
                        <p class="text-muted mt-2">{{ __('app.time.no_entries') }}</p>
                        <a href="{{ route('timesheet.create', ['task_id' => $task->id]) }}" class="btn btn-sm btn-primary">
                            {{ __('app.time.log_time') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@can('delete', $task)
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('app.delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('app.messages.confirm_delete') }}</p>
                <p class="text-danger">
                    <strong>{{ __('app.warning') }}:</strong>
                    {{ __('app.messages.action_cannot_be_undone') }}
                </p>
                <p><strong>{{ __('app.tasks.title') }}:</strong> {{ $task->title }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                <form method="POST" action="{{ route('tasks.destroy', $task) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('app.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan

<!-- Quick Action Modals -->

<!-- Start Task Modal -->
<div class="modal fade" id="startTaskModal" tabindex="-1" aria-labelledby="startTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="startTaskModalLabel">
                    <i class="bi bi-play-circle me-2"></i>{{ __('app.tasks.start_task') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('app.tasks.confirm_start_task') }}</p>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    {{ __('app.tasks.start_task_help') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                <form method="POST" action="{{ route('tasks.update', $task) }}" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="in_progress">
                    <input type="hidden" name="title" value="{{ $task->title }}">
                    <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                    <input type="hidden" name="priority" value="{{ $task->priority }}">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-play me-1"></i>{{ __('app.tasks.start_task') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Complete Task Modal -->
<div class="modal fade" id="completeTaskModal" tabindex="-1" aria-labelledby="completeTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeTaskModalLabel">
                    <i class="bi bi-check-circle me-2"></i>{{ __('app.tasks.mark_complete') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('app.tasks.confirm_complete_task') }}</p>
                <div class="mb-3">
                    <label for="completionNote" class="form-label">{{ __('app.tasks.completion_note') }} ({{ __('app.optional') }})</label>
                    <textarea class="form-control" id="completionNote" name="completion_note" rows="3"
                              placeholder="{{ __('app.tasks.completion_note_placeholder') }}"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                <form method="POST" action="{{ route('tasks.update', $task) }}" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="completed">
                    <input type="hidden" name="title" value="{{ $task->title }}">
                    <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                    <input type="hidden" name="priority" value="{{ $task->priority }}">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>{{ __('app.tasks.mark_complete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Priority Modal -->
<div class="modal fade" id="changePriorityModal" tabindex="-1" aria-labelledby="changePriorityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePriorityModalLabel">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ __('app.tasks.change_priority') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('tasks.update', $task) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="title" value="{{ $task->title }}">
                    <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                    <input type="hidden" name="status" value="{{ $task->status }}">

                    <div class="mb-3">
                        <label for="priority" class="form-label">{{ __('app.tasks.priority') }}</label>
                        <select class="form-select" id="priority" name="priority" required>
                            <option value="low" {{ $task->priority === 'low' ? 'selected' : '' }}>
                                <span class="badge bg-secondary">{{ __('app.tasks.low') }}</span>
                            </option>
                            <option value="medium" {{ $task->priority === 'medium' ? 'selected' : '' }}>
                                {{ __('app.tasks.medium') }}
                            </option>
                            <option value="high" {{ $task->priority === 'high' ? 'selected' : '' }}>
                                {{ __('app.tasks.high') }}
                            </option>
                            <option value="urgent" {{ $task->priority === 'urgent' ? 'selected' : '' }}>
                                {{ __('app.tasks.urgent') }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="priorityReason" class="form-label">{{ __('app.tasks.priority_change_reason') }} ({{ __('app.optional') }})</label>
                        <textarea class="form-control" id="priorityReason" name="priority_reason" rows="2"
                                  placeholder="{{ __('app.tasks.priority_change_reason_placeholder') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ __('app.tasks.update_priority') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Status Modal -->
<div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeStatusModalLabel">
                    <i class="bi bi-arrow-repeat me-2"></i>{{ __('app.tasks.change_status') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('tasks.update', $task) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="title" value="{{ $task->title }}">
                    <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                    <input type="hidden" name="priority" value="{{ $task->priority }}">

                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('app.status') }}</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>
                                {{ __('app.tasks.pending') }}
                            </option>
                            <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>
                                {{ __('app.tasks.in_progress') }}
                            </option>
                            <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>
                                {{ __('app.tasks.completed') }}
                            </option>
                            <option value="cancelled" {{ $task->status === 'cancelled' ? 'selected' : '' }}>
                                {{ __('app.tasks.cancelled') }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="statusReason" class="form-label">{{ __('app.tasks.status_change_reason') }} ({{ __('app.optional') }})</label>
                        <textarea class="form-control" id="statusReason" name="status_reason" rows="2"
                                  placeholder="{{ __('app.tasks.status_change_reason_placeholder') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat me-1"></i>{{ __('app.tasks.update_status') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Comment Modal -->
<div class="modal fade" id="addCommentModal" tabindex="-1" aria-labelledby="addCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCommentModalLabel">
                    <i class="bi bi-chat-plus me-2"></i>{{ __('app.tasks.add_comment') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('tasks.notes.store', $task) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="hidden" name="type" value="comment">

                    <div class="mb-3">
                        <label for="commentContent" class="form-label">{{ __('app.tasks.comment') }}</label>
                        <textarea class="form-control" id="commentContent" name="content" rows="4" required
                                  placeholder="{{ __('app.tasks.comment_placeholder') }}"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="commentAttachments" class="form-label">{{ __('app.tasks.attach_files') }}</label>
                        <input type="file" class="form-control" id="commentAttachments" name="attachments[]" multiple
                               accept="image/*">
                        <div class="form-text">{{ __('app.tasks.attach_files_help') }}</div>
                        <div id="commentPreview" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-chat-plus me-1"></i>{{ __('app.tasks.post_comment') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Intervention Modal -->
@if(auth()->user()->isAdmin() || auth()->user()->isManager())
<div class="modal fade" id="addInterventionModal" tabindex="-1" aria-labelledby="addInterventionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addInterventionModalLabel">
                    <i class="bi bi-megaphone me-2"></i>{{ __('app.tasks.add_intervention') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('tasks.notes.store', $task) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="hidden" name="type" value="intervention">

                    <div class="mb-3">
                        <label for="interventionContent" class="form-label">{{ __('app.tasks.intervention') }}</label>
                        <textarea class="form-control" id="interventionContent" name="content" rows="4" required
                                  placeholder="{{ __('app.tasks.intervention_placeholder') }}"></textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isInternal" name="is_internal" value="1">
                            <label class="form-check-label" for="isInternal">
                                {{ __('app.tasks.internal_note') }}
                            </label>
                            <div class="form-text">{{ __('app.tasks.internal_note_help') }}</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="interventionAttachments" class="form-label">{{ __('app.tasks.attach_files') }}</label>
                        <input type="file" class="form-control" id="interventionAttachments" name="attachments[]" multiple
                               accept="image/*">
                        <div class="form-text">{{ __('app.tasks.attach_files_help') }}</div>
                        <div id="interventionPreview" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-megaphone me-1"></i>{{ __('app.tasks.post_intervention') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
// Modal file preview functionality
document.addEventListener('DOMContentLoaded', function() {
    // Handle file preview for comment modal
    const commentAttachments = document.getElementById('commentAttachments');
    if (commentAttachments) {
        commentAttachments.addEventListener('change', function() {
            handleFilePreview(this, 'commentPreview');
        });
    }

    // Handle file preview for intervention modal
    const interventionAttachments = document.getElementById('interventionAttachments');
    if (interventionAttachments) {
        interventionAttachments.addEventListener('change', function() {
            handleFilePreview(this, 'interventionPreview');
        });
    }
});

function handleFilePreview(input, previewId) {
    const previewContainer = document.getElementById(previewId);
    previewContainer.innerHTML = '';

    if (input.files && input.files.length > 0) {
        Array.from(input.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageContainer = document.createElement('div');
                    imageContainer.className = 'position-relative d-inline-block me-2 mb-2';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail';
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle p-1';
                    removeBtn.style.width = '20px';
                    removeBtn.style.height = '20px';
                    removeBtn.style.fontSize = '10px';
                    removeBtn.style.transform = 'translate(50%, -50%)';
                    removeBtn.innerHTML = '<i class="bi bi-x"></i>';
                    removeBtn.onclick = function() {
                        imageContainer.remove();
                        // Remove file from input
                        const dt = new DataTransfer();
                        Array.from(input.files).forEach((f, i) => {
                            if (i !== index) dt.items.add(f);
                        });
                        input.files = dt.files;
                    };

                    imageContainer.appendChild(img);
                    imageContainer.appendChild(removeBtn);
                    previewContainer.appendChild(imageContainer);
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

// Note editing functionality
function toggleEdit(noteId) {
    const contentDiv = document.getElementById('note-content-' + noteId);
    const editForm = document.getElementById('note-edit-form-' + noteId);

    if (contentDiv.style.display === 'none') {
        contentDiv.style.display = 'block';
        editForm.style.display = 'none';
    } else {
        contentDiv.style.display = 'none';
        editForm.style.display = 'block';
    }
}

// Note filtering functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('input[name="noteFilter"]');
    const noteItems = document.querySelectorAll('.note-item');

    filterButtons.forEach(button => {
        button.addEventListener('change', function() {
            const filterType = this.id.replace('filter', '').toLowerCase();

            noteItems.forEach(item => {
                const noteType = item.dataset.type;
                const isInternal = item.dataset.internal === 'true';

                let shouldShow = false;

                switch(filterType) {
                    case 'all':
                        shouldShow = true;
                        break;
                    case 'comments':
                        shouldShow = noteType === 'comment';
                        break;
                    case 'status':
                        shouldShow = noteType === 'status_change';
                        break;
                    case 'internal':
                        shouldShow = isInternal;
                        break;
                }

                item.style.display = shouldShow ? 'block' : 'none';
            });
        });
    });

    // Note type selection changes
    const noteTypeSelect = document.getElementById('noteType');
    const contentLabel = document.getElementById('contentLabel');
    const submitText = document.getElementById('submitText');

    if (noteTypeSelect) {
        noteTypeSelect.addEventListener('change', function() {
            const selectedType = this.value;

            if (selectedType === 'intervention') {
                contentLabel.textContent = '{{ __("app.notes.add_intervention") }}';
                submitText.textContent = '{{ __("app.notes.submit_intervention") }}';
            } else {
                contentLabel.textContent = '{{ __("app.comments.add_comment") }}';
                submitText.textContent = '{{ __("app.comments.add_comment") }}';
            }
        });
    }
});

// Image preview functionality
function previewImages(input) {
    const previewContainer = document.getElementById('imagePreview');
    previewContainer.innerHTML = '';

    if (input.files) {
        Array.from(input.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imageContainer = document.createElement('div');
                    imageContainer.className = 'position-relative d-inline-block me-2 mb-2';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail';
                    img.style.width = '80px';
                    img.style.height = '80px';
                    img.style.objectFit = 'cover';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle';
                    removeBtn.style.transform = 'translate(50%, -50%)';
                    removeBtn.innerHTML = '<i class="bi bi-x"></i>';
                    removeBtn.onclick = function() {
                        imageContainer.remove();
                        // Remove file from input (requires recreating the input)
                        const dt = new DataTransfer();
                        Array.from(input.files).forEach((f, i) => {
                            if (i !== index) dt.items.add(f);
                        });
                        input.files = dt.files;
                    };

                    imageContainer.appendChild(img);
                    imageContainer.appendChild(removeBtn);
                    previewContainer.appendChild(imageContainer);
                };

                reader.readAsDataURL(file);
            }
        });
    }
}

// Status update functionality (if quick status change is added)
function updateTaskStatus(taskId, newStatus) {
    fetch(`/tasks/${taskId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh page or update UI
            location.reload();
        } else {
            alert('Failed to update status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}
</script>
@endpush

@endsection