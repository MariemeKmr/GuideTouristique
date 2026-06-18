<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActiviteRequest;
use App\Models\Activite;
use App\Models\Destination;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ActiviteController extends Controller
{
    public function index(): View
    {
        $activites = Activite::with('destination')->latest()->paginate(10);

        return view('admin.activites.index', compact('activites'));
    }

    public function create(): View
    {
        return view('admin.activites.create', [
            'destinations' => Destination::orderBy('name')->get(),
            'categories'   => Activite::CATEGORIES,
        ]);
    }

    public function store(ActiviteRequest $request): RedirectResponse
    {
        Activite::create($request->validated());

        return redirect()
            ->route('admin.activites.index')
            ->with('success', 'Activite ajoutee avec succes.');
    }

    public function edit(Activite $activite): View
    {
        return view('admin.activites.edit', [
            'activite'     => $activite,
            'destinations' => Destination::orderBy('name')->get(),
            'categories'   => Activite::CATEGORIES,
        ]);
    }

    public function update(ActiviteRequest $request, Activite $activite): RedirectResponse
    {
        $activite->update($request->validated());

        return redirect()
            ->route('admin.activites.index')
            ->with('success', 'Activite modifiee avec succes.');
    }

    public function destroy(Activite $activite): RedirectResponse
    {
        $activite->delete();

        return redirect()
            ->route('admin.activites.index')
            ->with('success', 'Activite supprimee.');
    }
}
