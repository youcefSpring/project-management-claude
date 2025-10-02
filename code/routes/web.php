<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ProjectController;
use App\Http\Controllers\Web\TaskController;
use App\Http\Controllers\Web\TimeEntryController;
use App\Http\Controllers\Web\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

// Authentication Routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // Password Reset Routes
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('/forgot-password', function () {
        // Password reset logic would go here
        return back()->with('status', 'Password reset link sent!');
    })->name('password.email');

    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');

    Route::post('/reset-password', function () {
        // Password reset logic would go here
        return redirect()->route('login')->with('status', 'Password has been reset!');
    })->name('password.update');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Projects
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/create', [ProjectController::class, 'create'])
            ->middleware('role:admin,manager')
            ->name('create');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])
            ->middleware('role:admin,manager')
            ->name('edit');
    });

    // Tasks
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/create', [TaskController::class, 'create'])
            ->middleware('role:admin,manager')
            ->name('create');
        Route::get('/{task}', [TaskController::class, 'show'])->name('show');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
    });

    // Time Tracking (Timesheet)
    Route::prefix('timesheet')->name('timesheet.')->group(function () {
        Route::get('/', [TimeEntryController::class, 'index'])->name('index');
        Route::get('/create', [TimeEntryController::class, 'create'])->name('create');
        Route::get('/{timeEntry}/edit', [TimeEntryController::class, 'edit'])->name('edit');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])
            ->middleware('role:admin,manager')
            ->name('index');

        Route::get('/projects', [ReportController::class, 'projects'])
            ->middleware('role:admin,manager')
            ->name('projects');

        Route::get('/users', [ReportController::class, 'users'])
            ->middleware('role:admin,manager')
            ->name('users');

        Route::get('/time-tracking', [ReportController::class, 'timeTracking'])
            ->name('time-tracking');
    });

    // User Profile & Settings
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function () {
            return view('profile.index');
        })->name('index');

        Route::get('/settings', function () {
            return view('profile.settings');
        })->name('settings');
    });

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', function () {
                return view('admin.users.index');
            })->name('index');

            Route::get('/create', function () {
                return view('admin.users.create');
            })->name('create');

            Route::get('/{user}/edit', function ($user) {
                return view('admin.users.edit', compact('user'));
            })->name('edit');
        });

        // Translation Management
        Route::prefix('translations')->name('translations.')->group(function () {
            Route::get('/', function () {
                return view('admin.translations.index');
            })->name('index');

            Route::get('/create', function () {
                return view('admin.translations.create');
            })->name('create');

            Route::get('/{key}/edit', function ($key) {
                return view('admin.translations.edit', compact('key'));
            })->name('edit');
        });

        // System Settings
        Route::get('/settings', function () {
            return view('admin.settings.index');
        })->name('settings');
    });

    // Help & Documentation
    Route::get('/help', function () {
        return view('help.index');
    })->name('help');

    // Search (Global)
    Route::get('/search', function () {
        $query = request('q');
        return view('search.results', compact('query'));
    })->name('search');
});

// Language Switching (for authenticated users)
Route::middleware('auth')->post('/language', function () {
    $language = request('language');

    if (in_array($language, ['fr', 'en', 'ar'])) {
        auth()->user()->update(['language' => $language]);
        session(['language' => $language]);
        app()->setLocale($language);
    }

    return back()->with('success', 'Langue mise à jour avec succès');
})->name('language.switch');

// File Downloads (for reports)
Route::middleware('auth')->get('/downloads/{file}', function ($file) {
    $path = storage_path('app/reports/' . $file);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->download($path);
})->where('file', '.*')->name('download');

// Health Check for Web
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'app' => config('app.name'),
        'version' => config('app.version', '1.0.0')
    ]);
})->name('health');

// Fallback for 404 pages
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});