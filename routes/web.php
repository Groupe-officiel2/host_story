<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// --- (Authentification) ---
Route::middleware('guest')->group(function () {
    // Inscription
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Connexion
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// --- (Espace Membre) ---
Route::middleware('auth')->group(function () {
    // Page d'accueil après connexion (Dashboard)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Déconnexion
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Optionnel : Une redirection simple si quelqu'un tape juste '/'
Route::get('/', function () {
    return redirect()->route('register');
});