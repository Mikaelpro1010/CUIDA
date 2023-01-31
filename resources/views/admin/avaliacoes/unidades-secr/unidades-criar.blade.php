@extends('template.base')

@section('titulo', 'EscutaSol - Cadastrar Unidade')
@section('content')
    <h1 class="text-primary">
        Cadastrar Unidade da Secretaria
    </h1>

    <hr>

    <div>
        <form id="cadastrar_unidade" class="row" action="{{ route('post-store-unidade') }}" method="POST">
            {{ csrf_field() }}
            <div class="col-md-6">
                <label for="" class="form-label mb-1 mt-3 fw-bold">Nome:</label>
                <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome">
            </div>
            <div class="col-md-6">
                <label for="" class="form-label mb-1 mt-3 fw-bold">Secretaria:</label>
                <select id="secretaria" class="form-select form-select" aria-label=".form-select-sm example"
                    name="secretaria">
                    <option value="">Selecione</option>
                    @foreach ($secretarias as $secretaria)
                        <option value="{{ $secretaria->id }}">
                            {{ $secretaria->sigla . ' - ' . $secretaria->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12">
                <label for="" class="form-label mb-1 mt-3 fw-bold">Descrição:</label>
                <textarea class="form-control resize-none" name="descricao" id="descricao" rows="5" placeholder="Descrição"></textarea>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-primary" type="submit">
                    <i class="fa-solid fa-plus me-1"></i>
                    Criar
                </button>
            </div>
        </form>
    </div>

    <div class="text-center">
        <a href="{{ route('get-unidades-secr-list') }}" class="mt-3 btn btn-warning" tabindex="-1" role="button"
            aria-disabled="true">
            <i class="fa-solid fa-chevron-left"></i>
            Voltar
        </a>
    </div>

@endsection
