<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // User Management (redirects to main user management)
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('users.index');
        })->name('index');

        Route::get('/create', function () {
            return redirect()->route('users.create');
        })->name('create');

        Route::get('/{user}/edit', function ($user) {
            return redirect()->route('users.edit', $user);
        })->name('edit');
    });

    // Course Management (placeholder)
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/', function () {
            return view('admin.courses.index');
        })->name('index');

        Route::get('/create', function () {
            return view('admin.courses.create');
        })->name('create');

        Route::get('/{id}/edit', function ($id) {
            return view('admin.courses.edit', ['id' => $id]);
        })->name('edit');
    });

    // Translation Management (placeholder)
    Route::prefix('translations')->name('translations.')->group(function () {
        Route::get('/', function () {
            return view('admin.translations.index');
        })->name('index');
    });

    // System Settings (placeholder)
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function () {
            return view('admin.settings.index');
        })->name('index');

        Route::get('general', function () {
            return view('admin.settings.general');
        })->name('general');
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
