@extends('layouts.app')

@section('title', 'Tableau de bord — Admin')

@section('content')
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-nuit">Espace administrateur</h1>
        <p class="mt-1 text-sm text-gray-500">
            Bienvenue {{ auth()->user()->first_name }}. Vue d'ensemble de la plateforme.
        </p>

        @include('partials.flash')

        {{-- Compteurs --}}
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <div class="text-3xl font-semibold text-nuit">{{ $stats['destinations'] }}</div>
                <div class="mt-1 text-sm text-gray-500">Destinations</div>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <div class="text-3xl font-semibold text-nuit">{{ $stats['transports'] }}</div>
                <div class="mt-1 text-sm text-gray-500">Transports</div>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <div class="text-3xl font-semibold text-nuit">{{ $stats['taximen'] }}</div>
                <div class="mt-1 text-sm text-gray-500">Chauffeurs</div>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <div class="text-3xl font-semibold text-nuit">{{ $stats['visiteurs'] }}</div>
                <div class="mt-1 text-sm text-gray-500">Visiteurs</div>
            </div>
        </div>

        {{-- Accès rapides --}}
        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <a href="{{ route('admin.destinations.index') }}"
               class="block rounded-lg border border-gray-200 bg-white p-5 hover:border-gray-400 transition">
                <h2 class="text-sm font-semibold text-nuit">Gérer les destinations</h2>
                <p class="mt-1 text-sm text-gray-500">Ajouter, modifier et supprimer des lieux à visiter.</p>
            </a>
            <a href="{{ route('admin.transports.index') }}"
               class="block rounded-lg border border-gray-200 bg-white p-5 hover:border-gray-400 transition">
                <h2 class="text-sm font-semibold text-nuit">Gérer les transports</h2>
                <p class="mt-1 text-sm text-gray-500">Définir les moyens de déplacement et leurs coûts.</p>
            </a>
        </div>
    </main>
@endsection
