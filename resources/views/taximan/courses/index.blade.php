@extends('layouts.app')

@section('title', 'Mes courses')

@section('content')
    @include('partials.navbar')

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-nuit">Mes courses</h1>
        <p class="mt-1 text-sm text-nuit/60">Gerez les demandes et l'avancement de vos courses.</p>

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
                            <div class="mt-1 text-xs text-nuit/40">{{ $course->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        @include('partials.course-statut')
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        @if ($course->statut === 'demandee')
                            <form method="POST" action="{{ route('taximan.courses.accept', $course) }}">
                                @csrf @method('PATCH')
                                <button class="rounded-xl bg-terracotta px-4 py-2 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 transition">Accepter</button>
                            </form>
                            <form method="POST" action="{{ route('taximan.courses.refuse', $course) }}" onsubmit="return confirm('Refuser cette course ?');">
                                @csrf @method('PATCH')
                                <button class="rounded-xl border border-sable-300 px-4 py-2 text-sm font-medium text-nuit/70 hover:bg-sable-50 transition">Refuser</button>
                            </form>
                        @elseif ($course->statut === 'terminee')
                            <span class="text-sm text-nuit/60">
                                @if ($course->note) Note recue : <span class="font-semibold text-nuit">{{ $course->note }} / 5</span> @else En attente de la note du client. @endif
                            </span>
                        @elseif ($course->statut === 'annulee')
                            <span class="text-sm text-nuit/40">Course annulee.</span>
                        @else
                            {{-- Course active : coordonner par telephone + faire avancer le statut --}}
                            @if ($course->visiteur->phone)
                                <a href="tel:{{ preg_replace('/\s+/', '', $course->visiteur->phone) }}"
                                   class="rounded-xl border border-lagon px-4 py-2 text-sm font-semibold text-lagon-700 hover:bg-lagon-50 transition">
                                    Appeler le client
                                </a>
                            @endif

                            <form method="POST" action="{{ route('taximan.courses.advance', $course) }}">
                                @csrf @method('PATCH')
                                <button class="rounded-xl bg-terracotta px-4 py-2 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 transition">
                                    @switch($course->statut)
                                        @case('acceptee') Demarrer le trajet (en route) @break
                                        @case('en_route') Je suis arrive @break
                                        @case('arrive') Demarrer la course @break
                                        @case('en_course') Terminer la course @break
                                    @endswitch
                                </button>
                            </form>

                            @if ($course->statut === 'arrive')
                                <span class="text-xs text-nuit/40">Le client peut aussi demarrer de son cote.</span>
                            @endif
                        @endif
                    </div>
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
