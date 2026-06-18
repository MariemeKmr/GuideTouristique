<?php

use App\Http\Controllers\Admin\ActiviteController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\TransportController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Taximan\TaximanController;
use App\Http\Controllers\Taximan\CourseController as TaximanCourseController;
use App\Http\Controllers\Visitor\CourseController as VisitorCourseController;
use App\Http\Controllers\Visitor\VisitorController;
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
        Route::resource('activites', ActiviteController::class)->except('show');
    });

    /*
    | --- Espace visiteur (préfixe /visitor, noms visitor.*) ---
    */
    Route::middleware('role:visiteur')->prefix('visitor')->name('visitor.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'visitor'])->name('dashboard');

        // Destinations
        Route::get('/destinations', [VisitorController::class, 'destinations'])->name('destinations.index');
        Route::get('/destinations/{destination}', [VisitorController::class, 'showDestination'])->name('destinations.show');
        Route::post('/destinations/{destination}/visite', [VisitorController::class, 'markVisited'])->name('destinations.visit');
        Route::delete('/destinations/{destination}/visite', [VisitorController::class, 'unmarkVisited'])->name('destinations.unvisit');

        // Mes visites
        Route::get('/mes-visites', [VisitorController::class, 'myVisits'])->name('visits');

        // Transports
        Route::get('/transports', [VisitorController::class, 'transports'])->name('transports.index');

        // Activites
        Route::get('/activites', [VisitorController::class, 'activites'])->name('activites.index');

        // Chauffeurs
        Route::get('/chauffeurs', [VisitorController::class, 'drivers'])->name('drivers.index');
        Route::get('/chauffeurs/{user}', [VisitorController::class, 'showDriver'])->name('drivers.show');
        Route::post('/chauffeurs/{user}/contact', [VisitorController::class, 'contactDriver'])->name('drivers.contact');

        // Courses
        Route::get('/mes-courses', [VisitorCourseController::class, 'index'])->name('courses.index');
        Route::post('/chauffeurs/{user}/course', [VisitorCourseController::class, 'store'])->name('courses.store');
        Route::patch('/courses/{course}/demarrer', [VisitorCourseController::class, 'demarrer'])->name('courses.start');
        Route::patch('/courses/{course}/annuler', [VisitorCourseController::class, 'annuler'])->name('courses.cancel');
        Route::patch('/courses/{course}/noter', [VisitorCourseController::class, 'noter'])->name('courses.rate');
    });

    /*
    | --- Espace chauffeur (préfixe /taximan, noms taximan.*) ---
    */
    Route::middleware('role:taximan')->prefix('taximan')->name('taximan.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'taximan'])->name('dashboard');
        Route::get('/profil', [TaximanController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profil', [TaximanController::class, 'updateProfile'])->name('profile.update');
        Route::patch('/contacts/{contactRequest}/lue', [TaximanController::class, 'marquerContactLu'])->name('contacts.read');

        // Courses
        Route::get('/mes-courses', [TaximanCourseController::class, 'index'])->name('courses.index');
        Route::patch('/courses/{course}/accepter', [TaximanCourseController::class, 'accepter'])->name('courses.accept');
        Route::patch('/courses/{course}/refuser', [TaximanCourseController::class, 'refuser'])->name('courses.refuse');
        Route::patch('/courses/{course}/avancer', [TaximanCourseController::class, 'avancer'])->name('courses.advance');
    });
});
