@extends('template.base')

@section('titulo', 'EscutaSol - Tipo de Avaliação')
@section('content')
        <h1 class="text-primary">
            Tipo de Avaliação
        </h1>
        <hr>
        <div class="row align-items-start">
            <div class="col-4">
                <label class="fw-bold" for="">Nome:</label>
                <div class="border-2 border-bottom border-warning">
                    {{ $tipo_avaliacao->nome }}
                </div>
            </div>
            <div class="col-1">
                <label class="fw-bold" for="">Ativo:</label>
                <div class="border-2 border-bottom border-warning">
                    @if ($tipo_avaliacao->ativo == true)
                    <i class="text-success fa-solid fa-circle-check"></i>
                    @else
                    <i class="text-danger fa-solid fa-circle-xmark"></i>
                    @endif
                </div>
            </div>
            <div class="col">
                <label class="fw-bold" for="">Última Alteração:</label>
                <div class="border-2 border-bottom border-warning">
                    {{ Carbon\Carbon::parse($tipo_avaliacao->updated_at)->format('d/m/Y \à\s H:i\h') }}
                </div>
            </div>
            <div class="text-center">
                <a href="{{ route('get-tipo-avaliacao-list') }}" class="mt-3 btn btn-warning" tabindex="-1"
                    role="button" aria-disabled="true">
                    <i class="fa-solid fa-chevron-left"></i>
                    Voltar
                </a>
            </div>
        </div>
@endsection