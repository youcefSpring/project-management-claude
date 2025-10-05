@extends('layouts.app')

@section('title', __('Edit Task'))
@section('page-title', __('Edit Task: :title', ['title' => $task->title]))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Task Information') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tasks.update', $task) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('Task Title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title', $task->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('Description') }}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(count($projects) > 0)
                    <div class="mb-3">
                        <label for="project_id" class="form-label">{{ __('Project') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('project_id') is-invalid @enderror" id="project_id" name="project_id" required>
                            <option value="">{{ __('Select a project') }}</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @else
                        <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Project') }}</label>
                            <p class="form-control-plaintext">{{ $task->project->title }}</p>
                            <small class="text-muted">{{ __('You can only edit tasks in projects you manage.') }}</small>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">{{ __('Assigned To') }}</label>
                                <select class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
                                    <option value="">{{ __('Unassigned') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="priority" class="form-label">{{ __('Priority') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                    <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>{{ __('Low') }}</option>
                                    <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                                    <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>{{ __('High') }}</option>
                                    <option value="urgent" {{ old('priority', $task->priority) === 'urgent' ? 'selected' : '' }}>{{ __('Urgent') }}</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                                    <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                    <option value="cancelled" {{ old('status', $task->status) === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">{{ __('Due Date') }}</label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                       id="due_date" name="due_date"
                                       value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>
                                {{ __('Cancel') }}
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ __('Update Task') }}
                            </button>
                        </div>
                    </div>
                </form>
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
                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>
                        {{ __('View Task') }}
                    </a>

                    <a href="{{ route('timesheet.create', ['task_id' => $task->id]) }}" class="btn btn-outline-success">
                        <i class="bi bi-clock me-2"></i>
                        {{ __('Log Time') }}
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
                <h5 class="mb-0">{{ __('Task Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>{{ __('Current Project') }}:</strong>
                    <br><small>{{ $task->project->title }}</small>
                </div>
                <div class="mb-2">
                    <strong>{{ __('Created') }}:</strong>
                    <br><small>{{ $task->created_at->format('M d, Y') }}</small>
                </div>
                <div class="mb-2">
                    <strong>{{ __('Time Logged') }}:</strong>
                    <br><small>{{ number_format($task->timeEntries->sum('duration_hours') ?? 0, 1) }}h</small>
                </div>
                @if($task->assignedUser)
                <div class="mb-2">
                    <strong>{{ __('Currently Assigned') }}:</strong>
                    <br><small>{{ $task->assignedUser->name }}</small>
                </div>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Status Guide') }}</h5>
            </div>
            <div class="card-body">
                <ul class="small text-muted">
                    <li><strong>{{ __('Pending') }}:</strong> {{ __('Not started yet') }}</li>
                    <li><strong>{{ __('In Progress') }}:</strong> {{ __('Currently being worked on') }}</li>
                    <li><strong>{{ __('Completed') }}:</strong> {{ __('Task is finished') }}</li>
                    <li><strong>{{ __('Cancelled') }}:</strong> {{ __('Task is no longer needed') }}</li>
                </ul>
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
@endsection