@extends('template.base')

@section('titulo', 'EscutaSol - Unidades Secretaria')
@section('content')
    <form action="{{ route('atualizar-unidade', $unidade) }}" method="POST">
        {{ method_field('PUT') }}
        {{ csrf_field() }}
        <div>
            <label class="form-label" for="nome">Nome:</label>
            <input class="form-control" type="text" name="nome" id="nome" value="{{ $unidade->nome }}">
        </div>
        <div class="mt-3">
            <label class="form-label" for="nome">Descrição</label>
            <textarea class="form-control" name="descricao" rows="6" id="descricao">{{ $unidade->descricao }}</textarea>
        </div>
        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-pen-to-square"></i>
                Editar
            </button>
        </div>
        <div class="text-center">
            <a href="{{ route('get-faq-list') }}" class="mt-3 btn btn-warning" tabindex="-1" role="button"
                aria-disabled="true">
                <i class="fa-solid fa-chevron-left"></i>
                Voltar
            </a>
        </div>
    </form>
@endsection
