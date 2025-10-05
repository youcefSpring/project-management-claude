@extends('layouts.app')

@section('title', __('Profile'))
@section('page-title', __('Profile'))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Profile Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('Name') }}:</strong>
                        <p>{{ auth()->user()->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('Email') }}:</strong>
                        <p>{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('Role') }}:</strong>
                        <p>
                            <span class="badge bg-{{ auth()->user()->role === 'admin' ? 'danger' : (auth()->user()->role === 'manager' ? 'warning' : 'info') }}">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('Member Since') }}:</strong>
                        <p>{{ auth()->user()->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('profile.settings') }}" class="btn btn-primary">
                        <i class="bi bi-gear me-2"></i>
                        {{ __('Edit Profile') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Quick Stats') }}</h5>
            </div>
            <div class="card-body">
                @php
                    $user = auth()->user();
                    $myTasks = \App\Models\Task::where('assigned_to', $user->id)->count();
                    $completedTasks = \App\Models\Task::where('assigned_to', $user->id)->where('status', 'completed')->count();
                    $totalTimeThisMonth = \App\Models\TimeEntry::where('user_id', $user->id)
                        ->whereBetween('start_time', [now()->startOfMonth(), now()->endOfMonth()])
                        ->sum('duration_hours');
                @endphp

                <div class="mb-3">
                    <strong>{{ __('My Tasks') }}:</strong>
                    <span class="float-end">{{ $myTasks }}</span>
                </div>
                <div class="mb-3">
                    <strong>{{ __('Completed Tasks') }}:</strong>
                    <span class="float-end">{{ $completedTasks }}</span>
                </div>
                <div class="mb-3">
                    <strong>{{ __('Time This Month') }}:</strong>
                    <span class="float-end">{{ number_format($totalTimeThisMonth, 1) }}h</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection