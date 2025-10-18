@extends('layouts.sidebar')

@section('title', __('Edit Project'))
@section('page-title', __('Edit Project: :title', ['title' => $project->title]))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Project Information') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('projects.update', $project) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('Project Title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title', $project->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('Description') }}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4">{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">{{ __('Start Date') }} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                       id="start_date" name="start_date" value="{{ old('start_date', $project->start_date instanceof \Carbon\Carbon ? $project->start_date->format('Y-m-d') : $project->start_date) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">{{ __('End Date') }} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                       id="end_date" name="end_date" value="{{ old('end_date', $project->end_date instanceof \Carbon\Carbon ? $project->end_date->format('Y-m-d') : $project->end_date) }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="manager_id" class="form-label">{{ __('Project Manager') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('manager_id') is-invalid @enderror" id="manager_id" name="manager_id" required>
                            <option value="">{{ __('Select a manager') }}</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" {{ old('manager_id', $project->manager_id) == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('manager_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="planning" {{ old('status', $project->status) === 'planning' ? 'selected' : '' }}>{{ __('Planning') }}</option>
                            <option value="active" {{ old('status', $project->status) === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="on_hold" {{ old('status', $project->status) === 'on_hold' ? 'selected' : '' }}>{{ __('On Hold') }}</option>
                            <option value="completed" {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                            <option value="cancelled" {{ old('status', $project->status) === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>
                                {{ __('Cancel') }}
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ __('Update Project') }}
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
                <h5 class="mb-0">{{ __('Project Actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>
                        {{ __('View Project') }}
                    </a>

                    @can('delete', $project)
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i>
                            {{ __('Delete Project') }}
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Project Stats') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>{{ __('Tasks') }}:</strong>
                    <span class="float-end">{{ $project->tasks_count ?? $project->tasks->count() }}</span>
                </div>
                <div class="mb-2">
                    <strong>{{ __('Created') }}:</strong>
                    <span class="float-end">{{ $project->created_at->format('M d, Y') }}</span>
                </div>
                <div class="mb-2">
                    <strong>{{ __('Duration') }}:</strong>
                    <span class="float-end">
                        @if($project->start_date && $project->end_date)
                            @php
                                $startDate = is_string($project->start_date) ? \Carbon\Carbon::parse($project->start_date) : $project->start_date;
                                $endDate = is_string($project->end_date) ? \Carbon\Carbon::parse($project->end_date) : $project->end_date;
                            @endphp
                            {{ $startDate->diffInDays($endDate) }} {{ __('days') }}
                        @else
                            {{ __('app.no_dates_set') }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@can('delete', $project)
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('Delete Project') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete this project?') }}</p>
                <p class="text-danger">
                    <strong>{{ __('Warning:') }}</strong>
                    {{ __('This action cannot be undone and will also delete all associated tasks and time entries.') }}
                </p>
                <p><strong>{{ __('Project:') }}</strong> {{ $project->title }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <form method="POST" action="{{ route('projects.destroy', $project) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Delete Project') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection