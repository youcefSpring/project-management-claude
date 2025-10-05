<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Available languages
        $availableLanguages = ['en', 'es', 'fr', 'de', 'it', 'pt', 'zh'];
        $defaultLanguage = 'en';

        // Get language from various sources (priority order)
        $language = $this->getLanguageFromSources($request, $availableLanguages, $defaultLanguage);

        // Set the application locale
        App::setLocale($language);

        // Store in session for persistence
        Session::put('locale', $language);

        return $next($request);
    }

    /**
     * Get language from various sources with priority
     */
    private function getLanguageFromSources(Request $request, array $availableLanguages, string $defaultLanguage): string
    {
        // 1. Check URL parameter (highest priority)
        if ($request->has('lang') && in_array($request->get('lang'), $availableLanguages)) {
            return $request->get('lang');
        }

        // 2. Check session
        $sessionLang = Session::get('locale');
        if ($sessionLang && in_array($sessionLang, $availableLanguages)) {
            return $sessionLang;
        }

        // 3. Check user preference (if authenticated)
        if (auth()->check() && auth()->user()->language) {
            $userLang = auth()->user()->language;
            if (in_array($userLang, $availableLanguages)) {
                return $userLang;
            }
        }

        // 4. Check Accept-Language header
        $browserLang = $this->getBrowserLanguage($request, $availableLanguages);
        if ($browserLang) {
            return $browserLang;
        }

        // 5. Return default language
        return $defaultLanguage;
    }

    /**
     * Get browser language from Accept-Language header
     */
    private function getBrowserLanguage(Request $request, array $availableLanguages): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');

        if (! $acceptLanguage) {
            return null;
        }

        // Parse Accept-Language header
        $languages = [];
        $parts = explode(',', $acceptLanguage);

        foreach ($parts as $part) {
            $langPart = trim($part);
            if (strpos($langPart, ';') !== false) {
                [$lang, $quality] = explode(';', $langPart, 2);
                $lang = trim($lang);
                $quality = (float) str_replace('q=', '', trim($quality));
            } else {
                $lang = $langPart;
                $quality = 1.0;
            }

            // Extract main language code (e.g., 'en' from 'en-US')
            $mainLang = substr($lang, 0, 2);

            if (in_array($mainLang, $availableLanguages)) {
                $languages[$mainLang] = $quality;
            }
        }

        // Sort by quality and return the best match
        if (! empty($languages)) {
            arsort($languages);

            return array_key_first($languages);
        }

        return null;
    }
}
