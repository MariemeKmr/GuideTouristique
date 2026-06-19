<?php

namespace App\Http\Controllers\Taximan;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    /** Etapes que le chauffeur peut declencher : statut courant => statut suivant */
    private const ETAPES = [
        'acceptee'  => 'en_route',
        'en_route'  => 'arrive',
        'arrive'    => 'attente_client',
        'en_course' => 'terminee',
    ];

    public function index(Request $request): View
    {
        $user = $request->user();

        // Consulter la liste acquitte les alertes (courses refusees par un client).
        $user->coursesChauffeur()->where('alerte_chauffeur', true)->update(['alerte_chauffeur' => false]);

        $courses = $user->coursesChauffeur()
            ->with(['visiteur', 'activite'])
            ->latest()
            ->paginate(10);

        return view('taximan.courses.index', compact('courses'));
    }

    /** Le chauffeur propose un prix pour la demande. */
    public function proposerPrix(Request $request, Course $course): RedirectResponse
    {
        $this->autorise($request, $course, ['demandee']);

        $data = $request->validate([
            'prix' => ['required', 'integer', 'min:0'],
        ], [], ['prix' => 'prix']);

        $course->update(['statut' => 'prix_propose', 'prix' => $data['prix']]);

        return back()->with('success', 'Prix propose au client.');
    }

    public function refuser(Request $request, Course $course): RedirectResponse
    {
        $this->autorise($request, $course, ['demandee']);
        $course->update(['statut' => 'annulee']);

        return back()->with('success', 'Demande refusee.');
    }

    /** Le chauffeur accepte la contre-proposition du client. */
    public function accepterContrePrix(Request $request, Course $course): RedirectResponse
    {
        $this->autorise($request, $course, ['contre_propose']);
        $course->update(['statut' => 'acceptee']);

        return back()->with('success', 'Prix accepte. Vous pouvez rejoindre le client.');
    }

    /** Le chauffeur refuse la contre-proposition : course annulee. */
    public function refuserContrePrix(Request $request, Course $course): RedirectResponse
    {
        $this->autorise($request, $course, ['contre_propose']);
        $course->update(['statut' => 'annulee', 'annulee_par' => 'prix']);

        return back()->with('success', "Contre-proposition refusee. La course est annulee.");
    }

    public function avancer(Request $request, Course $course): RedirectResponse
    {
        abort_unless($course->chauffeur_id === $request->user()->id, 403);

        // Course liee a une activite : on ne peut la lancer que le jour prevu.
        if ($course->date_prevue && $course->statut === 'acceptee' && $course->date_prevue->isFuture()) {
            return back()->with('error', 'Vous pourrez demarrer cette course le ' . $course->date_prevue->format('d/m/Y') . '.');
        }

        $suivant = self::ETAPES[$course->statut] ?? null;
        abort_if(is_null($suivant), 403);

        $course->update(['statut' => $suivant]);

        return back()->with('success', 'Statut mis a jour : ' . $course->statutLabel() . '.');
    }

    private function autorise(Request $request, Course $course, array $statuts): void
    {
        abort_unless($course->chauffeur_id === $request->user()->id, 403);
        abort_unless(in_array($course->statut, $statuts, true), 403);
    }
}
