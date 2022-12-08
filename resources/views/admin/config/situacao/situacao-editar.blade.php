@extends('template.base')

@section('titulo', 'EscutaSol - Situação')
@section('content')
<h2 class="text-primary">
    Editar Situação
</h2>
<hr>
<form class="row" action="{{ route('patch-update-situacao', $Situacao->id) }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <div class="col-12">
        <label for="nome" class="form-label mb-1 mt-3">Campo para editar o nome:</label>
        <input type="text" name="nome" id="nome" class="form-control" value="{{ $Situacao->nome }}">
    </div>

    <div class="col-12">
        <label for="quantidade" class="form-label mb-1 mt-3">Campo para editar a descrição:</label>
        <textarea class="form-control" name="descricao" id="descricao" rows="5">{{ $Situacao->descricao }}</textarea>
    </div>

    <div class="text-center">
        <a href="{{ route('get-situacao-list') }}" class="mt-3 btn btn-warning" tabindex="-1"
            role="button" aria-disabled="true">
            <i class="fa-solid fa-chevron-left"></i>
            Voltar
        </a>
    </div>
</form>
@endsection