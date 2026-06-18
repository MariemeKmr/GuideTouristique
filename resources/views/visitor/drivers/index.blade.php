@extends('layouts.app')

@section('title', 'Chauffeurs')

@section('content')
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-nuit">Chauffeurs disponibles</h1>
        <p class="mt-1 text-sm text-gray-500">Consultez les chauffeurs et contactez-les directement.</p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($drivers as $driver)
                @php($profile = $driver->chauffeurProfile)
                <div class="rounded-lg border border-gray-200 bg-white p-5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sable-50 text-sm font-semibold text-gray-700">
                                {{ strtoupper(substr($driver->first_name, 0, 1) . substr($driver->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="text-base font-semibold text-nuit">{{ $driver->full_name }}</h2>
                                <p class="text-xs text-gray-500">{{ $profile?->zone ?: 'Zone non renseignée' }}</p>
                            </div>
                        </div>
                        @if ($profile && $profile->disponible)
                            <span class="shrink-0 rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 border border-green-200">Disponible</span>
                        @elseif ($profile && ! $profile->disponible)
                            <span class="shrink-0 rounded-full bg-sable-50 px-2 py-0.5 text-xs font-medium text-gray-600 border border-gray-200">Indisponible</span>
                        @endif
                    </div>

                    @if ($profile && $profile->tarif_indicatif)
                        <p class="mt-3 text-sm text-gray-600">Tarif indicatif : {{ $profile->tarif_indicatif }}</p>
                    @endif

                    <div class="mt-4 flex items-center gap-2">
                        <a href="{{ route('visitor.drivers.show', $driver) }}"
                           class="rounded-md border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-sable-50">
                            Voir le profil
                        </a>
                        @if ($driver->phone)
                            <a href="tel:{{ preg_replace('/\s+/', '', $driver->phone) }}"
                               class="rounded-md bg-terracotta px-3 py-1.5 text-sm font-medium text-white hover:bg-terracotta-600">
                                Appeler
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-lg border border-gray-200 bg-white p-10 text-center text-sm text-gray-500">
                    Aucun chauffeur inscrit pour le moment.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $drivers->links() }}
        </div>
    </main>
@endsection
