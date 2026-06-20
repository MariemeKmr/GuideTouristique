<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Signalement;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SignalementController extends Controller
{
    public function index(): View
    {
        $signalements = Signalement::with(['course.visiteur', 'course.chauffeur', 'auteur'])
            ->latest()
            ->paginate(15);

        return view('admin.signalements.index', compact('signalements'));
    }

    public function marquerTraite(Signalement $signalement): RedirectResponse
    {
        $signalement->update(['lu' => true]);

        return back()->with('success', 'Signalement marque comme traite.');
    }
}
