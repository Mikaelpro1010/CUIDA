@extends('template.base')

@section('titulo', 'Unidade Secretaria')

@section('content')
    <div class="d-flex justify-content-between">
        <div>
            <h1 class="text-primary">
                {{ $unidade->nome }}
                @if (!$unidade->ativo)
                    <span class="text-danger"> (Inativo)</span>
                @endif
            </h1>
            <h5 class="text-secondary">
                {{ $unidade->secretaria->nome }} - {{ $unidade->secretaria->sigla }}
                @if (!$unidade->secretaria->ativo)
                    <span class="text-danger"> (Inativo)</span>
                @endif
            </h5>
        </div>
    </div>
    <hr>

    <h5>Total de Avaliações: {{ $totalAvaliacoes }}</h5>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Setor</th>
                    <th class="text-center">
                        <div class="text-danger">
                            <i class="fa-2x fa-regular fa-face-angry"></i>
                            <br>
                            <span>Muito Ruim</span>
                        </div>
                    </th>
                    <th class="text-center">
                        <div class="text-warning">
                            <i class="fa-2x fa-regular fa-face-frown"></i>
                            <br>
                            <span>Ruim</span>
                        </div>
                    </th>
                    <th class="text-center">
                        <div class="text-info">
                            <i class="fa-2x fa-regular fa-face-meh"></i>
                            <br>
                            <span>Neutro</span>
                        </div>
                    </th>
                    <th class="text-center">
                        <div class="text-primary">
                            <i class="fa-2x fa-regular fa-face-smile"></i>
                            <br>
                            <span>Bom</span>
                        </div>
                    </th>
                    <th class="text-center">
                        <div class="text-success">
                            <i class="fa-2x fa-regular fa-face-laugh-beam"></i>
                            <br>
                            <span>Muito Bom</span>
                        </div>
                    </th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($setores as $setor)
                    <tr>
                        <td>
                            {{ $setor['nome'] }}
                        </td>
                        <td class="table-danger text-center">
                            {{ $setor[2] }}
                        </td>
                        <td class="table-warning text-center">
                            {{ $setor[4] }}
                        </td>
                        <td class="table-info text-center">
                            {{ $setor[6] }}
                        </td>
                        <td class="table-primary text-center">
                            {{ $setor[8] }}
                        </td>
                        <td class="table-success text-center">
                            {{ $setor[10] }}
                        </td>
                        <td class="text-center">
                            {{ $setor['total'] }}
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


@endsection

@push('scripts')
    <script nonce="{{ app('csp-nonce') }}">
        tiposAvaliacao = [
            @foreach ($unidade->secretaria->tiposAvaliacao as $tipoAvaliacao)
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
