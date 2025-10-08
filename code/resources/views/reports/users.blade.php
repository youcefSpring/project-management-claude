@extends('layouts.app')

@section('title', __('Users Report'))
@section('page-title', __('Users Report'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>
                    {{ __('Users Report') }}
                </h5>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('reports.users') }}" class="row mb-4">
                    <div class="col-md-3">
                        <label for="project_id" class="form-label">{{ __('Project') }}</label>
                        <select class="form-select" id="project_id" name="project_id">
                            <option value="">{{ __('All Projects') }}</option>
                            @if(isset($data['projects']))
                                @foreach($data['projects'] as $project)
                                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->title }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">{{ __('End Date') }}</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search me-1"></i>{{ __('Filter') }}
                        </button>
                        <a href="{{ route('reports.users') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>{{ __('Clear') }}
                        </a>
                    </div>
                </form>

                <!-- Report Content -->
                @if(isset($data['users']) && count($data['users']) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Total Tasks') }}</th>
                                    <th>{{ __('Completed Tasks') }}</th>
                                    <th>{{ __('Completion Rate') }}</th>
                                    <th>{{ __('Total Hours') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['users'] as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                     style="width: 32px; height: 32px;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $user['name'] ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ $user['email'] ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst($user['role'] ?? 'member') }}</span>
                                        </td>
                                        <td>{{ $user['total_tasks'] ?? 0 }}</td>
                                        <td>{{ $user['completed_tasks'] ?? 0 }}</td>
                                        <td>
                                            @php
                                                $rate = ($user['total_tasks'] ?? 0) > 0
                                                    ? round((($user['completed_tasks'] ?? 0) / ($user['total_tasks'] ?? 1)) * 100)
                                                    : 0;
                                            @endphp
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{ $rate }}%</span>
                                                <div class="progress" style="width: 60px; height: 8px;">
                                                    <div class="progress-bar bg-{{ $rate >= 75 ? 'success' : ($rate >= 50 ? 'warning' : 'danger') }}"
                                                         style="width: {{ $rate }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ number_format($user['total_hours'] ?? 0, 1) }}h</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-people fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('No user data found') }}</h5>
                        <p class="text-muted">{{ __('Try adjusting your filters to see user reports.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection