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
            <textarea class="form-control resize-none" name="descricao" id="descricao" rows="5"
                placeholder="Descrição"></textarea>
        </div>

        <div id="tipos_avaliacao"></div>
    </form>
</div>

<div id="avaliacoes" class="mt-3 d-none">
    <h3 class="text-primary">
        Tipos de Avaliação
    </h3>
    <hr>

    <div class="row">
        <div class="col-md-8">
            <label class="fw-bold" for="">Tipos de avaliação:</label>
            <select id="tipos_avaliacao_select" class="form-select">
            </select>
        </div>
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
    function insertItemList(position, text){
        $('#tipos_avaliacao').append(`
        <input class="tipos_avaliacao_` + position + `" type="hidden" name="tipos_avaliacao[]"
            value="` + position + `">
        `);
        $("#tipos_avaliacao_list").append(`
        <li id="list_` + position + `" class="list-group-item d-flex justify-content-between">
            <div class="d-flex align-items-center">` +
                text + `
            </div>
            <button class="deleteTipoAvaliacao btn" data-id="` + position + `">
                <i class="fa-lg text-danger fa-solid fa-trash"></i>
            </button>
        </li>
        `);
        $("#tipos_avaliacao_list").removeClass('d-none');
    }

    $("#tipos_avaliacao_select").change(function() {
        if ($('.tipos_avaliacao_' + $("#tipos_avaliacao_select").val()).length == 0) {
            insertItemList($("#tipos_avaliacao_select").val(), $("#tipos_avaliacao_select option:selected").text());
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

    $('#secretaria').change(function(){
        let url = "{{ route('get-tipo-avaliacao-secretaria', 'secretariaId') }}";
        url = url.replace('secretariaId', $('#secretaria').val());
        $.ajax({
            url: url,
            success: function(response) {
                $("#tipos_avaliacao_select").empty();
                $("#tipos_avaliacao_list").empty();
                $("#tipos_avaliacao").empty();
                $("#tipos_avaliacao_select").append(`<option value="">Selecione</option>`);
                response.data.forEach(function(tipo){
                    $("#tipos_avaliacao_select").append(`
                        <option value="` + tipo.id + `">` + tipo.nome + `</option>
                    `);
                    if(tipo.default){
                        insertItemList(tipo.id, tipo.nome);
                    }
                });
                $('#avaliacoes').removeClass('d-none');
            }
        });
    });

</script>
@endpush