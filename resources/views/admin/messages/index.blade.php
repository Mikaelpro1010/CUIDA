@extends('template.base')

@section('titulo', 'EscutaSol - Mensagens')
@section('content')
<div>

    <div class="d-sm-grid d-md-flex justify-content-between d-flex align-items-center">
        <h3 class="d-flex align-self-middle">Mensagens</h3>
        <div class="m-0 p-0 row gap-2">
            <a alt="abertos" href="{{ route('mensagens') }}" class="btn btn-info col-md-auto">
                <p class="m-0 p-0">
                    Chats Aberto(s):
                </p>
                <b>{{ $totalCanaisMsg }}</b>
            </a>
            <a href="{{ route('mensagens', 
            [
                'protocolo' => request()->protocolo, 
                'data_inicio' => request()->data_inicio,  
                'data_fim' => request()->data_fim, 
                'status' => 1
            ]) }}" class="btn btn-warning col-md-auto">
                <p class="m-0 p-0">
                    Respondido(s):
                </p>
                <b>{{ $respondido }}</b>
            </a>

            <a href="{{ route('mensagens', 
            [
                'protocolo' => request()->protocolo, 
                'data_inicio' => request()->data_inicio,  
                'data_fim' => request()->data_fim, 
                'status' => 2
            ]) }}" class="btn btn-danger col-md-auto">
                <p class="m-0 p-0">
                    Aguardando Resposta(s):
                </p>
                <b>{{ $aguardandoResposta }}</b>
            </a>
            <a href="{{ route('mensagens', 
            [
                'protocolo' => request()->protocolo, 
                'data_inicio' => request()->data_inicio,  
                'data_fim' => request()->data_fim, 
                'status' => 3
            ]) }}" class="btn btn-success col-md-auto">
                <p class="m-0 p-0">
                    Encerrado(s):
                </p>
                <b>{{ $encerrado }}</b>
            </a>
        </div>
    </div>
    <hr>

    <form class="m-0 p-0 row" action="{{ route('mensagens') }}" method="GET" id="filtroCanaisMensagem">
        <div class="m-0 p-0 row col-md-9">
            <div class="col-md-3">
                <label for="protocolo">Protocolo:</label>
                <input id="protocolo" class="form-control" type="text" name="protocolo" placeholder="Pesquisar"
                    value="{{ request()->protocolo }}">
            </div>
            <div class="col-md-3">
                <label for="data-inicio">Data Inicio:</label>
                <input id="data-inicio" class="form-control" type="date" name="data_inicio" max="{{ dateAndFormat() }}"
                    value="@if (request()->data_inicio){{ formatData(request()->data_inicio) }}@endif">
            </div>
            <div class="col-md-3">
                <label for="data-fim">Data Fim:</label>
                <input id="data-fim" class="form-control" type="date" name="data_fim" max="{{ dateAndFormat() }}"
                    value="@if (request()->data_fim){{ formatData(request()->data_fim) }}@endif">
            </div>
            <div class="col-md-3">
                <label for="status">Status:</label>
                <select id="status" class="form-select" name="status">
                    <option value="" @if (is_null(request()->status)) selected @endif>Selecione</option>
                    @foreach ( canalMensagem()::STATUS_CANAL_MSG as $statusId => $statusName )
                    <option value="{{ $statusId }}" @if (request()->status == $statusId) selected @endif>
                        {{$statusName}}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="m-0 p-0 row col-md-3">
            <div class="col-7 col-md-7 d-flex align-items-end">
                <button class="btn btn-success form-control mt-3" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Buscar
                </button>
            </div>
            <div class="col-5 col-md-5 d-flex align-items-end">
                <a class="btn btn-warning form-control mt-3" onclick="limparForm()">
                    Limpar
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </div>
    </form>

    <div class="table-responsive mt-3">
        <table class="table table-sm table-striped align-middle">
            <thead>
                <tr>
                    <th class="text-center">Protocolo</th>
                    <th>Autor</th>
                    <th>Primeira Msg</th>
                    <th>Ultima Msg</th>
                    <th>Dias Passados</th>
                    <th>Status</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @if (isset($canaisMensagem))
                @foreach ( $canaisMensagem as $canalMensagem )
                <tr>
                    <th class="text-center">
                        {{ $canalMensagem->manifestacao->protocolo }}
                    </th>
                    <td class="">{{ $canalMensagem->manifestacao->autor->name }}</td>
                    <td class="">
                        {{ formatarDataHora($canalMensagem->created_at) }}
                    </td>
                    <td class="">
                        {{ formatarDataHora($canalMensagem->updated_at) }}
                    </td>
                    <td class="">
                        {{ carbonDiffInDays($canalMensagem->updated_at) }}
                        dia(s)
                        {{ carbonDiffInHoursMinusDays($canalMensagem->updated_at) }}
                        horas(s)
                        {{ carbonDiffInMinutesMinusHours($canalMensagem->updated_at) }}
                        minutos(s)
                    </td>
                    <td class="
                        @if ($canalMensagem->id_status == canalMensagem()::STATUS_ENCERRADO)
                        table-success
                        @endif
                        @if ($canalMensagem->id_status == canalMensagem()::STATUS_AGUARDANDO_RESPOSTA)
                        table-danger
                        @endif
                        @if ($canalMensagem->id_status == canalMensagem()::STATUS_RESPONDIDO)
                        table-warning 
                        @endif
                    ">
                        {{ canalMensagem()::STATUS_CANAL_MSG[$canalMensagem->id_status] }}
                    </td>
                    <td class=" text-center">
                        <a href="{{ route('visualizarMsg', $canalMensagem->id) }}" class="btn btn-primary">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td class="text-center" colspan="7">Não existem Resultados!</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-evenly">
        {{ $canaisMensagem->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    function limparForm(){
        $('#protocolo').val('');
        $('#data-inicio').val('');
        $('#data-fim').val('');
        $('#status').val(0);
    }
</script>
@endpush