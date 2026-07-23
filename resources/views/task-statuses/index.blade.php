@extends('layouts.sidebar')

@section('title', __('Task Statuses'))
@section('page-title', __('Task Statuses'))

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h2 class="mb-1">{{ __('Task Statuses') }}</h2>
                <p class="text-muted mb-0">{{ __('Statuses used by the tasks of your organization.') }}</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStatusModal">
                <i class="bi bi-plus-circle me-2"></i>{{ __('New Status') }}
            </button>
        </div>
    </div>

    @if(session('error'))
        <div class="col-12">
            <div class="alert alert-danger">{{ session('error') }}</div>
        </div>
    @endif

    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Order') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Slug') }}</th>
                            <th>{{ __('Color') }}</th>
                            <th>{{ __('Default') }}</th>
                            <th>{{ __('Final') }}</th>
                            <th>{{ __('Tasks') }}</th>
                            <th class="text-end">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($statuses as $status)
                            @php $used = $usage[$status->id] ?? 0; @endphp
                            <tr>
                                <td>{{ $status->sort_order }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $status->color }}">{{ $status->name }}</span>
                                </td>
                                <td><code>{{ $status->slug }}</code></td>
                                <td><span class="text-muted small">{{ $status->color }}</span></td>
                                <td>
                                    @if($status->is_default)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($status->is_final)
                                        <i class="bi bi-flag-fill text-secondary"></i>
                                    @endif
                                </td>
                                <td>{{ $used }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="modal" data-bs-target="#editStatusModal{{ $status->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    @if($used === 0 && $statuses->count() > 1)
                                        <form action="{{ route('task-statuses.destroy', $status) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('{{ __('Delete this status?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-danger" disabled
                                                title="{{ $used > 0 ? __('Status is used by tasks') : __('Last status cannot be deleted') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">{{ __('No status defined yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach($statuses as $status)
    <!-- Edit modal -->
    <div class="modal fade" id="editStatusModal{{ $status->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('task-statuses.update', $status) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Status') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" name="name" class="form-control" value="{{ $status->name }}" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Color') }}</label>
                        <input type="color" name="color" class="form-control form-control-color" value="{{ $status->color }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Order') }}</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ $status->sort_order }}" min="0">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_default" value="1" class="form-check-input"
                               id="edit-default-{{ $status->id }}" {{ $status->is_default ? 'checked' : '' }}>
                        <label class="form-check-label" for="edit-default-{{ $status->id }}">
                            {{ __('Default status for new tasks') }}
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_final" value="1" class="form-check-input"
                               id="edit-final-{{ $status->id }}" {{ $status->is_final ? 'checked' : '' }}>
                        <label class="form-check-label" for="edit-final-{{ $status->id }}">
                            {{ __('Closed status (task no longer active)') }}
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<!-- Create modal -->
<div class="modal fade" id="createStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('task-statuses.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('New Status') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">{{ __('Name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required maxlength="100">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Color') }}</label>
                    <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', '#6c757d') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Order') }}</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order') }}" min="0">
                </div>
                <div class="form-check">
                    <input type="checkbox" name="is_default" value="1" class="form-check-input" id="create-default">
                    <label class="form-check-label" for="create-default">{{ __('Default status for new tasks') }}</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="is_final" value="1" class="form-check-input" id="create-final">
                    <label class="form-check-label" for="create-final">{{ __('Closed status (task no longer active)') }}</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
            </div>
        </form>
    </div>
</div>

@if(isset($errors) && $errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('createStatusModal')).show();
    });
</script>
@endif
@endsection
