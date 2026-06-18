@extends('layouts.app')

@section('title', 'Destinations')

@section('content')
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-nuit">Destinations</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $destinations->total() }} destination(s) enregistrée(s).</p>
            </div>
            <a href="{{ route('admin.destinations.create') }}"
               class="rounded-md bg-terracotta px-4 py-2 text-sm font-medium text-white hover:bg-terracotta-600 transition">
                Ajouter
            </a>
        </div>

        @include('partials.flash')

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-sable-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nom</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Localité</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Rue</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($destinations as $destination)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-nuit">{{ $destination->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $destination->localite }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $destination->rue ?: '—' }}</td>
                            <td class="px-4 py-3 text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.destinations.edit', $destination) }}"
                                       class="rounded-md border border-gray-300 px-3 py-1.5 font-medium text-gray-700 hover:bg-sable-50">
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.destinations.destroy', $destination) }}"
                                          onsubmit="return confirm('Supprimer cette destination ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="rounded-md border border-red-200 px-3 py-1.5 font-medium text-red-700 hover:bg-red-50">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-500">
                                Aucune destination pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $destinations->links() }}
        </div>
    </main>
@endsection
