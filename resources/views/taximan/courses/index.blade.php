@extends('layouts.app')

@section('title', 'Mes courses')

@section('content')
    @include('partials.navbar')

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-nuit">Mes courses</h1>
        <p class="mt-1 text-sm text-nuit/60">Proposez un prix, gerez l'avancement de vos courses.</p>

        @include('partials.flash')

        <div class="mt-8 space-y-4">
            @forelse ($courses as $course)
                <div class="rounded-2xl border border-sable-200 bg-white shadow-soft p-5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <div class="text-sm font-semibold text-nuit">{{ $course->visiteur->full_name }}</div>
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

                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        @if ($course->statut === 'demandee')
                            <form method="POST" action="{{ route('taximan.courses.price.propose', $course) }}" class="flex items-end gap-2">
                                @csrf @method('PATCH')
                                <div>
                                    <label class="block text-xs text-nuit/60 mb-1">Prix (FCFA)</label>
                                    <input type="number" name="prix" min="0" required
                                           class="w-40 rounded-xl border border-sable-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
                                </div>
                                <button class="rounded-xl bg-terracotta px-4 py-2 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 transition">Proposer ce prix</button>
                            </form>
                            <form method="POST" action="{{ route('taximan.courses.refuse', $course) }}" onsubmit="return confirm('Refuser cette demande ?');">
                                @csrf @method('PATCH')
                                <button class="rounded-xl border border-sable-300 px-4 py-2 text-sm font-medium text-nuit/70 hover:bg-sable-50 transition">Refuser</button>
                            </form>
                        @elseif ($course->statut === 'prix_propose')
                            <span class="text-sm text-nuit/50">Prix propose. En attente de la reponse du client.</span>
                        @elseif ($course->statut === 'contre_propose')
                            <span class="text-sm text-nuit">Le client propose <span class="font-semibold">{{ number_format($course->prix, 0, ',', ' ') }} FCFA</span>.</span>
                            <form method="POST" action="{{ route('taximan.courses.counter.accept', $course) }}">
                                @csrf @method('PATCH')
                                <button class="rounded-xl bg-terracotta px-4 py-2 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 transition">Accepter</button>
                            </form>
                            <form method="POST" action="{{ route('taximan.courses.counter.refuse', $course) }}" onsubmit="return confirm('Refuser et annuler la course ?');">
                                @csrf @method('PATCH')
                                <button class="rounded-xl border border-sable-300 px-4 py-2 text-sm font-medium text-nuit/70 hover:bg-sable-50 transition">Refuser</button>
                            </form>
                        @elseif ($course->statut === 'terminee')
                            <span class="text-sm text-nuit/50">Course terminee.</span>
                        @elseif ($course->statut === 'annulee')
                            @if ($course->annulee_par === 'client')
                                <span class="inline-flex items-center gap-2 rounded-xl border border-terracotta/30 bg-terracotta/10 px-3 py-1.5 text-sm font-medium text-terracotta-700">
                                    <span class="h-2 w-2 rounded-full bg-terracotta"></span>
                                    Le client a refuse le demarrage : course annulee.
                                </span>
                            @elseif ($course->annulee_par === 'prix')
                                <span class="text-sm text-nuit/50">Pas d'accord sur le prix.</span>
                            @else
                                <span class="text-sm text-nuit/40">Course annulee.</span>
                            @endif
                        @else
                            {{-- Course active : coordonner par telephone --}}
                            @if ($course->visiteur->phone)
                                <a href="tel:{{ preg_replace('/\s+/', '', $course->visiteur->phone) }}"
                                   class="rounded-xl border border-lagon px-4 py-2 text-sm font-semibold text-lagon-700 hover:bg-lagon-50 transition">
                                    Appeler le client
                                </a>
                            @endif

                            @if ($course->statut === 'attente_client')
                                <span class="text-sm text-nuit/50">En attente de la confirmation du client.</span>
                            @else
                                <form method="POST" action="{{ route('taximan.courses.advance', $course) }}">
                                    @csrf @method('PATCH')
                                    <button class="rounded-xl bg-terracotta px-4 py-2 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 transition">
                                        @switch($course->statut)
                                            @case('acceptee') Je pars vers le client (en route) @break
                                            @case('en_route') Je suis arrive @break
                                            @case('arrive') Demarrer la course @break
                                            @case('en_course') Terminer la course @break
                                        @endswitch
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>

                    @if (in_array($course->statut, ['en_course', 'terminee', 'annulee']))
                        <div class="mt-3 border-t border-sable-200 pt-3">
                            <a href="{{ route('signalements.create', $course) }}" class="text-xs font-medium text-nuit/50 hover:text-terracotta-700 hover:underline">Signaler le passager</a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="rounded-2xl border border-sable-200 bg-white shadow-soft p-10 text-center text-sm text-nuit/50">
                    Aucune course pour le moment.
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $courses->links() }}</div>
    </main>
@endsection
