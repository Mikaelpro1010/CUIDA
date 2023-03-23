@extends('template.base')

@section('titulo', 'EscutaSol - Motivações')
@section('content')
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-primary">Motivações</h1>
        <a class="btn btn-primary" href="{{ route('get-create-motivacao') }}">
            <i class="fa-solid fa-plus me-1"></i>
            Nova Motivação
        </a>
    </div>
    <hr>

    @component('admin.config.components_crud.filtrar-pesquisa', ['route' => 'get-motivacao-list'])
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
                @forelse ($motivacoes as $motivacao)
                    <tr id="{{ $motivacao->id }}">
                        <td>
                            {{ $motivacao->id }}
                        </td>
                        <td>
                            @if ($motivacao->ativo)
                                <a class="btn"
                                    href="{{ route('get-toggle-motivacao-status', ['id' => $motivacao->id]) }}">
                                    <i class="text-success fa-solid fa-circle-check"></i>
                                </a>
                            @else
                                <a class="btn"
                                    href="{{ route('get-toggle-motivacao-status', ['id' => $motivacao->id]) }}">
                                    <i class="text-danger fa-solid fa-circle-xmark"></i>
                                </a>
                            @endif
                        </td>
                        <td class="name">
                            {{ $motivacao->nome }}
                        </td>
                        <td>
                            {{ $motivacao->descricao }}
                        </td>
                        <td>
                            {{ Carbon\Carbon::parse($motivacao->updated_at)->format('d/m/Y \à\s H:i\h') }}
                        </td>
                        <td class="col-md-1">
                            <div class="d-flex justify-content-evenly">
                                @component('admin.config.components_crud.view', ['item' => $motivacao], ['route' => 'get-motivacao-view'])
                                @endcomponent
                                @component('admin.config.components_crud.edit', ['item' => $motivacao], ['route' => 'get-edit-motivacao-view'])
                                @endcomponent
                                @component('admin.config.components_crud.delete', ['item' => $motivacao], ['route' => 'delete-delete-motivacao'])
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
        {{ $motivacoes->links('pagination::bootstrap-4') }}
    </div>
    </div>
    <div id="deleteModal_2" name="id" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-light">Deletar Motivação!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Deseja realmente deletar a Motivação: <span id="deleteName" class="fw-bold"></span>
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
    var MotivacaoID = 0;

        $('#btnLimpaForm').click(function(){
            $('#pesquisa').val('');
        });

        $('.btnDelete').click(function() {
        deleteMotivacao($(this).data('id'));
        });

        function deleteMotivacao(id) {
            $("#deleteName").text($("#" + id + " .name").text());
            $('#deleteModal_2').modal('show');
            MotivacaoID = id;
        }

        $("#btnDeleteConfirm").click(function() {
            $('#deleteModal_2').modal('hide');
            $('#deleteMotivacao' + MotivacaoID).submit();
            MotivacaoID = 0;
        });

</script>
@endpush