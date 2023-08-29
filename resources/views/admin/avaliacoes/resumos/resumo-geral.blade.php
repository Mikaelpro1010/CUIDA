@extends('admin.avaliacoes.resumos.template.avaliacao')

@section('titulo', 'Resumo Geral')
@section('content')
    <h3 class="text-primary">Resumo Geral</h3>
    <hr>

    <div class="row">
        @component('admin.avaliacoes.resumos.components.total-avaliacoes', compact('qtdAvaliacoes', 'notas'))
        @endcomponent
        @component(
            'admin.avaliacoes.resumos.components.avaliacao-geral',
            compact('avaliacoesAverage', 'percentAverage', 'qtdAvaliacoes'))
            @slot('title')
                Avaliação Geral
            @endslot
        @endcomponent
    </div>

    <div class="row">
        <div class=" mt-3">
            <div class="card">
                <div class="card-header">
                    <h4>Maiores Notas Secretarias</h4>
                </div>
                @if (count($mediaAvaliacoes) > 0)
                    <div id="graphDiv" class="p-2">
                        <canvas id="mediaAvaliacoes" height="100px"></canvas>
                    </div>
                @else
                    <div class="m-3 alert alert-info">
                        <ul>
                            <li>Não existem avaliações</li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <div class="row">
        <div class=" mt-3">
            <div class="card">
                <div class="card-header">
                    <h4>Menores Notas Secretarias</h4>
                </div>
                @if (count($mediaAvaliacoesasc) > 0)
                    <div id="graphDiv" class="p-2">
                        <canvas id="mediaAvaliacoesasc" height="100px"></canvas>
                    </div>
                @else
                    <div class="m-3 alert alert-info">
                        <ul>
                            <li>Não existem avaliações</li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6 mt-3">
            <div class="card">
                <div class="card-header">
                    <h4>Top 5 melhores Unidades</h4>
                </div>
                <div class="px-2">
                    @if (count($top5BestUnidades) > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Média</th>
                                    <th>Avaliações (Qtd)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($top5BestUnidades as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('get-resumo-avaliacoes-unidade', ['secretaria' => $item['secretaria_id'], 'unidade' => $item['id']]) }}"
                                                target="_blank">
                                                {{ $item['nome'] }}
                                            </a>
                                        </td>
                                        <td>{{ number_format($item['nota'], 2, ',', '') }}</td>
                                        <td class="text-center">{{ $item['qtd'] }}</td>
                                    </tr>
                                    @if ($loop->iteration == 5)
                                        <tr>
                                            <td>...</td>
                                            <td>...</td>
                                            <td class="text-center">...</td>
                                        </tr>
                                    @break
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="m-3 alert alert-info">
                        <ul>
                            <li>As Unidades ainda não possuem nota</li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6 mt-3">
        <div class="card">
            <div class="card-header">
                <h4>Top 5 melhores Secretarias</h4>
            </div>
            <div class="px-2">
                <table class="table">
                    <tbody>
                        @forelse ($bestSecretarias as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('get-resumo-avaliacoes-secretaria', $item['id']) }}"
                                        target="_blank">
                                        {{ $item['nome'] }}
                                    </a>
                                </td>
                                <td>{{ number_format($item['nota'], 2, ',', '') }}</td>
                            </tr>
                            @if ($loop->iteration == 5)
                                <tr>
                                    <td>...</td>
                                    <td>...</td>
                                </tr>
                            @break
                        @endif
                    @empty
                        <div class="m-3 alert alert-info">
                            <ul>
                                <li>As Secretarias ainda não possuem nota</li>
                            </ul>
                        </div>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="mt-3">
        <div class="card">
            <div class="card-header">
                <h4>Maiores Notas das Unidades ({{ $qtdBestUnidades }})</h4>
            </div>
            @if (count($bestUnidades) > 0)
                <div id="graphDiv" class="p-2">
                    <canvas id="melhoresUnidades" height="200px"></canvas>
                </div>
            @else
                <div class="m-3 alert alert-info">
                    <ul>
                        <li>Não existem avaliações</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="mt-3">
        <div class="card">
            <div class="card-header">
                <h4>Menores Notas das Unidades ({{ $qtdWostUnidades }})</h4>
            </div>
            @if (count($wostUnidades) > 0)
                <div id="graphDiv" class="p-2">
                    <canvas id="pioresUnidades" height="200px"></canvas>
                </div>
            @else
                <div class="m-3 alert alert-info">
                    <ul>
                        <li>Não existem avaliações</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts_resumo')
<script nonce="{{ app('csp-nonce') }}">
    const mediaAvaliacoesCtx = $('#mediaAvaliacoes')[0].getContext('2d');
    const mediaAvaliacoes = new Chart(mediaAvaliacoesCtx, {
        type: 'bar',
        data: {
            labels: ["Secretarias"],
            datasets: @json($mediaAvaliacoes),
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    min: 0,
                    max: 10,
                }
            }
        }
    });

    const mediaAvaliacoesascCtx = $('#mediaAvaliacoesasc')[0].getContext('2d');
    const mediaAvaliacoesasc = new Chart(mediaAvaliacoesascCtx, {
        type: 'bar',
        data: {
            labels: ["Secretarias"],
            datasets: @json($mediaAvaliacoesasc),
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    min: 0,
                    max: 10,
                }
            }
        }
    });


    const melhoresUnidadesCtx = $('#melhoresUnidades')[0].getContext('2d');
    const melhoresUnidades = new Chart(melhoresUnidadesCtx, {
        type: 'bar',
        data: {
            labels: ["Unidade - Secretaria"],
            datasets: @json($bestUnidades),
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    min: 0,
                    max: 10,
                }
            }
        }


    });
    const pioresUnidadesCtx = $('#pioresUnidades')[0].getContext('2d');
    const pioresUnidades = new Chart(pioresUnidadesCtx, {
        type: 'bar',
        data: {
            labels: ["Unidade - Secretaria"],
            datasets: @json($wostUnidades),
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    min: 0,
                    max: 10,
                }
            }
        }


    });
</script>
@endpush
