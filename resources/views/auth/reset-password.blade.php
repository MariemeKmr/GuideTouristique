@extends('layouts.guest')

@section('title', 'Nouveau mot de passe')

@section('content')
    <h1 class="text-xl font-semibold text-nuit mb-1">Nouveau mot de passe</h1>
    <p class="text-sm text-gray-500 mb-6">Choisissez un nouveau mot de passe pour votre compte.</p>

    @if ($errors->any())
        <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3">
            <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="email"
                   class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                   class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                   class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
        </div>

        <button type="submit"
                class="w-full rounded-xl bg-terracotta px-4 py-2 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 hover:shadow-lift transition">
            Reinitialiser le mot de passe
        </button>
    </form>
@endsection
