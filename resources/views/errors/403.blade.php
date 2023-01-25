@extends('template.initial')

@section('titulo', 'Proibido')

@section('main')
<div class="card shadow py-2">
    <div class="text-center">
        <h1>Proibido!</h1>
        <h3>Você não tem Permissão para acessar esse conteúdo!</h3>
        <a class="btn btn-primary mt-3" href="{{ route('home') }}">Início</a>
    </div>
</div>
@endsection