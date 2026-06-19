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
                            <div class="flex items-center gap-2">
                                @if ($resa->chauffeur)
                                    <span class="inline-flex rounded-full border border-lagon-100 bg-lagon-50 px-2.5 py-0.5 text-xs font-medium text-lagon-700">Chauffeur commande</span>
                                @endif
                                <form method="POST" action="{{ route('visitor.activites.destroy', $resa) }}" onsubmit="return confirm('Annuler cette reservation ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Annuler la reservation"
                                            class="grid h-8 w-8 place-items-center rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.02-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                        <span class="sr-only">Annuler</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('visitor.activites.command', $resa) }}" class="mt-4 flex flex-wrap items-end gap-2">
                            @csrf
                            <div class="min-w-[12rem]">
                                <label class="block text-xs text-nuit/60 mb-1">Lieu de depart</label>
                                <input type="text" name="depart" required placeholder="D'ou partez-vous ?"
                                       class="w-full rounded-xl border border-sable-300 bg-white px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
                            </div>
                            <div class="min-w-[12rem]">
                                <label class="block text-xs text-nuit/60 mb-1">Chauffeur</label>
                                <select name="chauffeur_id" required
                                        class="w-full rounded-xl border border-sable-300 bg-white px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
                                    <option value="">Choisir un chauffeur</option>
                                    @foreach ($chauffeurs as $ch)
                                        <option value="{{ $ch->id }}" @selected($resa->chauffeur_id === $ch->id)>{{ $ch->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                    class="rounded-xl bg-terracotta px-4 py-2 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 transition">
                                {{ $resa->chauffeur ? 'Commander a nouveau' : 'Commander un chauffeur' }}
                            </button>
                        </form>
                        <p class="mt-1 text-xs text-nuit/40">Destination : {{ $resa->activite->lieu ?: $resa->activite->nom }}. Le chauffeur demarrera le jour de l'activite.</p>
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
