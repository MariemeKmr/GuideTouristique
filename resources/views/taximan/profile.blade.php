@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
    @include('partials.navbar')

    <main class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
        <a href="{{ route('taximan.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Tableau de bord</a>
        <h1 class="mt-2 text-2xl font-semibold text-gray-900 mb-6">Mon profil</h1>

        @include('partials.flash')

        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <form method="POST" action="{{ route('taximan.profile.update') }}">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3">
                        <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h2 class="text-sm font-semibold text-gray-900 mb-3">Coordonnées</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                            <input id="first_name" name="first_name" type="text" required
                                   value="{{ old('first_name', $user->first_name) }}"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                            <input id="last_name" name="last_name" type="text" required
                                   value="{{ old('last_name', $user->last_name) }}"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
                        </div>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <input id="phone" name="phone" type="text"
                               value="{{ old('phone', $user->phone) }}"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
                    </div>
                </div>

                <h2 class="text-sm font-semibold text-gray-900 mt-6 mb-3">Informations chauffeur</h2>
                <div class="space-y-4">
                    <div>
                        <label for="zone" class="block text-sm font-medium text-gray-700 mb-1">Zone desservie</label>
                        <input id="zone" name="zone" type="text" placeholder="Ex : Dakar — Plateau, Almadies"
                               value="{{ old('zone', $profile->zone ?? '') }}"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
                    </div>
                    <div>
                        <label for="vehicule" class="block text-sm font-medium text-gray-700 mb-1">Véhicule</label>
                        <input id="vehicule" name="vehicule" type="text" placeholder="Ex : Toyota Corolla blanche"
                               value="{{ old('vehicule', $profile->vehicule ?? '') }}"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
                    </div>
                    <div>
                        <label for="tarif_indicatif" class="block text-sm font-medium text-gray-700 mb-1">Tarif indicatif</label>
                        <input id="tarif_indicatif" name="tarif_indicatif" type="text" placeholder="Ex : 2 000 FCFA / course"
                               value="{{ old('tarif_indicatif', $profile->tarif_indicatif ?? '') }}"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
                    </div>
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">À propos (optionnel)</label>
                        <textarea id="bio" name="bio" rows="4"
                                  class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">{{ old('bio', $profile->bio ?? '') }}</textarea>
                    </div>
                    <div class="flex items-center">
                        <input id="disponible" name="disponible" type="checkbox" value="1"
                               @checked(old('disponible', $profile->disponible ?? true))
                               class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                        <label for="disponible" class="ml-2 text-sm text-gray-700">Je suis disponible (visible par les visiteurs)</label>
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button type="submit"
                            class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 transition">
                        Enregistrer
                    </button>
                    <a href="{{ route('taximan.dashboard') }}"
                       class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </main>
@endsection
