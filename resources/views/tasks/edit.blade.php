@extends('layouts.sidebar')

@section('title', __('app.tasks.edit'))
@section('page-title', __('app.tasks.edit_task_title', ['title' => $task->title]))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.tasks.task_information') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tasks.update', $task) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('app.tasks.task_title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title', $task->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('app.description') }}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(count($projects) > 0)
                    <div class="mb-3">
                        <label for="project_id" class="form-label">{{ __('app.tasks.project') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('project_id') is-invalid @enderror" id="project_id" name="project_id" required>
                            <option value="">{{ __('app.tasks.select_project') }}</option>
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
                            <label class="form-label">{{ __('app.tasks.project') }}</label>
                            <p class="form-control-plaintext">{{ $task->project->title }}</p>
                            <small class="text-muted">{{ __('app.tasks.edit_restriction') }}</small>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">{{ __('app.tasks.assigned_to') }}</label>
                                <select class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
                                    <option value="">{{ __('app.unassigned') }}</option>
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
                                <label for="priority" class="form-label">{{ __('app.tasks.priority') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                    <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>{{ __('app.tasks.low') }}</option>
                                    <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>{{ __('app.tasks.medium') }}</option>
                                    <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>{{ __('app.tasks.high') }}</option>
                                    <option value="urgent" {{ old('priority', $task->priority) === 'urgent' ? 'selected' : '' }}>{{ __('app.tasks.urgent') }}</option>
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
                                <label for="status" class="form-label">{{ __('app.status') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>{{ __('app.tasks.pending') }}</option>
                                    <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>{{ __('app.tasks.in_progress') }}</option>
                                    <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>{{ __('app.tasks.completed') }}</option>
                                    <option value="cancelled" {{ old('status', $task->status) === 'cancelled' ? 'selected' : '' }}>{{ __('app.tasks.cancelled') }}</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">{{ __('app.tasks.due_date') }}</label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                       id="due_date" name="due_date"
                                       value="{{ old('due_date', $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '') }}">
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
                                {{ __('app.cancel') }}
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ __('app.save') }}
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
                <h5 class="mb-0">{{ __('app.tasks.task_actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>
                        {{ __('app.tasks.view_task') }}
                    </a>

                    <a href="{{ route('timesheet.create', ['task_id' => $task->id]) }}" class="btn btn-outline-success">
                        <i class="bi bi-clock me-2"></i>
                        {{ __('app.time.log_time') }}
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
                <h5 class="mb-0">{{ __('app.tasks.task_information') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>{{ __('app.tasks.current_project') }}:</strong>
                    <br><small>{{ $task->project->title }}</small>
                </div>
                <div class="mb-2">
                    <strong>{{ __('app.created') }}:</strong>
                    <br><small>{{ $task->created_at->format('M d, Y') }}</small>
                </div>
                <div class="mb-2">
                    <strong>{{ __('app.time.logged') }}:</strong>
                    <br><small>{{ number_format($task->timeEntries->sum('duration_hours') ?? 0, 1) }}h</small>
                </div>
                @if($task->assignedUser)
                <div class="mb-2">
                    <strong>{{ __('app.tasks.currently_assigned') }}:</strong>
                    <br><small>{{ $task->assignedUser->name }}</small>
                </div>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.tasks.status_guide') }}</h5>
            </div>
            <div class="card-body">
                <ul class="small text-muted">
                    <li><strong>{{ __('app.tasks.pending') }}:</strong> {{ __('app.tasks.status_pending_desc') }}</li>
                    <li><strong>{{ __('app.tasks.in_progress') }}:</strong> {{ __('app.tasks.status_in_progress_desc') }}</li>
                    <li><strong>{{ __('app.tasks.completed') }}:</strong> {{ __('app.tasks.status_completed_desc') }}</li>
                    <li><strong>{{ __('app.tasks.cancelled') }}:</strong> {{ __('app.tasks.status_cancelled_desc') }}</li>
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
                <h5 class="modal-title" id="deleteModalLabel">{{ __('app.delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('app.tasks.confirm_delete') }}</p>
                <p class="text-danger">
                    <strong>{{ __('app.warning') }}:</strong>
                    {{ __('app.tasks.delete_warning') }}
                </p>
                <p><strong>{{ __('app.tasks.task') }}:</strong> {{ $task->title }}</p>
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
@endsection