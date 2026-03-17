<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\SubscriptionController;

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


    // Déconnexion de l'utilisateur
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    // Mise à jour du compte
    Route::put('/account/update', [LoginController::class, 'updateProfile'])->name('account.update');

    // Suppression de compte
    Route::delete('/account/destroy', [LoginController::class, 'destroy'])->name('account.destroy');
});
// --- (Abonnements) ---
Route::get('/plans', [SubscriptionController::class, 'index'])->name('plans.index');

// Routes protégées par auth
Route::middleware('auth')->group(function () {
    // Route du Webhook à faire

    
    Route::get('/subscribe/{plan}',      [SubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::get('/subscription/success',  [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/subscription/cancel',   [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
}); 

// Webhook PayPal (pas de auth, mais exclu du CSRF)
Route::post('/subscription/webhook', [SubscriptionController::class, 'webhook'])->name('subscription.webhook');

// Une redirection pour '/'
Route::get('/', function () {
    return redirect()->route('register');
});
