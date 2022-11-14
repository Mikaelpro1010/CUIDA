@extends('template.base')
@section('content')
        <h1 class="text-primary">
            FAQ
        </h1>
        <hr>
        <div class="row align-items-start">
            <div class="col">
                <label class="fw-bold" for="">Pergunta:</label>
                <div class="border-2 border-bottom border-warning">
                    {{ substr($faq->pergunta, 0, 100) . '...' }}
                </div>
            </div>
            <div class="col">
                <label class="fw-bold" for="">Ativo:</label>
                <div class="border-2 border-bottom border-warning">
                    @if (true)
                        <i class="text-success fa-solid fa-circle-check"></i>
                    @else
                        <i class="fa-solid fa-circle-xmark"></i>
                    @endif
                </div>
            </div>
            <div class="col">
                <label class="fw-bold" for="">Última Alteração:</label>
                <div class="border-2 border-bottom border-warning">
                    {{ Carbon\Carbon::parse($faq->updated_at)->format('d/m/Y \à\s H:i\h') }}
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