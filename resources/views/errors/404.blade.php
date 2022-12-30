@extends('template.initial')

@section('titulo', 'Não Encontrado')

@section('main')
<div class="card shadow py-2">
    <div class="text-center">
        <h1>Não Encontrado!</h1>
        <h3>Não encontrei nada por aqui!</h3>
        <a class="btn btn-primary mt-3" href="{{ route('home') }}">Início</a>
    </div>
</div>
@endsection