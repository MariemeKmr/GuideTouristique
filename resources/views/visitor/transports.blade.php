@extends('layouts.app')

@section('title', 'Transports')

@section('content')
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-nuit">Moyens de transport</h1>
        <p class="mt-1 text-sm text-gray-500">Comment vous déplacer et à quel coût.</p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($transports as $transport)
                <div class="rounded-lg border border-gray-200 bg-white p-5">
                    <div class="flex items-center justify-between gap-2">
                        <h2 class="text-base font-semibold text-nuit">{{ $transport->methode }}</h2>
                        @if ($transport->approximation_cout)
                            <span class="shrink-0 rounded-full bg-sable-50 px-2 py-0.5 text-xs font-medium text-gray-700">
                                {{ $transport->approximation_cout }}
                            </span>
                        @endif
                    </div>
                    @if ($transport->description)
                        <p class="mt-3 text-sm text-gray-600">{{ $transport->description }}</p>
                    @endif
                </div>
            @empty
                <div class="col-span-full rounded-lg border border-gray-200 bg-white p-10 text-center text-sm text-gray-500">
                    Aucun transport renseigné pour le moment.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $transports->links() }}
        </div>
    </main>
@endsection
