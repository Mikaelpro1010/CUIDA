@extends('admin.avaliacoes.template.avaliacao')

@section('titulo', 'Resumo Geral')
@section('content')
<h3>Resumo Geral</h3>
<hr>

<div class="row">
    @component('admin.avaliacoes.resumos.components.total-avaliacoes', compact('qtdAvaliacoes', 'notas'))
    @endcomponent
    @component('admin.avaliacoes.resumos.components.avaliacao-geral', compact('avaliacoesAverage', 'percentAverage'))
    @slot('title')
    Avaliação Geral
    @endslot
    @endcomponent
</div>

<div class="row">
    <div class="col-md-8 mt-3">
        <div class="card">
            <div class="card-header">
                <h4>Média das Avaliações</h4>
            </div>
            <div class="p-2" style="height: 35vh">
                <canvas id="mediaAvaliacoes" height="100px"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-3">
        <div class="card">
            <div class="card-header">
                <h4>Top 5 melhores Secretarias</h4>
            </div>
            <div class="px-2">
                <table class="table">
                    <tbody>
                        @foreach ($bestSecretarias as $item)
                        <tr>
                            <td>{{ $item['nome'] }}</td>
                            <td>{{ number_format($item['nota'],2,',','') }}</td>
                        </tr>
                        @if ($loop->iteration == 5)
                        <tr>
                            <td>...</td>
                            <td>...</td>
                        </tr>
                        @break
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-5 mt-3">
        <div class="card">
            <div class="card-header">
                <h4>Top 5 melhores Unidades</h4>
            </div>
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
                            <td>{{ number_format($item['nota'],2,',','') }}</td>
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
        </div>
    </div>
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
</div>

@endsection

@push('scripts_resumo')

<script>
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
                    max: 5,
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
                    max: 5,
                }
            }
        }
    });

</script>

@endpush