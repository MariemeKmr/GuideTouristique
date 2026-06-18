@extends('layouts.guest')

@section('title', 'Inscription')

@section('content')
    <h1 class="text-xl font-semibold text-gray-900 mb-1">Créer un compte</h1>
    <p class="text-sm text-gray-500 mb-6">Rejoignez le Guide Touristique.</p>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3">
            <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}"
                       required autofocus
                       class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}"
                       required
                       class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
            </div>
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                Téléphone <span class="text-gray-400 font-normal">(optionnel)</span>
            </label>
            <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   required autocomplete="email"
                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
        </div>

        <div>
            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Type de compte</label>
            <select id="role" name="role" required
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm bg-white focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
                <option value="visiteur" @selected(old('role') === 'visiteur')>Visiteur</option>
                <option value="taximan" @selected(old('role') === 'taximan')>Chauffeur (taximan)</option>
            </select>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
            <p class="mt-1 text-xs text-gray-400">8 caractères minimum.</p>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                Confirmer le mot de passe
            </label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
        </div>

        <button type="submit"
                class="w-full rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 transition">
            Créer mon compte
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Déjà inscrit ?
        <a href="{{ route('login') }}" class="font-medium text-gray-900 hover:underline">
            Se connecter
        </a>
    </p>
@endsection
