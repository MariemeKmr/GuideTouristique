<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ObjetPerduController extends Controller
{
    public function show(Request $request, Course $course): View
    {
        $this->autorise($request, $course);

        // Ouvrir le fil marque comme lus les messages recus.
        $course->messagesObjet()
            ->where('expediteur_id', '!=', $request->user()->id)
            ->where('lu', false)
            ->update(['lu' => true]);

        $messages = $course->messagesObjet()->with('expediteur')->oldest()->get();

        return view('objets.show', compact('course', 'messages'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->autorise($request, $course);

        $data = $request->validate([
            'contenu' => ['required', 'string', 'max:1000'],
        ], [], [
            'contenu' => 'message',
        ]);

        $course->messagesObjet()->create([
            'expediteur_id' => $request->user()->id,
            'contenu'       => $data['contenu'],
        ]);

        return redirect()
            ->route('objets.show', $course)
            ->with('success', 'Message envoye.');
    }

    /** Seuls le visiteur et le chauffeur de la course accedent au fil. */
    private function autorise(Request $request, Course $course): void
    {
        $id = $request->user()->id;
        abort_unless(in_array($id, [$course->visiteur_id, $course->chauffeur_id], true), 403);
    }
}
