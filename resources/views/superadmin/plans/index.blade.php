@extends('layouts.sidebar')

@section('title', __('Plans'))
@section('page-title', __('Plans'))

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h2 class="mb-1">{{ __('Plans') }}</h2>
                <p class="text-muted mb-0">{{ __('What visitors see in the pricing section of the landing page.') }}</p>
            </div>
            <a href="{{ route('superadmin.plans.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>{{ __('New plan') }}
            </a>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Order') }}</th>
                            <th>{{ __('Plan') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Button') }}</th>
                            <th>{{ __('On the page') }}</th>
                            <th class="text-end">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                            <tr>
                                <td>{{ $plan->sort_order }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $plan->text('name') ?: $plan->slug }}</span>
                                    @if($plan->is_featured)
                                        <span class="badge ms-2" style="background-color: var(--dz-clay);">{{ __('Highlighted') }}</span>
                                    @endif
                                    <div class="small text-muted">{{ $plan->text('audience') }}</div>
                                </td>
                                <td>
                                    {{ $plan->priceLabel() }}
                                    @if($plan->showsPriceSuffix())
                                        <span class="text-muted small">{{ $plan->currency }}{{ __('landing.pricing.per_month') }}</span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ $plan->cta_type === 'contact' ? __('Contact us') : __('Sign up') }}
                                </td>
                                <td>
                                    @if($plan->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success">{{ __('Published') }}</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ __('Hidden') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('superadmin.plans.edit', $plan) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('superadmin.plans.destroy', $plan) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('{{ __('Delete this plan?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    {{ __('No plan yet. The landing page shows nothing in the pricing section until you add one.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
