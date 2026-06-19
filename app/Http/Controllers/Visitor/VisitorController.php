<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\Activite;
use App\Models\ActiviteReservation;
use App\Models\Course;
use App\Models\Destination;
use App\Models\Transport;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

        $reservations = $request->user()
            ->reservationsActivites()
            ->with(['activite', 'chauffeur'])
            ->orderByDesc('date_activite')
            ->get();

        $activitesDispo = Activite::orderBy('nom')->get();

        $chauffeurs = User::where('role', User::ROLE_TAXIMAN)
            ->orderBy('first_name')
            ->get();

        return view('visitor.visits', compact('visits', 'reservations', 'activitesDispo', 'chauffeurs'));
    }

    public function reserverActivite(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'activite_id'   => [
                'required',
                'exists:activites,id',
                Rule::unique('activite_reservations')->where(function ($q) use ($request) {
                    return $q->where('visiteur_id', $request->user()->id)
                             ->where('date_activite', $request->input('date_activite'));
                }),
            ],
            'date_activite' => ['required', 'date', 'after_or_equal:today'],
        ], [
            'activite_id.unique' => 'Vous avez deja reserve cette activite a cette date.',
        ], [
            'activite_id'   => 'activite',
            'date_activite' => 'date',
        ]);

        ActiviteReservation::create([
            'visiteur_id'   => $request->user()->id,
            'activite_id'   => $data['activite_id'],
            'date_activite' => $data['date_activite'],
        ]);

        return back()->with('success', 'Activite reservee.');
    }

    public function commanderChauffeurActivite(Request $request, ActiviteReservation $reservation): RedirectResponse
    {
        abort_unless($reservation->visiteur_id === $request->user()->id, 403);

        $data = $request->validate([
            'chauffeur_id' => ['required', 'exists:users,id'],
            'depart'       => ['required', 'string', 'max:255'],
        ], [], [
            'chauffeur_id' => 'chauffeur',
            'depart'       => 'depart',
        ]);

        $chauffeur = User::findOrFail($data['chauffeur_id']);
        abort_unless($chauffeur->isTaximan(), 422);

        $reservation->loadMissing('activite');

        Course::create([
            'visiteur_id'  => $request->user()->id,
            'chauffeur_id' => $chauffeur->id,
            'depart'       => $data['depart'],
            'destination'  => $reservation->activite->lieu ?: $reservation->activite->nom,
            'statut'       => 'demandee',
            'activite_id'  => $reservation->activite_id,
            'date_prevue'  => $reservation->date_activite,
        ]);

        $reservation->update(['chauffeur_id' => $chauffeur->id]);

        return redirect()
            ->route('visitor.courses.index')
            ->with('success', 'Chauffeur commande pour cette activite. Il va vous proposer un prix.');
    }

    public function annulerActivite(Request $request, ActiviteReservation $reservation): RedirectResponse
    {
        abort_unless($reservation->visiteur_id === $request->user()->id, 403);

        $reservation->delete();

        return back()->with('success', 'Reservation annulee.');
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
            ->with('chauffeurProfile')
            ->orderBy('first_name')
            ->paginate(12);

        return view('visitor.drivers.index', compact('drivers'));
    }

    public function showDriver(User $user): View
    {
        // On ne montre que les profils de chauffeurs.
        abort_unless($user->isTaximan(), 404);

        $user->load('chauffeurProfile');

        return view('visitor.drivers.show', ['driver' => $user]);
    }

    /*
    |--------------------------------------------------------------------------
    | Activites (consultation, filtre par categorie)
    |--------------------------------------------------------------------------
    */

    public function activites(Request $request): View
    {
        $categorie = $request->query('categorie');

        $activites = Activite::with('destination')
            ->when($categorie, fn ($q) => $q->where('categorie', $categorie))
            ->orderBy('nom')
            ->paginate(12)
            ->withQueryString();

        return view('visitor.activites.index', [
            'activites'  => $activites,
            'categories' => Activite::CATEGORIES,
            'courante'   => $categorie,
        ]);
    }
}
