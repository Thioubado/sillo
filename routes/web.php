<?php

/**
 * (ɔ) LARAVEL.Sillo.org - 2015-2024
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;

// Routes publiques
 Route::get('/welcome', [WelcomeController::class, 'index']);

// Route::get('/', function () {
//     // event(new Accueil);
//     return view('welcome');

// });

