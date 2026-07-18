<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::view('card', 'dashboard.card')->name('card');
        Route::view('links', 'dashboard.links')->name('links');
        Route::view('photos', 'dashboard.photos')->name('photos');
        Route::view('messages', 'dashboard.messages')->name('messages');
        Route::view('schedule', 'dashboard.schedule')->name('schedule');
        Route::view('appointments', 'dashboard.appointments')->name('appointments');
        Route::view('plan', 'dashboard.plan')->name('plan');
        Route::view('settings', 'dashboard.settings')->name('settings');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
});

// Logout
Route::post('logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// Cartão público
Route::get('/u/{slug}', [\App\Http\Controllers\CardController::class, 'show'])->name('card.show');

require __DIR__.'/auth.php';
