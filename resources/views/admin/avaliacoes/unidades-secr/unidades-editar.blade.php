@extends('template.base')

@section('titulo', 'EscutaSol - Unidades Secretaria')
@section('content')
    <h1 class="text-primary">
        Editar Unidade da Secretaria
    </h1>
    <h5 class="text-secondary">
        {{ $unidade->secretaria->nome }} - {{ $unidade->secretaria->sigla }}
    </h5>
    <hr>
    <form id="editarUnidade" action="{{ route('patch-update-unidade-secr', $unidade) }}" method="POST">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-6">
                <label for="" class="form-label fw-bold">Nome:</label>
                <input type="text" class="form-control" name="nome" placeholder="Nome" value="{{ $unidade->nome }}">
            </div>
            <div class="col-md-6">
                <label for="" class="form-label fw-bold">Nome Oficial:</label>
                <input type="text" class="form-control" name="nome_oficial" placeholder="Nome Oficial"
                    value="{{ $unidade->nome_oficial }}">
            </div>
            <div class="col-md-12">
                <label for="" class="form-label fw-bold">Descrição:</label>
                <textarea class="form-control resize-none" name="descricao" id="descricao" rows="5" placeholder="Descrição">{{ $unidade->descricao }}</textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <button id="btnEditar" class="btn btn-primary" type="submit">
                <i class="fa-solid fa-pen-to-square"></i>
                Editar
            </button>
        </div>
    </form>

    <div class="text-center">
        <a href="{{ route('get-unidades-secr-list') }}" class="mt-3 btn btn-warning" tabindex="-1" role="button"
            aria-disabled="true">
            <i class="fa-solid fa-chevron-left"></i>
            Voltar
        </a>
    </div>
@endsection
