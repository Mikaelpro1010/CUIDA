@extends('admin.avaliacoes.template.avaliacao')

@section('titulo', 'Resumo por Unidade')

@section('content')
<h2 class="text-primary">{{ $unidade->nome }}</h2>
<h5 class="text-secondary">{{ $secretaria->nome }} - {{ $secretaria->sigla }}</h5>
<hr>

<div class="row">
    @component('admin.avaliacoes.resumos.components.total-avaliacoes', compact('qtdAvaliacoes', 'notas'))
    @endcomponent
    @component('admin.avaliacoes.resumos.components.avaliacao-geral', compact('avaliacoesAverage', 'percentAverage'))
    @slot('title')
    Avaliação da Unidade
    @endslot
    @endcomponent
</div>

<div class="row">
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4>Avaliaçoes por mês (qtd)</h4>
                    </div>
                    @if ($qtdAvaliacoes > 0)
                    <div class="col row">
                        <label class="col-md-9 col-form-label text-end" for="notasMes">Ano:</label>
                        <div class="col-md-3">
                            <select id="notasMes" class="form-select" name="notasMes">
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
            <div class="p-3" style="height: 35vh">
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
</div>

<div class="row">
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4>Contagem de avaliaçoes por mês (qtd)</h4>
                    </div>
                    @if ($qtdAvaliacoes > 0)
                    <div class="col row">
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
            <div class="p-3" style="height: 35vh">
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

@push('scripts_resumo')

@if ($qtdAvaliacoes > 0)
<script>
    $(document).ready(function(){
        atualizarAvaliacoesMes({{ formatarDataHora(null, 'Y') }});
        atualizarNotasMes({{ formatarDataHora(null, 'Y') }});
    });

    $("#avaliacoesMes").change(function(){ atualizarAvaliacoesMes($("#avaliacoesMes").val())});
    $("#notasMes").change(function(){ atualizarNotasMes($("#notasMes").val());});
    
    function atualizarAvaliacoesMes(ano){
        $.ajax({
            url: "{{ route('resumo-avaliacoes-unidade-avaliacoes-mes', $unidade) }}",
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

    function atualizarNotasMes(ano){
        $.ajax({
            url: "{{ route('resumo-avaliacoes-unidade-notas-mes', $unidade) }}",
            dataType:'json',
            data:{
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
            labels: ["janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"],
            datasets: [
                {
                    label : 'Muito Ruim',
                    data : [0],
                    borderColor : 'rgba(220,53,69,1)',
                    backgroundColor : 'rgba(220,53,69,0.3)',
                    fill : true,
                    tension : 0.3,
                },
                {
                    label : 'Ruim',
                    data : [0],
                    borderColor : 'rgba(255,193,6,1)',
                    backgroundColor : 'rgba(255,193,6,0.3)',
                    fill : true,
                    tension : 0.3,
                },
                {
                    label : 'Neutro',
                    data : [0],
                    borderColor : 'rgba(14,202,240,1)',
                    backgroundColor : 'rgba(14,202,240,0.3)',
                    fill : true,
                    tension : 0.3,
                },
                {
                    label : 'Bom',
                    data : [0],
                    borderColor : 'rgba(12,110,253,1)',
                    backgroundColor : 'rgba(12,110,253,0.3)',
                    fill : true,
                    tension : 0.3,
                },
                {
                    label : 'Muito Bom',
                    data : [0],
                    borderColor : 'rgba(26,135,84,1)',
                    backgroundColor : 'rgba(26,135,84,0.3)',
                    fill : true,
                    tension : 0.3,
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

    const avaliacoesMesCtx = $('#avaliacoesMesChart')[0].getContext('2d');
    const avaliacoesMes = new Chart(avaliacoesMesCtx, {
        type: 'line',
        data: {
            labels: ["janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"],
            datasets: [
                {
                    label : "Avaliações por Mês",
                    data : [0],
                    borderColor : "rgba({{ $corGrafico }}, 1)",
                    backgroundColor : "rgba({{ $corGrafico }}, 0.3)",
                    fill : true,
                    tension : 0.3
                }
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