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
        <form class="row g-2" action="{{ route('post-store-tipo-avaliacao') }}" method="POST">
            {{ csrf_field() }}
            <div class="col-md-6">
                <label for="nome" class="form-label fw-bold">Nome:</label>
                <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome">
            </div>

            <div class="col-md-6">
                <label for="" class="form-label fw-bold">Secretaria:</label>
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
                <label class="form-label fw-bold">Inserir Automaticamente?</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="inserir_automaticamente"
                        id="inserir_automaticamente1" value="1">
                    <label class="form-check-label" for="inserir_automaticamente1">
                        Sim, esse tipo de avaliação deve ser inserido automaticamente ao criar uma nova Unidade da
                        Secretaria.
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="inserir_automaticamente"
                        id="inserir_automaticamente2" value="0" checked>
                    <label class="form-check-label" for="inserir_automaticamente2">
                        Não, irei inserir esse tipo de avaliação somente em Unidades da Secretaria que eu escolher.
                    </label>
                </div>
            </div>

            <div class="col-md-12">
                <label for="pergunta" class="form-label fw-bold">Pergunta:*</label>
                <textarea class="form-control resize-none" name="pergunta" id="pergunta" rows="5" placeholder="Pergunta"></textarea>
            </div>

            <small>
                * Pergunta que será exibida na pagina de avaliação quando o usuário ler o QR-code
            </small>

            <div class="d-flex justify-content-end mt-2">
                <button class="btn btn-primary" type="submit">
                    <i class="fa-solid fa-plus me-1"></i>
                    Criar
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('get-tipo-avaliacao-list') }}" class="mt-3 btn btn-warning">
                    <i class="fa-solid fa-chevron-left"></i>
                    Voltar
                </a>
            </div>
        </form>
    </div>
@endsection
