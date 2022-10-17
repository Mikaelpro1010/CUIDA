@extends('admin.avaliacoes.template.avaliacao')

@section('titulo', 'Resumo por Secretaria')
@section('content')
<div class="d-flex justify-content-between">
    <h3 class="">
        Resumo por Secretaria
    </h3>
    <form id="secretariaChange" class="row" action="">
        <label class="col-md-2 col-form-label" for="secretaria">Secretaria:</label>
        <div class="col-md-10">
            <input id="anoSelect" type="hidden" name="ano" value="{{formatarDataHora(null, 'Y')}}">
            <select id="secretaria" class="form-select" name="secretaria">
                <option value="" @if(is_null(request()->secretaria)) selected @endif >Selecione</option>
                @foreach ( $secretariasSearchSelect as $secretaria )
                <option value="{{ $secretaria->id }}" @if (request()->secretaria == $secretaria->id) selected @endif>
                    {{ $secretaria->sigla . " - " . $secretaria->nome }}
                </option>
                @endforeach
            </select>
        </div>
    </form>
</div>
<hr>

@if (request()->secretaria)
<h3 class="">
    {{ $secretariaObj->nome }} - {{ $secretariaObj->sigla }}
</h3>
<div class="row">
    <div class="col-md-5 mt-3">
        <div class="card">
            <div class="card-header">
                <h4>Avaliações ({{ $qtdAvaliacoes }})</h4>
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
                <h4>Avaliação da Secretaria</h4>
            </div>
            @if ($qtdAvaliacoes > 0)

            <div class="">
                <div class=" p-3">
                    <div class="d-flex justify-content-between align-items-end">
                        <i
                            class="@if ($avaliacoesSecretariaAverage < 1.5) fa-6x @else fa-4x @endif text-danger fa-regular fa-face-angry"></i>
                        <i
                            class="@if ($avaliacoesSecretariaAverage >= 1.5 && $avaliacoesSecretariaAverage < 2.5) fa-6x @else fa-4x @endif text-warning fa-regular fa-face-frown"></i>
                        <i
                            class="@if ($avaliacoesSecretariaAverage >= 2.5 && $avaliacoesSecretariaAverage < 3.5) fa-6x @else fa-4x @endif text-info fa-regular fa-face-meh"></i>
                        <i
                            class="@if ($avaliacoesSecretariaAverage >= 3.5 && $avaliacoesSecretariaAverage < 4.5) fa-6x @else fa-4x @endif text-primary fa-regular fa-face-smile"></i>
                        <i
                            class="@if ($avaliacoesSecretariaAverage >= 4.5) fa-6x @else fa-4x @endif text-success fa-regular fa-face-laugh-beam"></i>
                    </div>
                    <div class="mt-2 progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated
                        @if ($avaliacoesSecretariaAverage < 1.5)
                            bg-danger
                        @else
                            @if ($avaliacoesSecretariaAverage >=  1.5 && $avaliacoesSecretariaAverage < 2.5)
                            bg-warning                            
                            @else
                            @if ($avaliacoesSecretariaAverage >=  2.5 && $avaliacoesSecretariaAverage < 3.5)
                            bg-info                            
                            @else
                            @if ($avaliacoesSecretariaAverage >=  3.5 && $avaliacoesSecretariaAverage < 4.5)
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
                        @if ($avaliacoesSecretariaAverage < 1.5)
                        text-danger
                        @else
                            @if ($avaliacoesSecretariaAverage >=  1.5 && $avaliacoesSecretariaAverage < 2.5)
                            text-warning                            
                            @else
                                @if ($avaliacoesSecretariaAverage >=  2.5 && $avaliacoesSecretariaAverage < 3.5)
                                text-info                            
                                @else
                                    @if ($avaliacoesSecretariaAverage >=  3.5 && $avaliacoesSecretariaAverage < 4.5)
                                    text-primary                            
                                    @else
                                    text-success                            
                                    @endif              
                                @endif    
                            @endif    
                        @endif">
                        {{ $avaliacoesSecretariaAverage }}<span class="fs-3 fw-normal text-body">/5</span>
                    </p>
                </div>
            </div>
            @else
            <div class=" m-3 alert alert-info">
                <ul>
                    <li>Não existem avaliações para esta Secretaria</li>
                </ul>
            </div>
            @endif
        </div>
    </div>
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

@else
<div class="alert alert-info">
    <ul>
        <li>Selecione uma Secretaria!</li>
    </ul>
</div>
@endif
@endsection

@push('scripts_resumo')

<script src="{{ asset('js/chart.js') }}"></script>
<script>
    $("#secretaria").change(function(){
        $('#secretariaChange').submit();
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

@if ($qtdAvaliacoes > 0)
<script>
    $("#avaliacoesMes").change(function(){
            $('#anoSelect').val($("#avaliacoesMes").val());
            $('#secretariaChange').submit();
        });

        const avaliacoesMesCtx = $('#avaliacoesMesChart')[0].getContext('2d');
        const avaliacoesMes = new Chart(avaliacoesMesCtx, {
            type: 'line',
            data: {
                labels: ["janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"],
                datasets: [
                    <?= $avaliacoesMes ?>,
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