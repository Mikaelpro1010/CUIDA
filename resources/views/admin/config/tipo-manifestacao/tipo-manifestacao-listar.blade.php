@extends('template.base')

@section('titulo', 'EscutaSol - Tipos de Manifestação')
@section('content')
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-primary">Tipos de Manifestação</h1>
        <a class="btn btn-primary" href="{{ route('get-create-tipo-manifestacao') }}">
            <i class="fa-solid fa-plus me-1"></i>
            Novo Tipo de Manifestação
        </a>
    </div>
    <hr>

    @component('admin.config.components_crud.filtrar-pesquisa', ['route' => 'get-tipo-manifestacao-list'])
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
            @forelse ($tipo_manifestacoes as $tipo_manifestacao)
                <tr id="{{ $tipo_manifestacao->id }}">
                    <td>
                        {{ $tipo_manifestacao->id }}
                    </td>
                    <td>
                        @if ($tipo_manifestacao->ativo)
                            <a class="btn"
                                href="{{ route('get-toggle-tipo-manifestacao-status', ['id' => $tipo_manifestacao->id]) }}">
                                <i class="text-success fa-solid fa-circle-check"></i>
                            </a>
                        @else
                            <a class="btn"
                                href="{{ route('get-toggle-tipo-manifestacao-status', ['id' => $tipo_manifestacao->id]) }}">
                                <i class="text-danger fa-solid fa-circle-xmark"></i>
                            </a>
                        @endif
                    </td>
                    <td class="name">
                        {{ $tipo_manifestacao->nome }}
                    </td>
                    <td>
                        {{ $tipo_manifestacao->descricao }}
                    </td>
                    <td>
                        {{ Carbon\Carbon::parse($tipo_manifestacao->updated_at)->format('d/m/Y \à\s H:i\h') }}
                    </td>
                    <td class="col-md-1">
                        <div class="d-flex justify-content-evenly">
                            @component('admin.config.components_crud.view', ['item' => $tipo_manifestacao], ['route' => 'get-tipo-manifestacao-view'])
                            @endcomponent
                            @component('admin.config.components_crud.edit', ['item' => $tipo_manifestacao], ['route' => 'get-edit-tipo-manifestacao-view'])
                            @endcomponent
                            @component('admin.config.components_crud.delete', ['item' => $tipo_manifestacao], ['route' => 'delete-delete-tipo-manifestacao'])
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
        {{ $tipo_manifestacoes->links('pagination::bootstrap-4') }}
    </div>
    </div>
    <div id="deleteModal" name="id" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-light">Deletar Tipo de Manifestação!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Deseja realmente deletar o Tipo de Manifestação: <span id="deleteName" class="fw-bold"></span>
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
    var tipoManifestacaoID = 0;

        $('#btnLimpaForm').click(function(){
            $('#pesquisa').val('');
        });

        $('.btnDelete').click(function() {
        deleteTipoManifestacao($(this).data('id'));
        });

        function deleteTipoManifestacao(id) {
            $("#deleteName").text($("#" + id + " .name").text());
            $('#deleteModal').modal('show');
            tipoManifestacaoID = id;
        }

        $("#btnDeleteConfirm").click(function() {
            $('#deleteModal').modal('hide');
            $('#deleteTipoManifestacao' + tipoManifestacaoID).submit();
            tipoManifestacaoID = 0;
        });

</script>
@endpush