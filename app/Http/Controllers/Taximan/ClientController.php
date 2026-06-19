<?php

namespace App\Http\Controllers\Taximan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $request->user()
            ->coursesChauffeur()
            ->with('visiteur')
            ->get();

        $clients = $courses
            ->groupBy('visiteur_id')
            ->map(function ($items) {
                return (object) [
                    'client'    => $items->first()->visiteur,
                    'total'     => $items->count(),
                    'terminees' => $items->where('statut', 'terminee')->count(),
                    'derniere'  => $items->max('created_at'),
                ];
            })
            ->sortByDesc('derniere')
            ->values();

        $stats = [
            'clients'   => $clients->count(),
            'courses'   => $courses->count(),
            'terminees' => $courses->where('statut', 'terminee')->count(),
        ];

        return view('taximan.clients.index', compact('clients', 'stats'));
    }
}
