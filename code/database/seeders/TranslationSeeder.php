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
            ['key' => 'dashboard.title', 'language' => 'es', 'value' => 'Panel de Control'],
            ['key' => 'dashboard.welcome', 'language' => 'en', 'value' => 'Welcome back!'],
            ['key' => 'dashboard.welcome', 'language' => 'es', 'value' => '¡Bienvenido de vuelta!'],

            // Project translations
            ['key' => 'projects.title', 'language' => 'en', 'value' => 'Projects'],
            ['key' => 'projects.title', 'language' => 'es', 'value' => 'Proyectos'],
            ['key' => 'projects.create', 'language' => 'en', 'value' => 'Create Project'],
            ['key' => 'projects.create', 'language' => 'es', 'value' => 'Crear Proyecto'],

            // Task translations
            ['key' => 'tasks.title', 'language' => 'en', 'value' => 'Tasks'],
            ['key' => 'tasks.title', 'language' => 'es', 'value' => 'Tareas'],
            ['key' => 'tasks.create', 'language' => 'en', 'value' => 'Create Task'],
            ['key' => 'tasks.create', 'language' => 'es', 'value' => 'Crear Tarea'],

            // Status translations
            ['key' => 'status.pending', 'language' => 'en', 'value' => 'Pending'],
            ['key' => 'status.pending', 'language' => 'es', 'value' => 'Pendiente'],
            ['key' => 'status.in_progress', 'language' => 'en', 'value' => 'In Progress'],
            ['key' => 'status.in_progress', 'language' => 'es', 'value' => 'En Progreso'],
            ['key' => 'status.completed', 'language' => 'en', 'value' => 'Completed'],
            ['key' => 'status.completed', 'language' => 'es', 'value' => 'Completado'],
        ];

        foreach ($translations as $translation) {
            Translation::create($translation);
        }
    }
}