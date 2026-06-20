<?php

use App\Http\Controllers\Admin\ActiviteController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\TransportController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ObjetPerduController;
use App\Http\Controllers\SignalementController;
use App\Http\Controllers\Admin\SignalementController as AdminSignalementController;
use App\Http\Controllers\Taximan\TaximanController;
use App\Http\Controllers\Taximan\ClientController as TaximanClientController;
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

    // Objet perdu : fil de discussion anonyme entre le client et le chauffeur d'une course.
    Route::get('/objets/{course}', [ObjetPerduController::class, 'show'])->name('objets.show');
    Route::post('/objets/{course}', [ObjetPerduController::class, 'store'])->name('objets.store');

    // Signalement d'un probleme sur une course (visiteur ou chauffeur)
    Route::get('/signalements/{course}/nouveau', [SignalementController::class, 'create'])->name('signalements.create');
    Route::post('/signalements/{course}', [SignalementController::class, 'store'])->name('signalements.store');

    /*
    | --- Espace administrateur (préfixe /admin, noms admin.*) ---
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        Route::resource('destinations', DestinationController::class)->except('show');
        Route::resource('transports', TransportController::class)->except('show');
        Route::resource('activites', ActiviteController::class)->except('show');

        // Signalements recus
        Route::get('/signalements', [AdminSignalementController::class, 'index'])->name('signalements.index');
        Route::patch('/signalements/{signalement}/traite', [AdminSignalementController::class, 'marquerTraite'])->name('signalements.read');
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
        Route::post('/activites/reserver', [VisitorController::class, 'reserverActivite'])->name('activites.reserve');
        Route::post('/mes-activites/{reservation}/commander', [VisitorController::class, 'commanderChauffeurActivite'])->name('activites.command');
        Route::delete('/mes-activites/{reservation}', [VisitorController::class, 'annulerActivite'])->name('activites.destroy');

        // Chauffeurs
        Route::get('/chauffeurs', [VisitorController::class, 'drivers'])->name('drivers.index');
        Route::get('/chauffeurs/{user}', [VisitorController::class, 'showDriver'])->name('drivers.show');

        // Courses
        Route::get('/mes-courses', [VisitorCourseController::class, 'index'])->name('courses.index');
        Route::post('/chauffeurs/{user}/course', [VisitorCourseController::class, 'store'])->name('courses.store');
        Route::patch('/courses/{course}/confirmer', [VisitorCourseController::class, 'confirmer'])->name('courses.confirm');
        Route::patch('/courses/{course}/prix/accepter', [VisitorCourseController::class, 'accepterPrix'])->name('courses.price.accept');
        Route::patch('/courses/{course}/prix/contre', [VisitorCourseController::class, 'contrePrix'])->name('courses.price.counter');
        Route::patch('/courses/{course}/prix/refuser', [VisitorCourseController::class, 'refuserPrix'])->name('courses.price.refuse');
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

        // Courses
        Route::get('/mes-courses', [TaximanCourseController::class, 'index'])->name('courses.index');
        Route::patch('/courses/{course}/prix', [TaximanCourseController::class, 'proposerPrix'])->name('courses.price.propose');
        Route::patch('/courses/{course}/contre/accepter', [TaximanCourseController::class, 'accepterContrePrix'])->name('courses.counter.accept');
        Route::patch('/courses/{course}/contre/refuser', [TaximanCourseController::class, 'refuserContrePrix'])->name('courses.counter.refuse');
        Route::patch('/courses/{course}/refuser', [TaximanCourseController::class, 'refuser'])->name('courses.refuse');
        Route::patch('/courses/{course}/avancer', [TaximanCourseController::class, 'avancer'])->name('courses.advance');

        // Historique des clients
        Route::get('/clients', [TaximanClientController::class, 'index'])->name('clients.index');
        Route::get('/activites', [TaximanController::class, 'activites'])->name('activites.index');
    });
});
