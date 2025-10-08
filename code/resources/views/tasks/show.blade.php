@extends('layouts.app')

@section('title', $task->title)
@section('page-title', $task->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('app.tasks.title') }}</h5>
                <div>
                    @can('update', $task)
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>
                            {{ __('app.edit') }}
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>{{ __('app.status') }}</h6>
                        <span class="badge bg-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'warning' : 'secondary') }} fs-6">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <h6>{{ __('app.tasks.priority') }}</h6>
                        <span class="badge bg-{{ $task->priority === 'urgent' ? 'danger' : ($task->priority === 'high' ? 'warning' : ($task->priority === 'medium' ? 'info' : 'secondary')) }} fs-6">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                </div>

                @if($task->description)
                <div class="mb-4">
                    <h6>{{ __('app.description') }}</h6>
                    <p class="text-muted">{{ $task->description }}</p>
                </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>{{ __('app.projects.title') }}</h6>
                        <p>
                            <a href="{{ route('projects.show', $task->project) }}" class="text-decoration-none">
                                {{ $task->project->title }}
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>{{ __('app.tasks.assigned_to') }}</h6>
                        <p>
                            @if($task->assignedUser)
                                <i class="bi bi-person-circle me-1"></i>
                                {{ $task->assignedUser->name }}
                            @else
                                <span class="text-muted">{{ __('Unassigned') }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>{{ __('app.date') }}</h6>
                        <p class="text-muted">{{ $task->created_at->format('M d, Y \a\t H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>{{ __('app.tasks.due_date') }}</h6>
                        <p class="text-muted">
                            @if($task->due_date)
                                @php
                                    $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                                @endphp
                                {{ $dueDate->format('M d, Y') }}
                                @if($dueDate->isPast() && $task->status !== 'completed')
                                    <span class="badge bg-danger ms-2">{{ __('app.tasks.overdue') }}</span>
                                @endif
                            @else
                                {{ __('No due date') }}
                            @endif
                        </p>
                    </div>
                </div>

                @if($task->status !== 'completed' && $task->assignedUser === auth()->user())
                <div class="mt-4">
                    <h6>{{ __('Quick Actions') }}</h6>
                    <div class="d-flex gap-2">
                        @if($task->status === 'pending')
                            <form method="POST" action="{{ route('tasks.update', $task) }}" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="in_progress">
                                <input type="hidden" name="title" value="{{ $task->title }}">
                                <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                                <input type="hidden" name="priority" value="{{ $task->priority }}">
                                <button type="submit" class="btn btn-sm btn-warning">
                                    <i class="bi bi-play me-1"></i>
                                    {{ __('app.time.start_timer') }}
                                </button>
                            </form>
                        @endif

                        @if($task->status === 'in_progress')
                            <form method="POST" action="{{ route('tasks.update', $task) }}" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="completed">
                                <input type="hidden" name="title" value="{{ $task->title }}">
                                <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                                <input type="hidden" name="priority" value="{{ $task->priority }}">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    {{ __('app.tasks.completed') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Comments/Notes -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Comments') }}</h5>
            </div>
            <div class="card-body">
                @if($task->notes && $task->notes->count() > 0)
                    <div class="mb-4">
                        @foreach($task->notes as $note)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-person-circle me-2 text-muted"></i>
                                            <strong>{{ $note->user->name }}</strong>
                                            <small class="text-muted ms-2">{{ $note->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div id="note-content-{{ $note->id }}">
                                            <p class="mb-0">{!! nl2br(e($note->content)) !!}</p>
                                        </div>
                                        <div id="note-edit-form-{{ $note->id }}" style="display: none;">
                                            <form method="POST" action="{{ route('tasks.notes.update', $note) }}">
                                                @csrf
                                                @method('PUT')
                                                <textarea class="form-control mb-2" name="content" rows="3" required>{{ $note->content }}</textarea>
                                                <button type="submit" class="btn btn-sm btn-primary">{{ __('app.update') }}</button>
                                                <button type="button" class="btn btn-sm btn-secondary ms-1" onclick="toggleEdit({{ $note->id }})">{{ __('app.cancel') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        @if($note->canBeEditedBy(auth()->user()))
                                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" onclick="toggleEdit({{ $note->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        @endif
                                        @if($note->canBeDeletedBy(auth()->user()))
                                            <form method="POST" action="{{ route('tasks.notes.destroy', $note) }}" style="display: inline;" onsubmit="return confirm('{{ __("app.messages.confirm_delete") }}')">
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
                @endif

                <!-- Add new comment form -->
                @if($task->canBeViewedBy(auth()->user()))
                    <form method="POST" action="{{ route('tasks.notes.store', $task) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="content" class="form-label">{{ __('app.comments.add_comment') }}</label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content" name="content" rows="3"
                                      placeholder="{{ __('Write your comment here...') }}" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-chat-left-text me-1"></i>
                            {{ __('app.comments.add_comment') }}
                        </button>
                    </form>
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

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @can('update', $task)
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-2"></i>
                            {{ __('app.tasks.edit') }}
                        </a>
                    @endcan

                    <a href="{{ route('timesheet.create', ['task_id' => $task->id]) }}" class="btn btn-outline-success">
                        <i class="bi bi-clock me-2"></i>
                        {{ __('app.time.log_time') }}
                    </a>

                    <a href="{{ route('projects.show', $task->project) }}" class="btn btn-outline-info">
                        <i class="bi bi-folder me-2"></i>
                        {{ __('app.projects.title') }}
                    </a>

                    @can('delete', $task)
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i>
                            {{ __('app.delete') }}
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Task Statistics') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>{{ __('app.time.total_time') }}:</strong>
                    <span class="float-end">{{ number_format($task->timeEntries->sum('duration_hours') ?? 0, 1) }}h</span>
                </div>
                <div class="mb-2">
                    <strong>{{ __('app.date') }}:</strong>
                    <span class="float-end">{{ $task->created_at->diffForHumans() }}</span>
                </div>
                @if($task->due_date)
                @php
                    $dueDate = is_string($task->due_date) ? \Carbon\Carbon::parse($task->due_date) : $task->due_date;
                @endphp
                <div class="mb-2">
                    <strong>{{ __('Time Remaining') }}:</strong>
                    <span class="float-end">
                        @if($dueDate->isFuture())
                            {{ $dueDate->diffForHumans() }}
                        @else
                            <span class="text-danger">{{ __('app.tasks.overdue') }}</span>
                        @endif
                    </span>
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

<script>
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
</script>

@endsection