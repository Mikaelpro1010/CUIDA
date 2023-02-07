@extends('template.base')

@section('titulo', 'EscutaSol - Lista de Setores')
@section('content')
    <h3 class="text-center">{{ $unidade->nome }}</h3>
    <h5 class="text-center text-secondary">{{ $unidade->nome_oficial }}</h5>

    <p>Qual setor gostaria de Avaliar?</p>
    <div class="d-grid gap-2">
        @foreach ($unidade->setores as $setor)
            <a href="{{ route('get-view-avaliacao', $setor->token) }}"class="rounded-pill btn btn-primary btn-lg">
                {{ $setor->nome }}
            </a>
        @endforeach
    </div>
@endsection
