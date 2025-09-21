<?php

use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\CourseController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProjectController;
use App\Http\Controllers\Public\PublicationController;
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

// =========================================================================
// PUBLIC ROUTES (No Authentication Required)
// =========================================================================

// Homepage and About
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/download-cv', [HomeController::class, 'downloadCV'])->name('download-cv');

// Courses
Route::name('courses.')->group(function () {
    Route::get('/courses', [CourseController::class, 'index'])->name('index');
    Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('show');
    Route::get('/courses/{course:slug}/syllabus', [CourseController::class, 'downloadSyllabus'])->name('syllabus');
});

// Projects Portfolio
Route::name('projects.')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->name('index');
    Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('show');
});

// Publications
Route::name('publications.')->group(function () {
    Route::get('/publications', [PublicationController::class, 'index'])->name('index');
    Route::get('/publications/{publication}', [PublicationController::class, 'show'])->name('show');
    Route::get('/publications/{publication}/download', [PublicationController::class, 'download'])->name('download');
});

// Blog
Route::name('blog.')->group(function () {
    Route::get('/blog', [BlogController::class, 'index'])->name('index');
    Route::get('/blog/{blogPost:slug}', [BlogController::class, 'show'])->name('show');
});

// Contact
Route::name('contact.')->group(function () {
    Route::get('/contact', [ContactController::class, 'show'])->name('show');
    Route::post('/contact', [ContactController::class, 'store'])->name('store');
});

// =========================================================================
// AUTHENTICATION ROUTES
// =========================================================================

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// =========================================================================
// ADMIN ROUTES (Authentication Required)
// =========================================================================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Courses Management
    Route::resource('courses', AdminCourseController::class)->except(['show']);

    // Projects Management
    Route::resource('projects', AdminProjectController::class);

    // Blog Posts Management
    Route::resource('blog', AdminBlogPostController::class);

    // Contact Messages Management
    Route::name('contact.')->group(function () {
        Route::get('/contact', [ContactMessageController::class, 'index'])->name('index');
        Route::get('/contact/{contactMessage}', [ContactMessageController::class, 'show'])->name('show');
        Route::put('/contact/{contactMessage}/status', [ContactMessageController::class, 'updateStatus'])->name('update-status');
        Route::delete('/contact/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('destroy');

        // Bulk Operations
        Route::post('/contact/bulk-status', [ContactMessageController::class, 'bulkUpdateStatus'])->name('bulk-status');
        Route::delete('/contact/bulk-delete', [ContactMessageController::class, 'bulkDelete'])->name('bulk-delete');
    });

    // Publications Management
    Route::resource('publications', \App\Http\Controllers\Admin\PublicationController::class);

    // Tags Management
    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)->except(['show']);

    // Profile Management
    Route::name('profile.')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('edit');
        Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('update');
        Route::post('/profile/cv', [\App\Http\Controllers\Admin\ProfileController::class, 'uploadCV'])->name('upload-cv');
        Route::delete('/profile/cv', [\App\Http\Controllers\Admin\ProfileController::class, 'deleteCV'])->name('delete-cv');
    });

    // Media Management
    Route::name('media.')->group(function () {
        Route::post('/media/upload', [\App\Http\Controllers\Admin\MediaController::class, 'upload'])->name('upload');
        Route::delete('/media/{file}', [\App\Http\Controllers\Admin\MediaController::class, 'delete'])->name('delete');
    });
});

// =========================================================================
// FALLBACK ROUTES
// =========================================================================

// Catch-all route for SPA-like behavior (optional)
// Route::fallback(function () {
//     return view('errors.404');
// });

// =========================================================================
// PASSWORD RESET ROUTES (if implementing custom password reset)
// =========================================================================

// Route::middleware('guest')->group(function () {
//     Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
//     Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
//     Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
//     Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
// });

// =========================================================================
// EMAIL VERIFICATION ROUTES (if implementing email verification)
// =========================================================================

// Route::middleware('auth')->group(function () {
//     Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
//     Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
//         ->middleware(['signed', 'throttle:6,1'])
//         ->name('verification.verify');
//     Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//         ->middleware('throttle:6,1')
//         ->name('verification.send');
// });