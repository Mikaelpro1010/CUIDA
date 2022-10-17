@extends('admin.avaliacoes.template.avaliacao')

@section('titulo', 'Resumo por Unidade')

@section('content')
<h2>{{ $unidade->nome }}</h2>
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
    $("#avaliacoesMes").change(function(){
        $.ajax({
            url: "{{ route('resumo-avaliacoes-unidade-avaliacoes-mes', $unidade) }}",
            dataType:'json',
            data:{
                    ano: $("#avaliacoesMes").val()
                },
            success: function(response) {
                avaliacoesMes.data.datasets[0].data = response.resposta;
                avaliacoesMes.update();
            }
        });
    });

    $("#notasMes").change(function(){
        $.ajax({
            url: "{{ route('resumo-avaliacoes-unidade-notas-mes', $unidade) }}",
            dataType:'json',
            data:{
                    ano: $("#notasMes").val()
                },
            success: function(response) {
                notasMes.data.datasets[0].data = response.resposta[0];
                notasMes.data.datasets[1].data = response.resposta[1];
                notasMes.data.datasets[2].data = response.resposta[2];
                notasMes.data.datasets[3].data = response.resposta[3];
                notasMes.data.datasets[4].data = response.resposta[4];
                notasMes.update();
            }
        });
    });

    const avaliacoesMesCtx = $('#avaliacoesMesChart')[0].getContext('2d');
    const avaliacoesMes = new Chart(avaliacoesMesCtx, {
        type: 'line',
        data: {
            labels: ["janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"],
            datasets: [
                @json($avaliacoesMes),
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
    const notasMesCtx = $('#notasMesChart')[0].getContext('2d');
    const notasMes = new Chart(notasMesCtx, {
        type: 'line',
        data: {
            labels: ["janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"],
            datasets: @json($notasMes)
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