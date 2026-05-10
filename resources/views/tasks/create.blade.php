@extends('layouts.sidebar')

@section('title', __('app.tasks.create'))
@section('page-title', __('app.tasks.create_new_task'))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.tasks.task_information') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('app.tasks.task_title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('app.description') }}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="project_id" class="form-label">{{ __('app.tasks.project') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('project_id') is-invalid @enderror" id="project_id" name="project_id" required>
                            <option value="">{{ __('app.select_project') }}</option>
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
                                <label for="assigned_to" class="form-label">{{ __('app.tasks.assigned_to') }}</label>
                                <select class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
                                    <option value="">{{ __('app.unassigned') }}</option>
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
                                <label for="priority" class="form-label">{{ __('app.tasks.priority') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>{{ __('app.tasks.low') }}</option>
                                    <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>{{ __('app.tasks.medium') }}</option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>{{ __('app.tasks.high') }}</option>
                                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>{{ __('app.tasks.urgent') }}</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="due_date" class="form-label">{{ __('app.tasks.due_date') }}</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                               id="due_date" name="due_date" value="{{ old('due_date') }}">
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            {{ __('app.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            {{ __('app.tasks.create') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.guidelines') }}</h5>
            </div>
            <div class="card-body">
                <h6>{{ __('app.tasks.task_title') }}</h6>
                <p class="small text-muted">{{ __('app.tasks.title_guidelines') }}</p>

                <h6>{{ __('app.tasks.priority_levels') }}</h6>
                <ul class="small text-muted">
                    <li><strong>{{ __('app.tasks.low') }}:</strong> {{ __('app.tasks.low_description') }}</li>
                    <li><strong>{{ __('app.tasks.medium') }}:</strong> {{ __('app.tasks.medium_description') }}</li>
                    <li><strong>{{ __('app.tasks.high') }}:</strong> {{ __('app.tasks.high_description') }}</li>
                    <li><strong>{{ __('app.tasks.urgent') }}:</strong> {{ __('app.tasks.urgent_description') }}</li>
                </ul>

                <h6>{{ __('app.tasks.assignment') }}</h6>
                <p class="small text-muted">{{ __('app.tasks.assignment_guidelines') }}</p>
            </div>
        </div>

        @if($selectedProject)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.selected_project') }}</h5>
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