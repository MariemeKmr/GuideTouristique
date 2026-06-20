@extends('layouts.app')

@section('title', 'Activites')

@section('content')
    @include('partials.navbar')

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-10">
        @include('partials.tabs-chauffeur-activite')
        <h1 class="text-2xl font-semibold text-nuit">Activites</h1>
        <p class="mt-1 text-sm text-nuit/60">Les activites pour lesquelles un client vous a contacte.</p>

        @include('partials.flash')

        <div class="mt-8 space-y-3">
            @forelse ($reservations as $resa)
                <div class="rounded-2xl border border-sable-200 bg-white shadow-soft p-5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <div class="text-sm font-semibold text-nuit">{{ $resa->activite->nom }}</div>
                            <div class="text-sm text-nuit/60">
                                {{ $resa->activite->lieu ?: 'Lieu non precise' }}
                                @if ($resa->activite->tarif) - {{ $resa->activite->tarif }} @endif
                            </div>
                            <div class="mt-1 text-xs text-nuit/40">
                                Client : {{ $resa->visiteur->full_name }} &middot; le {{ $resa->date_activite->format('d/m/Y') }}
                            </div>
                        </div>

                        @if ($resa->visiteur->phone)
                            <a href="tel:{{ preg_replace('/\s+/', '', $resa->visiteur->phone) }}"
                               class="rounded-xl border border-lagon px-4 py-2 text-sm font-semibold text-lagon-700 hover:bg-lagon-50 transition">
                                Appeler le client
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-sable-200 bg-white shadow-soft p-10 text-center text-sm text-nuit/50">
                    Aucune activite pour le moment.
                </div>
            @endforelse
        </div>
    </main>
@endsection
