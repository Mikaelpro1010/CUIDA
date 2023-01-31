@extends('template.base')

@section('titulo', 'EscutaSol - Lista de Setores')
@section('content')
    <h3 class="text-center mt-3">{{ $unidade->nome }}</h3>

    @foreach($unidade->setores as $setor)
    <a href="">
        <div class="mt-3 text-center">
            <div style="background-color: rgb(68, 66, 66)" class="rounded-pill card-body text-white">
                <label>{{ $setor->nome }}</label>
            </div>
        </div>
    </a>
    @endforeach 
@endsection