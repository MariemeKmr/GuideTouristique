@extends('layouts.app')

@section('title', 'Objet perdu')

@section('content')
    @include('partials.navbar')

    @php($estVisiteur = auth()->id() === $course->visiteur_id)
    @php($autre = $estVisiteur ? 'le chauffeur' : 'le client')
    @php($retour = $estVisiteur ? route('visitor.courses.index') : route('taximan.courses.index'))

    <main class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
        <a href="{{ $retour }}" class="text-sm text-lagon-700 hover:underline">&larr; Retour a mes courses</a>

        <div class="mt-4 rounded-2xl border border-sable-200 bg-white shadow-soft p-6">
            <h1 class="text-xl font-semibold text-nuit">Objet perdu</h1>
            <p class="mt-1 text-sm text-nuit/60">
                Trajet : {{ $course->depart ?: 'depart non precise' }} &rarr; {{ $course->destination ?: 'destination non precisee' }}
            </p>
            <p class="mt-2 text-xs text-nuit/50">
                Echange anonyme avec {{ $autre }}. Vos coordonnees (nom, telephone) ne sont jamais partagees.
            </p>

            @include('partials.flash')

            {{-- Fil de messages --}}
            <div class="mt-5 space-y-3">
                @forelse ($messages as $message)
                    @php($estMoi = $message->expediteur_id === auth()->id())
                    <div class="flex {{ $estMoi ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] rounded-2xl px-4 py-2.5 text-sm {{ $estMoi ? 'bg-lagon text-white' : 'bg-sable-50 text-nuit border border-sable-200' }}">
                            <div class="text-[11px] {{ $estMoi ? 'text-white/70' : 'text-nuit/50' }} mb-0.5">
                                {{ $estMoi ? 'Vous' : ($estVisiteur ? 'Le chauffeur' : 'Le client') }}
                                &middot; {{ $message->created_at->format('d/m H:i') }}
                            </div>
                            <div class="whitespace-pre-line">{{ $message->contenu }}</div>
                        </div>
                    </div>
                @empty
                    <p class="py-6 text-center text-sm text-nuit/50">
                        Aucun message. Signalez ici un objet oublie ou retrouve pendant ce trajet.
                    </p>
                @endforelse
            </div>

            {{-- Nouveau message --}}
            <form method="POST" action="{{ route('objets.store', $course) }}" class="mt-5 border-t border-sable-200 pt-4">
                @csrf
                <label for="contenu" class="block text-sm font-medium text-nuit mb-1">Votre message</label>
                <textarea id="contenu" name="contenu" rows="3" required
                          class="w-full rounded-xl border border-sable-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1"
                          placeholder="Ex : J'ai oublie un sac noir sur la banquette arriere.">{{ old('contenu') }}</textarea>
                @error('contenu')
                    <p class="mt-1 text-xs text-terracotta-700">{{ $message }}</p>
                @enderror
                <div class="mt-3 flex justify-end">
                    <button type="submit"
                            class="rounded-xl bg-terracotta px-5 py-2.5 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 transition">
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
