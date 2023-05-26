@extends('template.base')

@section('titulo', 'Comentários das Avaliações')

@section('content')
    <div class="d-flex justify-content-between">
        <h1 class="text-primary fs-3">Comentarios das Avaliações({{ $avaliacoes->total() }})</h1>
    </div>
    <hr>
    <form class="" action="{{ route('get-comentarios-avaliacoes-list') }}" method="GET">
        <div class="d-flex flex-wrap gap-2">
            <div class="flex-fill">
                <label class="fw-bold" for="pesquisa">Unidade/Setor:</label>
                <input id="pesquisa_unidade_setor" class="form-control" type="text" name="pesquisa_unidade_setor"
                    placeholder="Nome da Unidade/Setor" value="{{ request()->pesquisa_unidade_setor }}">
            </div>

            <div class="flex-fill">
                <label class="fw-bold" for="secretaria_pesq">Secretaria:</label>
                <select id="secretaria_pesq" class="form-select" name="secretaria_pesq">
                    <option value="" @if (is_null(request()->secretaria_pesq)) selected @endif>Selecione</option>
                    @foreach ($secretariasSearchSelect as $secretaria)
                        <option value="{{ $secretaria->id }}" @if (request()->secretaria_pesq == $secretaria->id) selected @endif>
                            {{ $secretaria->sigla . ' - ' . $secretaria->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">

            <div class="flex-grow-1 @if (is_null(request()->secretaria_pesq) && is_null(request()->tipo_avaliacao)) d-none @endif">
                <label class="fw-bold" for="tipo_avaliacao">Tipo de Avaliação:</label>
                <select id="tipo_avaliacao" class="form-select" name="tipo_avaliacao">
                    <option value="" @if (is_null(request()->tipo_avaliacao)) selected @endif>Selecione</option>
                    @foreach ($tiposAvaliacao as $tipo)
                        <option value="{{ $tipo->id }}" @if (request()->tipo_avaliacao == $tipo->id) selected @endif>
                            {{ $tipo->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-fill @if (is_null(request()->secretaria_pesq) && is_null(request()->unidade_pesq)) d-none @endif">
                <label class="fw-bold" for="unidade_pesq">Unidade:</label>
                <select id="unidade_pesq" class="form-select" name="unidade_pesq">
                    <option value="" @if (is_null(request()->unidade_pesq)) selected @endif>Selecione</option>
                    @foreach ($unidades as $unidade)
                        <option value="{{ $unidade->id }}" @if (request()->unidade_pesq == $unidade->id) selected @endif>
                            {{ $unidade->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-grow-1 @if (is_null(request()->unidade_pesq) && is_null(request()->setor_pesq)) d-none @endif">
                <label class="fw-bold" for="setor_pesq">Setores:</label>
                <select id="setor_pesq" class="form-select" name="setor_pesq">
                    <option value="" @if (is_null(request()->setor_pesq)) selected @endif>Selecione</option>
                    @foreach ($setores as $setor)
                        <option value="{{ $setor->id }}" @if (request()->setor_pesq == $setor->id) selected @endif>
                            {{ $setor->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <div class="flex-fill w-sm-100">
                <label class="fw-bold" for="notas_select">Notas:</label>
                <div class="">
                    <button id="notas_select" class="form-select text-decoration-none text-start" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        @switch(request()->pesq_nota)
                            @case(2)
                                <span class="text-danger">
                                    <i class="fa-regular fa-face-angry"></i> - Muito Ruim
                                </span>
                            @break

                            @case(4)
                                <span class="text-warning">
                                    <i class="fa-regular fa-face-frown"></i> - Ruim
                                </span>
                            @break

                            @case(6)
                                <span class="text-info">
                                    <i class="fa-regular fa-face-meh"></i> - Neutro
                                </span>
                            @break

                            @case(8)
                                <span class="text-primary">
                                    <i class="fa-regular fa-face-smile"></i> - Bom
                                </span>
                            @break

                            @case(10)
                                <span class="text-success">
                                    <i class="fa-regular fa-face-laugh-beam"></i> - Muito Bom
                                </span>
                            @break

                            @default
                                Todas as Notas
                        @endswitch
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <button class="dropdown-item nota" data-nota=''>
                                <span>
                                    Todas as Notas
                                </span>
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item nota" data-nota='2'>
                                <span class="text-danger">
                                    <i class="fa-regular fa-face-angry"></i> - Muito Ruim
                                </span>
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item nota" data-nota='4'>
                                <span class="text-warning">
                                    <i class="fa-regular fa-face-frown"></i> - Ruim
                                </span>
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item nota" data-nota='6'>
                                <span class="text-info">
                                    <i class="fa-regular fa-face-meh"></i> - Neutro
                                </span>
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item nota" data-nota='8'>
                                <span class="text-primary">
                                    <i class="fa-regular fa-face-smile"></i> - Bom
                                </span>
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item nota" data-nota='10'>
                                <span class="text-success">
                                    <i class="fa-regular fa-face-laugh-beam"></i> - Muito Bom
                                </span>
                            </button>
                        </li>
                    </ul>
                </div>
                <input id="pesq_nota" type="hidden" name="pesq_nota" value="{{ request()->pesq_nota }}">
            </div>

            <div class="flex-grow-1">
                <div class="d-flex flex-wrap gap-2">
                    <div class="flex-fill">
                        <label class="fw-bold" for="data_inicial">Data Inicial:</label>
                        <input class="form-control" id="data_inicial" type="date" name="data_inicial"
                            min="2023-01-01" max="{{ now()->format('Y-m-d') }}" value="{{ request()->data_inicial }}">
                    </div>
                    <div class="flex-fill">
                        <label class="fw-bold" for="data_final">Data Final:</label>
                        <input class="form-control" id="data_final" type="date" name="data_final" min="2023-01-01"
                            max="{{ now()->format('Y-m-d') }}" value="{{ request()->data_final }}">
                    </div>
                </div>
            </div>

            <div class="flex-grow-1">
                <div class="d-flex flex-wrap h-100 ">
                    <div class="flex-grow-1 d-flex justify-content-center">
                        <div class="form-check form-switch align-self-end mb-2">
                            <input class="form-check-input days-filter" type="checkbox" role="switch" id="thisYear"
                                name='thisYear' {{ request()->thisYear ? 'checked' : '' }}>
                            <label class="form-check-label" for="thisYear">Este Ano</label>
                        </div>
                    </div>

                    <div class="flex-grow-1 d-flex justify-content-center">
                        <div class="form-check form-switch align-self-end mb-2">
                            <input class="form-check-input days-filter" type="checkbox" role="switch" id="last_30days"
                                name='last_30days' {{ request()->last_30days ? 'checked' : '' }}>
                            <label class="form-check-label" for="last_30days">Últimos 30 dias</label>
                        </div>
                    </div>

                    <div class="flex-grow-1 d-flex justify-content-center">
                        <div class="form-check form-switch  align-self-end mb-2">
                            <input class="form-check-input days-filter" type="checkbox" role="switch" id="last_7days"
                                name='last_7days' {{ request()->last_7days ? 'checked' : '' }}>
                            <label class="form-check-label" for="last_7days">Últimos 7 dias</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-end">
            @can(permissionConstant()::GERENCIAR_COMENTARIOS_AVALIACOES_EXPORT)
                <div class="col-md-3 align-self-end">
                    <a id="exportar" class="btn btn-success form-control mt-3" href="#">
                        <i class="fa-solid fa-file-excel"></i>
                        Exportar para Excel
                    </a>
                </div>
            @endcan
            <div class="col-md-2 align-self-end">
                <button class="btn btn-primary form-control mt-3" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Buscar
                </button>
            </div>
            <div class="col-md-2 align-self-end">
                <a id="btnLimpaForm" class="btn btn-warning form-control mt-3">
                    Limpar
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-sm table-striped mt-3 align-middle">
            <thead>
                <tr>
                    <th>Secretarias</th>
                    <th>Unidade</th>
                    <th>Setor</th>
                    <th>Notas</th>
                    <th>Data</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @forelse ($avaliacoes as $avaliacao)
                    <tr>
                        <td>
                            {{ $avaliacao->setor->unidade->secretaria->sigla . ' - ' . $avaliacao->setor->unidade->secretaria->nome }}
                        </td>
                        <td>
                            {{ $avaliacao->setor->unidade->nome }}
                        </td>
                        <td>
                            {{ $avaliacao->setor->nome }}
                        </td>
                        <td>
                            @switch($avaliacao->nota)
                                @case(2)
                                    <span class="text-danger">
                                        <i class="fa-regular fa-face-angry"></i> - Muito Ruim
                                    </span>
                                @break

                                @case(4)
                                    <span class="text-warning">
                                        <i class="fa-regular fa-face-frown"></i> - Ruim
                                    </span>
                                @break

                                @case(6)
                                    <span class="text-info">
                                        <i class="fa-regular fa-face-meh"></i> - Neutro
                                    </span>
                                @break

                                @case(8)
                                    <span class="text-primary">
                                        <i class="fa-regular fa-face-smile"></i> - Bom
                                    </span>
                                @break

                                @case(10)
                                    <span class="text-success">
                                        <i class="fa-regular fa-face-laugh-beam"></i> - Muito Bom
                                    </span>
                                @break
                            @endswitch
                        </td>
                        <td>
                            {{ formatarDataHora($avaliacao->created_at) }}
                        </td>
                        <td class="col-md-1">
                            <div class="d-flex justify-content-evenly">
                                <a class="btn text-primary see-comment" href="#" data-id="{{ $avaliacao->id }}">
                                    <i class="fa-xl fa-solid fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr id="{{ $avaliacao->id }}" class="d-none">
                        <td colspan='6'>
                            <label class="fw-bold">Comentario:</label>
                            <div class="border-2
                                border border-warning p-2">
                                {{ $avaliacao->comentario }}
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
        </div>

        <div class='mx-auto d-md-none'>
            {{ $avaliacoes->links('pagination::simple-bootstrap-4') }}
        </div>
        <div class='mx-auto d-none d-md-block'>
            {{ $avaliacoes->links('pagination::bootstrap-4') }}
        </div>

    @endsection

    @push('scripts')
        <script nonce="{{ app('csp-nonce') }}">
            window.addEventListener('load', function() {
                document.getElementById('pesquisa_unidade_setor').value = '';
                document.getElementById('secretaria_pesq').selectedIndex = -1;
            });
        </script>
    @endpush

    @push('scripts')
        <script nonce="{{ app('csp-nonce') }}">
            $('#btnLimpaForm').click(function() {
                $('#pesquisa_unidade_setor').val('');
                $('#pesq_nota').val('');
                $('#secretaria_pesq').val('');
            });

            $('.nota').click(function(e) {
                e.preventDefault();
                $('#pesq_nota').val($(this).data('nota'));

                switch ($(this).data('nota')) {
                    case (''):
                        $("#notas_select").html(`Todas as Notas`);
                        break;
                    case (2):
                        $("#notas_select").html(
                            `<span class="text-danger">
                        <i class="fa-regular fa-face-angry"></i> - Muito Ruim
                    </span>`
                        );
                        break;
                    case (4):
                        $("#notas_select").html(
                            `<span class="text-warning">
                        <i class="fa-regular fa-face-frown"></i> - Ruim
                    </span>`
                        );
                        break;
                    case (6):
                        $("#notas_select").html(
                            `<span class="text-info">
                        <i class="fa-regular fa-face-meh"></i> - Neutro
                    </span>`
                        );
                        break;
                    case (8):
                        $("#notas_select").html(
                            `<span class="text-primary">
                        <i class="fa-regular fa-face-smile"></i> - Bom
                    </span>`
                        );
                        break;
                    case (10):
                        $("#notas_select").html(
                            `<span class="text-success">
                        <i class="fa-regular fa-face-laugh-beam"></i> - Muito Bom
                    </span>`
                        );
                        break;
                }

            });

            $('#exportar').click(function(e) {
                e.preventDefault();
                let url = "{{ route('get-comentarios-avaliacoes-export') }}";
                let pesquisa_unidade_setor = $('#pesquisa_unidade_setor').val();
                let pesq_nota = $('#pesq_nota').val();
                let secretaria_pesq = $('#secretaria_pesq').val();
                let tipo_avaliacao = $('#tipo_avaliacao').val();
                let unidade_pesq = $('#unidade_pesq').val();
                let setor_pesq = $('#setor_pesq').val();
                let data_inicial = $('#data_inicial').val();
                let data_final = $('#data_final').val();

                window.open(url + '?pesquisa_unidade_setor=' + pesquisa_unidade_setor + '&pesq_nota=' + pesq_nota +
                    '&secretaria_pesq=' + secretaria_pesq + '&tipo_avaliacao=' + tipo_avaliacao + '&unidade_pesq=' +
                    unidade_pesq + '&setor_pesq=' + setor_pesq + '&data_inicial=' + data_inicial + '&data_final=' +
                    data_final, '_blank')
            });

            $(document).ready(function() {
                $('.see-comment').click(function(e) {
                    e.preventDefault();
                    var id = $(this).data('id');
                    $('#' + id).toggleClass('d-none');
                });
            });

            let dataFilters = {
                'last_7days': '{{ now()->subDays(7)->format('Y-m-d') }}',
                'last_30days': '{{ now()->subDays(30)->format('Y-m-d') }}',
                'thisYear': '{{ now()->format('Y') }}-01-01',
            };

            $('.days-filter').click(function() {
                id = $(this).attr('id');
                $('.days-filter').each(function() {
                    if ($(this).is(':checked') && $(this).attr('id') != id) {
                        $(this).prop('checked', false);
                    }
                });
                if ($(this).is(':checked')) {
                    $('#data_inicial').val(dataFilters[id]);
                    $('#data_final').val('{{ now()->format('Y-m-d') }}');
                } else {
                    $('#data_inicial').val('');
                    $('#data_final').val('');
                }
            });

            $('#secretaria_pesq').change(function() {
                ajaxUnidades($(this).val());
            });

            $('#unidade_pesq').change(function() {
                ajaxSetores($(this).val());
            });

            function ajaxUnidades(secretaria_id) {
                $.ajax({
                    url: "{{ route('get-comentarios-scretaria-info') }}",
                    type: 'GET',
                    data: {
                        secretaria_id: secretaria_id,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#unidade_pesq').html('<option value="">Selecione</option>');
                        $.each(data.unidades, function(key, value) {
                            $('#unidade_pesq').append('<option value=' + value.id + '>' + value
                                .nome +
                                '</option>');
                        });
                        $('#unidade_pesq').parent().removeClass('d-none');
                        $('#tipo_avaliacao').html('<option value="">Selecione</option>');
                        $.each(data.tipo_avaliacao, function(key, value) {
                            $('#tipo_avaliacao').append('<option value=' + value.id + '>' + value
                                .nome +
                                '</option>');
                        });
                        $('#tipo_avaliacao').parent().removeClass('d-none');
                        $('#setor_pesq').html('<option value="">Selecione</option>');
                    }
                });
            }

            function ajaxSetores(unidade_id) {
                $.ajax({
                    url: "{{ route('get-comentarios-setores-info') }}",
                    type: 'GET',
                    data: {
                        unidade_id: unidade_id,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#setor_pesq').html('<option value="">Selecione</option>');
                        $.each(data.setores, function(key, value) {
                            $('#setor_pesq').append('<option value=' + value.id + '>' + value.nome +
                                '</option>');
                        });
                        $('#setor_pesq').parent().removeClass('d-none');
                    }
                });
            }
        </script>
    @endpush
