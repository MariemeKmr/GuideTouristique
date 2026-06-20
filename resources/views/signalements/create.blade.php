@extends('layouts.app')

@section('title', 'Signaler un probleme')

@section('content')
    @include('partials.navbar')

    @php($retour = auth()->user()->isTaximan() ? route('taximan.courses.index') : route('visitor.courses.index'))

    <main class="max-w-xl mx-auto px-4 sm:px-6 py-10">
        <a href="{{ $retour }}" class="text-sm text-lagon-700 hover:underline">&larr; Retour a mes courses</a>

        <div class="mt-4 rounded-2xl border border-sable-200 bg-white shadow-soft p-6">
            <h1 class="text-xl font-semibold text-nuit">
                {{ $cible === 'chauffeur' ? 'Signaler le chauffeur ou le trajet' : 'Signaler le passager' }}
            </h1>
            <p class="mt-1 text-sm text-nuit/60">
                Course du {{ $course->created_at->format('d/m/Y') }} : {{ $course->depart ?: 'depart' }} &rarr; {{ $course->destination ?: 'destination' }}
            </p>

            @include('partials.flash')

            <form method="POST" action="{{ route('signalements.store', $course) }}" class="mt-5 space-y-4">
                @csrf

                <div>
                    <label for="motif" class="block text-sm font-medium text-nuit mb-1">Motif</label>
                    <select id="motif" name="motif" required
                            class="w-full rounded-xl border border-sable-300 bg-white px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
                        <option value="">Choisir un motif</option>
                        @foreach ($motifs as $cle => $libelle)
                            <option value="{{ $cle }}" @selected(old('motif') === $cle)>{{ $libelle }}</option>
                        @endforeach
                    </select>
                    @error('motif') <p class="mt-1 text-xs text-terracotta-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-nuit mb-1">
                        Explication <span class="text-nuit/50">(obligatoire si "Autre")</span>
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full rounded-xl border border-sable-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1"
                              placeholder="Decrivez ce qui s'est passe...">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-terracotta-700">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="rounded-xl bg-terracotta px-5 py-2.5 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 transition">
                        Envoyer le signalement
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
