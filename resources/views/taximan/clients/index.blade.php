@extends('layouts.app')

@section('title', 'Mes clients')

@section('content')
    @include('partials.navbar')

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-10">
        <h1 class="text-2xl font-semibold text-nuit">Mes clients</h1>
        <p class="mt-1 text-sm text-nuit/60">Les personnes que vous avez transportees.</p>

        {{-- Statistiques --}}
        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-sable-200 bg-white shadow-soft p-5">
                <div class="text-xs font-medium uppercase tracking-wider text-gray-400">Clients</div>
                <div class="mt-1 text-2xl font-semibold text-nuit">{{ $stats['clients'] }}</div>
            </div>
            <div class="rounded-2xl border border-sable-200 bg-white shadow-soft p-5">
                <div class="text-xs font-medium uppercase tracking-wider text-gray-400">Courses</div>
                <div class="mt-1 text-2xl font-semibold text-nuit">{{ $stats['courses'] }}</div>
            </div>
            <div class="rounded-2xl border border-sable-200 bg-white shadow-soft p-5">
                <div class="text-xs font-medium uppercase tracking-wider text-gray-400">Terminees</div>
                <div class="mt-1 text-2xl font-semibold text-nuit">{{ $stats['terminees'] }}</div>
            </div>
        </div>

        {{-- Liste --}}
        <div class="mt-8 overflow-hidden rounded-2xl border border-sable-200 bg-white shadow-soft">
            <table class="min-w-full divide-y divide-sable-200 text-sm">
                <thead class="bg-sable-50/60">
                    <tr class="text-left text-xs font-medium uppercase tracking-wider text-nuit/50">
                        <th class="px-5 py-3">Client</th>
                        <th class="px-5 py-3 text-center">Courses</th>
                        <th class="px-5 py-3 text-center">Terminees</th>
                        <th class="px-5 py-3">Derniere course</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sable-200">
                    @forelse ($clients as $c)
                        <tr>
                            <td class="px-5 py-3 font-medium text-nuit">{{ $c->client->full_name }}</td>
                            <td class="px-5 py-3 text-center text-nuit/70">{{ $c->total }}</td>
                            <td class="px-5 py-3 text-center text-nuit/70">{{ $c->terminees }}</td>
                            <td class="px-5 py-3 text-nuit/60">{{ $c->derniere->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-nuit/50">Aucun client pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
@endsection
