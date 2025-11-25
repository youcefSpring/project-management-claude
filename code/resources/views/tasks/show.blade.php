@extends('layouts.sidebar')

@section('title', $task->title)
@section('page-title', $task->title)

@section('content')
<div class="row">
    <!-- Left Column: Task Details & Activity -->
    <div class="col-lg-8">
        <!-- Task Header & Details Card -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <div class="mb-2">
                            @php
                                $statusColor = match($task->status) {
                                    'completed' => 'success',
                                    'in_progress' => 'primary',
                                    'pending' => 'warning',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                                $statusLabel = match($task->status) {
                                    'completed' => __('app.tasks.completed'),
                                    'in_progress' => __('app.tasks.in_progress'),
                                    'pending' => __('app.tasks.pending'),
                                    'cancelled' => __('app.tasks.cancelled'),
                                    default => ucfirst(str_replace('_', ' ', $task->status))
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }} me-2">{{ $statusLabel }}</span>
                            <a href="{{ route('projects.show', $task->project) }}" class="text-decoration-none text-muted small">
                                <i class="bi bi-folder me-1"></i>{{ $task->project->title }}
                            </a>
                        </div>
                        <h3 class="card-title mb-1 fw-bold">{{ $task->title }}</h3>
                    </div>

                    <!-- Actions Dropdown -->
                    @if(auth()->user()->can('update', $task) || auth()->user()->can('delete', $task))
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle" type="button" id="taskActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width: 32px; height: 32px;">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="taskActionsDropdown">
                            @can('update', $task)
                                <li>
                                    <a href="{{ route('tasks.edit', $task) }}" class="dropdown-item">
                                        <i class="bi bi-pencil me-2 text-primary"></i>{{ __('app.edit') }}
                                    </a>
                                </li>
                                @if($task->status === 'pending')
                                    <li>
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#startTaskModal">
                                            <i class="bi bi-play me-2 text-warning"></i>{{ __('app.tasks.start_task') }}
                                        </button>
                                    </li>
                                @endif
                                @if($task->status === 'in_progress')
                                    <li>
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#completeTaskModal">
                                            <i class="bi bi-check-circle me-2 text-success"></i>{{ __('app.tasks.mark_complete') }}
                                        </button>
                                    </li>
                                @endif
                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changePriorityModal">
                                        <i class="bi bi-exclamation-circle me-2 text-info"></i>{{ __('app.tasks.change_priority') }}
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                                        <i class="bi bi-arrow-repeat me-2 text-secondary"></i>{{ __('app.tasks.change_status') }}
                                    </button>
                                </li>
                            @endcan

                            @can('delete', $task)
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="bi bi-trash me-2"></i>{{ __('app.delete') }}
                                    </button>
                                </li>
                            @endcan
                        </ul>
                    </div>
                    @endif
                </div>

                <!-- Key Metrics Grid -->
                <div class="row g-4 mb-4">
                    <!-- Priority -->
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <small class="text-muted d-block mb-2 text-uppercase fw-bold" style="font-size: 0.75rem;">{{ __('app.tasks.priority') }}</small>
                            @php
                                $priorityColor = match($task->priority) {
                                    'urgent' => 'danger',
                                    'high' => 'warning',
                                    'medium' => 'info',
                                    default => 'secondary'
                                };
                                $priorityLabel = match($task->priority) {
                                    'urgent' => __('app.tasks.urgent'),
                                    'high' => __('app.tasks.high'),
                                    'medium' => __('app.tasks.medium'),
                                    'low' => __('app.tasks.low'),
                                    default => ucfirst($task->priority)
                                };
                            @endphp
                            <span class="fw-bold text-{{ $priorityColor }} d-flex align-items-center">
                                <i class="bi bi-flag-fill me-2"></i>{{ $priorityLabel }}
                            </span>
                        </div>
                    </div>

                    <!-- Assigned To -->
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <small class="text-muted d-block mb-2 text-uppercase fw-bold" style="font-size: 0.75rem;">{{ __('app.tasks.assigned_to') }}</small>
                            @if($task->assignedUser)
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2" style="width: 24px; height: 24px; font-size: 0.8rem;">
                                        {{ substr($task->assignedUser->name, 0, 1) }}
                                    </div>
                                    <span class="fw-bold text-dark text-truncate" title="{{ $task->assignedUser->name }}">
                                        {{ Str::limit($task->assignedUser->name, 15) }}
                                    </span>
                                </div>
                            @else
                                <span class="text-muted fst-italic">{{ __('app.unassigned') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Due Date -->
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <small class="text-muted d-block mb-2 text-uppercase fw-bold" style="font-size: 0.75rem;">{{ __('app.tasks.due_date') }}</small>
                            @php
                                $dueDate = $task->due_date ? (is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date) : null;
                                $isOverdue = $dueDate && $dueDate->isPast() && $task->status !== 'completed';
                                $isDueToday = $dueDate && $dueDate->isToday();
                            @endphp
                            @if($dueDate)
                                <span class="fw-bold {{ $isOverdue ? 'text-danger' : ($isDueToday ? 'text-warning' : 'text-dark') }} d-flex align-items-center">
                                    <i class="bi bi-calendar-event me-2"></i>
                                    {{ $dueDate->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-muted fst-italic">{{ __('app.tasks.no_due_date') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Total Time -->
                    <div class="col-md-3 col-6">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <small class="text-muted d-block mb-2 text-uppercase fw-bold" style="font-size: 0.75rem;">{{ __('app.time.total_time') }}</small>
                            <span class="fw-bold text-primary d-flex align-items-center">
                                <i class="bi bi-clock me-2"></i>
                                {{ number_format($task->timeEntries->sum('duration_hours') ?? 0, 1) }}h
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-0">
                    <h5 class="h6 fw-bold text-uppercase text-muted mb-3" style="font-size: 0.75rem;">{{ __('app.description') }}</h5>
                    @if($task->description)
                        <div class="text-dark" style="white-space: pre-line;">{{ $task->description }}</div>
                    @else
                        <span class="text-muted fst-italic">{{ __('app.tasks.no_description') }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Activity / Comments Section -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">{{ __('app.tasks.activity') }}</h5>
                
                <!-- Filter Controls -->
                <div class="btn-group btn-group-sm">
                    <input type="radio" class="btn-check" name="noteFilter" id="filterAll" autocomplete="off" checked>
                    <label class="btn btn-outline-secondary" for="filterAll">{{ __('app.all') }}</label>

                    <input type="radio" class="btn-check" name="noteFilter" id="filterComments" autocomplete="off">
                    <label class="btn btn-outline-secondary" for="filterComments">{{ __('app.notes.comments') }}</label>

                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                        <input type="radio" class="btn-check" name="noteFilter" id="filterInternal" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="filterInternal">{{ __('app.notes.internal') }}</label>
                    @endif
                </div>
            </div>
            
            <div class="card-body p-4">
                <!-- Add Comment Form -->
                @if($task->canBeViewedBy(auth()->user()))
                    <div class="mb-5">
                        <form method="POST" action="{{ route('tasks.notes.store', $task) }}" enctype="multipart/form-data" id="commentForm">
                            @csrf
                            
                            @can('createIntervention', $task)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="isInternal" name="is_internal">
                                        <label class="form-check-label small text-muted" for="isInternal">
                                            {{ __('app.notes.internal_note') }}
                                        </label>
                                    </div>
                                    <select class="form-select form-select-sm w-auto border-0 bg-light" name="type" id="noteType">
                                        <option value="comment">{{ __('app.notes.comment') }}</option>
                                        <option value="intervention">{{ __('app.notes.intervention') }}</option>
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="type" value="comment">
                            @endcan

                            <div class="position-relative">
                                <textarea class="form-control bg-light border-0" 
                                          id="content" 
                                          name="content" 
                                          rows="3" 
                                          placeholder="{{ __('app.notes.write_comment_placeholder') }}"
                                          style="resize: none;"></textarea>
                                
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div class="d-flex align-items-center">
                                        <label class="btn btn-sm btn-link text-muted p-0 me-3" title="{{ __('app.notes.attach_photos') }}">
                                            <i class="bi bi-paperclip fs-5"></i>
                                            <input type="file" name="attachments[]" multiple accept="image/*" class="d-none" onchange="previewImages(this)">
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm px-4 rounded-pill">
                                        <i class="bi bi-send me-1"></i> {{ __('app.comments.post_comment') }}
                                    </button>
                                </div>
                                <div id="imagePreview" class="mt-2"></div>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Timeline -->
                <div class="timeline position-relative">
                    @if($task->notes && $task->notes->count() > 0)
                        @foreach($task->notes as $note)
                            <div class="note-item d-flex mb-4" 
                                 data-type="{{ $note->type }}" 
                                 data-internal="{{ $note->is_internal ? 'true' : 'false' }}">
                                
                                <!-- Avatar -->
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-secondary border" style="width: 40px; height: 40px;">
                                        {{ substr($note->user->name, 0, 1) }}
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-grow-1">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <span class="fw-bold text-dark">{{ $note->user->name }}</span>
                                                    <small class="text-muted ms-2">{{ $note->created_at->diffForHumans() }}</small>
                                                    @if($note->is_internal)
                                                        <span class="badge bg-secondary ms-2" style="font-size: 0.65rem;">
                                                            <i class="bi bi-eye-slash me-1"></i>{{ __('app.notes.internal') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                <!-- Note Actions -->
                                                @if($note->canBeEditedBy(auth()->user()) || $note->canBeDeletedBy(auth()->user()))
                                                    <div class="dropdown">
                                                        <button class="btn btn-link btn-sm text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                            <i class="bi bi-three-dots"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                                            @if($note->canBeEditedBy(auth()->user()))
                                                                <li>
                                                                    <button class="dropdown-item small" onclick="toggleEdit({{ $note->id }})">
                                                                        <i class="bi bi-pencil me-2"></i>{{ __('app.edit') }}
                                                                    </button>
                                                                </li>
                                                            @endif
                                                            @if($note->canBeDeletedBy(auth()->user()))
                                                                <li>
                                                                    <form method="POST" action="{{ route('tasks.notes.destroy', $note) }}" onsubmit="return confirm('{{ __('app.messages.confirm_delete') }}')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item small text-danger">
                                                                            <i class="bi bi-trash me-2"></i>{{ __('app.delete') }}
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>

                                            <div id="note-content-{{ $note->id }}">
                                                <div class="text-dark mb-2" style="white-space: pre-wrap;">{{ $note->content }}</div>
                                                
                                                @if($note->hasAttachments())
                                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                                        @foreach($note->image_attachments as $attachment)
                                                            <a href="{{ $attachment['versions']['original']['url'] ?? '#' }}" target="_blank" class="d-block">
                                                                <img src="{{ $attachment['versions']['thumbnail']['url'] ?? '#' }}" 
                                                                     class="rounded border" 
                                                                     style="width: 60px; height: 60px; object-fit: cover;"
                                                                     alt="Attachment">
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Edit Form -->
                                            <div id="note-edit-form-{{ $note->id }}" style="display: none;">
                                                <form method="POST" action="{{ route('tasks.notes.update', $note) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <textarea class="form-control mb-2" name="content" rows="3">{{ $note->content }}</textarea>
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <button type="button" class="btn btn-sm btn-light" onclick="toggleEdit({{ $note->id }})">{{ __('app.cancel') }}</button>
                                                        <button type="submit" class="btn btn-sm btn-primary">{{ __('app.update') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3 text-muted">
                                <i class="bi bi-chat-square-dots display-4 opacity-25"></i>
                            </div>
                            <h6 class="text-muted">{{ __('app.notes.no_comments_yet') }}</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Time Entries & Sidebar -->
    <div class="col-lg-4">
        <!-- Time Entries Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">{{ __('app.time.title') }}</h5>
                <a href="{{ route('timesheet.create', ['task_id' => $task->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                    <i class="bi bi-plus-lg me-1"></i>{{ __('app.time.log_time') }}
                </a>
            </div>
            <div class="card-body p-0">
                @if($task->timeEntries && $task->timeEntries->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($task->timeEntries->take(5) as $entry)
                            <div class="list-group-item px-4 py-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark">{{ $entry->user->name }}</span>
                                    <span class="badge bg-light text-dark border">{{ number_format($entry->duration_hours, 1) }}h</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center small">
                                    <span class="text-muted">
                                        {{ is_string($entry->start_time) ? \Carbon\Carbon::parse($entry->start_time)->format('M d') : $entry->start_time->format('M d') }}
                                    </span>
                                    <span class="text-truncate text-muted ms-2" style="max-width: 150px;">
                                        {{ $entry->comment ?? $entry->description ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($task->timeEntries->count() > 5)
                        <div class="card-footer bg-transparent text-center border-0 py-3">
                            <a href="{{ route('timesheet.index', ['task_id' => $task->id]) }}" class="text-decoration-none small fw-bold">
                                {{ __('app.view_all') }}
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4 px-4">
                        <p class="text-muted small mb-0">{{ __('app.time.no_entries') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modals (Keep existing modals but ensure they are guarded) -->
@can('delete', $task)
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">{{ __('app.delete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="text-danger mb-3">
                        <i class="bi bi-exclamation-triangle display-1"></i>
                    </div>
                    <h5 class="mb-3">{{ __('app.messages.confirm_delete') }}</h5>
                    <p class="text-muted mb-0">{{ __('app.messages.action_cannot_be_undone') }}</p>
                </div>
                <div class="modal-footer border-top-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">{{ __('app.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endcan

@can('update', $task)
    <!-- Start Task Modal -->
    <div class="modal fade" id="startTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">{{ __('app.tasks.start_task') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('app.tasks.confirm_start_task') }}</p>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                    <form method="POST" action="{{ route('tasks.update', $task) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="in_progress">
                        <input type="hidden" name="title" value="{{ $task->title }}">
                        <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                        <input type="hidden" name="priority" value="{{ $task->priority }}">
                        <button type="submit" class="btn btn-warning text-white">{{ __('app.tasks.start_task') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Task Modal -->
    <div class="modal fade" id="completeTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">{{ __('app.tasks.mark_complete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('app.tasks.confirm_complete_task') }}</p>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                    <form method="POST" action="{{ route('tasks.update', $task) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="completed">
                        <input type="hidden" name="title" value="{{ $task->title }}">
                        <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                        <input type="hidden" name="priority" value="{{ $task->priority }}">
                        <button type="submit" class="btn btn-success">{{ __('app.tasks.mark_complete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Priority Modal -->
    <div class="modal fade" id="changePriorityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">{{ __('app.tasks.change_priority') }}</h5>
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
                            <label class="form-label">{{ __('app.tasks.priority') }}</label>
                            <select class="form-select" name="priority">
                                <option value="low" {{ $task->priority === 'low' ? 'selected' : '' }}>{{ __('app.tasks.low') }}</option>
                                <option value="medium" {{ $task->priority === 'medium' ? 'selected' : '' }}>{{ __('app.tasks.medium') }}</option>
                                <option value="high" {{ $task->priority === 'high' ? 'selected' : '' }}>{{ __('app.tasks.high') }}</option>
                                <option value="urgent" {{ $task->priority === 'urgent' ? 'selected' : '' }}>{{ __('app.tasks.urgent') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('app.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Status Modal -->
    <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">{{ __('app.tasks.change_status') }}</h5>
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
                            <label class="form-label">{{ __('app.status') }}</label>
                            <select class="form-select" name="status">
                                <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>{{ __('app.tasks.pending') }}</option>
                                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>{{ __('app.tasks.in_progress') }}</option>
                                <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>{{ __('app.tasks.completed') }}</option>
                                <option value="cancelled" {{ $task->status === 'cancelled' ? 'selected' : '' }}>{{ __('app.tasks.cancelled') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('app.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan

@push('scripts')
<script>
    // File Preview
    function handleFilePreview(input, previewId) {
        const previewContainer = document.getElementById(previewId);
        previewContainer.innerHTML = '';
        if (input.files) {
            Array.from(input.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail me-2 mb-2';
                        img.style.width = '60px';
                        img.style.height = '60px';
                        img.style.objectFit = 'cover';
                        previewContainer.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    function previewImages(input) {
        handleFilePreview(input, 'imagePreview');
    }

    // Toggle Edit Form
    function toggleEdit(noteId) {
        const content = document.getElementById(`note-content-${noteId}`);
        const form = document.getElementById(`note-edit-form-${noteId}`);
        if (content.style.display === 'none') {
            content.style.display = 'block';
            form.style.display = 'none';
        } else {
            content.style.display = 'none';
            form.style.display = 'block';
        }
    }

    // Filter Notes
    document.addEventListener('DOMContentLoaded', function() {
        const filters = document.querySelectorAll('input[name="noteFilter"]');
        filters.forEach(filter => {
            filter.addEventListener('change', function() {
                const type = this.id.replace('filter', '').toLowerCase();
                document.querySelectorAll('.note-item').forEach(item => {
                    const itemType = item.dataset.type;
                    const isInternal = item.dataset.internal === 'true';
                    
                    if (type === 'all') item.style.display = 'flex';
                    else if (type === 'comments') item.style.display = itemType === 'comment' ? 'flex' : 'none';
                    else if (type === 'internal') item.style.display = isInternal ? 'flex' : 'none';
                });
            });
        });
    });
</script>
@endpush
@endsection