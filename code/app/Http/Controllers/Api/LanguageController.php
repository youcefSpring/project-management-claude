<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TranslationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LanguageController extends Controller
{
    protected TranslationService $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function setLanguage(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'language' => 'required|in:fr,en,ar'
            ]);

            $language = $request->input('language');

            // Update user's language preference
            $user = $request->user();
            $user->update(['language' => $language]);

            // Set session language
            session(['language' => $language]);
            app()->setLocale($language);

            return response()->json([
                'success' => true,
                'message' => 'Langue mise à jour avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la langue'
            ], 500);
        }
    }

    public function getTranslations(Request $request): JsonResponse
    {
        try {
            $language = $request->input('language', app()->getLocale());
            $keys = $request->input('keys');

            // Validate language
            if (!in_array($language, ['fr', 'en', 'ar'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Langue non supportée'
                ], 422);
            }

            $translations = [];

            if ($keys) {
                // Get specific translation keys
                $keyArray = is_string($keys) ? explode(',', $keys) : $keys;
                $translations = $this->translationService->getTranslations($keyArray, $language);
            } else {
                // Get all translations for the language
                $translations = $this->translationService->getAllTranslations($language);
            }

            return response()->json([
                'data' => $translations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des traductions'
            ], 500);
        }
    }

    public function updateTranslation(Request $request): JsonResponse
    {
        try {
            // Only admin can update translations
            $this->authorize('updateTranslations', auth()->user());

            $request->validate([
                'key' => 'required|string',
                'language' => 'required|in:fr,en,ar',
                'value' => 'required|string|max:1000'
            ]);

            $translation = $this->translationService->updateTranslation(
                $request->input('key'),
                $request->input('language'),
                $request->input('value'),
                $request->user()
            );

            return response()->json([
                'success' => true,
                'data' => $translation,
                'message' => 'Traduction mise à jour avec succès'
            ]);

        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès refusé'
                ], 403);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la traduction'
            ], 500);
        }
    }

    public function createTranslation(Request $request): JsonResponse
    {
        try {
            // Only admin can create translations
            $this->authorize('updateTranslations', auth()->user());

            $request->validate([
                'key' => 'required|string|unique:translations,key',
                'fr' => 'required|string|max:1000',
                'en' => 'required|string|max:1000',
                'ar' => 'required|string|max:1000'
            ]);

            $translations = $this->translationService->createTranslation(
                $request->input('key'),
                [
                    'fr' => $request->input('fr'),
                    'en' => $request->input('en'),
                    'ar' => $request->input('ar')
                ],
                $request->user()
            );

            return response()->json([
                'success' => true,
                'data' => $translations,
                'message' => 'Traduction créée avec succès'
            ], 201);

        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès refusé'
                ], 403);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la traduction'
            ], 500);
        }
    }
}