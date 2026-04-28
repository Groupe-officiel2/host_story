<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ServerController;

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
    Route::get('/dashboard', [ServerController::class, 'index'])
        ->name('dashboard');
    
    // Création d'un serveur
    Route::post('/servers', [ServerController::class, 'store'])
        ->name('servers.store');


    // Déconnexion de l'utilisateur
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    // Mise à jour du compte
    Route::put('/account/update', [LoginController::class, 'updateProfile'])->name('account.update');

    // Suppression de compte
    Route::delete('/account/destroy', [LoginController::class, 'destroy'])->name('account.destroy');
});

// Une redirection pour '/'
Route::get('/', function () {
    return redirect()->route('register');
});
Route::get('/servers-data', [ServerController::class, 'data']);

Route::post('/api/servers', [ServerController::class, 'storeFromGo']);
