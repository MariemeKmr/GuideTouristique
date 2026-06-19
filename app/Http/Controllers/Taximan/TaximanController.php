<?php

namespace App\Http\Controllers\Taximan;

use App\Http\Controllers\Controller;
use App\Models\ActiviteReservation;
use App\Models\ContactRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaximanController extends Controller
{
    /**
     * Affiche le formulaire d'édition du profil chauffeur.
     */
    public function editProfile(Request $request): View
    {
        $user    = $request->user();
        $profile = $user->chauffeurProfile; // peut être null à la première visite

        return view('taximan.profile', compact('user', 'profile'));
    }

    /**
     * Met à jour les coordonnées (table users) et le profil chauffeur.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $data = $request->validate([
            // Coordonnées personnelles
            'first_name'      => ['required', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'phone'           => ['nullable', 'string', 'max:30'],
            // Profil chauffeur
            'zone'            => ['nullable', 'string', 'max:255'],
            'vehicule'        => ['nullable', 'string', 'max:255'],
            'tarif_indicatif' => ['nullable', 'string', 'max:255'],
            'disponible'      => ['nullable', 'boolean'],
            'bio'             => ['nullable', 'string', 'max:1000'],
        ], [], [
            'first_name'      => 'prénom',
            'last_name'       => 'nom',
            'phone'           => 'téléphone',
            'zone'            => 'zone',
            'vehicule'        => 'véhicule',
            'tarif_indicatif' => 'tarif indicatif',
            'bio'             => 'description',
        ]);

        $user = $request->user();

        // 1. Coordonnées personnelles
        $user->update([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'phone'      => $data['phone'] ?? null,
        ]);

        // 2. Profil chauffeur (créé s'il n'existe pas encore)
        $user->chauffeurProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'zone'            => $data['zone'] ?? null,
                'vehicule'        => $data['vehicule'] ?? null,
                'tarif_indicatif' => $data['tarif_indicatif'] ?? null,
                'disponible'      => $request->boolean('disponible'),
                'bio'             => $data['bio'] ?? null,
            ]
        );

        return redirect()
            ->route('taximan.profile.edit')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    public function marquerContactLu(ContactRequest $contactRequest): RedirectResponse
    {
        abort_unless($contactRequest->chauffeur_id === auth()->id(), 403);

        $contactRequest->update(['lu' => true]);

        return back()->with('success', 'Demande marquee comme lue.');
    }

    public function activites(Request $request): View
    {
        $reservations = ActiviteReservation::with(['activite', 'visiteur'])
            ->where('chauffeur_id', $request->user()->id)
            ->orderByDesc('date_activite')
            ->get();

        return view('taximan.activites.index', compact('reservations'));
    }
}
