<?php

/**
 * (ɔ) LARAVEL.Sillo.org - 2015-2024
 */

use App\Http\Controllers\PetsController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::get('/', [WelcomeController::class, 'index'])->name('home)');

Route::get('/pet', [PetsController::class, 'index'])->name('pet)');

// Route::get('/', function () {
//     // event(new Accueil);
//     return view('welcome');
// });

// Route::get('/', function () {
// 	// event(new Accueil);
// 	return 'ok';
// })->name('home');
