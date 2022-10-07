@extends('admin.avaliacoes.template.avaliacao')

@section('titulo', 'Resumo por Secretaria')
@section('content')
<div class="d-flex justify-content-between">
    <h3 class="">
        Resumo por Unidade
    </h3>
    <form id="secretariaChange" action="">

        <div class="row">
            <label class="col-md-2 col-form-label" for="secretaria">Secretaria:</label>
            <div class="col-md-10">
                <input id="anoSelect" type="hidden" name="ano" value="{{formatarDataHora(null, 'Y')}}">
                <select id="secretaria" class="form-select" name="secretaria">
                    <option value="" @if(is_null(request()->secretaria)) selected @endif >Selecione</option>
                    @foreach ( $secretariasSearchSelect as $secretaria )
                    <option value="{{ $secretaria->id }}" @if (request()->secretaria == $secretaria->id) selected
                        @endif>
                        {{ $secretaria->sigla . " - " . $secretaria->nome }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="unidades" class="row">

        </div>
    </form>
</div>
<hr>

@if (request()->secretaria)
<h3 class="">
    {{ $secretariaObj->nome }} - {{ $secretariaObj->sigla }}
</h3>
<div id="content"></div>

@else
<div class="alert alert-info">
    <ul>
        <li>Selecione uma Secretaria!</li>
    </ul>
</div>
@endif
@endsection

@section('scripts')

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
@endsection