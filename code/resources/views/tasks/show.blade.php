@extends('layouts.app')

@section('title', $task->title)
@section('page-title', $task->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Task Details') }}</h5>
                <div>
                    @can('update', $task)
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>
                            {{ __('Edit') }}
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>{{ __('Status') }}</h6>
                        <span class="badge bg-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'warning' : 'secondary') }} fs-6">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <h6>{{ __('Priority') }}</h6>
                        <span class="badge bg-{{ $task->priority === 'urgent' ? 'danger' : ($task->priority === 'high' ? 'warning' : ($task->priority === 'medium' ? 'info' : 'secondary')) }} fs-6">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                </div>

                @if($task->description)
                <div class="mb-4">
                    <h6>{{ __('Description') }}</h6>
                    <p class="text-muted">{{ $task->description }}</p>
                </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>{{ __('Project') }}</h6>
                        <p>
                            <a href="{{ route('projects.show', $task->project) }}" class="text-decoration-none">
                                {{ $task->project->title }}
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>{{ __('Assigned To') }}</h6>
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
                        <h6>{{ __('Created') }}</h6>
                        <p class="text-muted">{{ $task->created_at->format('M d, Y \a\t H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>{{ __('Due Date') }}</h6>
                        <p class="text-muted">
                            @if($task->due_date)
                                {{ $task->due_date->format('M d, Y') }}
                                @if($task->due_date->isPast() && $task->status !== 'completed')
                                    <span class="badge bg-danger ms-2">{{ __('Overdue') }}</span>
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
                                    {{ __('Start Working') }}
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
                                    {{ __('Mark Complete') }}
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
                                                <button type="submit" class="btn btn-sm btn-primary">{{ __('Update') }}</button>
                                                <button type="button" class="btn btn-sm btn-secondary ms-1" onclick="toggleEdit({{ $note->id }})">{{ __('Cancel') }}</button>
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
                                            <form method="POST" action="{{ route('tasks.notes.destroy', $note) }}" style="display: inline;" onsubmit="return confirm('{{ __('Are you sure you want to delete this comment?') }}')">
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
                            <label for="content" class="form-label">{{ __('Add a comment') }}</label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content" name="content" rows="3"
                                      placeholder="{{ __('Write your comment here...') }}" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-chat-left-text me-1"></i>
                            {{ __('Add Comment') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Time Entries -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Time Entries') }}</h5>
                <a href="{{ route('timesheet.create', ['task_id' => $task->id]) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    {{ __('Log Time') }}
                </a>
            </div>
            <div class="card-body">
                @if($task->timeEntries && $task->timeEntries->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('Description') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($task->timeEntries as $entry)
                                    <tr>
                                        <td>{{ $entry->start_time->format('M d, Y') }}</td>
                                        <td>{{ $entry->user->name }}</td>
                                        <td>{{ number_format($entry->duration_hours, 1) }}h</td>
                                        <td>{{ $entry->description ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <strong>{{ __('Total Time:') }}</strong>
                        {{ number_format($task->timeEntries->sum('duration_hours'), 1) }}h
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-clock text-muted fs-2"></i>
                        <p class="text-muted mt-2">{{ __('No time entries logged yet.') }}</p>
                        <a href="{{ route('timesheet.create', ['task_id' => $task->id]) }}" class="btn btn-sm btn-primary">
                            {{ __('Log First Entry') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Task Actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @can('update', $task)
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-2"></i>
                            {{ __('Edit Task') }}
                        </a>
                    @endcan

                    <a href="{{ route('timesheet.create', ['task_id' => $task->id]) }}" class="btn btn-outline-success">
                        <i class="bi bi-clock me-2"></i>
                        {{ __('Log Time') }}
                    </a>

                    <a href="{{ route('projects.show', $task->project) }}" class="btn btn-outline-info">
                        <i class="bi bi-folder me-2"></i>
                        {{ __('View Project') }}
                    </a>

                    @can('delete', $task)
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i>
                            {{ __('Delete Task') }}
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
                    <strong>{{ __('Total Time Logged') }}:</strong>
                    <span class="float-end">{{ number_format($task->timeEntries->sum('duration_hours') ?? 0, 1) }}h</span>
                </div>
                <div class="mb-2">
                    <strong>{{ __('Created') }}:</strong>
                    <span class="float-end">{{ $task->created_at->diffForHumans() }}</span>
                </div>
                @if($task->due_date)
                <div class="mb-2">
                    <strong>{{ __('Time Remaining') }}:</strong>
                    <span class="float-end">
                        @if($task->due_date->isFuture())
                            {{ $task->due_date->diffForHumans() }}
                        @else
                            <span class="text-danger">{{ __('Overdue') }}</span>
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
                <h5 class="modal-title" id="deleteModalLabel">{{ __('Delete Task') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete this task?') }}</p>
                <p class="text-danger">
                    <strong>{{ __('Warning:') }}</strong>
                    {{ __('This action cannot be undone and will also delete all associated time entries.') }}
                </p>
                <p><strong>{{ __('Task:') }}</strong> {{ $task->title }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <form method="POST" action="{{ route('tasks.destroy', $task) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Delete Task') }}</button>
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