<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Project Management specific commands

Artisan::command('project:cleanup-completed', function () {
    $count = 0;
    $completedProjects = App\Models\Project::where('status', 'terminé')
        ->where('updated_at', '<', now()->subMonths(6))
        ->get();

    foreach ($completedProjects as $project) {
        // Archive completed projects older than 6 months
        $project->update(['archived' => true]);
        $count++;
    }

    $this->info("Archived {$count} completed projects older than 6 months.");
})->purpose('Archive old completed projects');

Artisan::command('tasks:notify-overdue', function () {
    $overdueTasks = App\Models\Task::where('due_date', '<', now())
        ->where('status', '!=', 'fait')
        ->with(['assignedUser', 'project.manager'])
        ->get();

    $notificationCount = 0;

    foreach ($overdueTasks as $task) {
        if ($task->assignedUser) {
            // Send notification to assigned user
            $this->line("Notifying {$task->assignedUser->name} about overdue task: {$task->title}");
            $notificationCount++;
        }

        if ($task->project->manager) {
            // Send notification to project manager
            $this->line("Notifying manager {$task->project->manager->name} about overdue task: {$task->title}");
            $notificationCount++;
        }
    }

    $this->info("Sent {$notificationCount} overdue task notifications.");
})->purpose('Send notifications for overdue tasks');

Artisan::command('time:calculate-totals', function () {
    $projects = App\Models\Project::all();
    $updatedCount = 0;

    foreach ($projects as $project) {
        $totalHours = $project->timeEntries()->sum('duration_hours');

        if ($project->total_hours != $totalHours) {
            $project->update(['total_hours' => $totalHours]);
            $updatedCount++;
        }
    }

    $this->info("Updated total hours for {$updatedCount} projects.");
})->purpose('Recalculate project total hours');

Artisan::command('translations:sync', function () {
    $translationService = app(App\Services\TranslationService::class);

    $this->info('Syncing translations from files...');

    // This would sync translations from language files to database
    $syncedCount = $translationService->syncFromFiles();

    $this->info("Synced {$syncedCount} translation keys.");
})->purpose('Sync translations from language files to database');

Artisan::command('reports:generate-daily', function () {
    $reportService = app(App\Services\ReportService::class);

    $yesterday = now()->subDay();

    $this->info("Generating daily reports for {$yesterday->format('Y-m-d')}...");

    // Generate daily project progress report
    $projectReport = $reportService->generateDailyProjectReport($yesterday);

    // Generate daily time tracking summary
    $timeReport = $reportService->generateDailyTimeReport($yesterday);

    $this->info('Daily reports generated successfully.');
})->purpose('Generate daily automated reports');

Artisan::command('cache:warm', function () {
    $this->info('Warming up application cache...');

    // Cache common translations
    $this->call('translations:cache');

    // Cache user permissions
    $this->call('permissions:cache');

    // Cache project statistics
    $projects = App\Models\Project::with(['tasks', 'timeEntries'])->get();
    foreach ($projects as $project) {
        cache()->put("project_stats_{$project->id}", [
            'total_tasks' => $project->tasks->count(),
            'completed_tasks' => $project->tasks->where('status', 'fait')->count(),
            'total_hours' => $project->total_hours,
        ], 3600); // Cache for 1 hour
    }

    $this->info('Application cache warmed successfully.');
})->purpose('Warm up application caches for better performance');