@extends('layouts.app')

@section('title', 'Nouvelle activite')

@section('content')
    @include('partials.navbar')

    <main class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
        <a href="{{ route('admin.activites.index') }}" class="text-sm text-nuit/50 hover:text-nuit">&larr; Retour</a>
        <h1 class="mt-2 text-2xl font-semibold text-nuit mb-6">Nouvelle activite</h1>

        <div class="rounded-2xl border border-sable-200 bg-white shadow-soft p-6">
            <form method="POST" action="{{ route('admin.activites.store') }}">
                @csrf
                @include('admin.activites._form')
                <div class="mt-6 flex items-center gap-3">
                    <button type="submit" class="rounded-xl bg-terracotta px-4 py-2 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 hover:shadow-lift transition">Enregistrer</button>
                    <a href="{{ route('admin.activites.index') }}" class="rounded-xl border border-sable-300 px-4 py-2 text-sm font-medium text-nuit/80 hover:bg-sable-50">Annuler</a>
                </div>
            </form>
        </div>
    </main>
@endsection
