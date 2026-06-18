<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Transport;
use App\Models\User;
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
        $stats = [
            'destinations' => Destination::count(),
            'transports'   => Transport::count(),
            'taximen'      => User::where('role', User::ROLE_TAXIMAN)->count(),
            'visiteurs'    => User::where('role', User::ROLE_VISITEUR)->count(),
        ];

        return view('dashboards.admin', compact('stats'));
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
