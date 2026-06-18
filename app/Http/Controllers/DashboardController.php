<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Point d'entrée unique : redirige chaque utilisateur vers
     * le tableau de bord correspondant à son rôle.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route(auth()->user()->dashboardRoute());
    }

    public function admin(): View
    {
        return view('dashboards.admin');
    }

    public function visitor(): View
    {
        return view('dashboards.visitor');
    }

    public function taximan(): View
    {
        return view('dashboards.taximan');
    }
}
