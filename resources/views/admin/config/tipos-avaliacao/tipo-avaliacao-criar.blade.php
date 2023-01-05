@extends('template.base')

@section('titulo', 'EscutaSol - Tipo de Avaliação')
@section('content')
    <div class="text-primary">
        <h2>
            Cadastrar Tipo de Avaliação 
        </h2>
        <hr>
    </div>
    <div>
        <form class="row" action="{{route('post-store-tipo-avaliacao')}}" method="POST">
            {{ csrf_field() }}
            <div class="col-12">
                <label for="nome" class="form-label mb-1 mt-3">Informe o nome:</label>
                <input type="text" name="nome" id="nome" class="form-control">
            </div>

            <div class="d-flex justify-content-end mt-2">
                <button class="btn btn-primary" type="submit">
                    <i class="fa-solid fa-plus me-1"></i>
                    Criar
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
    </div>
@endsection