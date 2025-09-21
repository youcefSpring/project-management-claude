<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\PublicationController;
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

// =========================================================================
// API AUTHENTICATION (if needed for future mobile app or SPA)
// =========================================================================

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// =========================================================================
// PUBLIC API ENDPOINTS (no authentication required)
// =========================================================================

// Contact form submission (alternative endpoint)
Route::post('/contact', [ContactController::class, 'store'])->name('api.contact.store');

// Public read-only endpoints for publications (for academic networks integration)
Route::prefix('publications')->name('api.publications.')->group(function () {
    Route::get('/', [PublicationController::class, 'index'])->name('index');
    Route::get('/{publication}', [PublicationController::class, 'show'])->name('show');
    Route::get('/search/{query}', [PublicationController::class, 'search'])->name('search');
});

// =========================================================================
// FUTURE API ROUTES (placeholder for potential expansion)
// =========================================================================

// If implementing mobile app or SPA in the future:
// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('courses', App\Http\Controllers\Api\CourseController::class);
//     Route::apiResource('projects', App\Http\Controllers\Api\ProjectController::class);
//     Route::apiResource('blog', App\Http\Controllers\Api\BlogController::class);
// });

// Rate limiting for API endpoints
Route::middleware(['throttle:api'])->group(function () {
    // API routes that need rate limiting would go here
});