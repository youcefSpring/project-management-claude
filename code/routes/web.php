<?php

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ProjectController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\TaskController;
use App\Http\Controllers\Web\TaskNoteController;
use App\Http\Controllers\Web\TimeEntryController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Root redirect to default language
Route::get('/', function () {
    $locale = session('locale', config('app.locale', 'en'));
    return redirect("/{$locale}");
})->name('home');

// Language switching route (outside prefix)
Route::post('/language', function () {
    $language = request('language');
    $currentLocale = app()->getLocale();

    if (in_array($language, ['fr', 'en', 'ar', 'es'])) {
        auth()->user()->update(['language' => $language]);
        session(['language' => $language]);
        app()->setLocale($language);

        // Redirect to the same page with new language prefix
        $currentPath = str_replace('/' . $currentLocale, '', request()->headers->get('referer'));
        $newPath = parse_url($currentPath, PHP_URL_PATH);
        $cleanPath = ltrim(str_replace('/' . $currentLocale, '', $newPath), '/');

        return redirect("/{$language}/" . $cleanPath);
    }

    return back();
})->name('language.switch')->middleware('auth');

// Routes with MCamara language prefix
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {

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
            ->middleware('permission:manage.projects')
            ->name('create');
        Route::post('/', [ProjectController::class, 'store'])
            ->middleware('permission:manage.projects')
            ->name('store');
        Route::get('/{project}', [ProjectController::class, 'show'])
            ->middleware('permission:view.project')
            ->name('show');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])
            ->middleware('permission:edit.project')
            ->name('edit');
        Route::put('/{project}', [ProjectController::class, 'update'])
            ->middleware('permission:edit.project')
            ->name('update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])
            ->middleware('permission:delete.projects')
            ->name('destroy');
    });

    // Tasks
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/create', [TaskController::class, 'create'])
            ->middleware('permission:assign.tasks')
            ->name('create');
        Route::post('/', [TaskController::class, 'store'])
            ->middleware('permission:assign.tasks')
            ->name('store');
        Route::get('/{task}', [TaskController::class, 'show'])
            ->middleware('permission:view.task')
            ->name('show');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])
            ->middleware('permission:edit.task')
            ->name('edit');
        Route::put('/{task}', [TaskController::class, 'update'])
            ->middleware('permission:edit.task')
            ->name('update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])
            ->middleware('permission:edit.task')
            ->name('destroy');
        Route::post('/{task}/status', [TaskController::class, 'updateStatus'])
            ->middleware('permission:edit.task')
            ->name('update-status');

        // Task Notes
        Route::post('/{task}/notes', [TaskNoteController::class, 'store'])
            ->middleware('permission:view.task')
            ->name('notes.store');
        Route::put('/notes/{note}', [TaskNoteController::class, 'update'])
            ->name('notes.update');
        Route::delete('/notes/{note}', [TaskNoteController::class, 'destroy'])
            ->name('notes.destroy');
    });

    // Time Tracking (Timesheet)
    Route::prefix('timesheet')->name('timesheet.')->middleware('permission:view.timetracking')->group(function () {
        Route::get('/', [TimeEntryController::class, 'index'])->name('index');
        Route::get('/create', [TimeEntryController::class, 'create'])->name('create');
        Route::post('/', [TimeEntryController::class, 'store'])->name('store');
        Route::get('/{timeEntry}', [TimeEntryController::class, 'show'])->name('show');
        Route::get('/{timeEntry}/edit', [TimeEntryController::class, 'edit'])->name('edit');
        Route::put('/{timeEntry}', [TimeEntryController::class, 'update'])->name('update');
        Route::delete('/{timeEntry}', [TimeEntryController::class, 'destroy'])->name('destroy');
    });

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])
            ->middleware('permission:view.users')
            ->name('index');
        Route::get('/create', [UserController::class, 'create'])
            ->middleware('permission:manage.users')
            ->name('create');
        Route::post('/', [UserController::class, 'store'])
            ->middleware('permission:manage.users')
            ->name('store');
        Route::get('/{user}', [UserController::class, 'show'])
            ->middleware('permission:view.users')
            ->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])
            ->middleware('permission:manage.users')
            ->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])
            ->name('update'); // Users can edit their own profile
        Route::delete('/{user}', [UserController::class, 'destroy'])
            ->middleware('permission:manage.users')
            ->name('destroy');
        Route::patch('/{user}/role', [UserController::class, 'updateRole'])
            ->middleware('permission:manage.users')
            ->name('update-role');
        Route::get('/api/stats', [UserController::class, 'getUserStats'])
            ->middleware('permission:manage.users')
            ->name('stats');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])
            ->middleware('permission:view.reports')
            ->name('index');

        Route::get('/projects', [ReportController::class, 'projects'])
            ->middleware('permission:view.reports')
            ->name('projects');

        Route::get('/users', [ReportController::class, 'users'])
            ->middleware('permission:view.reports')
            ->name('users');

        Route::get('/time-tracking', [ReportController::class, 'timeTracking'])
            ->middleware('permission:view.timetracking')
            ->name('time-tracking');

        Route::get('/financial', [ReportController::class, 'financial'])
            ->middleware('permission:view.financial.reports')
            ->name('financial');

        Route::get('/export', [ReportController::class, 'export'])
            ->middleware('permission:view.reports')
            ->name('export');
    });

    // User Profile & Settings
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function () {
            return view('profile.index');
        })->name('index');

        Route::get('/settings', function () {
            return view('profile.settings');
        })->name('settings');

        Route::patch('/', function () {
            $user = auth()->user();
            $request = request();

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'language' => 'required|in:en,fr,ar',
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'language' => $request->language,
            ]);

            session(['language' => $request->language]);
            app()->setLocale($request->language);

            return back()->with('success', __('Profile updated successfully.'));
        })->name('update');

        Route::put('/password', function () {
            $request = request();

            $request->validate([
                'current_password' => 'required|current_password',
                'password' => 'required|string|min:8|confirmed',
            ]);

            auth()->user()->update([
                'password' => bcrypt($request->password)
            ]);

            return back()->with('success', __('Password updated successfully.'));
        })->name('password.update');
    });

    // Help & Documentation
    Route::get('/help', function () {
        return view('help.index');
    })->name('help');

    // Translation Manager (Admin only)
    Route::middleware('permission:access.admin.dashboard')->group(function () {
        Route::get('/translations', '\Barryvdh\TranslationManager\Controller@getIndex');
        Route::get('/translations/view/{group?}', '\Barryvdh\TranslationManager\Controller@getView')->where('group', '.*');
        Route::post('/translations/add/{group}', '\Barryvdh\TranslationManager\Controller@postAdd')->where('group', '.*');
        Route::post('/translations/edit/{group}', '\Barryvdh\TranslationManager\Controller@postEdit')->where('group', '.*');
        Route::post('/translations/delete/{group}/{key?}', '\Barryvdh\TranslationManager\Controller@postDelete')->where('group', '.*');
        Route::post('/translations/import', '\Barryvdh\TranslationManager\Controller@postImport');
        Route::post('/translations/find', '\Barryvdh\TranslationManager\Controller@postFind');
        Route::post('/translations/locales/add', '\Barryvdh\TranslationManager\Controller@postAddLocale');
        Route::post('/translations/locales/remove', '\Barryvdh\TranslationManager\Controller@postRemoveLocale');
        Route::post('/translations/publish/{group}', '\Barryvdh\TranslationManager\Controller@postPublish')->where('group', '.*');
        Route::post('/translations/publish', '\Barryvdh\TranslationManager\Controller@postPublish');
    });

    // Search (Global)
    Route::get('/search', function () {
        $query = request('q');

        return view('search.results', compact('query'));
    })->name('search.results');

    }); // End of authenticated routes

}); // End of language prefix group

