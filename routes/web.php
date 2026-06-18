<?php

use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\TransportController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Page d'accueil publique
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Routes réservées aux invités (non connectés)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Routes réservées aux utilisateurs connectés
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Aiguilleur : redirige vers le bon dashboard selon le rôle.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    | --- Espace administrateur (préfixe /admin, noms admin.*) ---
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        Route::resource('destinations', DestinationController::class)->except('show');
        Route::resource('transports', TransportController::class)->except('show');
    });

    // --- Espace visiteur ---
    Route::get('/visitor/dashboard', [DashboardController::class, 'visitor'])
        ->middleware('role:visiteur')
        ->name('visitor.dashboard');

    // --- Espace chauffeur ---
    Route::get('/taximan/dashboard', [DashboardController::class, 'taximan'])
        ->middleware('role:taximan')
        ->name('taximan.dashboard');
});
