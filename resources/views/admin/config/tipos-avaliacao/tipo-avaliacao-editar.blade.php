@extends('template.base')

@section('titulo', 'EscutaSol - Tipo de Avaliação')
@section('content')
    <h2 class="text-primary">
        Editar Tipo de Avaliação
    </h2>
    <hr>
    <form class="row" action="{{ route('patch-update-tipo-avaliacao', $tipoAvaliacao->id) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
        <div class="col-12">
            <label for="nome" class="form-label fw-bold">Nome:</label>
            <input type="text" name="nome" id="nome" class="form-control"
                value="{{ old('nome', $tipoAvaliacao->nome) }}">
        </div>

        <div class="col-md-12">
            <label class="form-label fw-bold">Inserir Automaticamente?</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="inserir_automaticamente" id="inserir_automaticamente1"
                    value="1" {{ old('inserir_automaticamente', $tipoAvaliacao->default) ? 'checked' : '' }}>
                <label class="form-check-label" for="inserir_automaticamente1">
                    Sim, esse tipo de avaliação deve ser inserido automaticamente ao criar uma nova Unidade da
                    Secretaria.
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="inserir_automaticamente" id="inserir_automaticamente2"
                    value="0" {{ old('inserir_automaticamente', $tipoAvaliacao->default) ? '' : 'checked' }}>
                <label class="form-check-label" for="inserir_automaticamente2">
                    Não, irei inserir esse tipo de avaliação somente em Unidades da Secretaria que eu escolher.
                </label>
            </div>
        </div>

        <div class="col-12">
            <label for="pergunta" class="form-label fw-bold">Pergunta:*</label>
            <textarea class="form-control resize-none" name="pergunta" id="pergunta" rows="5" placeholder="pergunta">{{ old('pergunta', $tipoAvaliacao->pergunta) }}
            </textarea>
        </div>

        <small>
            * Pergunta que será exibida na pagina de avaliação quando o usuário ler o QR-code
        </small>

        <div class="d-flex justify-content-end mt-2">
            <button class="btn btn-primary " type="submit">
                <i class="fa-solid fa-pen-to-square"></i>
                Editar
            </button>
        </div>

        <div class="text-center">
            <a href="{{ route('get-tipo-avaliacao-list') }}" class="mt-3 btn btn-warning" tabindex="-1" role="button"
                aria-disabled="true">
                <i class="fa-solid fa-chevron-left"></i>
                Voltar
            </a>
        </div>
    </form>
@endsection
