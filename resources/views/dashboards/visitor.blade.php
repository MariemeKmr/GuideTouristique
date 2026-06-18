@extends('layouts.app')

@section('title', 'Tableau de bord — Visiteur')

@section('content')
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-gray-900">Bonjour {{ auth()->user()->first_name }}</h1>
        <p class="mt-1 text-sm text-gray-500">
            Découvrez les destinations, planifiez vos visites et contactez un chauffeur.
        </p>

        @include('partials.flash')

        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <div class="text-3xl font-semibold text-gray-900">{{ $stats['destinations'] }}</div>
                <div class="mt-1 text-sm text-gray-500">Destinations disponibles</div>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <div class="text-3xl font-semibold text-gray-900">{{ $stats['mes_visites'] }}</div>
                <div class="mt-1 text-sm text-gray-500">Mes visites</div>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <div class="text-3xl font-semibold text-gray-900">{{ $stats['chauffeurs'] }}</div>
                <div class="mt-1 text-sm text-gray-500">Chauffeurs disponibles</div>
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('visitor.destinations.index') }}"
               class="block rounded-lg border border-gray-200 bg-white p-5 hover:border-gray-400 transition">
                <h2 class="text-sm font-semibold text-gray-900">Explorer les destinations</h2>
                <p class="mt-1 text-sm text-gray-500">Parcourez les lieux et marquez vos visites.</p>
            </a>
            <a href="{{ route('visitor.visits') }}"
               class="block rounded-lg border border-gray-200 bg-white p-5 hover:border-gray-400 transition">
                <h2 class="text-sm font-semibold text-gray-900">Mes visites</h2>
                <p class="mt-1 text-sm text-gray-500">Retrouvez l'historique de vos visites.</p>
            </a>
            <a href="{{ route('visitor.drivers.index') }}"
               class="block rounded-lg border border-gray-200 bg-white p-5 hover:border-gray-400 transition">
                <h2 class="text-sm font-semibold text-gray-900">Trouver un chauffeur</h2>
                <p class="mt-1 text-sm text-gray-500">Consultez et contactez les chauffeurs.</p>
            </a>
        </div>
    </main>
@endsection
