@extends('layouts.app')

@section('title', __('app.profile.title'))
@section('page-title', __('app.profile.title'))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.profile.profile_information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('app.profile.name') }}:</strong>
                        <p>{{ auth()->user()->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('app.profile.email') }}:</strong>
                        <p>{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('app.profile.role') }}:</strong>
                        <p>
                            <span class="badge bg-{{ auth()->user()->role === 'admin' ? 'danger' : (auth()->user()->role === 'manager' ? 'warning' : 'info') }}">
                                @switch(auth()->user()->role)
                                    @case('admin') {{ __('app.users.admin') }} @break
                                    @case('manager') {{ __('app.users.manager') }} @break
                                    @case('developer') {{ __('app.users.developer') }} @break
                                    @case('designer') {{ __('app.users.designer') }} @break
                                    @case('tester') {{ __('app.users.tester') }} @break
                                    @case('hr') {{ __('app.users.hr') }} @break
                                    @case('accountant') {{ __('app.users.accountant') }} @break
                                    @case('client') {{ __('app.users.client') }} @break
                                    @case('member') {{ __('app.users.member') }} @break
                                    @default {{ ucfirst(auth()->user()->role) }}
                                @endswitch
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('app.profile.member_since') }}:</strong>
                        <p>{{ auth()->user()->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('profile.settings') }}" class="btn btn-primary">
                        <i class="bi bi-gear me-2"></i>
                        {{ __('app.profile.edit_profile') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- My Productivity Charts -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.productivity.overview') }}</h5>
            </div>
            <div class="card-body">
                <!-- Weekly Hours Chart -->
                <div class="mb-4">
                    <h6>{{ __('app.productivity.weekly_summary') }}</h6>
                    <canvas id="weeklyHoursChart" height="100"></canvas>
                </div>

                <!-- Task Progress Chart -->
                <div class="mb-3">
                    <h6>{{ __('app.productivity.tasks_completed') }}</h6>
                    <canvas id="taskProgressChart" height="80"></canvas>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const user = @json(auth()->user());

    // Get last 7 days for weekly chart
    const last7Days = @json(array_map(function($i) {
        return now()->subDays(6-$i)->format('M d');
    }, range(0, 6)));

    // Get time entries for last 7 days
    const weeklyHours = @json(
        auth()->user()->timeEntries()
            ->where('start_time', '>=', now()->subDays(7))
            ->get()
            ->groupBy(function($entry) {
                return $entry->start_time->format('Y-m-d');
            })
            ->map(function($entries) {
                return $entries->sum('duration_hours');
            })
            ->values()
            ->toArray()
    ) || [0, 0, 0, 0, 0, 0, 0];

    // Task completion data
    const taskData = [
        {{ auth()->user()->assignedTasks()->where('status', 'completed')->count() }},
        {{ auth()->user()->assignedTasks()->where('status', 'in_progress')->count() }},
        {{ auth()->user()->assignedTasks()->where('status', 'pending')->count() }}
    ];

    // Common chart options
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    };

    // Weekly Hours Chart
    const weeklyCtx = document.getElementById('weeklyHoursChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: last7Days,
            datasets: [{
                label: '{{ __("app.time.hours") }}',
                data: weeklyHours,
                backgroundColor: 'rgba(0, 123, 255, 0.6)',
                borderColor: '#007bff',
                borderWidth: 1
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + 'h {{ __("app.time.logged") }}';
                        }
                    }
                }
            }
        }
    });

    // Task Progress Chart
    const taskCtx = document.getElementById('taskProgressChart').getContext('2d');
    new Chart(taskCtx, {
        type: 'doughnut',
        data: {
            labels: [
                '{{ __("app.tasks.completed") }}',
                '{{ __("app.tasks.in_progress") }}',
                '{{ __("app.tasks.pending") }}'
            ],
            datasets: [{
                data: taskData,
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#6c757d'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 10,
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((context.parsed * 100) / total) : 0;
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection