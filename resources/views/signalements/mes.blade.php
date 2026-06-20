@extends('layouts.app')

@section('title', 'Mes signalements')

@section('content')
    @include('partials.navbar')

    <main class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-nuit">Mes signalements</h1>
        <p class="mt-1 text-sm text-nuit/60">Suivi de vos signalements et echanges avec l'administration.</p>

        @include('partials.flash')

        <div class="mt-8 space-y-3">
            @forelse ($signalements as $signalement)
                @php($nonLus = $signalement->messages()->where('expediteur_id', '!=', auth()->id())->where('lu', false)->count())
                <a href="{{ route('signalements.show', $signalement) }}"
                   class="block rounded-2xl border border-sable-200 bg-white shadow-soft p-5 hover:shadow-lift transition">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-sm font-semibold text-nuit">{{ $signalement->motifLabel() }}</div>
                            <div class="mt-0.5 text-sm text-nuit/60">
                                {{ $signalement->course?->depart ?: 'depart' }} &rarr; {{ $signalement->course?->destination ?: 'destination' }}
                            </div>
                            <div class="mt-1 text-xs text-nuit/40">{{ $signalement->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        @if ($nonLus > 0)
                            <span class="inline-flex items-center justify-center rounded-full bg-terracotta px-2 py-0.5 text-xs font-semibold text-white">{{ $nonLus }} nouveau(x)</span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="rounded-2xl border border-sable-200 bg-white shadow-soft p-10 text-center text-sm text-nuit/50">
                    Vous n'avez fait aucun signalement.
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $signalements->links() }}</div>
    </main>
@endsection
