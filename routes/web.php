<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\Dashboard\CheckoutController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\EfiBankWebhookController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Páginas legais
Route::prefix('legal')->name('legal.')->group(function () {
    Route::view('privacidade', 'legal.privacidade')->name('privacidade');
    Route::view('cookies',     'legal.cookies')->name('cookies');
    Route::view('termos',      'legal.termos')->name('termos');
});

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
        Route::get('share', [App\Http\Controllers\Dashboard\ShareController::class, 'index'])->name('share');
        Route::get('settings', [SettingsController::class, 'index'])->name('settings');
        Route::delete('settings/account', [SettingsController::class, 'destroyAccount'])->name('settings.account.destroy');
        Route::get('checkout/{type}', [CheckoutController::class, 'redirect'])->name('checkout');
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

// Webhook Efi Bank (sem CSRF)
Route::post('/webhook/efibank', [EfiBankWebhookController::class, 'handle'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('webhook.efibank');

// Tokens de agendamento (e-mail do titular)
Route::get('/appointments/{token}/confirm', [AppointmentController::class, 'confirm'])->name('appointment.confirm');
Route::get('/appointments/{token}/refuse', [AppointmentController::class, 'refuse'])->name('appointment.refuse');

// Cartão público
Route::get('/u/{slug}', [CardController::class, 'show'])->name('card.show');
Route::get('/u/{slug}/contato.vcf', [CardController::class, 'vcard'])->name('card.vcard');
Route::get('/u/{slug}/qr.svg', [CardController::class, 'qrSvg'])->name('card.qr.svg');
Route::get('/u/{slug}/qr.png', [CardController::class, 'qrPng'])->name('card.qr.png');
Route::get('/u/{slug}/agendar/slots', [AppointmentController::class, 'slots'])->name('card.slots');
Route::get('/u/{slug}/link/{linkId}', [CardController::class, 'trackClick'])->name('card.link.click');

require __DIR__.'/auth.php';
