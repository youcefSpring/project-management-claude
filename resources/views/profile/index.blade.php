@extends('layouts.sidebar')

@section('title', __('app.profile_settings.title'))
@section('page-title', __('app.profile_settings.title'))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.profile_settings.profile_information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('app.profile_settings.name') }}:</strong>
                        <p>{{ auth()->user()->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('app.profile_settings.email') }}:</strong>
                        <p>{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{ __('app.profile_settings.role') }}:</strong>
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
                        <strong>{{ __('app.profile_settings.member_since') }}:</strong>
                        <p>{{ \Carbon\Carbon::parse(auth()->user()->created_at)->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('profile.settings') }}" class="btn btn-primary">
                        <i class="bi bi-gear me-2"></i>
                        {{ __('app.profile_settings.edit_profile') }}
                    </a>
                </div>
            </div>
        </div>

        @php
    $user = auth()->user();
    $hasTimeData = $user->timeEntries()->where('start_time', '>=', now()->subDays(7))->exists();
    $hasTaskData = $user->assignedTasks()->exists();
@endphp

@if($hasTimeData || $hasTaskData)
<!-- My Productivity Charts -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">{{ __('app.productivity.overview') }}</h5>
    </div>
    <div class="card-body">
        @if($hasTimeData)
        <!-- Weekly Hours Chart -->
        <div class="mb-4">
            <h6>{{ __('app.productivity.weekly_summary') }}</h6>
            <div style="height: 200px; position: relative;">
                <canvas id="weeklyHoursChart"></canvas>
            </div>
        </div>
        @endif

        @if($hasTaskData)
        <!-- Task Progress Chart -->
        <div class="mb-3">
            <h6>{{ __('app.productivity.tasks_completed') }}</h6>
            <div style="height: 200px; position: relative;">
                <canvas id="taskProgressChart"></canvas>
            </div>
        </div>
        @endif
    </div>
</div>
@endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('app.profile_settings.quick_stats') }}</h5>
            </div>
            <div class="card-body">
                @php
                    $user = auth()->user();
                    $myTasks = $user->assignedTasks()->count();
                    $completedTasks = $user->assignedTasks()->where('status', 'completed')->count();
                    $totalTimeThisMonth = $user->timeEntries()
                        ->whereBetween('start_time', [now()->startOfMonth(), now()->endOfMonth()])
                        ->sum('duration_hours');
                @endphp

                @if($myTasks > 0 || $totalTimeThisMonth > 0)
                <div class="mb-3">
                    <strong>{{ __('app.tasks.my_tasks') }}:</strong>
                    <span class="float-end">{{ $myTasks }}</span>
                </div>
                @if($completedTasks > 0)
                <div class="mb-3">
                    <strong>{{ __('app.tasks.completed') }}:</strong>
                    <span class="float-end">{{ $completedTasks }}</span>
                </div>
                @endif
                @if($totalTimeThisMonth > 0)
                <div class="mb-3">
                    <strong>{{ __('app.time.this_month') }}:</strong>
                    <span class="float-end">{{ number_format($totalTimeThisMonth, 1) }}h</span>
                </div>
                @endif
                @else
                <p class="text-muted text-center mb-0">{{ __('app.tasks.no_tasks') }}</p>
                @endif
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
    @php
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $last7Days[] = now()->subDays($i)->format('M d');
        }

        // Get time entries for last 7 days
        $weeklyTimeEntries = auth()->user()->timeEntries()
            ->where('start_time', '>=', now()->subDays(7))
            ->get()
            ->groupBy(function($entry) {
                return \Carbon\Carbon::parse($entry->start_time)->format('Y-m-d');
            });

        $weeklyHours = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayHours = isset($weeklyTimeEntries[$date])
                ? $weeklyTimeEntries[$date]->sum('duration_hours')
                : 0;
            $weeklyHours[] = $dayHours;
        }
    @endphp

    const last7Days = @json($last7Days);
    const weeklyHours = @json($weeklyHours);

    // Task completion data
    @php
        $user = auth()->user();
        $completedTasks = $user->assignedTasks()->where('status', 'completed')->count();
        $inProgressTasks = $user->assignedTasks()->where('status', 'in_progress')->count();
        $pendingTasks = $user->assignedTasks()->where('status', 'pending')->count();
    @endphp

    const taskData = [
        {{ $completedTasks }},
        {{ $inProgressTasks }},
        {{ $pendingTasks }}
    ];

    // Common chart options
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        }
    };

    // Weekly Hours Chart
    const weeklyCanvas = document.getElementById('weeklyHoursChart');
    if (weeklyCanvas) {
        const weeklyCtx = weeklyCanvas.getContext('2d');
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
                                return context.parsed.y + 'h {{ __("app.tasks.logged") }}';
                            }
                        }
                    }
                }
            }
        });
    }

    // Task Progress Chart
    const taskCanvas = document.getElementById('taskProgressChart');
    if (taskCanvas) {
        const taskCtx = taskCanvas.getContext('2d');
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
    }
});
</script>
@endpush
@endsection