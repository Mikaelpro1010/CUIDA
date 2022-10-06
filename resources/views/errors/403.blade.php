@extends('template.base')

@section('titulo', 'Proibido')

@section('content')
<div class="text-center">
    <h1>Proibido!</h1>
    <h3>Você não tem Permissão para acessar esse conteúdo!</h3>
    <a class="btn btn-primary mt-3" href="{{ route('home') }}">Início</a>
</div>
@endsection