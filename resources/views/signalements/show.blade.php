@extends('layouts.app')

@section('title', 'Signalement')

@section('content')
    @include('partials.navbar')

    @php($retour = $estAdmin ? route('admin.signalements.index') : route('signalements.mes'))
    @php($c = $signalement->course)

    <main class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
        <a href="{{ $retour }}" class="text-sm text-lagon-700 hover:underline">&larr; Retour</a>

        <div class="mt-4 rounded-2xl border border-sable-200 bg-white shadow-soft p-6">
            <h1 class="text-xl font-semibold text-nuit">{{ $signalement->motifLabel() }}</h1>
            <div class="mt-1 text-xs uppercase tracking-wider text-nuit/40">
                {{ $signalement->cible === 'chauffeur' ? 'Le visiteur signale le chauffeur' : 'Le chauffeur signale le passager' }}
            </div>

            <div class="mt-3 grid gap-1 text-sm text-nuit/70 sm:grid-cols-2">
                <div>Trajet : {{ $c?->depart ?: 'depart' }} &rarr; {{ $c?->destination ?: 'destination' }}</div>
                <div>Date : {{ $c?->created_at?->format('d/m/Y') ?? '-' }}</div>
                @if ($estAdmin)
                    <div>Client : {{ $c?->visiteur?->full_name ?? '-' }}</div>
                    <div>Chauffeur : {{ $c?->chauffeur?->full_name ?? '-' }}</div>
                @endif
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

            @include('partials.flash')

            {{-- Conversation --}}
            <div class="mt-5 space-y-3 border-t border-sable-200 pt-5">
                <p class="text-sm font-medium text-nuit">Echange avec {{ $estAdmin ? 'le plaignant' : "l'administration" }}</p>
                @forelse ($signalement->messages as $message)
                    @php($estMoi = $message->expediteur_id === auth()->id())
                    <div class="flex {{ $estMoi ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] rounded-2xl px-4 py-2.5 text-sm {{ $estMoi ? 'bg-lagon text-white' : 'bg-sable-50 text-nuit border border-sable-200' }}">
                            <div class="text-[11px] {{ $estMoi ? 'text-white/70' : 'text-nuit/50' }} mb-0.5">
                                {{ $estMoi ? 'Vous' : ($estAdmin ? 'Le plaignant' : "L'administration") }}
                                &middot; {{ $message->created_at->format('d/m H:i') }}
                            </div>
                            <div class="whitespace-pre-line">{{ $message->contenu }}</div>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-nuit/50">
                        @if ($estAdmin) Envoyez un message au plaignant pour en savoir plus. @else Aucun message pour le moment. @endif
                    </p>
                @endforelse
            </div>

            {{-- Nouveau message (impossible si le signalement est traite) --}}
            @if ($signalement->lu)
                <div class="mt-5 border-t border-sable-200 pt-4">
                    <div class="rounded-xl border border-sable-200 bg-sable-50 px-4 py-3 text-sm text-nuit/60">
                        Ce signalement a ete traite. La conversation est close, vous pouvez la consulter mais plus y repondre.
                    </div>
                </div>
            @else
                <form method="POST" action="{{ route('signalements.message', $signalement) }}" class="mt-5 border-t border-sable-200 pt-4">
                    @csrf
                    <label for="contenu" class="block text-sm font-medium text-nuit mb-1">Votre message</label>
                    <textarea id="contenu" name="contenu" rows="3" required
                              class="w-full rounded-xl border border-sable-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">{{ old('contenu') }}</textarea>
                    @error('contenu') <p class="mt-1 text-xs text-terracotta-700">{{ $message }}</p> @enderror
                    <div class="mt-3 flex justify-end">
                        <button type="submit"
                                class="rounded-xl bg-terracotta px-5 py-2.5 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 transition">
                            Envoyer
                        </button>
                    </div>
                </form>

                @if ($estAdmin)
                    <form method="POST" action="{{ route('admin.signalements.read', $signalement) }}" class="mt-3 flex justify-end">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="rounded-xl border border-sable-300 px-4 py-2 text-sm font-medium text-nuit/70 hover:bg-sable-50 transition">
                            Marquer comme traite et clore la conversation
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </main>
@endsection
