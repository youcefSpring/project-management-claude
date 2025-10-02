<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            // Dashboard translations
            ['key' => 'dashboard.title', 'language' => 'en', 'value' => 'Dashboard'],
            ['key' => 'dashboard.title', 'language' => 'fr', 'value' => 'Tableau de bord'],
            ['key' => 'dashboard.welcome', 'language' => 'en', 'value' => 'Welcome back!'],
            ['key' => 'dashboard.welcome', 'language' => 'fr', 'value' => 'Bon retour !'],

            // Project translations
            ['key' => 'projects.title', 'language' => 'en', 'value' => 'Projects'],
            ['key' => 'projects.title', 'language' => 'fr', 'value' => 'Projets'],
            ['key' => 'projects.create', 'language' => 'en', 'value' => 'Create Project'],
            ['key' => 'projects.create', 'language' => 'fr', 'value' => 'Créer un projet'],

            // Task translations
            ['key' => 'tasks.title', 'language' => 'en', 'value' => 'Tasks'],
            ['key' => 'tasks.title', 'language' => 'fr', 'value' => 'Tâches'],
            ['key' => 'tasks.create', 'language' => 'en', 'value' => 'Create Task'],
            ['key' => 'tasks.create', 'language' => 'fr', 'value' => 'Créer une tâche'],

            // Status translations
            ['key' => 'status.pending', 'language' => 'en', 'value' => 'Pending'],
            ['key' => 'status.pending', 'language' => 'fr', 'value' => 'En attente'],
            ['key' => 'status.in_progress', 'language' => 'en', 'value' => 'In Progress'],
            ['key' => 'status.in_progress', 'language' => 'fr', 'value' => 'En cours'],
            ['key' => 'status.completed', 'language' => 'en', 'value' => 'Completed'],
            ['key' => 'status.completed', 'language' => 'fr', 'value' => 'Terminé'],
        ];

        foreach ($translations as $translation) {
            Translation::create($translation);
        }
    }
}