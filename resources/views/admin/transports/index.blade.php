@extends('layouts.app')

@section('title', 'Transports')

@section('content')
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Transports</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $transports->total() }} moyen(s) de transport enregistré(s).</p>
            </div>
            <a href="{{ route('admin.transports.create') }}"
               class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 transition">
                Ajouter
            </a>
        </div>

        @include('partials.flash')

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Méthode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Coût approx.</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($transports as $transport)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $transport->methode }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $transport->approximation_cout ?: '—' }}</td>
                            <td class="px-4 py-3 text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.transports.edit', $transport) }}"
                                       class="rounded-md border border-gray-300 px-3 py-1.5 font-medium text-gray-700 hover:bg-gray-50">
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.transports.destroy', $transport) }}"
                                          onsubmit="return confirm('Supprimer ce transport ?');">
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
                            <td colspan="3" class="px-4 py-10 text-center text-sm text-gray-500">
                                Aucun transport pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transports->links() }}
        </div>
    </main>
@endsection
