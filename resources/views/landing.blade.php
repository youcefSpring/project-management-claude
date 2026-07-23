@php
    use App\Models\LandingContent;
    use App\Models\Plan;
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

    // Editable copy: super admin override first, translation file second
    $t = fn (string $key) => LandingContent::text($key);

    $locale = app()->getLocale();
    $rtl = $locale === 'ar';
    $localeCountry = ['en' => 'us', 'fr' => 'fr', 'ar' => 'dz', 'es' => 'es'];
    $flag = fn ($code) => 'https://flagcdn.com/' . ($localeCountry[$code] ?? $code) . '.svg';
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('landing.meta_title') }}</title>
    <meta name="description" content="{{ __('landing.meta_description') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&family=Tajawal:wght@500;700&display=swap" rel="stylesheet">

    @if($rtl)
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --dz-deep: #0B2A22;      /* near-black green, page ink */
            --dz-green: #0F6B4F;     /* flag green, darkened for text contrast */
            --dz-bright: #21A97C;    /* interactive green */
            --dz-sand: #F3EFE4;      /* plaster */
            --dz-clay: #C1592B;      /* zellige terracotta, used sparingly */
            --dz-line: rgba(11, 42, 34, 0.12);
        }

        * { -webkit-font-smoothing: antialiased; }

        body {
            font-family: {{ $rtl ? "'Tajawal', 'Inter'" : "'Inter'" }}, system-ui, sans-serif;
            color: var(--dz-deep);
            background: var(--dz-sand);
            line-height: 1.65;
        }

        h1, h2, h3, .display-face {
            font-family: {{ $rtl ? "'Tajawal'" : "'Space Grotesk'" }}, system-ui, sans-serif;
            letter-spacing: {{ $rtl ? '0' : '-0.02em' }};
            line-height: 1.1;
        }

        /* ── Zellige: the eight-point star of Algerian tilework, drawn in CSS ── */
        .zellige {
            position: absolute;
            inset: 0;
            background-image:
                repeating-linear-gradient(45deg, rgba(255,255,255,0.05) 0 2px, transparent 2px 26px),
                repeating-linear-gradient(-45deg, rgba(255,255,255,0.05) 0 2px, transparent 2px 26px),
                repeating-linear-gradient(0deg, rgba(255,255,255,0.04) 0 2px, transparent 2px 26px),
                repeating-linear-gradient(90deg, rgba(255,255,255,0.04) 0 2px, transparent 2px 26px);
            mask-image: radial-gradient(120% 90% at 70% 10%, #000 20%, transparent 75%);
            -webkit-mask-image: radial-gradient(120% 90% at 70% 10%, #000 20%, transparent 75%);
            pointer-events: none;
        }

        /* ── Top bar ── */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 30;
            background: rgba(243, 239, 228, 0.88);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--dz-line);
        }
        .brand {
            font-family: 'Space Grotesk', system-ui, sans-serif;
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: -0.03em;
            color: var(--dz-deep);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
        }
        .brand-mark {
            width: 30px; height: 30px;
            display: grid; place-items: center;
            border-radius: 9px;
            background: var(--dz-green);
            color: #fff;
            font-size: 0.95rem;
        }
        .topbar a.nav-item-link {
            color: var(--dz-deep);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            opacity: 0.75;
        }
        .topbar a.nav-item-link:hover { opacity: 1; }

        .btn-dz {
            background: var(--dz-green);
            color: #fff;
            border: 0;
            border-radius: 10px;
            padding: 0.6rem 1.15rem;
            font-weight: 600;
        }
        .btn-dz:hover { background: var(--dz-bright); color: #fff; }
        .btn-dz-ghost {
            border: 1px solid var(--dz-line);
            color: var(--dz-deep);
            border-radius: 10px;
            padding: 0.6rem 1.15rem;
            font-weight: 600;
            background: transparent;
        }
        .btn-dz-ghost:hover { border-color: var(--dz-deep); color: var(--dz-deep); }

        .lang-flag { width: 20px; height: 14px; object-fit: cover; border-radius: 2px; }

        /* ── Hero ── */
        .hero {
            position: relative;
            background: linear-gradient(160deg, var(--dz-deep) 0%, var(--dz-green) 100%);
            color: #fff;
            overflow: hidden;
        }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            padding: 0.35rem 0.8rem;
            border: 1px solid rgba(255,255,255,0.28);
            border-radius: 999px;
        }
        .hero h1 {
            font-size: clamp(2.4rem, 6vw, 4.1rem);
            font-weight: 700;
            margin: 1.5rem 0 0;
        }
        .hero h1 .accent { color: #8FE3C0; }
        .hero .lede {
            font-size: 1.08rem;
            max-width: 34rem;
            color: rgba(255,255,255,0.82);
        }

        /* ── Hero board: the product itself as the hero image ── */
        .board {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.16);
            border-radius: 16px;
            padding: 0.85rem;
        }
        .board-col {
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 0.6rem;
        }
        .board-col-head {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.7);
            padding: 0.15rem 0.25rem 0.5rem;
            border-top: 3px solid var(--col);
            margin-top: -0.6rem;
            padding-top: 0.6rem;
        }
        .board-card {
            background: #fff;
            color: var(--dz-deep);
            border-radius: 9px;
            padding: 0.55rem 0.65rem;
            margin-bottom: 0.5rem;
            font-size: 0.82rem;
            font-weight: 500;
            box-shadow: 0 1px 2px rgba(0,0,0,0.12);
            border-inline-start: 3px solid var(--col);
            opacity: 0;
            transform: translateY(6px);
            animation: card-in 0.5s ease forwards;
        }
        .board-card .who {
            display: block;
            font-size: 0.68rem;
            font-weight: 600;
            color: #6b7d76;
            margin-top: 0.2rem;
        }
        .board-card.travelling { animation: card-travel 7s ease-in-out infinite; }

        @keyframes card-in { to { opacity: 1; transform: none; } }
        @keyframes card-travel {
            0%, 45%   { opacity: 1; transform: none; }
            55%, 95%  { opacity: 1; transform: translateY(-46px) scale(1.02); }
            100%      { opacity: 1; transform: none; }
        }
        @media (prefers-reduced-motion: reduce) {
            .board-card, .board-card.travelling { animation: none; opacity: 1; transform: none; }
        }

        /* ── Sections ── */
        .section { padding: 5.5rem 0; }
        .section-eyebrow {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--dz-clay);
        }
        .section h2 { font-size: clamp(1.8rem, 3.6vw, 2.6rem); font-weight: 700; }
        .lede-muted { color: rgba(11, 42, 34, 0.68); }

        .proof {
            background: #fff;
            border-block: 1px solid var(--dz-line);
        }
        .proof-item { padding-inline-start: 1rem; border-inline-start: 3px solid var(--dz-clay); }
        .proof-item h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.35rem; }

        .feature {
            background: #fff;
            border: 1px solid var(--dz-line);
            border-radius: 14px;
            padding: 1.5rem;
            height: 100%;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .feature:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(11,42,34,0.08); }
        .feature i {
            font-size: 1.35rem;
            color: var(--dz-green);
            display: block;
            margin-bottom: 0.75rem;
        }
        .feature h3 { font-size: 1.05rem; font-weight: 700; }
        .feature p { font-size: 0.94rem; margin: 0; color: rgba(11,42,34,0.7); }

        .workflow { background: var(--dz-deep); color: #fff; position: relative; overflow: hidden; }
        .workflow .lede-muted { color: rgba(255,255,255,0.75); }
        .workflow .section-eyebrow { color: #E9A17C; }
        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 999px;
            padding: 0.45rem 0.95rem;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .status-chip .dot { width: 10px; height: 10px; border-radius: 50%; background: var(--col); }
        .step-list { list-style: none; padding: 0; margin: 0; }
        .step-list li {
            display: flex;
            gap: 0.75rem;
            padding: 0.55rem 0;
            color: rgba(255,255,255,0.85);
        }
        .step-list i { color: #8FE3C0; }

        .plan {
            background: #fff;
            border: 1px solid var(--dz-line);
            border-radius: 16px;
            padding: 1.75rem;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .plan.featured { border-color: var(--dz-green); box-shadow: 0 16px 40px rgba(15,107,79,0.14); }
        .plan-name { font-weight: 700; font-size: 1.1rem; }
        .plan-price { font-family: 'Space Grotesk', system-ui, sans-serif; font-size: 2.3rem; font-weight: 700; line-height: 1; }
        .plan-price small { font-size: 0.95rem; font-weight: 500; color: rgba(11,42,34,0.6); }
        .plan ul { list-style: none; padding: 0; margin: 1.25rem 0; flex: 1; }
        .plan li { display: flex; gap: 0.6rem; padding: 0.3rem 0; font-size: 0.94rem; }
        .plan li i { color: var(--dz-bright); }
        .badge-popular {
            background: var(--dz-clay);
            color: #fff;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.25rem 0.7rem;
        }

        .faq-item { border-bottom: 1px solid var(--dz-line); padding: 1.25rem 0; }
        .faq-item h3 { font-size: 1.02rem; font-weight: 700; margin-bottom: 0.4rem; }
        .faq-item p { margin: 0; color: rgba(11,42,34,0.7); font-size: 0.95rem; }

        .closing {
            background: linear-gradient(160deg, var(--dz-green) 0%, var(--dz-deep) 100%);
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        footer { background: var(--dz-deep); color: rgba(255,255,255,0.72); padding: 3rem 0 2rem; }
        footer a { color: rgba(255,255,255,0.72); text-decoration: none; font-size: 0.94rem; }
        footer a:hover { color: #fff; }
        footer .footer-title { color: #fff; font-weight: 700; font-size: 0.85rem; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.75rem; }

        a:focus-visible, button:focus-visible {
            outline: 3px solid var(--dz-bright);
            outline-offset: 3px;
            border-radius: 6px;
        }

        @media (max-width: 767.98px) {
            .section { padding: 3.5rem 0; }
            .board { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- ══ Top bar ══ -->
<header class="topbar">
    <div class="container d-flex align-items-center justify-content-between py-3 gap-3">
        <a href="#" class="brand">
            <span class="brand-mark"><i class="bi bi-kanban-fill"></i></span>
            ProManage
        </a>

        <nav class="d-none d-lg-flex align-items-center gap-4">
            <a class="nav-item-link" href="#features">{{ __('landing.nav.features') }}</a>
            <a class="nav-item-link" href="#workflow">{{ __('landing.nav.workflow') }}</a>
            <a class="nav-item-link" href="#pricing">{{ __('landing.nav.pricing') }}</a>
        </nav>

        <div class="d-flex align-items-center gap-2">
            <div class="dropdown">
                <button class="btn btn-sm btn-dz-ghost dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ $flag($locale) }}" alt="" class="lang-flag">
                    <span class="d-none d-sm-inline">{{ LaravelLocalization::getCurrentLocaleNative() }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    @foreach(LaravelLocalization::getSupportedLocales() as $code => $properties)
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 {{ $locale === $code ? 'active' : '' }}"
                               rel="alternate" hreflang="{{ $code }}"
                               href="{{ LaravelLocalization::getLocalizedURL($code, null, [], true) }}">
                                <img src="{{ $flag($code) }}" alt="" class="lang-flag">
                                <span>{{ $properties['native'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <a href="{{ route('login') }}" class="btn btn-sm btn-dz-ghost d-none d-sm-inline-block">{{ __('landing.nav.login') }}</a>
            <a href="{{ route('register') }}" class="btn btn-sm btn-dz">{{ __('landing.nav.register') }}</a>
        </div>
    </div>
</header>

<!-- ══ Hero ══ -->
<section class="hero">
    <div class="zellige"></div>
    <div class="container position-relative py-5">
        <div class="row align-items-center g-5 py-4">
            <div class="col-lg-6">
                <span class="eyebrow">
                    <img src="{{ $flag('ar') }}" alt="" class="lang-flag">
                    {{ $t('hero.eyebrow') }}
                </span>

                <h1>
                    {{ $t('hero.title_line1') }}<br>
                    <span class="accent">{{ $t('hero.title_line2') }}</span><br>
                    {{ $t('hero.title_line3') }}
                </h1>

                <p class="lede mt-4">{{ $t('hero.lede') }}</p>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <a href="{{ route('register') }}" class="btn btn-dz btn-lg">{{ $t('hero.cta_primary') }}</a>
                    <a href="#pricing" class="btn btn-lg btn-dz-ghost text-white" style="border-color: rgba(255,255,255,0.3);">
                        {{ $t('hero.cta_secondary') }}
                    </a>
                </div>

                <p class="small mt-3 mb-0" style="color: rgba(255,255,255,0.6);">
                    <i class="bi bi-shield-check {{ $rtl ? 'ms-1' : 'me-1' }}"></i>{{ $t('hero.note') }}
                </p>
            </div>

            <div class="col-lg-6">
                <div class="d-flex align-items-center justify-content-between mb-2 small" style="color: rgba(255,255,255,0.65);">
                    <span><i class="bi bi-broadcast {{ $rtl ? 'ms-1' : 'me-1' }}"></i>{{ $t('hero.board_label') }}</span>
                    <span>{{ $t('hero.board_project') }}</span>
                </div>

                @php
                    $columns = [
                        ['key' => 'todo',   'color' => '#E9B949', 'cards' => [['card1', 'assignee1'], ['card4', 'assignee4']]],
                        ['key' => 'doing',  'color' => '#21A97C', 'cards' => [['card2', 'assignee2']]],
                        ['key' => 'review', 'color' => '#5B9BD5', 'cards' => [['card3', 'assignee3']]],
                    ];
                    $delay = 0;
                @endphp

                <div class="board">
                    @foreach($columns as $column)
                        <div class="board-col">
                            <div class="board-col-head" style="--col: {{ $column['color'] }}">{{ __('landing.board.' . $column['key']) }}</div>
                            @foreach($column['cards'] as [$title, $who])
                                @php $delay += 0.12; @endphp
                                <div class="board-card {{ $title === 'card2' ? 'travelling' : '' }}"
                                     style="--col: {{ $column['color'] }}; animation-delay: {{ $delay }}s;">
                                    {{ __('landing.board.' . $title) }}
                                    <span class="who">{{ __('landing.board.' . $who) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ Why local ══ -->
<section class="proof section">
    <div class="container">
        <h2 class="mb-5" style="max-width: 22ch;">{{ $t('proof.title') }}</h2>
        <div class="row g-4">
            @foreach(['item1', 'item2', 'item3'] as $item)
                <div class="col-md-4">
                    <div class="proof-item">
                        <h3>{{ $t('proof.' . $item . '_title') }}</h3>
                        <p class="mb-0 lede-muted">{{ $t('proof.' . $item . '_body') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ Features ══ -->
<section class="section" id="features">
    <div class="container">
        <p class="section-eyebrow mb-2">{{ $t('features.eyebrow') }}</p>
        <h2>{{ $t('features.title') }}</h2>
        <p class="lede-muted mb-5" style="max-width: 46ch;">{{ $t('features.lede') }}</p>

        @php
            $features = [
                'f1' => 'bi-folder2-open',
                'f2' => 'bi-kanban',
                'f3' => 'bi-clock-history',
                'f4' => 'bi-chat-dots',
                'f5' => 'bi-person-badge',
                'f6' => 'bi-bar-chart',
            ];
        @endphp

        <div class="row g-4">
            @foreach($features as $key => $icon)
                <div class="col-md-6 col-lg-4">
                    <div class="feature">
                        <i class="bi {{ $icon }}"></i>
                        <h3>{{ $t('features.' . $key . '_title') }}</h3>
                        <p>{{ $t('features.' . $key . '_body') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ══ Custom statuses — the differentiator ══ -->
<section class="workflow section" id="workflow">
    <div class="zellige"></div>
    <div class="container position-relative">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <p class="section-eyebrow mb-2">{{ $t('workflow.eyebrow') }}</p>
                <h2>{{ $t('workflow.title') }}</h2>
                <p class="lede-muted mt-3">{{ $t('workflow.body') }}</p>

                <ul class="step-list mt-4">
                    @foreach(['step1', 'step2', 'step3'] as $step)
                        <li><i class="bi bi-check2"></i><span>{{ $t('workflow.' . $step) }}</span></li>
                    @endforeach
                </ul>

                <a href="{{ route('register') }}" class="btn btn-dz mt-4">{{ $t('workflow.cta') }}</a>
            </div>

            <div class="col-lg-6">
                @php
                    $samples = [
                        'sample1' => '#9C8AA5',
                        'sample2' => '#E9B949',
                        'sample3' => '#21A97C',
                        'sample4' => '#C1592B',
                    ];
                @endphp
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    @foreach($samples as $sample => $color)
                        <span class="status-chip" style="--col: {{ $color }}">
                            <span class="dot"></span>{{ __('landing.workflow.' . $sample) }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ Pricing ══ -->
<section class="section" id="pricing">
    <div class="container">
        <p class="section-eyebrow mb-2">{{ $t('pricing.eyebrow') }}</p>
        <h2>{{ $t('pricing.title') }}</h2>
        <p class="lede-muted mb-5">{{ $t('pricing.lede') }}</p>

        @php $plans = Plan::published(); @endphp

        @if($plans->isEmpty())
            <p class="lede-muted">{{ $t('pricing.lede') }}</p>
        @else
            <div class="row g-4 align-items-stretch">
                @foreach($plans as $plan)
                    <div class="col-lg-4">
                        <div class="plan {{ $plan->is_featured ? 'featured' : '' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="plan-name mb-1">{{ $plan->text('name') }}</p>
                                    <p class="lede-muted small mb-3">{{ $plan->text('audience') }}</p>
                                </div>
                                @if($plan->is_featured)
                                    <span class="badge-popular">{{ __('landing.pricing.popular') }}</span>
                                @endif
                            </div>

                            <p class="plan-price mb-0" style="{{ $plan->showsPriceSuffix() ? '' : 'font-size: 1.7rem;' }}">
                                {{ $plan->priceLabel() }}@if($plan->showsPriceSuffix())<small>{{ $plan->currency }}{{ __('landing.pricing.per_month') }}</small>@endif
                            </p>

                            <ul>
                                @foreach($plan->features() as $feature)
                                    <li><i class="bi bi-check2"></i><span>{{ $feature }}</span></li>
                                @endforeach
                            </ul>

                            @if($plan->cta_type === 'contact')
                                <a href="mailto:contact@promanage.dz" class="btn btn-dz-ghost w-100">{{ __('landing.pricing.cta_contact') }}</a>
                            @else
                                <a href="{{ route('register') }}" class="btn {{ $plan->is_featured ? 'btn-dz' : 'btn-dz-ghost' }} w-100">{{ __('landing.pricing.cta') }}</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- ══ FAQ ══ -->
<section class="section pt-0">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <h2 class="mb-4">{{ $t('faq.title') }}</h2>
                @foreach([1, 2, 3] as $n)
                    <div class="faq-item">
                        <h3>{{ $t('faq.q' . $n) }}</h3>
                        <p>{{ $t('faq.a' . $n) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ══ Closing ══ -->
<section class="closing section">
    <div class="zellige"></div>
    <div class="container position-relative text-center">
        <h2 class="mb-3" style="max-width: 20ch; margin-inline: auto;">{{ $t('cta.title') }}</h2>
        <p class="mb-4 mx-auto" style="max-width: 44ch; color: rgba(255,255,255,0.8);">{{ $t('cta.body') }}</p>
        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a href="{{ route('register') }}" class="btn btn-lg btn-light fw-semibold" style="color: var(--dz-deep); border-radius: 10px;">
                {{ $t('cta.button') }}
            </a>
            <a href="{{ route('login') }}" class="btn btn-lg btn-dz-ghost text-white" style="border-color: rgba(255,255,255,0.35);">
                {{ $t('cta.secondary') }}
            </a>
        </div>
    </div>
</section>

<!-- ══ Footer ══ -->
<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-md-5">
                <a href="#" class="brand text-white mb-2">
                    <span class="brand-mark"><i class="bi bi-kanban-fill"></i></span>
                    ProManage
                </a>
                <p class="mb-0 mt-2" style="max-width: 32ch;">{{ $t('footer.tagline') }}</p>
            </div>

            <div class="col-6 col-md-3">
                <p class="footer-title">{{ __('landing.footer.product') }}</p>
                <ul class="list-unstyled d-grid gap-2">
                    <li><a href="#features">{{ __('landing.nav.features') }}</a></li>
                    <li><a href="#workflow">{{ __('landing.nav.workflow') }}</a></li>
                    <li><a href="#pricing">{{ __('landing.nav.pricing') }}</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-4">
                <p class="footer-title">{{ __('landing.footer.company') }}</p>
                <ul class="list-unstyled d-grid gap-2">
                    <li><a href="{{ route('login') }}">{{ __('landing.nav.login') }}</a></li>
                    <li><a href="{{ route('register') }}">{{ __('landing.nav.register') }}</a></li>
                    <li><a href="mailto:contact@promanage.dz">{{ __('landing.footer.contact') }}</a></li>
                </ul>
            </div>
        </div>

        <hr style="border-color: rgba(255,255,255,0.15);" class="my-4">

        <div class="d-flex flex-wrap justify-content-between gap-2 small">
            <span>© {{ date('Y') }} ProManage. {{ __('landing.footer.rights') }}</span>
            <span><i class="bi bi-geo-alt {{ $rtl ? 'ms-1' : 'me-1' }}"></i>{{ $t('footer.built') }}</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
