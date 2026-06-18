@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
    <h1 class="text-xl font-semibold text-nuit mb-1">Connexion</h1>
    <p class="text-sm text-gray-500 mb-6">Accédez à votre espace.</p>

    {{-- Erreurs de validation globales --}}
    @if ($errors->any())
        <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3">
            <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Adresse email
            </label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   required autofocus autocomplete="email"
                   class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Mot de passe
            </label>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                   class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
        </div>

        <div class="flex items-center">
            <input id="remember" name="remember" type="checkbox"
                   class="h-4 w-4 rounded border-gray-300 text-nuit focus:ring-lagon">
            <label for="remember" class="ml-2 text-sm text-gray-600">Se souvenir de moi</label>
        </div>

        <button type="submit"
                class="w-full rounded-xl bg-terracotta px-4 py-2 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 hover:shadow-lift transition">
            Se connecter
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Pas encore de compte ?
        <a href="{{ route('register') }}" class="font-medium text-nuit hover:underline">
            Créer un compte
        </a>
    </p>
@endsection
