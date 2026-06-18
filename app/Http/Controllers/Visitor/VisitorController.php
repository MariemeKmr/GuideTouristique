<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Transport;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VisitorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Destinations
    |--------------------------------------------------------------------------
    */

    public function destinations(Request $request): View
    {
        $destinations = Destination::orderBy('name')->paginate(12);

        // IDs des destinations déjà visitées par l'utilisateur (pour le badge).
        $visitedIds = $request->user()->destinations()->pluck('destinations.id')->all();

        return view('visitor.destinations.index', compact('destinations', 'visitedIds'));
    }

    public function showDestination(Request $request, Destination $destination): View
    {
        $visited = $request->user()
            ->destinations()
            ->where('destinations.id', $destination->id)
            ->first();

        return view('visitor.destinations.show', compact('destination', 'visited'));
    }

    public function markVisited(Request $request, Destination $destination): RedirectResponse
    {
        $data = $request->validate([
            'date_visite' => ['nullable', 'date'],
        ]);

        $date = $data['date_visite'] ?? now()->toDateString();
        $user = $request->user();

        $already = $user->destinations()
            ->where('destinations.id', $destination->id)
            ->exists();

        if ($already) {
            $user->destinations()->updateExistingPivot($destination->id, [
                'date_visite' => $date,
            ]);
            $message = 'Date de visite mise à jour.';
        } else {
            // La clé primaire UUID du pivot doit être fournie explicitement.
            $user->destinations()->attach($destination->id, [
                'id'          => (string) Str::uuid(),
                'date_visite' => $date,
            ]);
            $message = 'Destination ajoutée à vos visites.';
        }

        return redirect()
            ->route('visitor.destinations.show', $destination)
            ->with('success', $message);
    }

    public function unmarkVisited(Request $request, Destination $destination): RedirectResponse
    {
        $request->user()->destinations()->detach($destination->id);

        return redirect()
            ->route('visitor.destinations.show', $destination)
            ->with('success', 'Destination retirée de vos visites.');
    }

    public function myVisits(Request $request): View
    {
        $visits = $request->user()
            ->destinations()
            ->orderByPivot('date_visite', 'desc')
            ->paginate(12);

        return view('visitor.visits', compact('visits'));
    }

    /*
    |--------------------------------------------------------------------------
    | Transports (consultation)
    |--------------------------------------------------------------------------
    */

    public function transports(): View
    {
        $transports = Transport::orderBy('methode')->paginate(12);

        return view('visitor.transports', compact('transports'));
    }

    /*
    |--------------------------------------------------------------------------
    | Chauffeurs (consultation / contact)
    |--------------------------------------------------------------------------
    */

    public function drivers(): View
    {
        $drivers = User::where('role', User::ROLE_TAXIMAN)
            ->orderBy('first_name')
            ->paginate(12);

        return view('visitor.drivers.index', compact('drivers'));
    }

    public function showDriver(User $user): View
    {
        // On ne montre que les profils de chauffeurs.
        abort_unless($user->isTaximan(), 404);

        return view('visitor.drivers.show', ['driver' => $user]);
    }
}
