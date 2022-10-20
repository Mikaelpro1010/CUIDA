@extends('template.base')

@section('titulo', 'Ultrapassou o Limite')

@section('content')
<div class="text-center">
    <h1>Limite Ultrapassado!</h1>
    <h5>Você ultrapassou o Limite diário! Volte Amanhã caso queira fazer mais avaliações</h5>
    <a class="btn btn-primary mt-3" href="{{ route('home') }}">Início</a>
</div>
@endsection