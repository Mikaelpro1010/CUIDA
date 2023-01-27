@extends('template.base')

@section('titulo', 'EscutaSol - Tipo de Avaliação')
@section('content')
    <h1 class="text-primary">
        Tipo de Avaliação
    </h1>
    <hr>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="fw-bold" for="">Nome:</label>
            <div class="border-2 border-bottom border-warning">
                {{ $tipo_avaliacao->nome }}
            </div>
        </div>

        <div class="col-md-2">
            <label class="fw-bold" for="">Status:</label>
            <div class="border-2 border-bottom border-warning">
                @if ($tipo_avaliacao->ativo)
                    Ativo <i class="text-success fa-solid fa-circle-check"></i>
                @else
                    Inativo <i class="text-danger fa-solid fa-circle-xmark"></i>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <label class="fw-bold" for="">Última Alteração:</label>
            <div class="border-2 border-bottom border-warning">
                {{ formatarDataHora($tipo_avaliacao->updated_at) }}
            </div>
        </div>
        <div class="col-md-3">
            <label class="fw-bold" for="">Inserir Automaticamente?</label>
            <div class="border-2 border-bottom border-warning">
                @if ($tipo_avaliacao->default == true)
                    Sim <i class="text-success fa-solid fa-circle-check"></i>
                @else
                    Não <i class="text-danger fa-solid fa-circle-xmark"></i>
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <label class="fw-bold">Pergunta:</label>
            <div class="border-2 border border-warning p-2">
                {{ $tipo_avaliacao->pergunta }}
            </div>
        </div>
        <div class="text-center">
            <a href="{{ route('get-tipo-avaliacao-list') }}" class="mt-3 btn btn-warning" tabindex="-1" role="button"
                aria-disabled="true">
                <i class="fa-solid fa-chevron-left"></i>
                Voltar
            </a>
        </div>
    </div>
@endsection
