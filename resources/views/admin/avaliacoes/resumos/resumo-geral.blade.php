@extends('admin.avaliacoes.template.avaliacao')

@section('titulo', 'Resumo Geral')
@section('content')
<h3>Resumo Geral</h3>
<hr>

<div class="row">
    <div class="col-md-5 mt-3">
        <div class="card">
            <div class="card-header">
                <h4>Avaliações ({{ $totalNotas }})</h4>
            </div>
            <div class=" p-3">
                <div class="mt-2 row d-flex align-items-center">
                    <div class="d-inline col-2 text-end">
                        <span class="text-nowrap ">{{$notas[5]['qtd']}}</span>
                    </div>
                    <div class="d-inline col-9">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                role="progressbar" aria-label="Animated striped example"
                                aria-valuenow="{{$notas[5]['percent']}}" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{$notas[5]['percent']}}%">
                                {{$notas[5]['percent']}}%
                            </div>
                        </div>
                    </div>
                    <div class="col-1 m-0 p-0">
                        <i class="fa-2x text-success fa-regular fa-face-laugh-beam"></i>
                    </div>
                </div>

                <div class="mt-2 row d-flex align-items-center">
                    <div class="d-inline col-2 text-end">
                        <span class="text-nowrap">{{$notas[4]['qtd']}}</span>
                    </div>
                    <div class="d-inline col-9">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                role="progressbar" aria-label="Animated striped example"
                                aria-valuenow="{{$notas[4]['percent']}}" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{$notas[4]['percent']}}%">
                                {{$notas[4]['percent']}}%
                            </div>
                        </div>
                    </div>
                    <div class="col-1 m-0 p-0">
                        <i class="fa-2x text-primary fa-regular fa-face-smile"></i>
                    </div>
                </div>

                <div class="mt-2 row d-flex align-items-center">
                    <div class="d-inline col-2 text-end">
                        <span class="text-nowrap">{{$notas[3]['qtd']}}</span>
                    </div>
                    <div class="d-inline col-9">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                                role="progressbar" aria-label="Animated striped example"
                                aria-valuenow="{{$notas[3]['percent']}}" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{$notas[3]['percent']}}%">
                                {{$notas[3]['percent']}}%
                            </div>
                        </div>
                    </div>
                    <div class="col-1 m-0 p-0">
                        <i class="fa-2x text-info fa-regular fa-face-meh"></i>
                    </div>
                </div>

                <div class="mt-2 row d-flex align-items-center">
                    <div class="d-inline col-2 text-end">
                        <span class="text-nowrap">{{$notas[2]['qtd']}}</span>
                    </div>
                    <div class="d-inline col-9">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning"
                                role="progressbar" aria-label="Animated striped example"
                                aria-valuenow="{{$notas[2]['percent']}}" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{$notas[2]['percent']}}%">
                                {{$notas[2]['percent']}}%
                            </div>
                        </div>
                    </div>
                    <div class="col-1 m-0 p-0">
                        <i class="fa-2x text-warning fa-regular fa-face-frown"></i>
                    </div>
                </div>

                <div class="mt-2 row d-flex align-items-center">
                    <div class="d-inline col-2 text-end">
                        <span class="text-nowrap">{{$notas[1]['qtd']}}</span>
                    </div>
                    <div class="d-inline col-9">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                                role="progressbar" aria-label="Animated striped example"
                                aria-valuenow="{{$notas[1]['percent']}}" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{$notas[1]['percent']}}%">
                                {{$notas[1]['percent']}}%
                            </div>
                        </div>
                    </div>
                    <div class="col-1 m-0 p-0">
                        <i class="fa-2x text-danger fa-regular fa-face-angry d-inline"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7 mt-3">
        <div class="card">
            <div class="card-header">
                <h4>Avaliação Geral</h4>
            </div>
            <div class="">
                <div class=" p-3">
                    <div class="d-flex justify-content-between align-items-end">
                        <i
                            class="@if ($generalAverage < 1.5) fa-6x @else fa-4x @endif text-danger fa-regular fa-face-angry"></i>
                        <i
                            class="@if ($generalAverage >= 1.5 && $generalAverage < 2.5) fa-6x @else fa-4x @endif text-warning fa-regular fa-face-frown"></i>
                        <i
                            class="@if ($generalAverage >= 2.5 && $generalAverage < 3.5) fa-6x @else fa-4x @endif text-info fa-regular fa-face-meh"></i>
                        <i
                            class="@if ($generalAverage >= 3.5 && $generalAverage < 4.5) fa-6x @else fa-4x @endif text-primary fa-regular fa-face-smile"></i>
                        <i
                            class="@if ($generalAverage >= 4.5) fa-6x @else fa-4x @endif text-success fa-regular fa-face-laugh-beam"></i>
                    </div>
                    <div class="mt-2 progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated
                        @if ($generalAverage < 1.5)
                            bg-danger
                        @else
                            @if ($generalAverage >=  1.5 && $generalAverage < 2.5)
                            bg-warning                            
                            @else
                            @if ($generalAverage >=  2.5 && $generalAverage < 3.5)
                            bg-info                            
                            @else
                            @if ($generalAverage >=  3.5 && $generalAverage < 4.5)
                            bg-primary                            
                            @else
                            bg-success                            
                            @endif                
                            @endif    
                            @endif    
                        @endif
                        " role="progressbar" aria-label="Animated striped example" aria-valuenow="{{$percentAverage}}"
                            aria-valuemin="0" aria-valuemax="100" style="width: {{$percentAverage}}%">
                            {{$percentAverage}} %
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <span class="fs-3 fw-normal">Avaliação Geral:</span>
                    <p class="fs-1 fw-bold 
                        @if ($generalAverage < 1.5)
                        text-danger
                        @else
                            @if ($generalAverage >=  1.5 && $generalAverage < 2.5)
                            text-warning                            
                            @else
                                @if ($generalAverage >=  2.5 && $generalAverage < 3.5)
                                text-info                            
                                @else
                                    @if ($generalAverage >=  3.5 && $generalAverage < 4.5)
                                    text-primary                            
                                    @else
                                    text-success                            
                                    @endif              
                                @endif    
                            @endif    
                        @endif">
                        {{ $generalAverage }}<span class="fs-3 fw-normal text-body">/5</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
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
                            <td>{{ $item['nota'] }}</td>
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

<script src="{{ asset('js/chart.js') }}"></script>
<script>
    const mediaAvaliacoesCtx = $('#mediaAvaliacoes')[0].getContext('2d');
    const mediaAvaliacoes = new Chart(mediaAvaliacoesCtx, {
        type: 'bar',
        data: {
            labels: ["Secretarias"],
            datasets: <?= $secretariaAvg ?>,
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
            datasets: <?= $bestUnidades ?>,
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