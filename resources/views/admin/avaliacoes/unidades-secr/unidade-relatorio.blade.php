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
