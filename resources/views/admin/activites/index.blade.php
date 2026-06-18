@extends('layouts.app')

@section('title', 'Activites')

@section('content')
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-nuit">Activites</h1>
                <p class="mt-1 text-sm text-nuit/60">{{ $activites->total() }} activite(s) enregistree(s).</p>
            </div>
            <a href="{{ route('admin.activites.create') }}"
               class="rounded-xl bg-terracotta px-4 py-2 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 hover:shadow-lift transition">
                Ajouter
            </a>
        </div>

        @include('partials.flash')

        <div class="overflow-hidden rounded-2xl border border-sable-200 bg-white shadow-soft">
            <table class="min-w-full divide-y divide-sable-200">
                <thead class="bg-sable-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-nuit/50">Nom</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-nuit/50">Categorie</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-nuit/50">Lieu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-nuit/50">Tarif</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-nuit/50">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sable-200">
                    @forelse ($activites as $activite)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-nuit">{{ $activite->nom }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="rounded-full bg-lagon-50 px-2 py-0.5 text-xs font-medium text-lagon-700">{{ $activite->categorieLabel() }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-nuit/70">{{ $activite->lieu ?: '-' }}</td>
                            <td class="px-4 py-3 text-sm text-nuit/70">{{ $activite->tarif ?: '-' }}</td>
                            <td class="px-4 py-3 text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.activites.edit', $activite) }}"
                                       class="rounded-xl border border-sable-300 px-3 py-1.5 font-medium text-nuit/80 hover:bg-sable-50">Modifier</a>
                                    <form method="POST" action="{{ route('admin.activites.destroy', $activite) }}"
                                          onsubmit="return confirm('Supprimer cette activite ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="rounded-xl border border-red-200 px-3 py-1.5 font-medium text-red-700 hover:bg-red-50">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-nuit/50">Aucune activite pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $activites->links() }}</div>
    </main>
@endsection
