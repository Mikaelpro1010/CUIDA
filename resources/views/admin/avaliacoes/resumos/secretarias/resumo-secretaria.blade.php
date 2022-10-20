@extends('admin.avaliacoes.template.avaliacao')

@section('titulo', 'Resumo por Secretaria')
@section('content')

<h3 class="">
    {{ $secretaria->nome }} - {{ $secretaria->sigla }}
</h3>
<hr>

<div class="row">
    @component('admin.avaliacoes.resumos.components.total-avaliacoes', compact('qtdAvaliacoes', 'notas'))
    @endcomponent
    @component('admin.avaliacoes.resumos.components.avaliacao-geral', compact('avaliacoesAverage', 'percentAverage'))
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
            <div class="" style="height: 35vh">
                <canvas id="melhoresUnidades" height="100px"></canvas>
            </div>
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
                            <td>{{ $item['nome'] }}</td>
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
                        <h4>Avaliaçoes por mês (qtd)</h4>
                    </div>
                    @if ($qtdAvaliacoes > 0)
                    <div class="col-md-8 row">
                        <label class="col-md-9 col-form-label text-end" for="avaliacoesMes">Ano:</label>
                        <div class="col-md-3">
                            <select id="avaliacoesMes" class="form-select" name="avaliacoesMes">
                                @for ($ano = intval(formatarDataHora(null, 'Y')); $ano >= 2020 ; $ano--)
                                <option value="{{$ano}}" @if (request()->ano == $ano) selected @endif>
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
            <div class="m-3" style="height: 35vh">
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

@endsection

@push('scripts_resumo')

<script>
    $("#secretaria").change(function(){
        $('#secretariaChange').submit();
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
                    max: 5,
                }
            }
        }
    });
</script>

@if ($qtdAvaliacoes > 0)
<script>
    $(document).ready(function(){updateAvaliacoesMes({{ formatarDataHora(null, 'Y') }})});

    $("#avaliacoesMes").change(function(){updateAvaliacoesMes($("#avaliacoesMes").val())});

    function updateAvaliacoesMes(ano){
        $.ajax({
            url: "{{ route('resumo-avaliacoes-secretaria-avaliacoes-mes', $secretaria) }}",
            dataType:'json',
            data:{
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
            labels: ["janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"],
                datasets: [
                    {
                        label : "Quantidade de Avaliações",
                        data : [0],
                        borderColor : "rgba({{ $corGrafico  }}, 1)",
                        backgroundColor : "rgba({{ $corGrafico  }}, 0.3)",
                        fill : true,
                        tension : 0.3
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