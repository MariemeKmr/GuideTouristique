@extends('layouts.app')

@section('title', 'Tableau de bord — Visiteur')

@section('content')
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-gray-900">Bonjour {{ auth()->user()->first_name }}</h1>
        <p class="mt-1 text-sm text-gray-500">
            Découvrez les destinations, planifiez vos visites et contactez un chauffeur.
        </p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-gray-900">Explorer les destinations</h2>
                <p class="mt-1 text-sm text-gray-500">À venir.</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-gray-900">Mes visites</h2>
                <p class="mt-1 text-sm text-gray-500">À venir.</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-gray-900">Trouver un chauffeur</h2>
                <p class="mt-1 text-sm text-gray-500">À venir.</p>
            </div>
        </div>
    </main>
@endsection
