@extends('template.base')

@section('titulo', 'Não Encontrado')

@section('content')
<div class="text-center">
    <h1>Não Encontrado!</h1>
    <h3>Não encontrei nada por aqui!</h3>
    <a class="btn btn-primary mt-3" href="{{ route('home') }}">Início</a>
</div>
@endsection