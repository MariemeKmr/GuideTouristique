@extends('layouts.app')

@section('title', 'Tableau de bord — Chauffeur')

@section('content')
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-gray-900">Espace chauffeur</h1>
        <p class="mt-1 text-sm text-gray-500">
            Bonjour {{ auth()->user()->first_name }}. Gérez votre profil et vos demandes de course.
        </p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2">
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-gray-900">Mon profil</h2>
                <p class="mt-1 text-sm text-gray-500">Visible par les visiteurs. Édition à venir.</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-gray-900">Demandes de course</h2>
                <p class="mt-1 text-sm text-gray-500">À venir.</p>
            </div>
        </div>
    </main>
@endsection
