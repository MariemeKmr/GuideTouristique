<?php

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

    // Tableaux de bord protégés par rôle.
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/visitor/dashboard', [DashboardController::class, 'visitor'])
        ->middleware('role:visiteur')
        ->name('visitor.dashboard');

    Route::get('/taximan/dashboard', [DashboardController::class, 'taximan'])
        ->middleware('role:taximan')
        ->name('taximan.dashboard');
});
