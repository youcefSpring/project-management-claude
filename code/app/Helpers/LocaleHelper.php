<?php

namespace App\Helpers;

class LocaleHelper
{
    /**
     * Get all available locales
     */
    public static function getAvailableLocales(): array
    {
        return config('app.available_locales', [
            'en' => 'English',
            'fr' => 'Français',
            'ar' => 'العربية',
        ]);
    }

    /**
     * Get current locale name
     */
    public static function getCurrentLocaleName(): string
    {
        $locales = self::getAvailableLocales();
        $currentLocale = app()->getLocale();

        return $locales[$currentLocale] ?? $currentLocale;
    }

    /**
     * Check if current locale is RTL
     */
    public static function isRtl(): bool
    {
        return in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur']);
    }

    /**
     * Get locale direction
     */
    public static function getDirection(): string
    {
        return self::isRtl() ? 'rtl' : 'ltr';
    }

    /**
     * Get supported locale codes
     */
    public static function getSupportedLocales(): array
    {
        return array_keys(self::getAvailableLocales());
    }

    /**
     * Check if locale is supported
     */
    public static function isSupported(string $locale): bool
    {
        return in_array($locale, self::getSupportedLocales());
    }

    /**
     * Get locale flag emoji
     */
    public static function getFlag(string $locale): string
    {
        $flags = [
            'en' => '🇺🇸',
            'fr' => '🇫🇷',
            'ar' => '🇸🇦',
            'es' => '🇪🇸',
            'de' => '🇩🇪',
        ];

        return $flags[$locale] ?? '🌐';
    }

    /**
     * Get locale meta information
     */
    public static function getLocaleInfo(string $locale): array
    {
        $info = [
            'en' => [
                'name' => 'English',
                'native' => 'English',
                'flag' => '🇺🇸',
                'direction' => 'ltr',
            ],
            'fr' => [
                'name' => 'French',
                'native' => 'Français',
                'flag' => '🇫🇷',
                'direction' => 'ltr',
            ],
            'ar' => [
                'name' => 'Arabic',
                'native' => 'العربية',
                'flag' => '🇸🇦',
                'direction' => 'rtl',
            ],
        ];

        return $info[$locale] ?? [
            'name' => $locale,
            'native' => $locale,
            'flag' => '🌐',
            'direction' => 'ltr',
        ];
    }
}