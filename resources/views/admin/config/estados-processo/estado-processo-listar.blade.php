@extends('template.base')

@section('titulo', 'EscutaSol - Estados do Processo')
@section('content')
<div class="col-lg-12 d-flex justify-content-between align-items-center">
    <h1 class="m-0 text-primary">Estados do Processo</h1>
    <a class="btn btn-primary" href="{{ route('get-create-estado-processo') }}">
        <i class="fa-solid fa-plus me-1"></i>
        Novo Estados do Processo
    </a>
</div>
<hr>

@component('admin.config.components_crud.filtrar-pesquisa', ['route' => 'get-estado-processo-list'])
@endcomponent

<table class="table table-striped">
    <thead>
        <th>Id</th>
        <th>Ativo</th>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Última alteração</th>
        <th class="text-center">Ações</th>
    </thead>
    <tbody class="table-group-divider">
        @forelse ($estados_processo as $estado_processo)
        <tr id="{{ $estado_processo->id }}">
            <td>
                {{ $estado_processo->id }}
            </td>
            <td>
                @if ($estado_processo->ativo)
                <a class="btn" href="{{ route('get-toggle-estado-processo-status', ['id' => $estado_processo->id]) }}">
                    <i class="text-success fa-solid fa-circle-check"></i>
                </a>
                @else
                <a class="btn" href="{{ route('get-toggle-estado-processo-status', ['id' => $estado_processo->id]) }}">
                    <i class="text-danger fa-solid fa-circle-xmark"></i>
                </a>
                @endif
            </td>
            <td class="name">
                {{ $estado_processo->nome }}
            </td>
            <td>
                {{ $estado_processo->descricao }}
            </td>
            <td>
                {{ Carbon\Carbon::parse($estado_processo->updated_at)->format('d/m/Y \à\s H:i\h') }}
            </td>
            <td class="col-md-1">
                <div class="d-flex justify-content-evenly">
                    @component('admin.config.components_crud.view', ['item' => $estado_processo], ['route' => 'get-estado-processo-view'])
                    @endcomponent
                    @component('admin.config.components_crud.edit', ['item' => $estado_processo], ['route' => 'get-edit-estado-processo-view'])
                    @endcomponent
                    @component('admin.config.components_crud.delete', ['item' => $estado_processo], ['route' => 'delete-delete-estado_processo'])
                    @endcomponent
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center table-warning">
                Nenhum resultado encontrado!
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
<div class='mx-auto'>
    {{ $estados_processo->links('pagination::bootstrap-4') }}
</div>
</div>
<div id="deleteModal_1" name="id" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-light">Deletar Estado do Processo!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Deseja realmente deletar o Estado do Processo: <span id="deleteName" class="fw-bold"></span>
                </p>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancelar</button>
                    <button id="btnDeleteConfirm" type="button" class="btn btn-danger">Deletar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script nonce="{{ app('csp-nonce') }}">
    var estadoProcessoID = 0;

    $('#btnLimpaForm').click(function(){
        $('#pesquisa').val('');
    });

    $('.btnDelete').click(function() {
        deleteEstadoProcesso($(this).data('id'));
    });

    function deleteEstadoProcesso(id) {
        $("#deleteName").text($("#" + id + " .name").text());
        $('#deleteModal_1').modal('show');
        EstadoProcessoID = id;
    }

    $("#btnDeleteConfirm").click(function() {
        $('#deleteModal_3').modal('hide');
        $('#deleteEstadoProcesso' + EstadoProcessoID).submit();
        EstadoProcessoID = 0;
    });

</script>
@endpush