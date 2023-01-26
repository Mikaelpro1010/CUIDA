@extends('template.base')

@section('titulo', 'EscutaSol - Tipos de Avaliação')
@section('content')
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-primary">Tipos de Avaliação</h1>
        @can(permissionConstant()::GERENCIAR_TIPOS_AVALIACAO_CREATE)
            <a class="btn btn-primary" href="{{ route('get-create-tipo-avaliacao') }}">
                <i class="fa-solid fa-plus me-1"></i>
                Novo Tipo de Avaliação
            </a>
        @endcan
    </div>
    <hr>

    <form class="" action="" method="GET">
        <div class="m-0 p-0 row">
            <div class="col-md-5">
                <label for="pesquisa">Nome:</label>
                <input id="pesquisa" class="form-control" type="text" name="pesquisa" placeholder="Pesquisar"
                    value="{{ request()->pesquisa }}">
            </div>
            <div class="col-md-3">
                <label for="secretaria_pesq">Secretaria:</label>
                <select id="secretaria_pesq" class="form-select" name="secretaria_pesq">
                    <option value="" @if (is_null(request()->secretaria_pesq)) selected @endif>Selecione</option>
                    @foreach ($secretariasSearchSelect as $secretaria)
                        <option value="{{ $secretaria->id }}" @if (request()->secretaria_pesq == $secretaria->id) selected @endif>
                            {{ $secretaria->sigla . ' - ' . $secretaria->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary form-control mt-3" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Buscar
                </button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a id="btnLimpaForm" class="btn btn-warning form-control mt-3">
                    Limpar
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <th>Ativo</th>
            <th>Nome</th>
            <th>Secretaria</th>
            <th>Última alteração</th>
            @if (auth()->user()->can(permissionConstant()::GERENCIAR_TIPOS_AVALIACAO_VIEW) ||
                    auth()->user()->can(permissionConstant()::GERENCIAR_TIPOS_AVALIACAO_EDIT) ||
                    auth()->user()->can(permissionConstant()::GERENCIAR_TIPOS_AVALIACAO_DELETE))
                <th class="text-center">Ações</th>
            @endif
        </thead>
        <tbody>
            @forelse ($tipo_avaliacoes as $tipo_avaliacao)
                <tr id="{{ $tipo_avaliacao->id }}">
                    <td>
                        @can(permissionConstant()::GERENCIAR_TIPOS_AVALIACAO_ACTIVE_TOGGLE)
                            <a class="btn"
                                href="{{ route('get-toggle-tipo-avaliacao-status', ['id' => $tipo_avaliacao->id]) }}">
                                @if ($tipo_avaliacao->ativo)
                                    <i class="text-success fa-solid fa-circle-check"></i>
                                @else
                                    <i class="text-danger fa-solid fa-circle-xmark"></i>
                                @endif
                            </a>
                        @else
                            @if ($tipo_avaliacao->ativo)
                                <i class="text-success fa-solid fa-circle-check"></i>
                            @else
                                <i class="text-danger fa-solid fa-circle-xmark"></i>
                            @endif
                        @endcan
                    </td>
                    <td class="name">
                        {{ $tipo_avaliacao->nome }}
                    </td>
                    <td>
                        {{ $tipo_avaliacao->secretaria->sigla . ' - ' . $tipo_avaliacao->secretaria->nome }}
                    </td>
                    <td>
                        {{ formatarDataHora($tipo_avaliacao->updated_at) }}
                    </td>
                    <td class="col-md-1">
                        <div class="d-flex justify-content-evenly">
                            @can(permissionConstant()::GERENCIAR_TIPOS_AVALIACAO_VIEW)
                                <a href="{{ route('get-tipo-avaliacao-view', ['id' => $tipo_avaliacao->id]) }}">
                                    <i class="fa-xl fa-solid fa-magnifying-glass text-primary"></i>
                                </a>
                            @endcan
                            @can(permissionConstant()::GERENCIAR_TIPOS_AVALIACAO_EDIT)
                                <a href="{{ route('get-edit-tipo-avaliacao-view', ['id' => $tipo_avaliacao->id]) }}">
                                    <i class="fa-xl fa-solid fa-pen-to-square text-warning"></i>
                                </a>
                            @endcan
                            @can(permissionConstant()::GERENCIAR_TIPOS_AVALIACAO_DELETE)
                                <a class="btnDelete" data-id="{{ $tipo_avaliacao->id }}">
                                    <i class="fa-xl text-danger fa-solid fa-trash"></i>
                                </a>
                                <form class="d-none" id="deleteTipoAvaliacao{{ $tipo_avaliacao->id }}"
                                    action="{{ route('delete-delete-tipo-avaliacao', $tipo_avaliacao) }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                            @endcan
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
        {{ $tipo_avaliacoes->links('pagination::bootstrap-4') }}
    </div>

    @can(permissionConstant()::GERENCIAR_TIPOS_AVALIACAO_DELETE)
        <div id="deleteModal" name="id" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-light">Deletar Tipo de Avaliação!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Deseja realmente deletar o Tipo de Avaliação: <span id="deleteName" class="fw-bold"></span>
                        </p>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancelar</button>
                            <button id="btnDeleteConfirm" type="button" class="btn btn-danger">Deletar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection

@push('scripts')
    <script nonce="{{ app('csp-nonce') }}">
        $('#btnLimpaForm').click(function() {
            $('#pesquisa').val('');
            $('#secretaria_pesq').val('');
        });

        @can(permissionConstant()::GERENCIAR_TIPOS_AVALIACAO_DELETE)
            var tipoAvaliacaoID = 0;
            $('.btnDelete').click(function() {
                deleteTipoAvaliacao($(this).data('id'));
            });

            function deleteTipoAvaliacao(id) {
                $("#deleteName").text($("#" + id + " .name").text());
                $('#deleteModal').modal('show');
                TipoAvaliacaoID = id;
            }

            $("#btnDeleteConfirm").click(function() {
                $('#deleteModal').modal('hide');
                $('#deleteTipoAvaliacao' + TipoAvaliacaoID).submit();
                TipoAvaliacaoID = 0;
            });
        @endcan
    </script>
@endpush