// File Downloads (for reports) - Outside language prefix
Route::middleware('auth')->get('/downloads/{file}', function ($file) {
    $path = storage_path('app/reports/'.$file);

    if (! file_exists($path)) {
        abort(404);
    }

    return response()->download($path);
})->where('file', '.*')->name('download');

// AJAX Routes for web pages
Route::middleware('auth')->group(function () {
    Route::get('/ajax/project-tasks', function (Request $request) {
        try {
            $filters = $request->only(['project_id', 'status', 'search']);

            // Clean up empty values
            $filters = array_filter($filters, function($value) {
                return $value !== null && $value !== '';
            });

            $taskService = app(\App\Services\TaskService::class);
            $tasks = $taskService->getAccessibleTasks($request->user(), $filters);

            // Load relationships for the frontend
            $tasks->load(['project', 'assignedUser']);

            return response()->json([
                'success' => true,
                'data' => $tasks->values(),
                'count' => $tasks->count(),
            ]);

        } catch (\Exception $e) {
            \Log::error('AJAX Project Tasks error: ' . $e->getMessage(), [
                'user_id' => $request->user()?->id,
                'filters' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error loading tasks',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error',
            ], 500);
        }
    })->name('ajax.project-tasks');
});

// Health Check for Web
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'app' => config('app.name'),
        'version' => config('app.version', '1.0.0'),
    ]);
})->name('health');

// Fallback for 404 pages
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
