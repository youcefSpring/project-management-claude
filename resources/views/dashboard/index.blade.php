@extends('layouts.sidebar')

@section('title', __('app.Dashboard'))
@section('page-title', __('app.Dashboard'))

@section('content')
<div class="row g-3">
    <!-- Stats Cards -->
    <div class="col-12">
        <div class="row g-3">
            @php
                $tiles = [
                    ['key' => 'total_projects', 'label' => __('app.Projects'), 'variant' => '', 'icon' => 'bi-folder'],
                    ['key' => 'total_tasks', 'label' => __('app.tasks.title'), 'variant' => 'success', 'icon' => 'bi-check2-square'],
                    ['key' => 'pending_tasks', 'label' => __('app.dashboard.my_tasks'), 'variant' => 'warning', 'icon' => 'bi-person-check'],
                    ['key' => 'total_time_this_week', 'label' => __('app.time.this_week'), 'variant' => 'info', 'icon' => 'bi-clock', 'time' => true],
                ];
            @endphp
            @foreach($tiles as $tile)
                <div class="col-sm-6 col-xl-3">
                    <div class="card stats-card {{ $tile['variant'] }} text-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <h3 class="stats-number mb-0">
                                    <span class="skeleton skeleton-number d-inline-block"
                                          data-stat="{{ $tile['key'] }}"
                                          data-format="{{ !empty($tile['time']) ? 'time' : 'int' }}"></span>
                                </h3>
                                <p class="stats-label mb-0">{{ $tile['label'] }}</p>
                            </div>
                            <div class="stats-icon">
                                <i class="bi {{ $tile['icon'] }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="col-lg-8">
        <!-- Recent Activity -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-activity me-2"></i>
                    {{ __('app.dashboard.recent_activity') }}
                </h5>
            </div>
            <div class="card-body" id="dash-activity">
                @for($i = 0; $i < 4; $i++)
                    <div class="d-flex mb-3">
                        <div class="skeleton rounded-circle me-3" style="width:40px;height:40px;"></div>
                        <div class="flex-grow-1">
                            <div class="skeleton skeleton-line" style="width:70%;"></div>
                            <div class="skeleton skeleton-line" style="width:30%;"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- My Tasks (server-rendered) -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-check2-square me-2"></i>
                    {{ __('app.dashboard.my_tasks') }}
                </h5>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-primary">
                    {{ __('app.View all') }}
                </a>
            </div>
            <div class="card-body">
                @if($myTasks && count($myTasks) > 0)
                    <div class="row g-3">
                        @php
                            $columns = [
                                'pending' => ['label' => __('app.tasks.pending'), 'text' => 'text-warning', 'border' => 'border-warning'],
                                'in_progress' => ['label' => __('app.tasks.in_progress'), 'text' => 'text-info', 'border' => 'border-info'],
                                'completed' => ['label' => __('app.tasks.completed'), 'text' => 'text-success', 'border' => 'border-success'],
                            ];
                        @endphp
                        @foreach($columns as $status => $col)
                            <div class="col-md-4">
                                <h6 class="{{ $col['text'] }}">{{ $col['label'] }}</h6>
                                @forelse($tasksByStatus[$status] as $task)
                                    <div class="card border-start {{ $col['border'] }} border-3 mb-2">
                                        <div class="card-body p-2">
                                            <h6 class="card-title mb-1">
                                                <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none">{{ $task->title }}</a>
                                            </h6>
                                            <small class="text-muted">{{ $task->project->title ?? '' }}</small>
                                            @if($task->due_date)
                                                <br><small class="text-muted">{{ __('app.tasks.due_date') }}: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted small mb-0">—</p>
                                @endforelse
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="bi bi-check2-square fs-2"></i>
                        <p class="mt-2">{{ __('app.tasks.no_tasks') }}</p>
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary">
                            {{ __('app.tasks.browse_available') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    {{ __('app.dashboard.quick_actions') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <a href="{{ route('projects.create') }}" class="btn btn-outline-primary"
                       data-modal-url="{{ route('projects.create') }}" data-modal-title="{{ __('app.projects.create') }}">
                        <i class="bi bi-plus-circle me-2"></i>
                        {{ __('app.projects.create') }}
                    </a>
                    <a href="{{ route('tasks.create') }}" class="btn btn-outline-success"
                       data-modal-url="{{ route('tasks.create') }}" data-modal-title="{{ __('app.tasks.create') }}">
                        <i class="bi bi-plus-square me-2"></i>
                        {{ __('app.tasks.create') }}
                    </a>
                    @endif
                    <a href="{{ route('timesheet.create') }}" class="btn btn-outline-info"
                       data-modal-url="{{ route('timesheet.create') }}" data-modal-title="{{ __('app.time.log_time') }}">
                        <i class="bi bi-clock me-2"></i>
                        {{ __('app.time.log_time') }}
                    </a>
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-warning">
                        <i class="bi bi-graph-up me-2"></i>
                        {{ __('app.reports.title') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-bell me-2"></i>
                    {{ __('app.Notifications') }}
                </h5>
                <span class="badge bg-primary d-none" id="dash-notif-badge"></span>
            </div>
            <div class="card-body" id="dash-notifications">
                @for($i = 0; $i < 3; $i++)
                    <div class="d-flex mb-3">
                        <div class="skeleton rounded-circle me-3" style="width:30px;height:30px;"></div>
                        <div class="flex-grow-1"><div class="skeleton skeleton-line" style="width:80%;"></div></div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Time Summary -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-stopwatch me-2"></i>
                    {{ __('app.reports.time_summary') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="fs-4 fw-bold text-primary">
                        <span class="skeleton skeleton-number d-inline-block" data-stat="total_time_today" data-format="time"></span>
                    </div>
                    <p class="text-muted">{{ __("app.time.today") }}</p>
                </div>
                <div class="mb-3">
                    <small class="text-muted">{{ __('app.time.this_week') }}: </small>
                    <span class="fw-bold"><span class="skeleton skeleton-number d-inline-block" data-stat="total_time_this_week" data-format="time"></span></span>
                </div>
                <div class="mb-3">
                    <small class="text-muted">{{ __('app.time.this_month') }}: </small>
                    <span class="fw-bold"><span class="skeleton skeleton-number d-inline-block" data-stat="total_time_this_month" data-format="time"></span></span>
                </div>
                <div class="d-grid">
                    <a href="{{ route('timesheet.create') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>
                        {{ __('app.time.log_time') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const urls = {
        stats: '{{ route('dashboard.widgets.stats') }}',
        activity: '{{ route('dashboard.widgets.activity') }}',
        notifications: '{{ route('dashboard.widgets.notifications') }}',
    };
    const i18n = { noActivity: @json(__('app.dashboard.no_recent_activity')), noNotifications: @json(__('app.No notifications')) };
    const esc = (s) => { const d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; };

    function fillStats(data) {
        document.querySelectorAll('[data-stat]').forEach(function (el) {
            const v = data[el.dataset.stat] ?? 0;
            el.textContent = el.dataset.format === 'time' ? Number(v).toFixed(1) + 'h' : v;
            el.classList.remove('skeleton', 'skeleton-number');
        });
    }

    function fillActivity(items) {
        const box = document.getElementById('dash-activity');
        if (!items || !items.length) {
            box.innerHTML = '<div class="text-center text-muted"><i class="bi bi-activity fs-2"></i><p class="mt-2">' + esc(i18n.noActivity) + '</p></div>';
            return;
        }
        box.innerHTML = items.map(function (it) {
            const isTask = it.type === 'task_update';
            return '<a href="' + esc(it.url) + '" class="d-flex mb-3 text-decoration-none text-reset">'
                + '<div class="flex-shrink-0 me-3"><div class="rounded-circle d-flex align-items-center justify-content-center text-white ' + (isTask ? 'bg-primary' : 'bg-success') + '" style="width:40px;height:40px;"><i class="bi ' + (isTask ? 'bi-check2-square' : 'bi-clock') + '"></i></div></div>'
                + '<div class="flex-grow-1"><p class="mb-1">' + esc(it.description) + '</p><small class="text-muted">' + esc(it.ago) + '</small></div></a>';
        }).join('');
    }

    function fillNotifications(items) {
        const box = document.getElementById('dash-notifications');
        const badge = document.getElementById('dash-notif-badge');
        if (!items || !items.length) {
            box.innerHTML = '<div class="text-center text-muted"><i class="bi bi-bell-slash fs-2"></i><p class="mt-2">' + esc(i18n.noNotifications) + '</p></div>';
            return;
        }
        badge.textContent = items.length;
        badge.classList.remove('d-none');
        box.innerHTML = items.map(function (n) {
            return '<div class="d-flex mb-3"><div class="flex-shrink-0 me-3"><div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" style="width:30px;height:30px;"><i class="bi bi-bell-fill"></i></div></div>'
                + '<div class="flex-grow-1"><p class="mb-1 small">' + esc(n.title) + '</p><small class="text-muted">' + esc(n.message) + '</small></div></div>';
        }).join('');
    }

    function load() {
        axios.get(urls.stats).then(r => fillStats(r.data || {})).catch(() => {});
        axios.get(urls.activity).then(r => fillActivity((r.data && r.data.data) || [])).catch(() => {});
        axios.get(urls.notifications).then(r => fillNotifications((r.data && r.data.data) || [])).catch(() => {});
    }

    if (document.readyState !== 'loading') load();
    else document.addEventListener('DOMContentLoaded', load);
})();
</script>
@endpush
@endsection
