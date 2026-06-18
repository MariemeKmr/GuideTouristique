@extends('layouts.app')

@section('title', 'Tableau de bord — Admin')

@section('content')
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-gray-900">Espace administrateur</h1>
        <p class="mt-1 text-sm text-gray-500">
            Bienvenue {{ auth()->user()->first_name }}. Gérez les destinations, les transports et les utilisateurs.
        </p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-gray-900">Destinations</h2>
                <p class="mt-1 text-sm text-gray-500">Gestion à venir (CRUD).</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-gray-900">Transports</h2>
                <p class="mt-1 text-sm text-gray-500">Gestion à venir (CRUD).</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-gray-900">Utilisateurs</h2>
                <p class="mt-1 text-sm text-gray-500">Gestion à venir.</p>
            </div>
        </div>
    </main>
@endsection
