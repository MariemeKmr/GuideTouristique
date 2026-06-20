<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Signalement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SignalementController extends Controller
{
    private const STATUTS_ELIGIBLES = ['en_course', 'terminee'];

    public function create(Request $request, Course $course): View
    {
        $cible  = $this->cible($request, $course);
        $motifs = $cible === 'chauffeur' ? Signalement::MOTIFS_VISITEUR : Signalement::MOTIFS_CHAUFFEUR;

        return view('signalements.create', compact('course', 'cible', 'motifs'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $cible  = $this->cible($request, $course);
        $motifs = $cible === 'chauffeur' ? Signalement::MOTIFS_VISITEUR : Signalement::MOTIFS_CHAUFFEUR;

        $data = $request->validate([
            'motif'       => ['required', Rule::in(array_keys($motifs))],
            'description' => ['nullable', 'required_if:motif,autre', 'string', 'max:1000'],
        ], [
            'description.required_if' => 'Merci de preciser votre souci.',
        ], [
            'motif'       => 'motif',
            'description' => 'description',
        ]);

        Signalement::create([
            'course_id'   => $course->id,
            'auteur_id'   => $request->user()->id,
            'cible'       => $cible,
            'motif'       => $data['motif'],
            'description' => $data['description'] ?? null,
        ]);

        $route = $request->user()->isTaximan() ? 'taximan.courses.index' : 'visitor.courses.index';

        return redirect()->route($route)->with('success', "Votre signalement a ete transmis a l'administration.");
    }

    /** Fil de discussion entre l'administration et le plaignant. */
    public function show(Request $request, Signalement $signalement): View
    {
        $this->autoriseConversation($request, $signalement);

        $signalement->messages()
            ->where('expediteur_id', '!=', $request->user()->id)
            ->where('lu', false)
            ->update(['lu' => true]);

        $signalement->load(['course.visiteur', 'course.chauffeur', 'auteur', 'messages.expediteur']);
        $estAdmin = $request->user()->isAdmin();

        return view('signalements.show', compact('signalement', 'estAdmin'));
    }

    public function message(Request $request, Signalement $signalement): RedirectResponse
    {
        $this->autoriseConversation($request, $signalement);
        abort_if($signalement->lu, 403, 'Ce signalement est traite, la conversation est close.');

        $data = $request->validate([
            'contenu' => ['required', 'string', 'max:1000'],
        ], [], ['contenu' => 'message']);

        $signalement->messages()->create([
            'expediteur_id' => $request->user()->id,
            'contenu'       => $data['contenu'],
        ]);

        return redirect()->route('signalements.show', $signalement)->with('success', 'Message envoye.');
    }

    /** Liste des signalements emis par l'utilisateur courant. */
    public function mes(Request $request): View
    {
        $signalements = $request->user()
            ->signalementsAuteur()
            ->with(['course'])
            ->latest()
            ->paginate(10);

        return view('signalements.mes', compact('signalements'));
    }

    /** Seuls un admin et l'auteur du signalement accedent a la conversation. */
    private function autoriseConversation(Request $request, Signalement $signalement): void
    {
        $user = $request->user();
        abort_unless($user->isAdmin() || $signalement->auteur_id === $user->id, 403);
    }

    /** Verifie que l'utilisateur est un participant autorise et renvoie la cible du signalement. */
    private function cible(Request $request, Course $course): string
    {
        $id = $request->user()->id;
        abort_unless(in_array($id, [$course->visiteur_id, $course->chauffeur_id], true), 403);
        abort_unless(in_array($course->statut, self::STATUTS_ELIGIBLES, true), 403);

        return $id === $course->visiteur_id ? 'chauffeur' : 'passager';
    }
}
