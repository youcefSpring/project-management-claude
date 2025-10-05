<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Translation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'language',
        'value',
    ];

    /**
     * Supported languages
     */
    const SUPPORTED_LANGUAGES = ['fr', 'en', 'ar'];

    /**
     * Default language
     */
    const DEFAULT_LANGUAGE = 'fr';

    /**
     * RTL languages
     */
    const RTL_LANGUAGES = ['ar'];

    /**
     * Get all supported languages
     */
    public static function getSupportedLanguages(): array
    {
        return self::SUPPORTED_LANGUAGES;
    }

    /**
     * Check if language is supported
     */
    public static function isLanguageSupported(string $language): bool
    {
        return in_array($language, self::SUPPORTED_LANGUAGES);
    }

    /**
     * Check if language is RTL
     */
    public static function isRtlLanguage(string $language): bool
    {
        return in_array($language, self::RTL_LANGUAGES);
    }

    /**
     * Get translation for a specific key and language
     */
    public static function get(string $key, ?string $language = null, ?string $default = null): string
    {
        $language = $language ?? self::DEFAULT_LANGUAGE;

        // Use cache for better performance
        $cacheKey = "translation.{$language}.{$key}";

        return Cache::remember($cacheKey, 3600, function () use ($key, $language, $default) {
            $translation = self::where('key', $key)
                ->where('language', $language)
                ->first();

            if ($translation) {
                return $translation->value;
            }

            // Fallback to default language if not found
            if ($language !== self::DEFAULT_LANGUAGE) {
                $fallback = self::where('key', $key)
                    ->where('language', self::DEFAULT_LANGUAGE)
                    ->first();

                if ($fallback) {
                    return $fallback->value;
                }
            }

            // Return default or key if no translation found
            return $default ?? $key;
        });
    }

    /**
     * Set translation for a key and language
     */
    public static function set(string $key, string $language, string $value): self
    {
        if (! self::isLanguageSupported($language)) {
            throw new \InvalidArgumentException("Language '{$language}' is not supported");
        }

        $translation = self::updateOrCreate(
            ['key' => $key, 'language' => $language],
            ['value' => $value]
        );

        // Clear cache
        Cache::forget("translation.{$language}.{$key}");

        return $translation;
    }

    /**
     * Get all translations for a specific language
     */
    public static function getAllForLanguage(string $language): array
    {
        if (! self::isLanguageSupported($language)) {
            return [];
        }

        $cacheKey = "translations.{$language}";

        return Cache::remember($cacheKey, 3600, function () use ($language) {
            return self::where('language', $language)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Get translations for multiple keys
     */
    public static function getMultiple(array $keys, ?string $language = null): array
    {
        $language = $language ?? self::DEFAULT_LANGUAGE;
        $translations = [];

        foreach ($keys as $key) {
            $translations[$key] = self::get($key, $language);
        }

        return $translations;
    }

    /**
     * Import translations from array
     */
    public static function import(array $translations, string $language): int
    {
        if (! self::isLanguageSupported($language)) {
            throw new \InvalidArgumentException("Language '{$language}' is not supported");
        }

        $imported = 0;

        foreach ($translations as $key => $value) {
            self::set($key, $language, $value);
            $imported++;
        }

        return $imported;
    }

    /**
     * Export translations for a language
     */
    public static function export(string $language): array
    {
        return self::getAllForLanguage($language);
    }

    /**
     * Get missing translations (keys that exist in one language but not another)
     */
    public static function getMissingTranslations(string $fromLanguage, string $toLanguage): array
    {
        $fromKeys = self::where('language', $fromLanguage)->pluck('key')->toArray();
        $toKeys = self::where('language', $toLanguage)->pluck('key')->toArray();

        return array_diff($fromKeys, $toKeys);
    }

    /**
     * Clear all translation cache
     */
    public static function clearCache(): void
    {
        foreach (self::SUPPORTED_LANGUAGES as $language) {
            Cache::forget("translations.{$language}");

            // Clear individual key caches (this is less efficient, but thorough)
            $keys = self::where('language', $language)->pluck('key');
            foreach ($keys as $key) {
                Cache::forget("translation.{$language}.{$key}");
            }
        }
    }

    /**
     * Scope to filter by language
     */
    public function scopeForLanguage($query, string $language)
    {
        return $query->where('language', $language);
    }

    /**
     * Scope to filter by key pattern
     */
    public function scopeKeyLike($query, string $pattern)
    {
        return $query->where('key', 'LIKE', $pattern);
    }

    /**
     * Scope to search in values
     */
    public function scopeValueLike($query, string $search)
    {
        return $query->where('value', 'LIKE', "%{$search}%");
    }

    /**
     * Get translation statistics
     */
    public static function getStats(): array
    {
        $stats = [];

        foreach (self::SUPPORTED_LANGUAGES as $language) {
            $stats[$language] = [
                'total' => self::forLanguage(self::query(), $language)->count(),
                'name' => self::getLanguageName($language),
                'is_rtl' => self::isRtlLanguage($language),
            ];
        }

        return $stats;
    }

    /**
     * Get language display name
     */
    public static function getLanguageName(string $language): string
    {
        $names = [
            'fr' => 'Français',
            'en' => 'English',
            'ar' => 'العربية',
        ];

        return $names[$language] ?? $language;
    }

    /**
     * Bootstrap default translations
     */
    public static function bootstrap(): void
    {
        $defaultTranslations = [
            'fr' => [
                'app.name' => 'Gestion de Projets',
                'nav.projects' => 'Projets',
                'nav.tasks' => 'Tâches',
                'nav.timesheet' => 'Feuille de temps',
                'nav.reports' => 'Rapports',
                'nav.dashboard' => 'Tableau de bord',
                'auth.login' => 'Connexion',
                'auth.logout' => 'Déconnexion',
                'common.save' => 'Enregistrer',
                'common.cancel' => 'Annuler',
                'common.delete' => 'Supprimer',
                'common.edit' => 'Modifier',
            ],
            'en' => [
                'app.name' => 'Project Management',
                'nav.projects' => 'Projects',
                'nav.tasks' => 'Tasks',
                'nav.timesheet' => 'Timesheet',
                'nav.reports' => 'Reports',
                'nav.dashboard' => 'Dashboard',
                'auth.login' => 'Login',
                'auth.logout' => 'Logout',
                'common.save' => 'Save',
                'common.cancel' => 'Cancel',
                'common.delete' => 'Delete',
                'common.edit' => 'Edit',
            ],
            'ar' => [
                'app.name' => 'إدارة المشاريع',
                'nav.projects' => 'المشاريع',
                'nav.tasks' => 'المهام',
                'nav.timesheet' => 'جدول الأوقات',
                'nav.reports' => 'التقارير',
                'nav.dashboard' => 'لوحة التحكم',
                'auth.login' => 'تسجيل الدخول',
                'auth.logout' => 'تسجيل الخروج',
                'common.save' => 'حفظ',
                'common.cancel' => 'إلغاء',
                'common.delete' => 'حذف',
                'common.edit' => 'تعديل',
            ],
        ];

        foreach ($defaultTranslations as $language => $translations) {
            self::import($translations, $language);
        }
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when translation is updated
        static::saved(function ($translation) {
            Cache::forget("translation.{$translation->language}.{$translation->key}");
            Cache::forget("translations.{$translation->language}");
        });

        static::deleted(function ($translation) {
            Cache::forget("translation.{$translation->language}.{$translation->key}");
            Cache::forget("translations.{$translation->language}");
        });

        // Validate language and key
        static::saving(function ($translation) {
            if (! self::isLanguageSupported($translation->language)) {
                throw new \InvalidArgumentException("Language '{$translation->language}' is not supported");
            }

            if (empty(trim($translation->key))) {
                throw new \InvalidArgumentException('Translation key cannot be empty');
            }

            if (empty(trim($translation->value))) {
                throw new \InvalidArgumentException('Translation value cannot be empty');
            }
        });
    }
}
