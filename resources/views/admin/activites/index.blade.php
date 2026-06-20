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

        <div class="overflow-x-auto rounded-2xl border border-sable-200 bg-white shadow-soft">
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
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.activites.edit', $activite) }}" title="Modifier"
                                       class="grid h-8 w-8 place-items-center rounded-lg border border-sable-300 text-nuit/70 hover:bg-sable-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                                        <span class="sr-only">Modifier</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.activites.destroy', $activite) }}" onsubmit="return confirm('Supprimer cette activite ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Supprimer"
                                                class="grid h-8 w-8 place-items-center rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.02-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                            <span class="sr-only">Supprimer</span>
                                        </button>
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
