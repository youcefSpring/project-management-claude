<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| These routes handle user authentication including login, register, logout
| and password reset functionality.
|
*/

// Guest Routes (Login & Register)
Route::middleware('guest')->group(function () {

    // Login Routes
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);

    // Registration Routes
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Password Reset Routes (Future Implementation)
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

    // Email Verification Routes (Future Implementation)
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function () {
        // Email verification logic would go here
        return redirect()->route('dashboard');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function () {
        // Resend verification email logic would go here
        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Profile Management
    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');

    Route::patch('/profile', function () {
        // Profile update logic would go here
        return back()->with('status', 'Profile updated!');
    })->name('profile.update');

    Route::delete('/profile', function () {
        // Account deletion logic would go here
        return redirect('/');
    })->name('profile.destroy');

    // Change Password
    Route::put('/password', function () {
        // Password change logic would go here
        return back()->with('status', 'Password updated!');
    })->name('password.update');
});
