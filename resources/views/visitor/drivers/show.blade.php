@extends('layouts.app')

@section('title', $driver->full_name)

@section('content')
    @include('partials.navbar')

    <main class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
        <a href="{{ route('visitor.drivers.index') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Tous les chauffeurs</a>

        <div class="mt-4 rounded-lg border border-gray-200 bg-white p-6">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-lg font-semibold text-gray-700">
                    {{ strtoupper(substr($driver->first_name, 0, 1) . substr($driver->last_name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">{{ $driver->full_name }}</h1>
                    <p class="text-sm text-gray-500">Chauffeur</p>
                </div>
            </div>

            <div class="mt-6 border-t border-gray-200 pt-6 space-y-3">
                <div>
                    <div class="text-xs font-medium uppercase tracking-wider text-gray-400">Téléphone</div>
                    <div class="mt-1 text-sm text-gray-900">{{ $driver->phone ?: 'Non renseigné' }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium uppercase tracking-wider text-gray-400">Email</div>
                    <div class="mt-1 text-sm text-gray-900">{{ $driver->email }}</div>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-2">
                @if ($driver->phone)
                    <a href="tel:{{ preg_replace('/\s+/', '', $driver->phone) }}"
                       class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                        Appeler
                    </a>
                @endif
                <a href="mailto:{{ $driver->email }}"
                   class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Envoyer un email
                </a>
            </div>
        </div>
    </main>
@endsection
