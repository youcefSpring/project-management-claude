@extends('layouts.app')

@section('title', __('Log Time Entry'))
@section('page-title', __('Log New Time Entry'))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Time Entry Information') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('timesheet.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="task_id" class="form-label">{{ __('Task') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('task_id') is-invalid @enderror" id="task_id" name="task_id" required>
                            <option value="">{{ __('Select a task') }}</option>
                            @foreach($availableTasks as $task)
                                <option value="{{ $task->id }}"
                                    {{ old('task_id', request('task_id')) == $task->id ? 'selected' : '' }}>
                                    {{ $task->title }} ({{ optional($task->project)->title ?? __('No Project') }})
                                </option>
                            @endforeach
                        </select>
                        @error('task_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_time" class="form-label">{{ __('Start Time') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror"
                                       id="start_time" name="start_time"
                                       value="{{ old('start_time', now()->format('Y-m-d\TH:i')) }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_time" class="form-label">{{ __('End Time') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror"
                                       id="end_time" name="end_time"
                                       value="{{ old('end_time', now()->addHour()->format('Y-m-d\TH:i')) }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="hours" class="form-label">{{ __('Duration (hours)') }}</label>
                        <input type="number" class="form-control @error('hours') is-invalid @enderror"
                               id="hours" name="hours" step="0.25" min="0.1" max="24"
                               value="{{ old('hours') }}" placeholder="{{ __('Enter hours or use start/end time') }}">
                        <small class="form-text text-muted">{{ __('Enter hours directly or use start/end time fields') }}</small>
                        @error('hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('Description') }}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4"
                                  placeholder="{{ __('Describe what you worked on...') }}">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('timesheet.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-clock me-2"></i>
                            {{ __('Log Time Entry') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Quick Actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="setQuickTime(1)">
                        {{ __('1 Hour') }}
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="setQuickTime(2)">
                        {{ __('2 Hours') }}
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="setQuickTime(4)">
                        {{ __('4 Hours') }}
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="setQuickTime(8)">
                        {{ __('8 Hours') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Guidelines') }}</h5>
            </div>
            <div class="card-body">
                <h6>{{ __('Time Tracking Tips') }}</h6>
                <ul class="small text-muted">
                    <li>{{ __('Be accurate with your time entries') }}</li>
                    <li>{{ __('Include detailed descriptions of work done') }}</li>
                    <li>{{ __('Log time daily for best accuracy') }}</li>
                    <li>{{ __('Maximum 24 hours per entry') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
let isCalculatingFromTime = false;
let isCalculatingFromHours = false;

function calculateDuration() {
    if (isCalculatingFromHours) return;

    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    const hoursField = document.getElementById('hours');

    if (startTime && endTime) {
        const start = new Date(startTime);
        const end = new Date(endTime);

        if (end > start) {
            isCalculatingFromTime = true;
            const diffMs = end - start;
            const diffHours = diffMs / (1000 * 60 * 60);
            hoursField.value = diffHours.toFixed(2);
            isCalculatingFromTime = false;
        }
    }
}

function calculateEndTime() {
    if (isCalculatingFromTime) return;

    const startTime = document.getElementById('start_time').value;
    const hours = parseFloat(document.getElementById('hours').value);
    const endTimeField = document.getElementById('end_time');

    if (startTime && hours && hours > 0) {
        isCalculatingFromHours = true;
        const start = new Date(startTime);
        const end = new Date(start.getTime() + (hours * 60 * 60 * 1000));
        endTimeField.value = end.toISOString().slice(0, 16);
        isCalculatingFromHours = false;
    }
}

function setQuickTime(hours) {
    const now = new Date();
    const endTime = new Date(now.getTime() + (hours * 60 * 60 * 1000));

    document.getElementById('start_time').value = now.toISOString().slice(0, 16);
    document.getElementById('end_time').value = endTime.toISOString().slice(0, 16);
    document.getElementById('hours').value = hours.toFixed(2);
}

// Calculate duration when times change
document.getElementById('start_time').addEventListener('change', function() {
    calculateDuration();
    calculateEndTime(); // Also update end time if hours are set
});
document.getElementById('end_time').addEventListener('change', calculateDuration);

// Calculate end time when hours change
document.getElementById('hours').addEventListener('input', calculateEndTime);

// Calculate initial duration
document.addEventListener('DOMContentLoaded', function() {
    calculateDuration();
});
</script>
@endsection