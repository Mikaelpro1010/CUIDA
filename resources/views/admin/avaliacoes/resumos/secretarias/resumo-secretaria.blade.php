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

                    {{--  --}}

                    <div class="flex-grow-1">
                        <div class="d-flex flex-wrap h-100 ">
                            <div class="flex-grow-1 d-flex justify-content-center">
                                <div class="form-check form-switch align-self-end mb-2">
                                    <input class="form-check-input days-filter" type="checkbox" role="switch"
                                        id="last_30days" name='last_30days'
                                        {{ request()->last_30days ? 'checked' : '' }}>
                                    <label class="form-check-label" for="last_30days">Últimos 30 dias</label>
                                </div>
                            </div>

                            <div class="flex-grow-1 d-flex justify-content-center">
                                <div class="form-check form-switch  align-self-end mb-2">
                                    <input class="form-check-input days-filter" type="checkbox" role="switch"
                                        id="last_7days" name='last_7days' {{ request()->last_7days ? 'checked' : '' }}>
                                    <label class="form-check-label" for="last_7days">Últimos 7 dias</label>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{--  --}}
                    @if ($qtdAvaliacoes > 0)
                        <div class="ms-auto d-flex">
                            <label class="col-form-label me-2" for="notasMes">Ano:</label>
                            <select id="notasMes" class="form-select" name="notasMes">
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
                <div class="col-md-3 align-self-end">
                    <a id="buscar" class="btn btn-success form-control mt-3" href="#">
                        <svg class="svg-inline--fa fa-magnifying-glass" aria-hidden="true" focusable="false"
                            data-prefix="fas" data-icon="magnifying-glass" role="img"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352c79.5 0 144-64.5 144-144s-64.5-144-144-144S64 128.5 64 208s64.5 144 144 144z">
                            </path>
                        </svg><!-- <i class="fa-solid fa-magnifying-glass"></i> Font Awesome fontawesome.com -->
                        Buscar
                    </a>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex flex-wrap gap-2">
                        <div class="flex-fill">
                            <label class="fw-bold" for="data_inicial">Data Inicial:</label>
                            <input class="form-control" id="data_inicial" type="date" name="data_inicial"
                                min="2023-01-01" max="{{ now()->format('Y-m-d') }}"
                                value="{{ request()->data_inicial }}">
                        </div>
                        <div class="flex-fill">
                            <label class="fw-bold" for="data_final">Data Final:</label>
                            <input class="form-control" id="data_final" type="date" name="data_final"
                                min="2023-01-01" max="{{ now()->format('Y-m-d') }}"
                                value="{{ request()->data_final }}">
                        </div>
                    </div>
                </div>
            </div>

            @if ($qtdAvaliacoes > 0)
                <div id="graphDiv" class="p-3">
                    <canvas id="notasMesChart" height="100px"></canvas>
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

@endsection

@push('scripts_resumo')
    @if ($qtdAvaliacoes > 0)
        <script nonce="{{ app('csp-nonce') }}">
            $('#buscar').click(function(e) {
                e.preventDefault();

                let url = "{{ route('get-resumo-filtro-avaliacoes-secretaria') }}";
                let currentURL = window.location.href;

                // Extract the secretaria ID from the URL
                let secretariaID = currentURL.split('/').pop();
                if (!window.secretariaID) {
                    url = secretariaID;
                }

                // Retrieve other input values
                let data_inicial = $('#data_inicial').val();
                let data_final = $('#data_final').val();

                // Construct the URL for the secretaria route (replace with your actual route)
                let finalURL = url + '?data_inicial=' + data_inicial + '&data_final=' + data_final;

                // Open a new window with the constructed URL
                window.open(finalURL, '_blank');
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

            //
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
                    labels: ["janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto",
                        "setembro",
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
                    labels: ["janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto",
                        "setembro",
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
