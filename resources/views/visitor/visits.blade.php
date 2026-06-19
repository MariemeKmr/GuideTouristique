@extends('layouts.app')

@section('title', 'Mes visites')

@section('content')
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-nuit">Mes visites</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $visits->total() }} destination(s) visitée(s).</p>

        @include('partials.flash')

        <div class="mt-8 overflow-hidden rounded-2xl border border-sable-200 bg-white shadow-soft">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-sable-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Destination</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Localité</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date de visite</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($visits as $visit)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-nuit">{{ $visit->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $visit->localite }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $visit->pivot->date_visite ? \Illuminate\Support\Carbon::parse($visit->pivot->date_visite)->format('d/m/Y') : '—' }}
                            </td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a href="{{ route('visitor.destinations.show', $visit) }}"
                                   class="font-medium text-gray-700 hover:text-nuit">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-500">
                                Vous n'avez pas encore marqué de visite.
                                <a href="{{ route('visitor.destinations.index') }}" class="font-medium text-nuit hover:underline">Explorer les destinations</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $visits->links() }}
        </div>

        {{-- Mes activites --}}
        <section class="mt-12">
            <h2 class="text-xl font-semibold text-nuit">Mes activites</h2>
            <p class="mt-1 text-sm text-gray-500">Reservez une activite, choisissez une date, puis contactez un chauffeur pour vous y rendre.</p>

            {{-- Reserver --}}
            <div class="mt-4 rounded-2xl border border-sable-200 bg-white shadow-soft p-5">
                <form method="POST" action="{{ route('visitor.activites.reserve') }}" class="flex flex-wrap items-end gap-3">
                    @csrf
                    <div class="flex-1 min-w-[14rem]">
                        <label for="activite_id" class="block text-xs text-nuit/60 mb-1">Activite</label>
                        <select id="activite_id" name="activite_id" required
                                class="w-full rounded-xl border border-sable-300 bg-white px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
                            <option value="">Choisir une activite</option>
                            @foreach ($activitesDispo as $a)
                                <option value="{{ $a->id }}" @selected(old('activite_id') === $a->id)>{{ $a->nom }}{{ $a->lieu ? ' - '.$a->lieu : '' }}</option>
                            @endforeach
                        </select>
                        @error('activite_id') <p class="mt-1 text-xs text-terracotta-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="date_activite" class="block text-xs text-nuit/60 mb-1">Date</label>
                        <input id="date_activite" name="date_activite" type="date" required min="{{ now()->format('Y-m-d') }}" value="{{ old('date_activite') }}"
                               class="rounded-xl border border-sable-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
                        @error('date_activite') <p class="mt-1 text-xs text-terracotta-700">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit"
                            class="rounded-xl bg-terracotta px-5 py-2.5 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 transition">
                        Reserver
                    </button>
                </form>
                @if ($activitesDispo->isEmpty())
                    <p class="mt-3 text-xs text-nuit/50">Aucune activite disponible pour le moment.</p>
                @endif
            </div>

            {{-- Liste des reservations --}}
            <div class="mt-4 space-y-3">
                @forelse ($reservations as $resa)
                    <div class="rounded-2xl border border-sable-200 bg-white shadow-soft p-5">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-nuit">{{ $resa->activite->nom }}</div>
                                <div class="text-sm text-nuit/60">
                                    {{ $resa->activite->lieu ?: 'Lieu non precise' }}
                                    @if ($resa->activite->tarif) - {{ $resa->activite->tarif }} @endif
                                </div>
                                <div class="mt-1 text-xs text-nuit/40">Le {{ $resa->date_activite->format('d/m/Y') }}</div>
                            </div>
                            @if ($resa->chauffeur)
                                <span class="inline-flex rounded-full border border-lagon-100 bg-lagon-50 px-2.5 py-0.5 text-xs font-medium text-lagon-700">Chauffeur contacte</span>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('visitor.activites.contact', $resa) }}" class="mt-4 flex flex-wrap items-end gap-2">
                            @csrf
                            <div class="min-w-[14rem]">
                                <label class="block text-xs text-nuit/60 mb-1">Contacter un chauffeur</label>
                                <select name="chauffeur_id" required
                                        class="w-full rounded-xl border border-sable-300 bg-white px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
                                    <option value="">Choisir un chauffeur</option>
                                    @foreach ($chauffeurs as $ch)
                                        <option value="{{ $ch->id }}" @selected($resa->chauffeur_id === $ch->id)>{{ $ch->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                    class="rounded-xl border border-lagon px-4 py-2 text-sm font-semibold text-lagon-700 hover:bg-lagon-50 transition">
                                {{ $resa->chauffeur ? 'Recontacter' : 'Contacter' }}
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="rounded-2xl border border-sable-200 bg-white shadow-soft p-8 text-center text-sm text-nuit/50">
                        Aucune activite reservee pour le moment.
                    </div>
                @endforelse
            </div>
        </section>
    </main>
@endsection
