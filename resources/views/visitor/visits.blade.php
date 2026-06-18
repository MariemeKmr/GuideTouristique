@extends('layouts.app')

@section('title', 'Mes visites')

@section('content')
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-gray-900">Mes visites</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $visits->total() }} destination(s) visitée(s).</p>

        @include('partials.flash')

        <div class="mt-8 overflow-hidden rounded-lg border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Destination</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Localité</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date de visite</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($visits as $visit)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $visit->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $visit->localite }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $visit->pivot->date_visite ? \Illuminate\Support\Carbon::parse($visit->pivot->date_visite)->format('d/m/Y') : '—' }}
                            </td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a href="{{ route('visitor.destinations.show', $visit) }}"
                                   class="font-medium text-gray-700 hover:text-gray-900">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-500">
                                Vous n'avez pas encore marqué de visite.
                                <a href="{{ route('visitor.destinations.index') }}" class="font-medium text-gray-900 hover:underline">Explorer les destinations</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $visits->links() }}
        </div>
    </main>
@endsection
