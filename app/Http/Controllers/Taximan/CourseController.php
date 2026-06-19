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
        'arrive'    => 'en_course',
        'en_course' => 'terminee',
    ];

    public function index(Request $request): View
    {
        $user = $request->user();

        // Consulter la liste acquitte les alertes (courses refusees par un client).
        $user->coursesChauffeur()->where('alerte_chauffeur', true)->update(['alerte_chauffeur' => false]);

        $courses = $user->coursesChauffeur()
            ->with('visiteur')
            ->latest()
            ->paginate(10);

        return view('taximan.courses.index', compact('courses'));
    }

    public function accepter(Request $request, Course $course): RedirectResponse
    {
        $this->autorise($request, $course, ['demandee']);
        $course->update(['statut' => 'acceptee']);

        return back()->with('success', 'Course acceptee.');
    }

    public function refuser(Request $request, Course $course): RedirectResponse
    {
        $this->autorise($request, $course, ['demandee']);
        $course->update(['statut' => 'annulee']);

        return back()->with('success', 'Course refusee.');
    }

    public function avancer(Request $request, Course $course): RedirectResponse
    {
        abort_unless($course->chauffeur_id === $request->user()->id, 403);

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
