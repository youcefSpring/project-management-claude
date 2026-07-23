<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'price',
        'currency',
        'is_free',
        'cta_type',
        'is_featured',
        'is_active',
        'sort_order',
        'translations',
    ];

    protected $casts = [
        'translations' => 'array',
        'is_free' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'price' => 'decimal:2',
    ];

    public const LOCALES = ['en', 'fr', 'ar'];

    /**
     * Plans shown on the landing page, in display order.
     */
    public static function published()
    {
        return static::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * A translated field, falling back to English then to any filled locale.
     */
    public function text(string $field, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $translations = $this->translations ?? [];

        foreach ([$locale, 'en', ...self::LOCALES] as $candidate) {
            $value = $translations[$candidate][$field] ?? null;

            if (is_string($value) && trim($value) !== '') {
                return $value;
            }
        }

        return '';
    }

    /**
     * The feature bullets for a locale.
     */
    public function features(?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        $translations = $this->translations ?? [];

        foreach ([$locale, 'en', ...self::LOCALES] as $candidate) {
            $features = $translations[$candidate]['features'] ?? null;

            if (is_array($features) && $features !== []) {
                return array_values(array_filter($features, fn ($f) => trim((string) $f) !== ''));
            }
        }

        return [];
    }

    /**
     * What to print where the price goes.
     */
    public function priceLabel(?string $locale = null): string
    {
        if ($custom = $this->text('price_label', $locale)) {
            return $custom;
        }

        if ($this->is_free) {
            return __('landing.pricing.free');
        }

        if ($this->price === null) {
            return __('landing.pricing.plan3_price');
        }

        return number_format((float) $this->price, 0, ',', ' ');
    }

    /**
     * True when the price is a number and needs the "DA /month" suffix.
     */
    public function showsPriceSuffix(): bool
    {
        return ! $this->is_free && $this->price !== null && ! $this->text('price_label');
    }
}
