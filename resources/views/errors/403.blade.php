@extends('errors.layout')

@section('code', '403')
@section('badge', 'Accès réservé')
@section('titre', 'Accès refusé')
@section('message', "Vous n'avez pas l'autorisation d'accéder à cette page. Si vous pensez qu'il s'agit d'une erreur, connectez-vous avec le bon compte.")

@section('action')
    <a href="{{ url('/login') }}"
       class="rounded-full border border-lagon bg-white/60 px-7 py-3.5 text-sm font-semibold text-lagon-700 backdrop-blur hover:bg-lagon-50 transition">
        Se connecter
    </a>
@endsection
