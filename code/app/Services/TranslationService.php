<?php

namespace App\Services;

use App\Models\Translation;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class TranslationService
{
    private const CACHE_TTL = 3600; // 1 hour
    private const CACHE_PREFIX = 'translations';

    /**
     * Get translation for key and current locale
     */
    public function get(string $key, ?string $locale = null, ?string $default = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();
        return Translation::get($key, $locale, $default);
    }

    /**
     * Set translation for key and locale
     */
    public function set(string $key, string $value, ?string $locale = null): Translation
    {
        $locale = $locale ?? $this->getCurrentLocale();
        return Translation::set($key, $locale, $value);
    }

    /**
     * Get multiple translations
     */
    public function getMultiple(array $keys, ?string $locale = null): array
    {
        $locale = $locale ?? $this->getCurrentLocale();
        return Translation::getMultiple($keys, $locale);
    }

    /**
     * Get all translations for locale
     */
    public function getAllForLocale(?string $locale = null): array
    {
        $locale = $locale ?? $this->getCurrentLocale();
        return Translation::getAllForLanguage($locale);
    }

    /**
     * Change user language
     */
    public function changeUserLanguage(User $user, string $language): bool
    {
        if (!Translation::isLanguageSupported($language)) {
            return false;
        }

        // Update user preference
        $user->update(['language' => $language]);

        // Set session locale
        $this->setSessionLocale($language);

        // Set application locale
        App::setLocale($language);

        return true;
    }

    /**
     * Get current locale
     */
    public function getCurrentLocale(): string
    {
        return Session::get('locale', Translation::DEFAULT_LANGUAGE);
    }

    /**
     * Set session locale
     */
    public function setSessionLocale(string $locale): void
    {
        if (Translation::isLanguageSupported($locale)) {
            Session::put('locale', $locale);
            App::setLocale($locale);
        }
    }

    /**
     * Initialize locale from user or session
     */
    public function initializeLocale(?User $user = null): void
    {
        $locale = Translation::DEFAULT_LANGUAGE;

        // Try to get from user preference
        if ($user && $user->language) {
            $locale = $user->language;
        }
        // Fallback to session
        elseif (Session::has('locale')) {
            $locale = Session::get('locale');
        }
        // Fallback to browser language
        else {
            $locale = $this->detectBrowserLanguage();
        }

        $this->setSessionLocale($locale);
    }

    /**
     * Get language direction (LTR/RTL)
     */
    public function getDirection(?string $locale = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();
        return Translation::isRtlLanguage($locale) ? 'rtl' : 'ltr';
    }

    /**
     * Check if current language is RTL
     */
    public function isRtl(?string $locale = null): bool
    {
        return $this->getDirection($locale) === 'rtl';
    }

    /**
     * Get language display name
     */
    public function getLanguageName(string $locale): string
    {
        return Translation::getLanguageName($locale);
    }

    /**
     * Get all supported languages with metadata
     */
    public function getSupportedLanguages(): array
    {
        return array_map(function($locale) {
            return [
                'code' => $locale,
                'name' => $this->getLanguageName($locale),
                'direction' => $this->getDirection($locale),
                'is_rtl' => $this->isRtl($locale),
            ];
        }, Translation::getSupportedLanguages());
    }

    /**
     * Get JavaScript translations for frontend
     */
    public function getJavaScriptTranslations(?string $locale = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();

        // Get common frontend translations
        $frontendKeys = [
            'common.save',
            'common.cancel',
            'common.delete',
            'common.edit',
            'common.close',
            'common.loading',
            'common.error',
            'common.success',
            'validation.required',
            'validation.email',
            'validation.min',
            'validation.max',
            'tasks.status.à_faire',
            'tasks.status.en_cours',
            'tasks.status.fait',
            'projects.status.en_cours',
            'projects.status.terminé',
            'projects.status.annulé',
            'time.hours',
            'time.minutes',
            'notifications.new_task',
            'notifications.status_changed',
        ];

        $translations = $this->getMultiple($frontendKeys, $locale);

        return json_encode([
            'locale' => $locale,
            'direction' => $this->getDirection($locale),
            'translations' => $translations,
        ]);
    }

    /**
     * Import translations from array
     */
    public function import(array $translations, string $locale): int
    {
        if (!Translation::isLanguageSupported($locale)) {
            throw new \InvalidArgumentException("Language '{$locale}' is not supported");
        }

        return Translation::import($translations, $locale);
    }

    /**
     * Export translations for locale
     */
    public function export(string $locale): array
    {
        if (!Translation::isLanguageSupported($locale)) {
            throw new \InvalidArgumentException("Language '{$locale}' is not supported");
        }

        return Translation::export($locale);
    }

    /**
     * Get missing translations between languages
     */
    public function getMissingTranslations(string $fromLocale, string $toLocale): array
    {
        return Translation::getMissingTranslations($fromLocale, $toLocale);
    }

    /**
     * Get translation statistics
     */
    public function getStatistics(): array
    {
        return Translation::getStats();
    }

    /**
     * Translate text using context-aware replacement
     */
    public function translate(string $key, array $replace = [], ?string $locale = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();
        $translation = $this->get($key, $locale, $key);

        // Replace placeholders
        foreach ($replace as $search => $replacement) {
            $translation = str_replace(":{$search}", $replacement, $translation);
        }

        return $translation;
    }

    /**
     * Get localized date format
     */
    public function getDateFormat(?string $locale = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();

        return match ($locale) {
            'fr' => 'd/m/Y',
            'en' => 'm/d/Y',
            'ar' => 'Y/m/d',
            default => 'd/m/Y',
        };
    }

    /**
     * Get localized datetime format
     */
    public function getDateTimeFormat(?string $locale = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();

        return match ($locale) {
            'fr' => 'd/m/Y H:i',
            'en' => 'm/d/Y g:i A',
            'ar' => 'Y/m/d H:i',
            default => 'd/m/Y H:i',
        };
    }

    /**
     * Get localized number format
     */
    public function formatNumber(float $number, ?string $locale = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();

        return match ($locale) {
            'fr' => number_format($number, 2, ',', ' '),
            'en' => number_format($number, 2, '.', ','),
            'ar' => number_format($number, 2, '.', ','),
            default => number_format($number, 2, '.', ','),
        };
    }

    /**
     * Get localized currency format
     */
    public function formatCurrency(float $amount, ?string $locale = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();

        $formatted = $this->formatNumber($amount, $locale);

        return match ($locale) {
            'fr' => $formatted . ' €',
            'en' => '$' . $formatted,
            'ar' => $formatted . ' د.م.',
            default => $formatted,
        };
    }

    /**
     * Clear translation cache
     */
    public function clearCache(): void
    {
        Translation::clearCache();
    }

    /**
     * Validate translation key format
     */
    public function isValidKey(string $key): bool
    {
        // Translation keys should follow dot notation (e.g., module.section.key)
        return preg_match('/^[a-z][a-z0-9_]*(\.[a-z][a-z0-9_]*)*$/', $key) === 1;
    }

    /**
     * Generate translation key suggestions
     */
    public function suggestKeys(string $partial): array
    {
        $cacheKey = self::CACHE_PREFIX . '.suggestions.' . md5($partial);

        return Cache::remember($cacheKey, self::CACHE_TTL, function() use ($partial) {
            $allKeys = [];

            foreach (Translation::getSupportedLanguages() as $locale) {
                $translations = Translation::getAllForLanguage($locale);
                $allKeys = array_merge($allKeys, array_keys($translations));
            }

            $allKeys = array_unique($allKeys);

            // Filter keys that start with the partial
            $suggestions = array_filter($allKeys, function($key) use ($partial) {
                return str_starts_with($key, $partial);
            });

            return array_values($suggestions);
        });
    }

    /**
     * Detect browser language
     */
    private function detectBrowserLanguage(): string
    {
        $acceptLanguage = request()->header('Accept-Language');

        if (!$acceptLanguage) {
            return Translation::DEFAULT_LANGUAGE;
        }

        // Parse Accept-Language header
        $languages = [];
        preg_match_all('/([a-z]{2}(?:-[a-z]{2})?)\s*(?:;\s*q\s*=\s*([01](?:\.[0-9]+)?))?/i', $acceptLanguage, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $lang = strtolower(substr($match[1], 0, 2));
            $quality = isset($match[2]) ? (float)$match[2] : 1.0;
            $languages[$lang] = $quality;
        }

        // Sort by quality
        arsort($languages);

        // Find first supported language
        foreach (array_keys($languages) as $lang) {
            if (Translation::isLanguageSupported($lang)) {
                return $lang;
            }
        }

        return Translation::DEFAULT_LANGUAGE;
    }

    /**
     * Get translation health check
     */
    public function getHealthCheck(): array
    {
        $stats = $this->getStatistics();
        $supportedLanguages = Translation::getSupportedLanguages();

        $health = [
            'status' => 'healthy',
            'languages' => [],
            'total_keys' => 0,
            'issues' => [],
        ];

        $maxKeys = 0;
        foreach ($supportedLanguages as $locale) {
            $maxKeys = max($maxKeys, $stats[$locale]['total']);
        }

        $health['total_keys'] = $maxKeys;

        foreach ($supportedLanguages as $locale) {
            $languageHealth = [
                'locale' => $locale,
                'name' => $this->getLanguageName($locale),
                'total_keys' => $stats[$locale]['total'],
                'completion_percentage' => $maxKeys > 0 ? round(($stats[$locale]['total'] / $maxKeys) * 100, 2) : 0,
                'missing_keys' => $maxKeys - $stats[$locale]['total'],
            ];

            // Check for issues
            if ($languageHealth['completion_percentage'] < 90) {
                $health['issues'][] = "Language '{$locale}' is incomplete ({$languageHealth['completion_percentage']}%)";
                $health['status'] = 'warning';
            }

            if ($languageHealth['completion_percentage'] < 50) {
                $health['status'] = 'critical';
            }

            $health['languages'][] = $languageHealth;
        }

        return $health;
    }

    /**
     * Bootstrap default translations
     */
    public function bootstrap(): void
    {
        Translation::bootstrap();
    }
}