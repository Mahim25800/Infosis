<?php

use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

// This route is intentionally public. It is the short link people will share.
Route::get('/{shortCode}', RedirectController::class)
    ->where('shortCode', '[A-Za-z0-9-]+')
    ->name('urls.redirect');
