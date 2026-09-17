<?php

use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'TinyLink API',
        'status' => 'online',
        'message' => 'Welcome to TinyLink URL Shortener API',
    ]);
});

// This route is intentionally public. It is the short link people will share.
Route::get('/{shortCode}', RedirectController::class)
    ->where('shortCode', '[A-Za-z0-9-]+')
    ->name('urls.redirect');
