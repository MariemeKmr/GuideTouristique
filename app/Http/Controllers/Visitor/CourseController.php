<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $request->user()
            ->coursesVisiteur()
            ->with(['chauffeur', 'activite'])
            ->latest()
            ->paginate(10);

        return view('visitor.courses.index', compact('courses'));
    }

    public function store(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->isTaximan(), 404);

        $data = $request->validate([
            'depart'      => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
        ], [], [
            'depart'      => 'depart',
            'destination' => 'destination',
        ]);

        Course::create([
            'visiteur_id'  => $request->user()->id,
            'chauffeur_id' => $user->id,
            'depart'       => $data['depart'],
            'destination'  => $data['destination'],
            'statut'       => 'demandee',
        ]);

        return redirect()
            ->route('visitor.courses.index')
            ->with('success', 'Demande envoyee. Le chauffeur va vous proposer un prix.');
    }

    /** Le client accepte le prix propose par le chauffeur. */
    public function accepterPrix(Request $request, Course $course): RedirectResponse
    {
        $this->autoriseVisiteur($request, $course);
        abort_unless($course->statut === 'prix_propose', 403);

        $course->update(['statut' => 'acceptee']);

        return back()->with('success', 'Prix accepte. Le chauffeur arrive vers vous.');
    }

    /** Le client propose un autre prix (une seule contre-proposition). */
    public function contrePrix(Request $request, Course $course): RedirectResponse
    {
        $this->autoriseVisiteur($request, $course);
        abort_unless($course->statut === 'prix_propose', 403);

        $data = $request->validate([
            'prix' => ['required', 'integer', 'min:0'],
        ], [], ['prix' => 'prix']);

        $course->update([
            'statut' => 'contre_propose',
            'prix'   => $data['prix'],
        ]);

        return back()->with('success', 'Votre prix a ete propose au chauffeur.');
    }

    /** Le client refuse le prix : course annulee. */
    public function refuserPrix(Request $request, Course $course): RedirectResponse
    {
        $this->autoriseVisiteur($request, $course);
        abort_unless($course->statut === 'prix_propose', 403);

        $course->update(['statut' => 'annulee', 'annulee_par' => 'prix']);

        return back()->with('success', "Vous n'etes pas tombes d'accord sur le prix. Essayez un autre chauffeur.");
    }

    /** Le client confirme (ou non) le demarrage une fois le chauffeur arrive. */
    public function confirmer(Request $request, Course $course): RedirectResponse
    {
        $this->autoriseVisiteur($request, $course);
        abort_unless($course->statut === 'attente_client', 403);

        $data = $request->validate([
            'reponse' => ['required', 'in:oui,non'],
        ]);

        if ($data['reponse'] === 'oui') {
            $course->update(['statut' => 'en_course']);

            return back()->with('success', 'Course demarree. Bon trajet !');
        }

        $course->update([
            'statut'           => 'annulee',
            'annulee_par'      => 'client',
            'alerte_chauffeur' => true,
        ]);

        return back()->with('success', 'Course annulee.');
    }

    public function annuler(Request $request, Course $course): RedirectResponse
    {
        $this->autoriseVisiteur($request, $course);
        abort_unless(in_array($course->statut, ['demandee', 'prix_propose', 'contre_propose', 'acceptee', 'en_route', 'arrive'], true), 403);

        $course->update(['statut' => 'annulee']);

        return back()->with('success', 'Course annulee.');
    }

    public function noter(Request $request, Course $course): RedirectResponse
    {
        $this->autoriseVisiteur($request, $course);
        abort_unless($course->peutEtreNotee(), 403);

        $data = $request->validate([
            'note'        => ['required', 'integer', 'between:1,5'],
            'commentaire' => ['nullable', 'string', 'max:500'],
        ], [], [
            'note'        => 'note',
            'commentaire' => 'commentaire',
        ]);

        $course->update($data);

        return back()->with('success', 'Merci, votre note a ete enregistree.');
    }

    private function autoriseVisiteur(Request $request, Course $course): void
    {
        abort_unless($course->visiteur_id === $request->user()->id, 403);
    }
}
