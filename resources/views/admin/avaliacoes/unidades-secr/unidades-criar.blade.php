@extends('template.base')

{{-- @section('titulo', 'EscutaSol - Estados do Processo') --}}
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
            <select class="form-select form-select" aria-label=".form-select-sm example" name="secretaria">
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
            <textarea class="form-control resize-none" name="descricao" id="descricao" rows="5"
                placeholder="Descrição"></textarea>
        </div>

        <div id="tipos_avaliacao"></div>
    </form>
</div>

<div class="text-primary mt-3">
    <h3>
        Tipos de Avaliação
    </h3>
    <hr>
</div>

<div class="row">
    <div class="col-md-8">
        <label class="fw-bold" for="">Tipos de avaliação:</label>
        <select id="tipos_avaliacao_select" class="form-select">
            <option value="">Selecione</option>
            @foreach ($tipos_avaliacao as $tipo_avaliacao)
            <option name="tipos_avaliacao" value="{{ $tipo_avaliacao->id }}"
                @if(old('tipo_avaliacao')==$tipo_avaliacao->id) selected @endif>
                {{ $tipo_avaliacao->nome }}
            </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-8">
        <ul id="tipos_avaliacao_list" class="list-group d-none">
        </ul>
    </div>
</div>

<div class="d-flex justify-content-end">
    <button id="btnCriar" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i>
        Criar
    </button>
</div>

<div class="text-center">
    <a href="{{ route('get-unidades-secr-list') }}" class="mt-3 btn btn-warning" tabindex="-1" role="button"
        aria-disabled="true">
        <i class="fa-solid fa-chevron-left"></i>
        Voltar
    </a>
</div>

@endsection

@push('scripts')
<script nonce="{{ app('csp-nonce') }}">
    $("#tipos_avaliacao_select").change(function() {
        if ($('.tipos_avaliacao_' + $("#tipos_avaliacao_select").val()).length == 0) {
            $('#tipos_avaliacao').append(`
            <input class="tipos_avaliacao_` + $("#tipos_avaliacao_select").val() +
                `" type="hidden" name="tipos_avaliacao[]" value="` + $("#tipos_avaliacao_select").val() + `">
        `);
            $("#tipos_avaliacao_list").append(`
            <li id="list_` + $("#tipos_avaliacao_select").val() +
                `" class="list-group-item d-flex justify-content-between">
                <div class="d-flex align-items-center">` + 
                    $("#tipos_avaliacao_select option:selected").text() + `
                </div>
                <button class="deleteTipoAvaliacao btn" data-id="` + $("#tipos_avaliacao_select").val() + `">
                    <i class="fa-lg text-danger fa-solid fa-trash"></i>
                </button>
            </li>
        `);
            $("#tipos_avaliacao_list").removeClass('d-none');
        }
        $("#tipos_avaliacao_select").val('');
    });

    $('#tipos_avaliacao_list').on('click', '.deleteTipoAvaliacao', function($this){
        $('.tipos_avaliacao_' + $(this).data('id')).remove();
        $('#list_' + $(this).data('id')).remove();
    });

    $('#btnCriar').click(function(){
        $('#cadastrar_unidade').submit();
    });

</script>
@endpush