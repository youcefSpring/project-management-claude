@extends('layouts.sidebar')

@section('title', __('app.trash.title'))
@section('page-title', __('app.trash.title'))

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <h2 class="mb-1"><i class="bi bi-trash me-2"></i>{{ __('app.trash.title') }}</h2>
        <p class="text-muted mb-0">{{ __('app.trash.subtitle') }}</p>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body p-0" id="trashList">
                @if($items->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">{{ __('app.trash.item') }}</th>
                                    <th>{{ __('app.type') ?? 'Type' }}</th>
                                    <th>{{ __('app.trash.deleted_label') }}</th>
                                    <th class="text-end pe-4">{{ __('app.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr data-row>
                                        <td class="ps-4">
                                            <i class="bi {{ $item['icon'] }} me-2 text-muted"></i>
                                            <span class="fw-semibold">{{ \Illuminate\Support\Str::limit($item['label'], 60) }}</span>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $item['type_label'] }}</span></td>
                                        <td class="text-muted small">{{ $item['deleted_at'] }}</td>
                                        <td class="text-end pe-4">
                                            <div class="d-inline-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-light text-success"
                                                        data-restore="{{ route('trash.restore', ['type' => $item['type'], 'id' => $item['id']]) }}"
                                                        data-refresh="#trashList"
                                                        title="{{ __('app.trash.restore') }}" data-bs-toggle="tooltip">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light text-danger"
                                                        data-ajax-delete="{{ route('trash.force', ['type' => $item['type'], 'id' => $item['id']]) }}"
                                                        data-confirm="{{ __('app.trash.confirm_force') }}"
                                                        data-refresh="#trashList"
                                                        title="{{ __('app.trash.delete_forever') }}" data-bs-toggle="tooltip">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-trash3 fs-1 opacity-25"></i>
                        <p class="mt-2 mb-0">{{ __('app.trash.empty') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Restore (POST) — reuses the global AJAX refresh; force-delete uses the engine's data-ajax-delete.
document.addEventListener('click', async function (e) {
    const btn = e.target.closest('[data-restore]');
    if (!btn) { return; }
    e.preventDefault();
    btn.disabled = true;
    try {
        const res = await axios.post(btn.getAttribute('data-restore'), {}, { headers: { 'Accept': 'application/json' } });
        window.ajaxToast && ajaxToast((res.data && res.data.message) || 'Restored.');
        const row = btn.closest('[data-row]');
        if (row) { row.remove(); }
    } catch (err) {
        window.ajaxToast && ajaxToast('Restore failed.', 'error');
        btn.disabled = false;
    }
});
</script>
@endpush
