@extends('layouts.app')

@section('title', 'Activites')

@section('content')
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-nuit">Activites</h1>
        <p class="mt-1 text-sm text-nuit/60">Loisirs et experiences a vivre pendant votre sejour.</p>

        {{-- Filtre par categorie --}}
        <div class="mt-6 flex flex-wrap items-center gap-2">
            <a href="{{ route('visitor.activites.index') }}"
               class="rounded-full px-3.5 py-1.5 text-sm font-medium transition {{ ! $courante ? 'bg-lagon text-white' : 'border border-sable-300 text-nuit/70 hover:bg-sable-50' }}">
                Toutes
            </a>
            @foreach ($categories as $cle => $libelle)
                <a href="{{ route('visitor.activites.index', ['categorie' => $cle]) }}"
                   class="rounded-full px-3.5 py-1.5 text-sm font-medium transition {{ $courante === $cle ? 'bg-lagon text-white' : 'border border-sable-300 text-nuit/70 hover:bg-sable-50' }}">
                    {{ $libelle }}
                </a>
            @endforeach
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($activites as $activite)
                <div class="rounded-2xl border border-sable-200 bg-white shadow-soft p-5">
                    <div class="flex items-start justify-between gap-2">
                        <h2 class="text-base font-semibold text-nuit">{{ $activite->nom }}</h2>
                        <span class="shrink-0 rounded-full bg-lagon-50 px-2 py-0.5 text-xs font-medium text-lagon-700">{{ $activite->categorieLabel() }}</span>
                    </div>

                    <p class="mt-1 text-sm text-nuit/60">
                        {{ $activite->lieu ?: ($activite->destination?->name ?? 'Lieu a preciser') }}
                    </p>

                    @if ($activite->description)
                        <p class="mt-3 text-sm text-nuit/70">{{ \Illuminate\Support\Str::limit($activite->description, 130) }}</p>
                    @endif

                    @if ($activite->tarif)
                        <div class="mt-4 inline-flex rounded-full bg-sable-50 px-3 py-1 text-xs font-medium text-nuit/80">
                            {{ $activite->tarif }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-sable-200 bg-white shadow-soft p-10 text-center text-sm text-nuit/50">
                    Aucune activite dans cette categorie pour le moment.
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $activites->links() }}</div>
    </main>
@endsection
