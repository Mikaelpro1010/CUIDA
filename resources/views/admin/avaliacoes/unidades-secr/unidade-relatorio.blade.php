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
        <h5>Total de Avaliações: {{ $totalAvaliacoes }}</h5>
    </div>
    <hr>

    <form action="{{ route('get-unidades-relatorio', $unidade) }}" method="GET">
        <div class="row">
            <div class="col-3">
                <label for="mes_pesq" class="fw-bold">Mês</label>
                <select name="mes_pesq" class="form-select" id="mes_pesq">
                    <option value="">Selecione</option>
                    <option value="1">Janeiro</option>
                    <option value="2">Fevreiro</option>
                    <option value="3">Março</option>
                    <option value="4">Abril</option>
                    <option value="5">Maio</option>
                    <option value="6">Junho</option>
                    <option value="7">Julho</option>
                    <option value="8">Agosto</option>
                    <option value="9">Setembro</option>
                    <option value="10">Outubro</option>
                    <option value="11">Novembro</option>
                    <option value="12">Dezembro</option>
                </select>
            </div>
    
            <div class="col-3">
                <label for="ano_pesq" class="fw-bold">Ano</label>
                <select name="ano_pesq" class="form-select" id="ano_pesq">
                    @for ($ano = 2021; $ano <= now()->format('Y') ;$ano++)
                        <option value="{{$ano}}" @if ($ano == now()->format('Y')) selected @endif>{{$ano}}</option>
                    @endfor
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

    {{-- <div class="col-md-3">
    </div> --}}

    <div class="table-responsive mt-3">
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
    $('#btnLimpaForm').click(function() {
        $('#mes_pesq').val('');
        $('#ano_pesq').val({{now()->format('Y')}});
    });
    </script>
@endpush
