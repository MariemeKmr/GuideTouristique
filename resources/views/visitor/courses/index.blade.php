@extends('layouts.app')

@section('title', 'Mes courses')

@section('content')
    @include('partials.navbar')

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-nuit">Mes courses</h1>
        <p class="mt-1 text-sm text-nuit/60">Suivez vos trajets, negociez le prix et notez vos chauffeurs.</p>

        @include('partials.flash')

        <div class="mt-8 space-y-4">
            @forelse ($courses as $course)
                <div class="rounded-2xl border border-sable-200 bg-white shadow-soft p-5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <div class="text-sm font-semibold text-nuit">{{ $course->chauffeur->full_name }}</div>
                            <div class="mt-0.5 text-sm text-nuit/60">
                                {{ $course->depart ?: 'Depart non precise' }} &rarr; {{ $course->destination ?: 'Destination non precisee' }}
                            </div>
                            @if ($course->activite)
                                <div class="mt-0.5 text-xs text-nuit/50">Activite : {{ $course->activite->nom }}@if ($course->date_prevue) le {{ $course->date_prevue->format('d/m/Y') }}@endif</div>
                            @endif
                            <div class="mt-1 text-xs text-nuit/40">{{ $course->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="text-right">
                            @include('partials.course-statut')
                            @if ($course->prix)
                                <div class="mt-1 text-sm font-semibold text-nuit">{{ number_format($course->prix, 0, ',', ' ') }} FCFA</div>
                            @endif
                        </div>
                    </div>

                    {{-- Negociation du prix --}}
                    @if ($course->statut === 'prix_propose')
                        <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50/60 p-4">
                            <p class="text-sm text-nuit">Le chauffeur propose <span class="font-semibold">{{ number_format($course->prix, 0, ',', ' ') }} FCFA</span>.</p>
                            <div class="mt-3 flex flex-wrap items-end gap-2">
                                <form method="POST" action="{{ route('visitor.courses.price.accept', $course) }}">
                                    @csrf @method('PATCH')
                                    <button class="rounded-xl bg-terracotta px-4 py-2 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 transition">Accepter</button>
                                </form>
                                <form method="POST" action="{{ route('visitor.courses.price.counter', $course) }}" class="flex items-end gap-2">
                                    @csrf @method('PATCH')
                                    <div>
                                        <label class="block text-xs text-nuit/60 mb-1">Proposer un autre prix (FCFA)</label>
                                        <input type="number" name="prix" min="0" required
                                               class="w-40 rounded-xl border border-sable-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
                                    </div>
                                    <button class="rounded-xl border border-lagon px-4 py-2 text-sm font-semibold text-lagon-700 hover:bg-lagon-50 transition">Proposer</button>
                                </form>
                                <form method="POST" action="{{ route('visitor.courses.price.refuse', $course) }}" onsubmit="return confirm('Refuser le prix et annuler la course ?');">
                                    @csrf @method('PATCH')
                                    <button class="rounded-xl border border-sable-300 px-4 py-2 text-sm font-medium text-nuit/70 hover:bg-sable-50 transition">Refuser</button>
                                </form>
                            </div>
                        </div>
                    @elseif ($course->statut === 'contre_propose')
                        <div class="mt-4 text-sm text-nuit/60">Vous avez propose {{ number_format($course->prix, 0, ',', ' ') }} FCFA. En attente de la reponse du chauffeur.</div>
                    @elseif ($course->statut === 'acceptee')
                        <div class="mt-4 text-sm text-nuit/60">Prix accepte. Votre chauffeur se met en route.</div>
                    @elseif ($course->statut === 'en_route')
                        <div class="mt-4 text-sm text-nuit/60">Votre chauffeur est en route.</div>
                    @elseif ($course->statut === 'arrive')
                        <div class="mt-4 rounded-xl border border-lagon-100 bg-lagon-50 p-3 text-sm font-medium text-lagon-700">Votre chauffeur est arrive. La course va demarrer.</div>
                    @elseif ($course->statut === 'en_course')
                        <div class="mt-4 text-sm text-nuit/50">Course en cours...</div>
                    @elseif ($course->statut === 'annulee')
                        @if ($course->annulee_par === 'prix')
                            <div class="mt-4 text-sm text-terracotta-700">Vous n'etes pas tombes d'accord sur le prix. Essayez un autre chauffeur.</div>
                        @else
                            <div class="mt-4 text-sm text-nuit/40">Course annulee.</div>
                        @endif
                    @endif

                    {{-- Confirmation de demarrage (oui / non) --}}
                    @if ($course->statut === 'attente_client')
                        <div class="mt-4 rounded-xl border border-terracotta/20 bg-terracotta/5 p-4">
                            <p class="text-sm font-medium text-nuit">Le chauffeur est pret a demarrer la course. Confirmez-vous ?</p>
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <form method="POST" action="{{ route('visitor.courses.confirm', $course) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="reponse" value="oui">
                                    <button class="rounded-xl bg-terracotta px-4 py-2 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 transition">Oui, demarrer</button>
                                </form>
                                <form method="POST" action="{{ route('visitor.courses.confirm', $course) }}" onsubmit="return confirm('Refuser et annuler la course ?');">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="reponse" value="non">
                                    <button class="rounded-xl border border-sable-300 px-4 py-2 text-sm font-medium text-nuit/70 hover:bg-sable-50 transition">Non, annuler</button>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- Annulation possible avant le demarrage --}}
                    @if (in_array($course->statut, ['demandee', 'prix_propose', 'contre_propose', 'acceptee', 'en_route', 'arrive']))
                        <div class="mt-3">
                            <form method="POST" action="{{ route('visitor.courses.cancel', $course) }}" onsubmit="return confirm('Annuler cette course ?');">
                                @csrf @method('PATCH')
                                <button class="text-xs font-medium text-nuit/50 hover:text-terracotta-700 hover:underline">Annuler la course</button>
                            </form>
                        </div>
                    @endif

                    {{-- Notation --}}
                    @if ($course->peutEtreNotee())
                        <form method="POST" action="{{ route('visitor.courses.rate', $course) }}" class="mt-4 border-t border-sable-200 pt-4">
                            @csrf @method('PATCH')
                            <p class="text-sm font-medium text-nuit">Comment s'est passee la course ?</p>
                            <div class="mt-2 flex flex-wrap items-end gap-3">
                                <div>
                                    <label for="note-{{ $course->id }}" class="block text-xs text-nuit/60 mb-1">Note</label>
                                    <select id="note-{{ $course->id }}" name="note" required
                                            class="rounded-xl border border-sable-300 px-3 py-2 text-sm bg-white focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}">{{ $i }} / 5</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="flex-1 min-w-[12rem]">
                                    <label for="com-{{ $course->id }}" class="block text-xs text-nuit/60 mb-1">Commentaire (optionnel)</label>
                                    <input id="com-{{ $course->id }}" name="commentaire" type="text"
                                           class="w-full rounded-xl border border-sable-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
                                </div>
                                <button class="rounded-xl bg-terracotta px-4 py-2 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 transition">Envoyer</button>
                            </div>
                        </form>
                    @elseif ($course->statut === 'terminee' && $course->note)
                        <div class="mt-4 border-t border-sable-200 pt-4 text-sm text-nuit/70">
                            Votre note : <span class="font-semibold text-nuit">{{ $course->note }} / 5</span>
                            @if ($course->commentaire) <span class="text-nuit/50">- {{ $course->commentaire }}</span> @endif
                        </div>
                    @endif

                    @if (in_array($course->statut, ['en_course', 'terminee', 'annulee']))
                        <div class="mt-3 border-t border-sable-200 pt-3">
                            <a href="{{ route('signalements.create', $course) }}" class="text-xs font-medium text-nuit/50 hover:text-terracotta-700 hover:underline">Signaler un probleme</a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="rounded-2xl border border-sable-200 bg-white shadow-soft p-10 text-center text-sm text-nuit/50">
                    Aucune course pour le moment. Demandez-en une depuis la fiche d'un chauffeur.
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $courses->links() }}</div>
    </main>
@endsection
