@extends('layouts.app')

@section('title', $destination->name)

@section('content')
    @include('partials.navbar')

    <main class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
        <a href="{{ route('visitor.destinations.index') }}" class="text-sm text-gray-500 hover:text-nuit">&larr; Toutes les destinations</a>

        <div class="mt-4 rounded-lg border border-gray-200 bg-white p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-semibold text-nuit">{{ $destination->name }}</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $destination->localite }}@if ($destination->rue) — {{ $destination->rue }}@endif
                    </p>
                </div>
                @if ($visited)
                    <span class="shrink-0 rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700 border border-green-200">
                        Visité le {{ \Illuminate\Support\Carbon::parse($visited->pivot->date_visite)->format('d/m/Y') }}
                    </span>
                @endif
            </div>

            @if ($destination->description)
                <p class="mt-4 text-sm text-gray-700 whitespace-pre-line">{{ $destination->description }}</p>
            @endif

            @include('partials.flash')

            <div class="mt-6 border-t border-gray-200 pt-6">
                @if ($visited)
                    <form method="POST" action="{{ route('visitor.destinations.visit', $destination) }}" class="flex flex-wrap items-end gap-3">
                        @csrf
                        <div>
                            <label for="date_visite" class="block text-sm font-medium text-gray-700 mb-1">Date de visite</label>
                            <input id="date_visite" name="date_visite" type="date"
                                   value="{{ \Illuminate\Support\Carbon::parse($visited->pivot->date_visite)->format('Y-m-d') }}"
                                   class="rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
                        </div>
                        <button type="submit"
                                class="rounded-md bg-terracotta px-4 py-2 text-sm font-medium text-white hover:bg-terracotta-600 transition">
                            Mettre à jour la date
                        </button>
                    </form>

                    <form method="POST" action="{{ route('visitor.destinations.unvisit', $destination) }}" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="rounded-md border border-red-200 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50 transition">
                            Retirer de mes visites
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('visitor.destinations.visit', $destination) }}" class="flex flex-wrap items-end gap-3">
                        @csrf
                        <div>
                            <label for="date_visite" class="block text-sm font-medium text-gray-700 mb-1">Date de visite</label>
                            <input id="date_visite" name="date_visite" type="date"
                                   value="{{ now()->format('Y-m-d') }}"
                                   class="rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
                        </div>
                        <button type="submit"
                                class="rounded-md bg-terracotta px-4 py-2 text-sm font-medium text-white hover:bg-terracotta-600 transition">
                            Marquer comme visité
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </main>
@endsection
