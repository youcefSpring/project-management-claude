<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LandingContent extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'locale', 'value'];

    public const LOCALES = ['en', 'fr', 'ar'];

    /**
     * Keys a super admin can rewrite, grouped for the editing screen.
     * Everything else on the landing page keeps its translation-file text.
     */
    public static function editableKeys(): array
    {
        return [
            'hero' => [
                'hero.eyebrow',
                'hero.title_line1',
                'hero.title_line2',
                'hero.title_line3',
                'hero.lede',
                'hero.cta_primary',
                'hero.cta_secondary',
                'hero.note',
            ],
            'proof' => [
                'proof.title',
                'proof.item1_title', 'proof.item1_body',
                'proof.item2_title', 'proof.item2_body',
                'proof.item3_title', 'proof.item3_body',
            ],
            'features' => [
                'features.eyebrow', 'features.title', 'features.lede',
                'features.f1_title', 'features.f1_body',
                'features.f2_title', 'features.f2_body',
                'features.f3_title', 'features.f3_body',
                'features.f4_title', 'features.f4_body',
                'features.f5_title', 'features.f5_body',
                'features.f6_title', 'features.f6_body',
            ],
            'workflow' => [
                'workflow.eyebrow', 'workflow.title', 'workflow.body',
                'workflow.step1', 'workflow.step2', 'workflow.step3', 'workflow.cta',
            ],
            'pricing' => [
                'pricing.eyebrow', 'pricing.title', 'pricing.lede',
            ],
            'faq' => [
                'faq.title',
                'faq.q1', 'faq.a1',
                'faq.q2', 'faq.a2',
                'faq.q3', 'faq.a3',
            ],
            'cta' => [
                'cta.title', 'cta.body', 'cta.button', 'cta.secondary',
            ],
            'footer' => [
                'footer.tagline', 'footer.built',
            ],
        ];
    }

    /**
     * Landing copy for a key: the super admin's text when set, otherwise the translation file.
     */
    public static function text(string $key, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        return static::overrides($locale)[$key] ?? __('landing.'.$key);
    }

    /**
     * All overrides for one locale, keyed by content key.
     */
    public static function overrides(string $locale): array
    {
        return Cache::rememberForever(static::cacheKey($locale), function () use ($locale) {
            return static::where('locale', $locale)->pluck('value', 'key')->all();
        });
    }

    public static function cacheKey(string $locale): string
    {
        return "landing_contents.{$locale}";
    }

    public static function flushCache(): void
    {
        foreach (static::LOCALES as $locale) {
            Cache::forget(static::cacheKey($locale));
        }
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::flushCache());
        static::deleted(fn () => static::flushCache());
    }
}
