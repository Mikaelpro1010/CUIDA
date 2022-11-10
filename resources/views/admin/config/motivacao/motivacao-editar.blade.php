@extends('template.base')

@section('content')
    @if (session('mensagem'))
        <div class="alert alert-success" role="alert">
            {{ session('mensagem') }}
        </div>
    @endif
        <h2 class="text-primary">
            Editar Motivação
        </h2>
        <hr>
    <form class="row" action="{{ route('patch-update-motivacao', $Motivacao->id) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
            <div class="col-6">
                <label for="nome" class="form-label mb-1 mt-3">Campo para editar o nome:</label>
                <input type="text" name="nome" id="nome" class="form-control" value="{{ $Motivacao->nome }}">
            </div>

            <div class="col-6">
                <label for="quantidade" class="form-label mb-1 mt-3">Campo para editar a descrição:</label>
                <textarea class="form-control" name="descricao" id="descricao" rows="5">{{ $Motivacao->descricao }}</textarea>
            </div>

            <div class="col-6">
                <button class="btn btn-info mt-4" type="submit">Salvar edição</button>
            </div>
    </form>
@endsection