@extends('layouts.app')

@section('title', 'Destinations')

@section('content')
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        @include('partials.tabs-decouvrir')
        <h1 class="text-2xl font-semibold text-nuit">Destinations</h1>
        <p class="mt-1 text-sm text-gray-500">Explorez les lieux à visiter.</p>

        @include('partials.flash')

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($destinations as $destination)
                <a href="{{ route('visitor.destinations.show', $destination) }}"
                   class="block rounded-2xl border border-sable-200 bg-white shadow-soft p-5 hover:border-gray-400 transition">
                    <div class="flex items-start justify-between gap-2">
                        <h2 class="text-base font-semibold text-nuit">{{ $destination->name }}</h2>
                        @if (in_array($destination->id, $visitedIds))
                            <span class="shrink-0 rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 border border-green-200">
                                Visité
                            </span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-gray-500">{{ $destination->localite }}</p>
                    @if ($destination->description)
                        <p class="mt-3 text-sm text-gray-600 line-clamp-3">{{ \Illuminate\Support\Str::limit($destination->description, 120) }}</p>
                    @endif
                </a>
            @empty
                <div class="col-span-full rounded-2xl border border-sable-200 bg-white shadow-soft p-10 text-center text-sm text-gray-500">
                    Aucune destination disponible pour le moment.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $destinations->links() }}
        </div>
    </main>
@endsection
