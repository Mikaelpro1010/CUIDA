@extends('admin.avaliacoes.resumos.template.avaliacao')

@section('titulo', 'Resumo por Secretaria')
@section('content')

    <h3 class="text-primary">
        {{ $secretaria->nome }} - {{ $secretaria->sigla }}
        @if (!$secretaria->ativo)
            <span class="text-danger"> (Inativo)</span>
        @endif
    </h3>
    <hr>

    <div class="row">
        @component('admin.avaliacoes.resumos.components.total-avaliacoes', compact('qtdAvaliacoes', 'notas'))
        @endcomponent

        @component(
            'admin.avaliacoes.resumos.components.avaliacao-geral',
            compact('avaliacoesAverage', 'percentAverage', 'qtdAvaliacoes', 'notas'))
            @slot('title')
                Avaliação da Secretaria
            @endslot
        @endcomponent
    </div>

    <div class="row">
        <div class="col-md-7 mt-3">
            <div class="card">
                <div class="card-header">
                    <h4>Melhores Unidades ({{ $qtdBestUnidades }})</h4>
                </div>
                @if ($qtdAvaliacoes > 0)
                    <div id="graphDiv" class="">
                        <canvas id="melhoresUnidades" height="100px"></canvas>
                    </div>
                @else
                    <div class="m-3 alert alert-info">
                        <ul>
                            <li>Não existem avaliações para esta Secretaria</li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-5 mt-3">
            <div class="card">
                <div class="card-header">
                    <h4>Top 5 melhores Unidades</h4>
                </div>
                @if (count($top5BestUnidades) > 0)
                    <div class="px-2">
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
                                            <a href="{{ route('get-resumo-avaliacoes-unidade', ['secretaria' => $secretaria, 'unidade' => $item['id']]) }}"
                                                target="_blank">
                                                {{ $item['nome'] }}
                                            </a>
                                        </td>
                                        <td>{{ $item['nota'] }}</td>
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
                </div>
            @else
                <div class=" m-3 alert alert-info">
                    <ul>
                        <li>Esta Secretaria não possui Unidades.</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <h4> Contagem de avaliaçoes por mês (qtd)</h4>
                    </div>
                    @if ($qtdAvaliacoes > 0)
                        <div class="col-md-8 row">
                            <label class="col-md-9 col-form-label text-end" for="avaliacoesMes">Ano:</label>
                            <div class="col-md-3">
                                <select id="avaliacoesMes" class="form-select" name="avaliacoesMes">
                                    @for ($ano = intval(formatarDataHora(null, 'Y')); $ano >= 2023; $ano--)
                                        <option value="{{ $ano }}"
                                            @if (request()->ano == $ano) selected @endif>
                                            {{ $ano }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @if ($qtdAvaliacoes > 0)
                <div id="graphDiv" class="m-3">
                    <canvas id="avaliacoesMesChart" height="100px"></canvas>
                </div>
            @else
                <div class="m-3 alert alert-info">
                    <ul>
                        <li>Não existem avaliações para esta Secretaria</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap">
                    <div>
                        <h4>Avaliaçoes por mês (qtd)</h4>
                    </div>
                    @if ($qtdAvaliacoes > 0)
                        <div class="ms-auto d-flex w-25">
                            <label class="col-form-label me-2" for="avaliacoesMes">Ano:</label>
                            <select id="avaliacoesMes" class="form-select" name="avaliacoesMes">
                                @for ($ano = intval(formatarDataHora(null, 'Y')); $ano >= 2023; $ano--)
                                    <option value="{{ $ano }}"
                                        @if (request()->ano == $ano) selected @endif>
                                        {{ $ano }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    @endif
                </div>
            </div>
            @if ($qtdAvaliacoes > 0)
                <div id="graphDiv" class="p-3">
                    <canvas id="avaliacoesMesChart" height="100px"></canvas>
                </div>
            @else
                <div class=" m-3 alert alert-info">
                    <ul>
                        <li>Não existem avaliações para esta Unidade</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@if ($qtdAvaliacoes > 0)
@push('scripts_resumo')
    <script nonce="{{ app('csp-nonce') }}">
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
        $(document).ready(function() {
            updateAvaliacoesMes({{ formatarDataHora(today(), 'Y') }})
        });

        $("#avaliacoesMes").change(function() {
            updateAvaliacoesMes($("#avaliacoesMes").val())
        });

        function updateAvaliacoesMes(ano) {
            $.ajax({
                url: "{{ route('get-resumo-avaliacoes-secretaria-avaliacoes-mes', $secretaria) }}",
                dataType: 'json',
                data: {
                    ano: ano
                },
                success: function(response) {
                    avaliacoesMes.data.datasets[0].data = response.resposta;
                    avaliacoesMes.update();
                }
            });
        }

        const avaliacoesMesCtx = $('#avaliacoesMesChart')[0].getContext('2d');
        const avaliacoesMes = new Chart(avaliacoesMesCtx, {
            type: 'line',
            data: {
                labels: ["janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro",
                    "outubro", "novembro", "dezembro"
                ],
                datasets: [{
                    label: "Quantidade de Avaliações",
                    data: [0],
                    borderColor: "rgba({{ $corGrafico }}, 1)",
                    backgroundColor: "rgba({{ $corGrafico }}, 0.3)",
                    fill: true,
                    tension: 0.3
                }, ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        min: 0,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endif

@push('scripts_resumo')
    @if ($qtdAvaliacoes > 0)
        <script nonce="{{ app('csp-nonce') }}">
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

            // TT



            $("#notasMes").change(function() {
                atualizarNotasMes($("#notasMes").val());
            });

            function atualizarAvaliacoesMes(ano) {
                $.ajax({
                    url: "{{ route('get-resumo-avaliacoes-secretaria-avaliacoes-mes', $unidade) }}",
                    dataType: 'json',
                    data: {
                        ano: ano
                    },
                    success: function(response) {
                        avaliacoesMes.data.datasets[0].data = response.resposta;
                        avaliacoesMes.update();
                    }
                });
            }

            //EE
            $(document).ready(function() {
                updateAvaliacoesMes({{ formatarDataHora(today(), 'Y') }})
            });

            $("#avaliacoesMes").change(function() {
                updateAvaliacoesMes($("#avaliacoesMes").val())
            });
            $(document).ready(function() {
                atualizarAvaliacoesMes({{ formatarDataHora(today(), 'Y') }});
                atualizarNotasMes({{ formatarDataHora(today(), 'Y') }});
            });

            $("#avaliacoesMes").change(function() {
                atualizarAvaliacoesMes($("#avaliacoesMes").val())
            });
            $("#notasMes").change(function() {
                atualizarNotasMes($("#notasMes").val());
            });

            function updateAvaliacoesMes(ano) {
                $.ajax({
                    url: "{{ route('get-resumo-avaliacoes-secretaria-avaliacoes-mes', $secretaria) }}",
                    dataType: 'json',
                    data: {
                        ano: ano
                    },
                    success: function(response) {
                        avaliacoesMes.data.datasets[0].data = response.resposta;
                        avaliacoesMes.update();
                    }
                });
            }

            const avaliacoesMesCtx = $('#avaliacoesMesChart')[0].getContext('2d');
            const avaliacoesMes = new Chart(avaliacoesMesCtx, {
                type: 'line',
                data: {
                    labels: ["janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro",
                        "outubro", "novembro", "dezembro"
                    ],
                    datasets: [{
                        label: "Quantidade de Avaliações",
                        data: [0],
                        borderColor: "rgba({{ $corGrafico }}, 1)",
                        backgroundColor: "rgba({{ $corGrafico }}, 0.3)",
                        fill: true,
                        tension: 0.3
                    }, ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 0,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            function atualizarNotasMes(ano) {
                $.ajax({
                    url: "{{ route('get-resumo-avaliacoes-secretarias-notas-mes', $secretaria) }}",
                    dataType: 'json',
                    data: {
                        ano: ano
                    },
                    success: function(response) {
                        notasMes.data.datasets[0].data = response.resposta[1];
                        notasMes.data.datasets[1].data = response.resposta[3];
                        notasMes.data.datasets[2].data = response.resposta[5];
                        notasMes.data.datasets[3].data = response.resposta[7];
                        notasMes.data.datasets[4].data = response.resposta[9];
                        notasMes.update();
                    }
                });
            }

            const notasMesCtx = $('#notasMesChart')[0].getContext('2d');
            const notasMes = new Chart(notasMesCtx, {
                type: 'line',
                data: {
                    labels: ["janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro",
                        "outubro", "novembro", "dezembro"
                    ],
                    datasets: [{
                            label: 'Muito Ruim',
                            data: [0],
                            borderColor: 'rgba(220,53,69,1)',
                            backgroundColor: 'rgba(220,53,69,0.3)',
                            fill: true,
                            tension: 0.3,
                        },
                        {
                            label: 'Ruim',
                            data: [0],
                            borderColor: 'rgba(255,193,6,1)',
                            backgroundColor: 'rgba(255,193,6,0.3)',
                            fill: true,
                            tension: 0.3,
                        },
                        {
                            label: 'Neutro',
                            data: [0],
                            borderColor: 'rgba(14,202,240,1)',
                            backgroundColor: 'rgba(14,202,240,0.3)',
                            fill: true,
                            tension: 0.3,
                        },
                        {
                            label: 'Bom',
                            data: [0],
                            borderColor: 'rgba(12,110,253,1)',
                            backgroundColor: 'rgba(12,110,253,0.3)',
                            fill: true,
                            tension: 0.3,
                        },
                        {
                            label: 'Muito Bom',
                            data: [0],
                            borderColor: 'rgba(26,135,84,1)',
                            backgroundColor: 'rgba(26,135,84,0.3)',
                            fill: true,
                            tension: 0.3,
                        },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 0,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        </script>
    @endif
@endpush
