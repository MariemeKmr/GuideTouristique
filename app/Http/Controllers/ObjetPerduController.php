<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ObjetThread;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ObjetPerduController extends Controller
{
    public function show(Request $request, User $user): View
    {
        [$chauffeur, $visiteur] = $this->couple($request, $user);
        $thread = $this->thread($chauffeur, $visiteur);

        // Ouvrir le fil marque comme lus les messages recus.
        $thread->messages()
            ->where('expediteur_id', '!=', $request->user()->id)
            ->where('lu', false)
            ->update(['lu' => true]);

        $messages    = $thread->messages()->with('expediteur')->oldest()->get();
        $estVisiteur = $request->user()->id === $visiteur->id;

        return view('objets.show', compact('thread', 'messages', 'user', 'estVisiteur'));
    }

    public function store(Request $request, User $user): RedirectResponse
    {
        [$chauffeur, $visiteur] = $this->couple($request, $user);
        $thread = $this->thread($chauffeur, $visiteur);
        abort_if($thread->rendu, 403, "L'objet a ete rendu, la discussion est close.");

        $data = $request->validate([
            'contenu' => ['required', 'string', 'max:1000'],
        ], [], ['contenu' => 'message']);

        $thread->messages()->create([
            'expediteur_id' => $request->user()->id,
            'contenu'       => $data['contenu'],
        ]);

        return redirect()->route('objets.show', $user)->with('success', 'Message envoye.');
    }

    /** Le chauffeur valide que l'objet a ete rendu : le client est notifie. */
    public function marquerRendu(Request $request, User $user): RedirectResponse
    {
        [$chauffeur, $visiteur] = $this->couple($request, $user);
        abort_unless($request->user()->id === $chauffeur->id, 403);

        $thread = $this->thread($chauffeur, $visiteur);
        $thread->update(['rendu' => true]);

        $thread->messages()->create([
            'expediteur_id' => $chauffeur->id,
            'contenu'       => "L'objet a ete recupere.",
        ]);

        return back()->with('success', 'Objet marque comme rendu. Le client est notifie.');
    }

    /**
     * Determine le couple (chauffeur, visiteur) a partir de l'utilisateur courant
     * et de l'autre participant, et verifie qu'ils ont partage au moins une course.
     *
     * @return array{0: User, 1: User}
     */
    private function couple(Request $request, User $autre): array
    {
        $moi = $request->user();

        if ($moi->isTaximan() && $autre->isVisiteur()) {
            [$chauffeur, $visiteur] = [$moi, $autre];
        } elseif ($moi->isVisiteur() && $autre->isTaximan()) {
            [$chauffeur, $visiteur] = [$autre, $moi];
        } else {
            abort(403);
        }

        abort_unless(
            Course::where('chauffeur_id', $chauffeur->id)->where('visiteur_id', $visiteur->id)->exists(),
            403
        );

        return [$chauffeur, $visiteur];
    }

    private function thread(User $chauffeur, User $visiteur): ObjetThread
    {
        return ObjetThread::firstOrCreate([
            'chauffeur_id' => $chauffeur->id,
            'visiteur_id'  => $visiteur->id,
        ]);
    }
}
