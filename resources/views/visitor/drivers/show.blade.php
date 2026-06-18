@extends('layouts.app')

@section('title', $driver->full_name)

@section('content')
    @include('partials.navbar')

    <main class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
        <a href="{{ route('visitor.drivers.index') }}" class="text-sm text-gray-500 hover:text-nuit">&larr; Tous les chauffeurs</a>

        @php($profile = $driver->chauffeurProfile)

        <div class="mt-4 rounded-2xl border border-sable-200 bg-white shadow-soft p-6">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-sable-50 text-lg font-semibold text-gray-700">
                        {{ strtoupper(substr($driver->first_name, 0, 1) . substr($driver->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-semibold text-nuit">{{ $driver->full_name }}</h1>
                        <p class="text-sm text-gray-500">Chauffeur</p>
                    </div>
                </div>
                @if ($profile && $profile->disponible)
                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700 border border-green-200">Disponible</span>
                @elseif ($profile && ! $profile->disponible)
                    <span class="rounded-full bg-sable-50 px-3 py-1 text-xs font-medium text-gray-600 border border-sable-200">Indisponible</span>
                @endif
            </div>

            @if ($profile && $profile->bio)
                <p class="mt-4 text-sm text-gray-700 whitespace-pre-line">{{ $profile->bio }}</p>
            @endif

            <div class="mt-6 border-t border-sable-200 pt-6 grid gap-4 sm:grid-cols-2">
                <div>
                    <div class="text-xs font-medium uppercase tracking-wider text-gray-400">Contact</div>
                    <div class="mt-1 text-sm text-nuit">Via la plateforme</div>
                </div>
                <div>
                    <div class="text-xs font-medium uppercase tracking-wider text-gray-400">Zone desservie</div>
                    <div class="mt-1 text-sm text-nuit">{{ $profile?->zone ?: 'Non renseignée' }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium uppercase tracking-wider text-gray-400">Véhicule</div>
                    <div class="mt-1 text-sm text-nuit">{{ $profile?->vehicule ?: 'Non renseigné' }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium uppercase tracking-wider text-gray-400">Tarif indicatif</div>
                    <div class="mt-1 text-sm text-nuit">{{ $profile?->tarif_indicatif ?: 'Non renseigné' }}</div>
                </div>
            </div>

            <div class="mt-6">
                @include('partials.flash')
                <form method="POST" action="{{ route('visitor.drivers.contact', $driver) }}">
                    @csrf
                    <button type="submit"
                            class="rounded-xl bg-terracotta px-5 py-2.5 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 hover:shadow-lift transition">
                        Contacter ce chauffeur
                    </button>
                </form>
                <p class="mt-2 text-xs text-nuit/50">Le chauffeur recevra une notification sur la plateforme.</p>
            </div>
        </div>
    </main>
@endsection
