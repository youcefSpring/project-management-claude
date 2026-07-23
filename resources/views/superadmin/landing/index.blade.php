@extends('layouts.sidebar')

@php
    $localeNames = ['en' => 'English', 'fr' => 'Français', 'ar' => 'العربية'];
    $groupTitles = [
        'hero' => __('app.landing_admin.hero'),
        'proof' => __('app.landing_admin.proof'),
        'features' => __('app.landing_admin.features'),
        'workflow' => __('app.landing_admin.workflow'),
        'pricing' => __('app.landing_admin.pricing'),
        'faq' => __('app.landing_admin.faq'),
        'cta' => __('app.landing_admin.cta'),
        'footer' => __('app.landing_admin.footer'),
    ];
    $fieldLabel = function (string $key) {
        $suffix = substr($key, strrpos($key, '.') + 1);
        $t = fn (string $token) => __('app.landing_admin.fields.'.$token);

        if (preg_match('/^title_line(\d)$/', $suffix, $m)) {
            return $t('title').' — '.$t('line').' '.$m[1];
        }
        if (preg_match('/^item(\d)_(title|body)$/', $suffix, $m)) {
            return $t('item').' '.$m[1].' — '.$t($m[2]);
        }
        if (preg_match('/^f(\d)_(title|body)$/', $suffix, $m)) {
            return $t('feature').' '.$m[1].' — '.$t($m[2]);
        }
        if (preg_match('/^step(\d)$/', $suffix, $m)) {
            return $t('step').' '.$m[1];
        }
        if (preg_match('/^q(\d)$/', $suffix, $m)) {
            return $t('question').' '.$m[1];
        }
        if (preg_match('/^a(\d)$/', $suffix, $m)) {
            return $t('answer').' '.$m[1];
        }

        $label = $t($suffix);

        return $label === 'app.landing_admin.fields.'.$suffix ? $suffix : $label;
    };
@endphp

@section('title', __('app.landing_admin.title'))
@section('page-title', __('app.landing_admin.title'))

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h2 class="mb-1">{{ __('app.landing_admin.title') }}</h2>
                <p class="text-muted mb-0">{{ __('app.landing_admin.intro') }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ LaravelLocalization::getLocalizedURL($locale, '/') }}" target="_blank" class="btn btn-outline-secondary">
                    <i class="bi bi-box-arrow-up-right me-2"></i>{{ __('app.landing_admin.view_page') }}
                </a>
                <form method="POST" action="{{ route('superadmin.landing.reset') }}"
                      onsubmit="return confirm('{{ __('app.landing_admin.reset_confirm') }}')">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="locale" value="{{ $locale }}">
                    <button type="submit" class="btn btn-outline-danger">{{ __('app.landing_admin.reset_language') }}</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <ul class="nav nav-pills">
            @foreach($localeNames as $code => $label)
                <li class="nav-item">
                    <a class="nav-link {{ $locale === $code ? 'active' : '' }}"
                       href="{{ route('superadmin.landing.index', ['locale' => $code]) }}">{{ $label }}</a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="col-12">
        <form method="POST" action="{{ route('superadmin.landing.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="locale" value="{{ $locale }}">

            <div class="accordion" id="landingAccordion">
                @foreach($groups as $group => $keys)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#group-{{ $group }}">
                                {{ $groupTitles[$group] ?? $group }}
                                <span class="badge bg-secondary bg-opacity-10 text-secondary ms-2">{{ count($keys) }}</span>
                            </button>
                        </h2>
                        <div id="group-{{ $group }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                             data-bs-parent="#landingAccordion">
                            <div class="accordion-body">
                                @foreach($keys as $key)
                                    @php
                                        $default = trans('landing.'.$key, [], $locale);
                                        $current = $overrides[$key] ?? '';
                                        $long = str_contains($key, 'body') || str_contains($key, 'lede') || str_starts_with(substr($key, strrpos($key, '.') + 1), 'a');
                                    @endphp
                                    <div class="mb-3">
                                        <label class="form-label d-flex justify-content-between align-items-center">
                                            <span class="fw-semibold">{{ $fieldLabel($key) }}</span>
                                            @if($current !== '')
                                                <span class="badge bg-success bg-opacity-10 text-success">{{ __('app.landing_admin.custom') }}</span>
                                            @endif
                                        </label>
                                        @if($long)
                                            <textarea name="content[{{ $key }}]" class="form-control" rows="3"
                                                      dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}"
                                                      placeholder="{{ $default }}">{{ $current }}</textarea>
                                        @else
                                            <input type="text" name="content[{{ $key }}]" class="form-control"
                                                   dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}"
                                                   value="{{ $current }}" placeholder="{{ $default }}">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary mt-4">{{ __('app.save') }}</button>
        </form>
    </div>
</div>
@endsection
