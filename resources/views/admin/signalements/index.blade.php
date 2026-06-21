@extends('layouts.app')

@section('title', 'Signalements')

@section('content')
    @include('partials.navbar')

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-nuit">Signalements</h1>
        <p class="mt-1 text-sm text-nuit/60">Problemes signales par les visiteurs et les chauffeurs.</p>

        @include('partials.flash')

        <div class="mt-8 space-y-4">
            @forelse ($signalements as $signalement)
                @php($c = $signalement->course)
                <div class="rounded-2xl border bg-white shadow-soft p-5 {{ $signalement->lu ? 'border-sable-200 opacity-75' : 'border-terracotta/30' }}">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-nuit">{{ $signalement->motifLabel() }}</span>
                                @if ($signalement->lu)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span> Traite
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-terracotta/10 px-2 py-0.5 text-xs font-semibold text-terracotta-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-terracotta"></span> Nouveau
                                    </span>
                                @endif
                            </div>
                            <div class="mt-1 text-xs uppercase tracking-wider text-nuit/40">
                                {{ $signalement->cible === 'chauffeur' ? 'Le visiteur signale le chauffeur' : 'Le chauffeur signale le passager' }}
                            </div>
                        </div>
                        <span class="text-xs text-nuit/40">{{ $signalement->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="mt-3 grid gap-1 text-sm text-nuit/70 sm:grid-cols-2">
                        <div>Trajet : {{ $c?->depart ?: 'depart' }} &rarr; {{ $c?->destination ?: 'destination' }}</div>
                        <div>Date de la course : {{ $c?->created_at?->format('d/m/Y') ?? '-' }}</div>
                        <div>Client : {{ $c?->visiteur?->full_name ?? '-' }}</div>
                        <div>Chauffeur : {{ $c?->chauffeur?->full_name ?? '-' }}</div>
                    </div>

                    @if ($signalement->description)
                        <div class="mt-3 rounded-xl bg-sable-50 border border-sable-200 px-3 py-2 text-sm text-nuit/80">
                            {{ $signalement->description }}
                        </div>
                    @endif

                    @if ($signalement->preuve)
                        @php($ext = strtolower(pathinfo($signalement->preuve, PATHINFO_EXTENSION)))
                        <div class="mt-3">
                            <div class="mb-1 text-xs font-medium uppercase tracking-wider text-nuit/40">Preuve jointe</div>
                            @if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                <a href="{{ asset('storage/' . $signalement->preuve) }}" target="_blank" rel="noopener">
                                    <img src="{{ asset('storage/' . $signalement->preuve) }}" alt="Preuve" class="h-32 rounded-xl border border-sable-200 object-cover">
                                </a>
                            @else
                                <a href="{{ asset('storage/' . $signalement->preuve) }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1 rounded-lg border border-sable-300 px-3 py-1.5 text-xs font-medium text-nuit/70 hover:bg-sable-50">
                                    Telecharger la preuve
                                </a>
                            @endif
                        </div>
                    @endif


                    <div class="mt-3 text-xs text-nuit/40">Signale par : {{ $signalement->auteur?->full_name ?? '-' }}</div>

                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <a href="{{ route('signalements.show', $signalement) }}"
                           class="rounded-xl border border-lagon px-3 py-1.5 text-xs font-semibold text-lagon-700 hover:bg-lagon-50 transition">
                            Voir et repondre
                        </a>
                        @unless ($signalement->lu)
                            <form method="POST" action="{{ route('admin.signalements.read', $signalement) }}">
                                @csrf @method('PATCH')
                                <button class="rounded-xl border border-sable-300 px-3 py-1.5 text-xs font-medium text-nuit/70 hover:bg-sable-50">Marquer comme traite</button>
                            </form>
                        @endunless
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-sable-200 bg-white shadow-soft p-10 text-center text-sm text-nuit/50">
                    Aucun signalement pour le moment.
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $signalements->links() }}</div>
    </main>
@endsection
