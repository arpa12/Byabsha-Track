<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;

// Language switcher
Route::get('/language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch')
    ->where('locale', 'en|bn');

// Root handled by Landing module
