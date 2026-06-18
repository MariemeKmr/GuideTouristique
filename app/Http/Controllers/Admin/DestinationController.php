<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestinationRequest;
use App\Models\Destination;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(): View
    {
        $destinations = Destination::latest()->paginate(10);

        return view('admin.destinations.index', compact('destinations'));
    }

    public function create(): View
    {
        return view('admin.destinations.create');
    }

    public function store(DestinationRequest $request): RedirectResponse
    {
        Destination::create($request->validated());

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'Destination ajoutée avec succès.');
    }

    public function edit(Destination $destination): View
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(DestinationRequest $request, Destination $destination): RedirectResponse
    {
        $destination->update($request->validated());

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'Destination modifiée avec succès.');
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        $destination->delete();

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'Destination supprimée.');
    }
}
