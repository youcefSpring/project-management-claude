@extends('layouts.sidebar')

@section('title', $project->title)
@section('page-title', $project->title)

@section('content')
@php
    $statusLabels = [
        'planning' => __('app.projects.planning'),
        'active' => __('app.projects.active'),
        'on_hold' => __('app.projects.on_hold'),
        'completed' => __('app.projects.completed'),
        'cancelled' => __('app.projects.cancelled'),
    ];
    $canManage = auth()->user()->isAdmin() || auth()->user()->isManager();
@endphp

<!-- Project Hero -->
<div class="card project-hero mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                    <h1 class="h3 mb-0 me-2">{{ $project->title }}</h1>
                    <span class="badge bg-light text-dark">{{ $statusLabels[$project->status] ?? ucfirst($project->status) }}</span>
                </div>
                @if($project->description)
                    <p class="mb-3" style="color: rgba(255,255,255,.85);">{{ $project->description }}</p>
                @endif

                <div class="row g-3">
                    <div class="col-sm-6 col-xl-3">
                        <div class="hero-stat p-2 px-3">
                            <small class="hero-label d-block"><i class="bi bi-person-fill me-1"></i>{{ __('app.projects.manager') }}</small>
                            <span class="fw-semibold">{{ $project->manager->name ?? '—' }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="hero-stat p-2 px-3">
                            <small class="hero-label d-block"><i class="bi bi-calendar me-1"></i>{{ __('app.projects.start_date') }}</small>
                            <span class="fw-semibold">{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : __('app.not_available') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="hero-stat p-2 px-3">
                            <small class="hero-label d-block"><i class="bi bi-calendar-check me-1"></i>{{ __('app.projects.end_date') }}</small>
                            <span class="fw-semibold">{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M d, Y') : __('app.not_available') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="hero-stat p-2 px-3">
                            <small class="hero-label d-block"><i class="bi bi-clock me-1"></i>{{ __('app.projects.total_hours') }}</small>
                            <span class="fw-semibold">{{ $stats['total_hours'] }}h</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 text-center">
                <div class="progress-ring mx-auto mb-3" style="--pct: {{ $stats['progress_percentage'] }};">
                    <div class="progress-ring-label">
                        <div class="h3 mb-0 fw-bold">{{ $stats['progress_percentage'] }}%</div>
                        <small class="hero-label">{{ __('app.projects.complete') }}</small>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-6">
                        <div class="fw-bold fs-4">{{ $stats['total_tasks'] }}</div>
                        <small class="hero-label">{{ __('app.projects.total_tasks') }}</small>
                    </div>
                    <div class="col-6">
                        <div class="fw-bold fs-4">{{ $stats['completed_tasks'] }}</div>
                        <small class="hero-label">{{ __('app.projects.completed_tasks') }}</small>
                    </div>
                </div>
            </div>
        </div>

        @if($canManage)
        <div class="mt-4 pt-3 border-top border-light border-opacity-25 d-flex flex-wrap gap-2">
            <a href="{{ route('projects.edit', $project) }}" class="btn btn-light btn-sm">
                <i class="bi bi-pencil me-1"></i>{{ __('app.projects.edit_project') }}
            </a>
            <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}" class="btn btn-outline-light btn-sm"
               data-modal-url="{{ route('tasks.create') }}?project_id={{ $project->id }}" data-modal-title="{{ __('app.projects.add_task') }}">
                <i class="bi bi-plus-circle me-1"></i>{{ __('app.projects.add_task') }}
            </a>
            <a href="{{ route('chat.project', $project) }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-chat-dots me-1"></i>{{ __('app.chat.open_chat') }}
            </a>
        </div>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- Tasks Section -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-check2-square me-2"></i>
                    {{ __('app.projects.project_tasks') }}
                    <span class="badge bg-secondary ms-1">{{ $project->tasks->count() }}</span>
                </h5>
                @if($canManage)
                <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}" class="btn btn-sm btn-primary"
                   data-modal-url="{{ route('tasks.create') }}?project_id={{ $project->id }}" data-modal-title="{{ __('app.projects.add_task') }}">
                    <i class="bi bi-plus-circle me-1"></i>{{ __('app.projects.add_task') }}
                </a>
                @endif
            </div>
            <div class="card-body">
                <!-- Task Filters -->
                <form method="GET" action="{{ route('projects.show', $project) }}" class="row g-2 mb-3">
                    <div class="col-md-4">
                        <select class="form-select form-select-sm" name="status" onchange="this.form.submit()">
                            <option value="">{{ __('app.projects.all_tasks') }}</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('app.tasks.pending') }}</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('app.tasks.in_progress') }}</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('app.tasks.completed') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control form-control-sm" name="search"
                               value="{{ request('search') }}" placeholder="{{ __('app.projects.search_tasks') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Tasks List -->
                @forelse($project->tasks as $task)
                    <div class="card border-start border-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'warning' : 'secondary') }} border-3 mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-1">
                                        <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none">{{ $task->title }}</a>
                                    </h6>
                                    @if($task->description)
                                        <p class="text-muted small mb-2">{{ \Illuminate\Support\Str::limit($task->description, 120) }}</p>
                                    @endif
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <span class="badge status-{{ $task->status }} status-badge">
                                            @switch($task->status)
                                                @case('pending') {{ __('app.tasks.pending') }} @break
                                                @case('in_progress') {{ __('app.tasks.in_progress') }} @break
                                                @case('completed') {{ __('app.tasks.completed') }} @break
                                                @case('cancelled') {{ __('app.tasks.cancelled') }} @break
                                                @default {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            @endswitch
                                        </span>
                                        @if($task->assignedUser)
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-person me-1"></i>{{ $task->assignedUser->name }}
                                            </span>
                                        @endif
                                        @if($task->due_date)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-calendar me-1"></i>{{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-label="{{ __('app.tasks.title') }}">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('tasks.show', $task) }}">
                                            <i class="bi bi-eye me-2"></i>{{ __('app.view') }}
                                        </a></li>
                                        @can('update', $task)
                                        <li><a class="dropdown-item" href="{{ route('tasks.edit', $task) }}">
                                            <i class="bi bi-pencil me-2"></i>{{ __('app.edit') }}
                                        </a></li>
                                        @endcan
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-check2-square fs-1"></i>
                        <p class="mt-2 mb-3">{{ __('app.projects.no_tasks_found') }}</p>
                        @if($canManage)
                            <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-circle me-1"></i>{{ __('app.projects.add_task') }}
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Team Members -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>
                    {{ __('app.projects.team_members') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-sm avatar-primary me-2">{{ \Illuminate\Support\Str::substr($project->manager->name ?? '?', 0, 1) }}</div>
                    <div>
                        <div class="fw-bold">{{ $project->manager->name ?? '—' }}</div>
                        <small class="text-muted">{{ __('app.projects.project_manager') }}</small>
                    </div>
                </div>
                @forelse($teamMembers as $member)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm avatar-success me-2">{{ \Illuminate\Support\Str::substr($member->name, 0, 1) }}</div>
                        <div>
                            <div class="fw-bold">{{ $member->name }}</div>
                            <small class="text-muted">{{ __('app.projects.team_member') }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">{{ __('app.unassigned') }}</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-activity me-2"></i>
                    {{ __('app.projects.recent_activity') }}
                </h5>
            </div>
            <div class="card-body activity-feed">
                @forelse($recentActivity as $item)
                    @php
                        $dot = $item['status'] === 'completed' ? 'avatar-success' : ($item['status'] === 'in_progress' ? 'avatar-warning' : 'avatar-primary');
                        $icon = $item['status'] === 'completed' ? 'bi-check' : ($item['status'] === 'in_progress' ? 'bi-arrow-repeat' : 'bi-dot');
                    @endphp
                    <a href="{{ $item['url'] }}" class="activity-item d-flex text-decoration-none text-reset py-2">
                        <div class="avatar avatar-xs {{ $dot }} me-2"><i class="bi {{ $icon }}"></i></div>
                        <div class="flex-grow-1">
                            <div class="small">{{ \Illuminate\Support\Str::limit($item['title'], 40) }}</div>
                            <small class="text-muted">{{ $item['ago'] }}</small>
                        </div>
                    </a>
                @empty
                    <p class="text-muted small mb-0 text-center py-2">{{ __('app.dashboard.no_recent_activity') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Project Discussion Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-chat-text me-2"></i>
                    {{ __('app.projects.discussion') }}
                    @if($project->notes && $project->notes->count() > 0)
                        <span class="badge bg-secondary ms-1">{{ $project->notes->count() }}</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <!-- Add New Comment Form -->
                <form method="POST" action="{{ route('projects.notes.store', $project) }}" enctype="multipart/form-data"
                      class="border rounded p-3 mb-4 bg-light ajax-form" data-refresh="#discussionList">
                    @csrf
                    <div class="mb-3">
                        <label for="content" class="form-label">{{ __('app.comments.add_comment') }}</label>
                        <textarea class="form-control" id="content" name="content" rows="3"
                                  placeholder="{{ __('app.notes.write_comment_placeholder') }}"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="attachments" class="form-label">
                            <i class="bi bi-camera me-1"></i>{{ __('app.notes.attach_photos') }}
                        </label>
                        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple accept="image/*">
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i>{{ __('app.comments.add_comment') }}
                        </button>
                        <small class="text-muted">{{ __('app.notes.stakeholders_notified') }}</small>
                    </div>
                </form>

                <!-- Discussion List -->
                <div id="discussionList">
                @forelse(($project->notes ?? collect())->sortByDesc('created_at') as $note)
                    <div class="d-flex mb-4" data-row>
                        <div class="avatar avatar-sm me-3">{{ \Illuminate\Support\Str::substr($note->user->name ?? '?', 0, 1) }}</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $note->user->name ?? '—' }}</strong>
                                    <small class="text-muted ms-2">{{ $note->created_at->diffForHumans() }}</small>
                                </div>
                                @if($note->canBeDeletedBy(auth()->user()))
                                    <button type="button" class="btn btn-sm btn-link text-danger p-0" aria-label="{{ __('app.delete') }}"
                                            data-ajax-delete="{{ route('projects.notes.destroy', $note) }}"
                                            data-confirm="{{ __('app.messages.confirm_delete') }}" data-refresh="#discussionList">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="note-content mt-1">{!! nl2br(e($note->content)) !!}</div>
                            @if($note->hasAttachments())
                                <div class="row g-2 mt-2">
                                    @foreach($note->image_attachments as $attachment)
                                        <div class="col-md-2 col-4">
                                            <a href="{{ $attachment['versions']['original']['url'] ?? '#' }}" target="_blank">
                                                <img src="{{ $attachment['versions']['thumbnail']['url'] ?? '#' }}"
                                                     class="img-thumbnail w-100" style="height: 80px; object-fit: cover;" alt="Attachment">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-chat-text fs-1 mb-3"></i>
                        <p>{{ __('app.notes.no_comments_yet') }}</p>
                    </div>
                @endforelse
                </div>
                {{-- /#discussionList --}}
            </div>
        </div>
    </div>
</div>
@endsection
