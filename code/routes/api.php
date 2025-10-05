<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskNoteController;
use App\Http\Controllers\Api\TimeEntryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication Routes (Public)
Route::post('/login', [LoginController::class, 'authenticate'])->name('api.login');
Route::post('/register', [RegisterController::class, 'register'])->name('api.register');

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::post('/logout', [LogoutController::class, 'logout'])->name('api.logout');

    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Ajax Routes Group (matches frontend expectations)
    Route::prefix('ajax')->group(function () {

        // Projects
        Route::apiResource('projects', ProjectController::class);

        // Tasks
        Route::apiResource('tasks', TaskController::class);
        Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])
            ->name('api.tasks.update-status');

        // Time Entries
        Route::apiResource('time-entries', TimeEntryController::class);

        // Task Notes (nested resource)
        Route::get('tasks/{task}/notes', [TaskNoteController::class, 'index'])
            ->name('api.tasks.notes.index');
        Route::post('tasks/{task}/notes', [TaskNoteController::class, 'store'])
            ->name('api.tasks.notes.store');
        Route::get('notes/{note}', [TaskNoteController::class, 'show'])
            ->name('api.notes.show');
        Route::put('notes/{note}', [TaskNoteController::class, 'update'])
            ->name('api.notes.update');
        Route::delete('notes/{note}', [TaskNoteController::class, 'destroy'])
            ->name('api.notes.destroy');

        // Reports
        Route::middleware(['role:admin,manager'])->group(function () {
            Route::get('reports/projects', [ReportController::class, 'projects'])
                ->name('api.reports.projects');
            Route::get('reports/users', [ReportController::class, 'users'])
                ->name('api.reports.users');
            Route::post('reports/export', [ReportController::class, 'export'])
                ->name('api.reports.export');
        });

        Route::get('reports/time-tracking', [ReportController::class, 'timeTracking'])
            ->name('api.reports.time-tracking');

        // Dashboard
        Route::get('dashboard/stats', [DashboardController::class, 'stats'])
            ->name('api.dashboard.stats');
        Route::get('dashboard/recent-activity', [DashboardController::class, 'recentActivity'])
            ->name('api.dashboard.recent-activity');
        Route::get('dashboard/notifications', [DashboardController::class, 'notifications'])
            ->name('api.dashboard.notifications');

        // Language & Translations
        Route::post('language', [LanguageController::class, 'setLanguage'])
            ->name('api.language.set');
        Route::get('translations', [LanguageController::class, 'getTranslations'])
            ->name('api.translations.get');

        // Translation Management (Admin only)
        Route::middleware(['role:admin'])->group(function () {
            Route::post('translations', [LanguageController::class, 'createTranslation'])
                ->name('api.translations.create');
            Route::put('translations', [LanguageController::class, 'updateTranslation'])
                ->name('api.translations.update');
        });
    });
});

// Health Check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => config('app.version', '1.0.0'),
    ]);
})->name('api.health');

// Fallback for 404 API routes
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Endpoint non trouvé',
        'code' => 404,
    ], 404);
});
