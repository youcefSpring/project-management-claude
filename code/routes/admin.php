<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\SettingsController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| These routes are only accessible by users with admin role.
| They handle user management, system settings, and other admin functions.
|
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // User Management
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
    Route::patch('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update-status');

    // Translation Management
    Route::resource('translations', TranslationController::class);
    Route::post('translations/import', [TranslationController::class, 'import'])->name('translations.import');
    Route::get('translations/export', [TranslationController::class, 'export'])->name('translations.export');
    Route::post('translations/sync', [TranslationController::class, 'sync'])->name('translations.sync');

    // System Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('settings', [SettingsController::class, 'update'])->name('settings.update');

    // System Configuration
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('general', [SettingsController::class, 'general'])->name('general');
        Route::get('email', [SettingsController::class, 'email'])->name('email');
        Route::get('security', [SettingsController::class, 'security'])->name('security');
        Route::get('backup', [SettingsController::class, 'backup'])->name('backup');
        Route::get('logs', [SettingsController::class, 'logs'])->name('logs');
    });

    // Project Management (Admin view)
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', function () {
            return view('admin.projects.index');
        })->name('index');

        Route::get('analytics', function () {
            return view('admin.projects.analytics');
        })->name('analytics');

        Route::get('archived', function () {
            return view('admin.projects.archived');
        })->name('archived');
    });

    // System Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('system', function () {
            return view('admin.reports.system');
        })->name('system');

        Route::get('usage', function () {
            return view('admin.reports.usage');
        })->name('usage');

        Route::get('performance', function () {
            return view('admin.reports.performance');
        })->name('performance');
    });

    // Audit Logs
    Route::prefix('audit')->name('audit.')->group(function () {
        Route::get('/', function () {
            return view('admin.audit.index');
        })->name('index');

        Route::get('users', function () {
            return view('admin.audit.users');
        })->name('users');

        Route::get('projects', function () {
            return view('admin.audit.projects');
        })->name('projects');

        Route::get('system', function () {
            return view('admin.audit.system');
        })->name('system');
    });

    // Cache Management
    Route::prefix('cache')->name('cache.')->group(function () {
        Route::post('clear', function () {
            // Clear application cache
            \Artisan::call('cache:clear');
            return back()->with('success', 'Cache cleared successfully');
        })->name('clear');

        Route::post('config', function () {
            // Clear config cache
            \Artisan::call('config:clear');
            return back()->with('success', 'Config cache cleared');
        })->name('config');

        Route::post('routes', function () {
            // Clear route cache
            \Artisan::call('route:clear');
            return back()->with('success', 'Route cache cleared');
        })->name('routes');

        Route::post('views', function () {
            // Clear view cache
            \Artisan::call('view:clear');
            return back()->with('success', 'View cache cleared');
        })->name('views');
    });

    // Maintenance Mode
    Route::post('maintenance/enable', function () {
        \Artisan::call('down');
        return response()->json(['success' => true, 'message' => 'Maintenance mode enabled']);
    })->name('maintenance.enable');

    Route::post('maintenance/disable', function () {
        \Artisan::call('up');
        return response()->json(['success' => true, 'message' => 'Maintenance mode disabled']);
    })->name('maintenance.disable');
});