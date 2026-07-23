@extends('layouts.sidebar')

@php
    $isNew = ! $plan->exists;
    $localeNames = ['en' => 'English', 'fr' => 'Français', 'ar' => 'العربية'];
@endphp

@section('title', $isNew ? __('New plan') : __('Edit plan'))
@section('page-title', $isNew ? __('New plan') : __('Edit plan'))

@section('content')
<form method="POST" action="{{ $isNew ? route('superadmin.plans.store') : route('superadmin.plans.update', $plan) }}">
    @csrf
    @unless($isNew) @method('PUT') @endunless

    <div class="row g-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">{{ $isNew ? __('New plan') : __('Edit plan') }}</h2>
                <a href="{{ route('superadmin.plans.index') }}" class="btn btn-outline-secondary">{{ __('app.cancel') }}</a>
            </div>
        </div>

        @if($errors->any())
            <div class="col-12">
                <div class="alert alert-danger mb-0">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">{{ __('Price and placement') }}</h6>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_free" value="1" class="form-check-input" id="is_free"
                               {{ old('is_free', $plan->is_free) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_free">{{ __('Free plan') }}</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Price') }}</label>
                        <div class="input-group">
                            <input type="number" step="1" min="0" name="price" class="form-control"
                                   value="{{ old('price', $plan->price !== null ? (int) $plan->price : '') }}"
                                   placeholder="{{ __('Leave empty for a quoted price') }}">
                            <input type="text" name="currency" class="form-control" style="max-width: 90px;"
                                   value="{{ old('currency', $plan->currency ?? 'DA') }}">
                        </div>
                        <div class="form-text">{{ __('Empty price shows the "let us talk" wording instead.') }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Button') }}</label>
                        <select name="cta_type" class="form-select">
                            <option value="register" {{ old('cta_type', $plan->cta_type) === 'register' ? 'selected' : '' }}>{{ __('Sign up') }}</option>
                            <option value="contact" {{ old('cta_type', $plan->cta_type) === 'contact' ? 'selected' : '' }}>{{ __('Contact us') }}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Order') }}</label>
                        <input type="number" min="0" name="sort_order" class="form-control"
                               value="{{ old('sort_order', $plan->sort_order ?? 0) }}">
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="is_featured"
                               {{ old('is_featured', $plan->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">{{ __('Highlight this plan') }}</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                               {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">{{ __('Show on the landing page') }}</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">{{ __('Wording') }}</h6>

                    <ul class="nav nav-pills mb-3" role="tablist">
                        @foreach($localeNames as $code => $label)
                            <li class="nav-item">
                                <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="pill"
                                        data-bs-target="#pane-{{ $code }}" type="button">{{ $label }}</button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        @foreach($localeNames as $code => $label)
                            @php $t = old("translations.$code", $plan->translations[$code] ?? []); @endphp
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pane-{{ $code }}"
                                 dir="{{ $code === 'ar' ? 'rtl' : 'ltr' }}">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Name') }} @if($code === 'en')<span class="text-danger">*</span>@endif</label>
                                    <input type="text" name="translations[{{ $code }}][name]" class="form-control"
                                           value="{{ $t['name'] ?? '' }}" maxlength="80">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Who it is for') }}</label>
                                    <input type="text" name="translations[{{ $code }}][audience]" class="form-control"
                                           value="{{ $t['audience'] ?? '' }}" maxlength="120">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Price wording') }}</label>
                                    <input type="text" name="translations[{{ $code }}][price_label]" class="form-control"
                                           value="{{ $t['price_label'] ?? '' }}" maxlength="60"
                                           placeholder="{{ __('Leave empty to print the number above') }}">
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">{{ __('Features — one per line') }}</label>
                                    <textarea name="translations[{{ $code }}][features]" class="form-control" rows="5">{{ is_array($t['features'] ?? null) ? implode("\n", $t['features']) : ($t['features'] ?? '') }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
        </div>
    </div>
</form>
@endsection
