@extends('layouts.app')

@section('title', 'Tableau de bord - Chauffeur')

@section('content')
    @include('partials.navbar')

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold text-nuit">Espace chauffeur</h1>
                <p class="mt-1 text-sm text-gray-500">Bonjour {{ auth()->user()->first_name }}.</p>
            </div>
            <a href="{{ route('taximan.profile.edit') }}"
               class="rounded-xl bg-terracotta px-4 py-2 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 hover:shadow-lift transition">
                Modifier mon profil
            </a>
        </div>

        @include('partials.flash')

        @if (! $profile)
            <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-800">
                Votre profil n'est pas encore complété. Les visiteurs voient peu d'informations sur vous.
                <a href="{{ route('taximan.profile.edit') }}" class="font-medium underline">Compléter mon profil</a>.
            </div>
        @else
            <div class="mt-8 rounded-2xl border border-sable-200 bg-white shadow-soft p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-nuit">Mon profil public</h2>
                    @if ($profile->disponible)
                        <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700 border border-green-200">Disponible</span>
                    @else
                        <span class="rounded-full bg-sable-50 px-3 py-1 text-xs font-medium text-gray-600 border border-sable-200">Indisponible</span>
                    @endif
                </div>

                <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Téléphone</dt>
                        <dd class="mt-1 text-sm text-nuit">{{ auth()->user()->phone ?: 'Non renseigné' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Zone desservie</dt>
                        <dd class="mt-1 text-sm text-nuit">{{ $profile->zone ?: 'Non renseignée' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Véhicule</dt>
                        <dd class="mt-1 text-sm text-nuit">{{ $profile->vehicule ?: 'Non renseigné' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">Tarif indicatif</dt>
                        <dd class="mt-1 text-sm text-nuit">{{ $profile->tarif_indicatif ?: 'Non renseigné' }}</dd>
                    </div>
                </dl>

                @if ($profile->bio)
                    <div class="mt-4">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400">À propos</dt>
                        <dd class="mt-1 text-sm text-gray-700 whitespace-pre-line">{{ $profile->bio }}</dd>
                    </div>
                @endif
            </div>
        @endif
    </main>
@endsection
