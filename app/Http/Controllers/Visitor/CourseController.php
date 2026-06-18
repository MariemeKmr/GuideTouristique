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
            ->with('chauffeur')
            ->latest()
            ->paginate(10);

        return view('visitor.courses.index', compact('courses'));
    }

    public function store(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->isTaximan(), 404);

        $data = $request->validate([
            'depart'      => ['nullable', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
        ], [], [
            'depart'      => 'depart',
            'destination' => 'destination',
        ]);

        Course::create([
            'visiteur_id'  => $request->user()->id,
            'chauffeur_id' => $user->id,
            'depart'       => $data['depart'] ?? null,
            'destination'  => $data['destination'] ?? null,
            'statut'       => 'demandee',
        ]);

        return redirect()
            ->route('visitor.courses.index')
            ->with('success', 'Votre demande de course a ete envoyee au chauffeur.');
    }

    public function demarrer(Request $request, Course $course): RedirectResponse
    {
        $this->autoriseVisiteur($request, $course);
        abort_unless($course->statut === 'arrive', 403);

        $course->update(['statut' => 'en_course']);

        return back()->with('success', 'Course demarree. Bon trajet !');
    }

    public function annuler(Request $request, Course $course): RedirectResponse
    {
        $this->autoriseVisiteur($request, $course);
        abort_unless(in_array($course->statut, ['demandee', 'acceptee', 'en_route', 'arrive'], true), 403);

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
