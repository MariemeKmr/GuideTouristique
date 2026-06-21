@extends('errors.layout')

@section('code', '419')
@section('badge', 'Session expirée')
@section('titre', 'Session expirée')
@section('message', "Votre session a expiré par sécurité, ou le formulaire est resté ouvert trop longtemps. Rechargez la page et reconnectez-vous.")

@section('action')
    <a href="{{ url('/login') }}"
       class="rounded-full border border-lagon bg-white/60 px-7 py-3.5 text-sm font-semibold text-lagon-700 backdrop-blur hover:bg-lagon-50 transition">
        Se reconnecter
    </a>
@endsection
