@extends('layouts.app')

@section('title', __('Create Project'))
@section('page-title', __('Create New Project'))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Project Information') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('projects.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('Project Title') }} <span class="text-danger">*</span></label>
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">{{ __('Start Date') }} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                       id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">{{ __('End Date') }} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                       id="end_date" name="end_date" value="{{ old('end_date') }}" required>
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
                                <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('manager_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            {{ __('Create Project') }}
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
                <h6>{{ __('Project Title') }}</h6>
                <p class="small text-muted">{{ __('Choose a clear, descriptive title that explains the project\'s purpose.') }}</p>

                <h6>{{ __('Dates') }}</h6>
                <p class="small text-muted">{{ __('Set realistic start and end dates. The end date must be after the start date.') }}</p>

                <h6>{{ __('Project Manager') }}</h6>
                <p class="small text-muted">{{ __('Select a manager who will oversee this project and its tasks.') }}</p>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Next Steps') }}</h5>
            </div>
            <div class="card-body">
                <p class="small">{{ __('After creating the project, you can:') }}</p>
                <ul class="small">
                    <li>{{ __('Add tasks to break down the work') }}</li>
                    <li>{{ __('Assign team members to tasks') }}</li>
                    <li>{{ __('Track progress and time') }}</li>
                    <li>{{ __('Generate reports') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection