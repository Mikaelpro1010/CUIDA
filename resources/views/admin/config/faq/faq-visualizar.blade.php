@extends('template.base')
@section('content')
        <h1 class="text-primary">
            FAQ
        </h1>
        <hr>
        <div class="row align-items-start">
            <div class="col-1">
                <label class="fw-bold">Ativo:</label>
                <div class="border-2 border-bottom border-warning">
                    @if (true)
                    <i class="text-success fa-solid fa-circle-check"></i>
                    @else
                    <i class="fa-solid fa-circle-xmark"></i>
                    @endif
                </div>
            </div>
            <div class="col-2">
                <label class="fw-bold">Última Alteração:</label>
                <div class="border-2 border-bottom border-warning">
                    {{ Carbon\Carbon::parse($faq->updated_at)->format('d/m/Y \à\s H:i\h') }}
                </div>
            </div>
            <div class="col-12 mt-3">
                <label class="fw-bold">Pergunta:</label>
                <div class="border-2 border border-warning p-2">
                    {{ $faq->pergunta }}
                </div>
            </div>
            <div class="col mt-3">
                <label class="fw-bold">Resposta:</label>
                <div class="border-2 border border-warning p-2">
                    {{ $faq->resposta }}
                </div>
            </div>
            <div class="text-center">
                <a href="{{ route('get-faq-list') }}" class="mt-3 btn btn-warning" tabindex="-1"
                role="button" aria-disabled="true">
                <i class="fa-solid fa-chevron-left"></i>
                Voltar
            </a>
        </div>
        </div>
@endsection