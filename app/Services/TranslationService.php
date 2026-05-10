<?php

namespace App\Services;

use App\Models\Translation;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class TranslationService
{
    public function get(string $key, ?string $locale = null, ?string $default = null): string
    {
        return Translation::get($key, $locale ?? $this->getCurrentLocale(), $default);
    }

    public function set(string $key, string $value, ?string $locale = null): Translation
    {
        return Translation::set($key, $locale ?? $this->getCurrentLocale(), $value);
    }

    public function changeUserLanguage(User $user, string $language): bool
    {
        if (! Translation::isLanguageSupported($language)) {
            return false;
        }

        $user->update(['language' => $language]);
        $this->setSessionLocale($language);
        App::setLocale($language);

        return true;
    }

    public function getCurrentLocale(): string
    {
        return Session::get('locale', Translation::DEFAULT_LANGUAGE);
    }

    public function setSessionLocale(string $locale): void
    {
        if (Translation::isLanguageSupported($locale)) {
            Session::put('locale', $locale);
            App::setLocale($locale);
        }
    }

    public function initializeLocale(?User $user = null): void
    {
        $locale = Translation::DEFAULT_LANGUAGE;

        if ($user && $user->language) {
            $locale = $user->language;
        } elseif (Session::has('locale')) {
            $locale = Session::get('locale');
        } else {
            $locale = $this->detectBrowserLanguage();
        }

        $this->setSessionLocale($locale);
    }

    public function getDirection(?string $locale = null): string
    {
        return Translation::isRtlLanguage($locale ?? $this->getCurrentLocale()) ? 'rtl' : 'ltr';
    }

    public function isRtl(?string $locale = null): bool
    {
        return $this->getDirection($locale) === 'rtl';
    }

    public function getSupportedLanguages(): array
    {
        return array_map(fn ($locale) => [
            'code' => $locale,
            'name' => Translation::getLanguageName($locale),
            'direction' => $this->getDirection($locale),
            'is_rtl' => $this->isRtl($locale),
        ], Translation::getSupportedLanguages());
    }

    private function detectBrowserLanguage(): string
    {
        $acceptLanguage = request()->header('Accept-Language');

        if (! $acceptLanguage) {
            return Translation::DEFAULT_LANGUAGE;
        }

        $languages = [];
        preg_match_all('/([a-z]{2}(?:-[a-z]{2})?)\s*(?:;\s*q\s*=\s*([01](?:\.[0-9]+)?))?/i', $acceptLanguage, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $lang = strtolower(substr($match[1], 0, 2));
            $quality = isset($match[2]) ? (float) $match[2] : 1.0;
            $languages[$lang] = $quality;
        }

        arsort($languages);

        foreach (array_keys($languages) as $lang) {
            if (Translation::isLanguageSupported($lang)) {
                return $lang;
            }
        }

        return Translation::DEFAULT_LANGUAGE;
    }
}
