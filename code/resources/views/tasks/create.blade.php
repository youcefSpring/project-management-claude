@extends('layouts.app')

@section('title', __('Create Task'))
@section('page-title', __('Create New Task'))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Task Information') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('Task Title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('Description') }}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="project_id" class="form-label">{{ __('Project') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('project_id') is-invalid @enderror" id="project_id" name="project_id" required>
                            <option value="">{{ __('Select a project') }}</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id', $selectedProject?->id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">{{ __('Assigned To') }}</label>
                                <select class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
                                    <option value="">{{ __('Unassigned') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
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
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>{{ __('Low') }}</option>
                                    <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>{{ __('High') }}</option>
                                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>{{ __('Urgent') }}</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="due_date" class="form-label">{{ __('Due Date') }}</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                               id="due_date" name="due_date" value="{{ old('due_date') }}">
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            {{ __('Create Task') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Guidelines') }}</h5>
            </div>
            <div class="card-body">
                <h6>{{ __('Task Title') }}</h6>
                <p class="small text-muted">{{ __('Use a clear, action-oriented title that describes what needs to be done.') }}</p>

                <h6>{{ __('Priority Levels') }}</h6>
                <ul class="small text-muted">
                    <li><strong>{{ __('Low') }}:</strong> {{ __('Can be done when time permits') }}</li>
                    <li><strong>{{ __('Medium') }}:</strong> {{ __('Normal priority task') }}</li>
                    <li><strong>{{ __('High') }}:</strong> {{ __('Important, should be done soon') }}</li>
                    <li><strong>{{ __('Urgent') }}:</strong> {{ __('Critical, needs immediate attention') }}</li>
                </ul>

                <h6>{{ __('Assignment') }}</h6>
                <p class="small text-muted">{{ __('Leave unassigned if you\'re not sure who should work on this task. You can assign it later.') }}</p>
            </div>
        </div>

        @if($selectedProject)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Selected Project') }}</h5>
            </div>
            <div class="card-body">
                <h6>{{ $selectedProject->title }}</h6>
                <p class="small text-muted">{{ $selectedProject->description }}</p>
                <small class="text-muted">
                    <i class="bi bi-person me-1"></i>
                    {{ $selectedProject->manager->name }}
                </small>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection