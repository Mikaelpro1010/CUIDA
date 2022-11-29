@extends('template.base')

@section('titulo', 'EscutaSol - Tipos de Manifestação')
@section('content')
    <div class="text-primary">
        <h2>
            Cadastrar Tipo de Manifestação
        </h2>
        <hr>
    </div>
    <div>
        <form class="row" action="{{route('post-store-tipo-manifestacao')}}" method="POST">
            {{ csrf_field() }}
            <div class="col-12">
                <label for="nome" class="form-label mb-1 mt-3">Informe o nome:</label>
                <input type="text" name="nome" id="nome" class="form-control">
            </div>

            <div class="col-12">
                <label for="descricao" class="form-label mb-1 mt-3">Descreva:</label>
                <textarea class="form-control" name="descricao" id="descricao" rows="5"></textarea>
            </div>
            
            <div class="text-center">
                <a href="{{ route('get-tipo-manifestacao-list') }}" class="mt-3 btn btn-warning" tabindex="-1"
                    role="button" aria-disabled="true">
                    <i class="fa-solid fa-chevron-left"></i>
                    Voltar
                </a>
            </div>
        </form>
    </div>
@endsection