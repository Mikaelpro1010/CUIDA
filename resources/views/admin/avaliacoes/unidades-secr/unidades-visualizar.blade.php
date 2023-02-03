@extends('template.base')

@section('titulo', 'Unidades Secretaria')

@section('content')
    <div class="d-flex justify-content-between">
        <div>
            <h1 class="text-primary">
                {{ $unidadeObj->nome }}
                @if (!$unidadeObj->ativo)
                    <span class="text-danger"> (Inativo)</span>
                @endif
            </h1>
            <h5 class="text-secondary">
                {{ $unidadeObj->secretaria->nome }} - {{ $unidadeObj->secretaria->sigla }}
                @if (!$unidadeObj->secretaria->ativo)
                    <span class="text-danger"> (Inativo)</span>
                @endif
            </h5>
        </div>
        <hr>
        @can(permissionConstant()::UNIDADE_SECRETARIA_EDIT)
            <div class="d-flex align-items-center">
                <a href="{{ route('get-edit-unidade-view', ['id' => $unidadeObj->id]) }}" class="btn btn-warning">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Editar Unidade
                </a>
            </div>
        @endcan
    </div>
    <hr>

    <div class="row">
        <div class="col-md-8">
            <b>Nome:</b>
            <p class="border-2 border-bottom border-warning">
                {{ $unidadeObj->nome }}
            </p>
        </div>

        <div class="col-md-4">
            <b>Emitir QRcode:</b>
            <p class="border-2 border-bottom border-warning">
                @if (!$unidadeObj->secretaria->ativo || !$unidadeObj->ativo)
                    <span class="text-danger"> (Inativo)</span>
                @else
                    <a href="{{ route('get-qrcode-unidade-secr', $unidadeObj) }}" target="_blank">
                        Abrir
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                @endif
            </p>
        </div>

        <div class="col-md-8">
            <b>Secretaria:</b>
            <p class="border-2 border-bottom border-warning">
                {{ $unidadeObj->secretaria->sigla }} -
                {{ $unidadeObj->secretaria->nome }}
            </p>
        </div>
        <div class="col-md-4">
            <b>Situação:</b>
            <p class="border-2 border-bottom border-warning">
                {{ $unidadeObj->ativo ? 'Ativo' : 'Inativo' }}
                @if ($unidadeObj->ativo)
                    <i class="text-success fa-solid fa-circle-check"></i>
                @else
                    <i class="text-danger fa-solid fa-circle-xmark"></i>
                @endif
            </p>
        </div>
    </div>
    @if (!is_null($unidadeObj->descricao))
        <div class="col-md-12">
            <b>Descrição:</b>
            <p class="border-2 border border-warning p-2">
                {{ $unidadeObj->descricao }}
            </p>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <b>Data de Criação:</b>
            <p class="border-2 border-bottom border-warning">
                {{ formatarDataHora($unidadeObj->created_at) }}
            </p>
        </div>
        <div class="col-md-4">
            <b>Ultima Atualização:</b>
            <p class="border-2 border-bottom border-warning">
                {{ formatarDataHora($unidadeObj->updated_at) }}
            </p>
        </div>
    </div>

    <div class="mt-3">
        <div class="d-flex justify-content-between">
            <h4 class="text-primary">
                Setores
            </h4>
            @can(permissionConstant()::SETOR_CREATE)
                <button id="btnCriarSetor" class="btn btn-primary" data-bs-backdrop="static">
                    <i class="fa-solid fa-plus"></i>
                </button>
            @endcan
        </div>
        <hr>
    </div>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th class="fw-bold text-center col-md-1">Ativo</th>
                    <th class="fw-bold">Nome</th>
                    <th class="fw-bold text-end">Ações</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">

                @foreach ($unidadeObj->setores as $setor)
                    <tr id="setor-{{ $setor->id }}">
                        <td class="text-center">
                            <a href="{{ route('get-toggle-setor-status', $setor) }}">
                                @if ($setor->ativo)
                                    <i class="text-success fa-solid fa-circle-check"></i>
                                @else
                                    <i class="text-danger fa-solid fa-circle-xmark"></i>
                                @endif
                            </a>
                        </td>
                        <td id="setor-{{ $setor->id }}-nome">
                            {{ $setor->nome }}
                        </td>
                        <td class="text-end">
                            @can(permissionConstant()::SETOR_VIEW)
                                <button class="btnView btn" data-id="{{ $setor->id }}">
                                    <i class="fa-xl text-primary fa-solid fa-magnifying-glass"></i>
                                </button>
                            @endcan
                            @can(permissionConstant()::SETOR_EDIT)
                                <form class="d-none" id="updateSetor_{{ $setor->id }}"
                                    action="{{ route('patch-update-setor', $setor) }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('PATCH') }}
                                    <input id="edit_name_{{ $setor->id }}" type="hidden" name="nome">
                                    <div id="tipos_avaliacao_setor_{{ $setor->id }}"></div>
                                </form>
                                <button class="btnEditarSetor btn" data-id="{{ $setor->id }}">
                                    <i class="fa-xl text-warning fa-solid fa-pen-to-square"></i>
                                </button>
                            @endcan
                            @if (!$setor->principal)
                                @can(permissionConstant()::SETOR_DELETE)
                                    <form class="d-none" id="deleteSetor_{{ $setor->id }}"
                                        action="{{ route('delete-delete-setor', $setor) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                    </form>
                                    <button class="btnDelete btn" data-id="{{ $setor->id }}">
                                        <i class="fa-xl text-danger fa-solid fa-trash"></i>
                                    </button>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        <a class="btn btn-warning" href="{{ route('get-unidades-secr-list') }}">
            <i class="fa-solid fa-chevron-left"></i>
            Voltar
        </a>
    </div>

    @can(permissionConstant()::SETOR_CREATE)
        {{-- Criar Setor --}}
        <div class="modal fade" id="CriarSetor" tabindex="-1" aria-labelledby="CriarSetorLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="CriarSetorLabel">Criar Novo Setor</h1>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="novoSetor" action="{{ route('post-store-setor', $unidadeObj) }}" method="post">
                            {{ csrf_field() }}
                            <div class="col-md-12">
                                <label for="nome" class="form-label fw-bold">Nome:</label>
                                <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome">
                            </div>

                            <div id="tipos_avaliacao"></div>
                        </form>
                        <div class="mt-3">
                            <h5 class="text-primary">
                                Tipos de Avaliação
                            </h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="fw-bold" for="">Tipos de avaliação:</label>
                                    <select id="tipos_avaliacao_select" class="form-select">
                                        <option value="">Selecione</option>
                                        @foreach ($unidadeObj->secretaria->tiposAvaliacao as $tipoAvaliacao)
                                            <option value="{{ $tipoAvaliacao->id }}">{{ $tipoAvaliacao->nome }}</option>
                                        @endforeach
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
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                        <button id="btnSubmitCriarSetor" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i>
                            Criar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can(permissionConstant()::SETOR_VIEW)
        {{-- Visualizar Setor --}}
        <div class="modal fade" id="visualizarSetor" tabindex="-1" aria-labelledby="visualizarSetorLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="visualizarSetorLabel">Informações Setor</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-8">
                            <b>Nome:</b>
                            <p id="viewNome" class="border-2 border-bottom border-warning"></p>
                        </div>
                        <div class="">
                            <h5 class="text-primary">
                                Tipos de Avaliação
                            </h5>

                            <div>
                                <ul id="view_tipos_avaliacao_list" class="list-group">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can(permissionConstant()::SETOR_EDIT)
        {{-- Editar Setor --}}
        <div class="modal fade" id="editarSetor" tabindex="-1" aria-labelledby="editarSetorLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h1 class="modal-title fs-5" id="editarSetorLabel">Editar Setor</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Nome:</label>
                            <input id="editNome" type="text" class="form-control" name="nome" placeholder="Nome">
                            <input type="hidden" id="edit_id">
                        </div>
                        <div class="mt-3">
                            <h5 class="text-primary">
                                Tipos de Avaliação
                            </h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="fw-bold" for="">Tipos de avaliação:</label>
                                    <select id="edit_tipos_avaliacao_select" class="form-select">
                                        <option value="">Selecione</option>
                                        @foreach ($unidadeObj->secretaria->tiposAvaliacao as $tipoAvaliacao)
                                            <option value="{{ $tipoAvaliacao->id }}">{{ $tipoAvaliacao->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-8">
                                <ul id="tipos_avaliacao_edit_list" class="list-group">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                            Cancelar
                        </button>

                        <button id="btnSubmitEditarSetor" class="btn btn-warning">
                            <i class="fa-solid fa-pen-to-square"></i>
                            Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can(permissionConstant()::SETOR_DELETE)
        {{-- Deletar Setor --}}
        <div id="deleteModal" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-light">Deletar Setor!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Deseja realmente deletar o Setor: <span id="deleteSetorName" class="fw-bold"></span></p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">cancelar</button>
                        <button id="btnDeleteConfirm" type="button" class="btn btn-danger">deletar</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

@endsection

@push('scripts')
    <script nonce="{{ app('csp-nonce') }}">
        tiposAvaliacao = [
            @foreach ($unidadeObj->secretaria->tiposAvaliacao as $tipoAvaliacao)
                {
                    id: {{ $tipoAvaliacao->id }},
                    nome: '{{ $tipoAvaliacao->nome }}',
                    padrao: {{ $tipoAvaliacao->default ? 'true' : 'false' }},
                },
            @endforeach
        ];

        function insertItemList(position, text, inputSelect = 'tipos_avaliacao', listaSelect = 'tipos_avaliacao_list',
            inputClass = 'tipos_avaliacao_') {
            $('#' + inputSelect).append(`
            <input class="` + inputClass + position + `" type="hidden" name="tipos_avaliacao[]"
                value="` + position + `">
            `);
            $("#" + listaSelect).append(`
            <li id="list_` + position + `" class="list-group-item d-flex justify-content-between">
                <div class="d-flex align-items-center">` +
                text + `
                </div>
                <a class="deleteTipoAvaliacao btn" data-id="` + position + `">
                    <i class="fa-lg text-danger fa-solid fa-trash"></i>
                </a>
            </li>
            `);
            $("#" + listaSelect).removeClass('d-none');
        }

        function removerTipoAvaliacao(id, inputSelect = 'tipos_avaliacao', listaSelect = 'list') {
            $('.' + inputSelect + '_' + id).remove();
            $('#' + listaSelect + '_' + id).remove();
        }

        @can(permissionConstant()::SETOR_CREATE)
            $('#btnCriarSetor').click(function() {
                $('#tipos_avaliacao_list').empty();
                $('#tipos_avaliacao').empty();
                tiposAvaliacao.forEach(function(tipo) {
                    if (tipo.padrao) {
                        insertItemList(tipo.id, tipo.nome);
                    }
                });
                $('#CriarSetor').modal('show');
            });

            $("#tipos_avaliacao_select").change(function() {
                if ($('.tipos_avaliacao_' + $("#tipos_avaliacao_select").val()).length == 0) {
                    insertItemList($("#tipos_avaliacao_select").val(), $("#tipos_avaliacao_select option:selected")
                        .text());
                }
                $("#tipos_avaliacao_select").val('');
            });

            $('#tipos_avaliacao_list').on('click', '.deleteTipoAvaliacao', function($this) {
                $this.stopPropagation();
                removerTipoAvaliacao($(this).data('id'));
            });

            $('#btnSubmitCriarSetor').click(function() {
                $('#novoSetor').submit();
            });
        @endcan

        $('.btnView').click(function($this) {
            let url = "{{ route('get-tipos-avaliacao-setor', 'setorId') }}";
            url = url.replace('setorId', $(this).data('id'));
            $.ajax({
                url: url,
                success: function(response) {
                    $('#viewNome').text(response.nome);
                    $('#view_tipos_avaliacao_list').empty();
                    $('#visualizarSetor').modal('show');
                    if (response.tipos_avaliacao.length > 0) {
                        response.tipos_avaliacao.forEach(function(tipo) {
                            $('#view_tipos_avaliacao_list').append(`
                            <li class="list-group-item">` + tipo.nome + `</li>
                        `);
                        })
                    } else {
                        $('#view_tipos_avaliacao_list').append(`
                            <li class="list-group-item list-group-item-warning">
                                Nenhum tipo Avalição cadastrado para este setor.
                            </li>
                        `);
                    }
                }
            });
        });

        @can(permissionConstant()::SETOR_EDIT)
            $('.btnEditarSetor').click(function($this) {
                id = $(this).data('id');
                $("#edit_id").val(id);

                $('#tipos_avaliacao_edit_list').empty();
                $('#tipos_avaliacao_setor_' + id).empty();

                let url = "{{ route('get-tipos-avaliacao-setor', 'setorId') }}";
                url = url.replace('setorId', id);
                $.ajax({
                    url: url,
                    success: function(response) {
                        $('#editNome').val(response.nome);
                        if (response.tipos_avaliacao.length > 0) {
                            response.tipos_avaliacao.forEach(function(tipo) {
                                insertItemList(tipo.id, tipo.nome, 'tipos_avaliacao_setor_' +
                                    id,
                                    'tipos_avaliacao_edit_list', 'tipos_avaliacao_edit_');
                            })
                        } else {
                            $('#tipos_avaliacao_edit_list').append(`
                                <li id="emptyTypes_` + response.id + `" class="list-group-item list-group-item-warning">
                                    Nenhum tipo Avalição cadastrado para este setor.
                                </li>
                            `);
                        }
                        $('#editarSetor').modal('show');
                    }
                });
            });

            $("#edit_tipos_avaliacao_select").change(function() {
                item = $("#edit_tipos_avaliacao_select").val();
                id = $("#edit_id").val();
                $("#emptyTypes_" + id).remove();
                if ($('.tipos_avaliacao_edit_' + item).length == 0) {
                    insertItemList(item, $("#edit_tipos_avaliacao_select option:selected").text(),
                        'tipos_avaliacao_setor_' + id,
                        'tipos_avaliacao_edit_list', 'tipos_avaliacao_edit_');
                }
                $("#edit_tipos_avaliacao_select").val('');
            });

            $('#tipos_avaliacao_edit_list').on('click', '.deleteTipoAvaliacao', function($this) {
                $this.stopPropagation();
                id = $(this).data('id');
                removerTipoAvaliacao(id, 'tipos_avaliacao_edit');
            });

            $("#btnSubmitEditarSetor").click(function() {
                id = $("#edit_id").val();
                $("#edit_name_" + id).val($("#editNome").val());
                $("#updateSetor_" + id).submit();
            });
        @endcan

        @can(permissionConstant()::SETOR_DELETE)
            var setorId = 0;

            $('.btnDelete').click(function() {
                deleteRole($(this).data('id'));
            });

            function deleteRole(id) {
                $("#deleteSetorName").text($("#setor-" + id + "-nome").text());
                $('#deleteModal').modal('show');
                setorId = id;
            }

            $("#btnDeleteConfirm").click(function() {
                $('#deleteModal').modal('hide');
                $('#deleteSetor_' + setorId).submit();
                setorId = 0;
            });
        @endcan
    </script>
@endpush
