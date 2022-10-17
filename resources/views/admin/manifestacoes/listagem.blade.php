@extends('template.base')

@section('titulo', 'EscutaSol - Manifestações')
@section('content')
<div>
    <div class="d-sm-grid d-md-flex justify-content-between d-flex align-items-center">
        <h3 class="d-flex align-self-middle">Manifestações</h3>
    </div>
    <hr>

    <form class="" action="{{ route('manifestacoes') }}" method="GET" id="filtroCanaisMensagem">
        <div class="m-0 p-0 row">
            <div class="col-md-2">
                <label for="protocolo">Protocolo:</label>
                <input id="protocolo" class="form-control" type="text" name="protocolo" placeholder="Pesquisar"
                    value="{{ request()->protocolo }}">
            </div>
            <div class="col-md-2">
                <label for="data-inicio">Data Inicio:</label>
                <input id="data-inicio" class="form-control" type="date" name="data_inicio" max="{{ dateAndFormat() }}"
                    value="@if (request()->data_inicio){{ formatData(request()->data_inicio) }}@endif">
            </div>
            <div class="col-md-2">
                <label for="data-fim">Data Fim:</label>
                <input id="data-fim" class="form-control" type="date" name="data_fim" max="{{ dateAndFormat() }}"
                    value="@if (request()->data_fim){{ formatData(request()->data_fim) }}@endif">
            </div>

            <div class="col-md-3">
                <label for="motivacao">Motivação:</label>
                <select id="motivacao" class="form-select" name="motivacao">
                    <option value="" @if(is_null(request()->motivacao)) selected @endif >Selecione</option>
                    @foreach ( manifest()::MOTIVACAO as $motivacaoId => $motivacao )
                    <option value="{{ $motivacaoId }}" @if (request()->motivacao == $motivacaoId) selected @endif>
                        {{ $motivacao }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="tipo">Tipo:</label>
                <select id="tipo" class="form-select" name="tipo">
                    <option value="0" selected>Selecione</option>
                    @foreach ( manifest()::TIPO_MANIFESTACAO as $tipoId => $tipoNome )
                    <option value="{{ $tipoId }}" @if (request()->tipo == $tipoId) selected @endif>
                        {{$tipoNome}}
                    </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="m-0 p-0 row justify-content-between">
            <div class="col-md-6 row">
                <div class="col-md-6">
                    <label for="situacao">Situação:</label>
                    <select id="situacao" class="form-select" name="situacao">
                        <option value="0" selected>Selecione</option>
                        @foreach ( manifest()::SITUACAO as $situacaoId => $situacaoNome )
                        <option value="{{ $situacaoId }}" @if (request()->situacao == $situacaoId) selected @endif>
                            {{$situacaoNome}}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label for="estado_processo">Estado do Processo:</label>
                    <select id="estado_processo" class="form-select" name="estado_processo">
                        <option value="0" selected>Selecione</option>
                        @foreach ( manifest()::ESTADO_PROCESSO as $estadoId => $estadoProcesso )
                        <option value="{{ $estadoId }}" @if (request()->estado_processo == $estadoId) selected @endif>
                            {{$estadoProcesso}}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4 row">
                <div class="col-8 col-md-8 d-flex align-items-end">
                    <button class="btn btn-primary form-control mt-3" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Buscar
                    </button>
                </div>
                <div class="col-4 col-md-4 d-flex align-items-end">
                    <a class="btn btn-warning form-control mt-3" onclick="limparForm()">
                        Limpar
                        <i class="fa-solid fa-eraser"></i>
                    </a>
                </div>
            </div>
        </div>
    </form>

    <div class="table-responsive mt-3">
        <table class="table table-sm table-striped align-middle">
            <thead>
                <tr>
                    <th class="text-center">Protocolo</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                    <th>Data Abertura</th>
                    <th>Manifestante</th>
                    <th>Situação</th>
                    <th>Tipo de Manifestação</th>
                    <th>Prazo</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @if (isset($manifestacoes) && count($manifestacoes) > 0)
                @foreach ( $manifestacoes as $manifestacao )
                <tr>
                    <th class="text-center">
                        {{ $manifestacao->protocolo }}
                        /
                        {{ count($manifestacao->canalMensagem) }}
                    </th>
                    <td class="">
                        {{ manifest()::MOTIVACAO[$manifestacao->id_motivacao] }}
                    </td>
                    <td class="">
                        {{ manifest()::ESTADO_PROCESSO[$manifestacao->id_estado_processo] }}
                    </td>
                    <td class="">
                        {{ formatarDataHora($manifestacao->created_at) }}
                    </td>
                    <td class="">
                        {{ $manifestacao->autor->name }}
                    </td>
                    <td class="">
                        {{ manifest()::SITUACAO[$manifestacao->id_situacao] }}
                    </td>
                    <td class="">
                        {{ manifest()::TIPO_MANIFESTACAO[$manifestacao->id_tipo_manifestacao] }}
                    </td>
                    <td class="">
                        {{ carbonDiffInDays(carbon()::parse($manifestacao->created_at)->addDays(10)) }}
                        dia(s)
                        {{ carbonDiffInHoursMinusDays(carbon()::parse($manifestacao->created_at)->addDays(10 -
                        carbonDiffInDays(carbon()::parse($manifestacao->created_at)->addDays(10)))) }}
                        horas(s)
                        {{ carbonDiffInMinutesMinusHours(carbon()::parse($manifestacao->created_at)->addDays(10-
                        carbonDiffInHoursMinusDays(carbon()::parse($manifestacao->created_at)->addDays(10 -
                        carbonDiffInDays(carbon()::parse($manifestacao->created_at)->addDays(10)))))) }}
                        minutos(s)
                    </td>
                    <td class=" text-center">
                        <a href="{{ route('visualizarManifests', $manifestacao->id) }}" class="btn btn-primary">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td class="text-center" colspan="9">Não existem Resultados!</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-evenly">
        {{ $manifestacoes->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    function limparForm(){
        $('#protocolo').val('');
        $('#data-inicio').val('');
        $('#data-fim').val('');
        $('#motivacao').val(0);
        $('#tipo').val(0);
        $('#situacao').val(0);
        $('#estado_processo').val(0);
    }
</script>
@endpush