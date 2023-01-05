@extends('template.base')

@section('titulo', 'EscutaSol - Tipo de Avaliação')
@section('content')
    @if (session('mensagem'))
        <div class="alert alert-success" role="alert">
            {{ session('mensagem') }}
        </div>
    @endif
        <h2 class="text-primary">
            Editar Tipo de Avaliação
        </h2>
        <hr>
    <form class="row" action="{{ route('patch-update-tipo-avaliacao', $tipoAvaliacao->id) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
            <div class="col-12">
                <label for="nome" class="form-label mb-1 mt-3">Campo para editar o nome:</label>
                <input type="text" name="nome" id="nome" class="form-control" value="{{ $tipoAvaliacao->nome }}">
            </div>

            <div class="d-flex justify-content-end mt-2">
                <button class="btn btn-primary " type="submit">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Editar
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('get-tipo-avaliacao-list') }}" class="mt-3 btn btn-warning" tabindex="-1"
                    role="button" aria-disabled="true">
                    <i class="fa-solid fa-chevron-left"></i>
                    Voltar
                </a>
            </div>
    </form>
@endsection