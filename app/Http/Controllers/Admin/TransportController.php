<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransportRequest;
use App\Models\Transport;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransportController extends Controller
{
    public function index(): View
    {
        $transports = Transport::latest()->paginate(10);

        return view('admin.transports.index', compact('transports'));
    }

    public function create(): View
    {
        return view('admin.transports.create');
    }

    public function store(TransportRequest $request): RedirectResponse
    {
        Transport::create($request->validated());

        return redirect()
            ->route('admin.transports.index')
            ->with('success', 'Transport ajouté avec succès.');
    }

    public function edit(Transport $transport): View
    {
        return view('admin.transports.edit', compact('transport'));
    }

    public function update(TransportRequest $request, Transport $transport): RedirectResponse
    {
        $transport->update($request->validated());

        return redirect()
            ->route('admin.transports.index')
            ->with('success', 'Transport modifié avec succès.');
    }

    public function destroy(Transport $transport): RedirectResponse
    {
        $transport->delete();

        return redirect()
            ->route('admin.transports.index')
            ->with('success', 'Transport supprimé.');
    }
}
