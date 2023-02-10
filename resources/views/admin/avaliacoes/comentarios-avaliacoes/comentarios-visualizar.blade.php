@extends('template.base')

@section('titulo', 'Comentários das Avaliações')

@section('content')
    <h1 class="text-primary">
        Comentário da Avaliação
    </h1>
    <hr>
    <div class="row align-items-start">
        <div class="col-5">
            <label class="fw-bold" for="">Secretaria:</label>
            <div class="border-2 border-bottom border-warning">
                {{ $avaliacoes->setor->unidade->secretaria->sigla. ' - ' .$avaliacoes->setor->unidade->secretaria->nome }}
            </div>
        </div>
        <div class="col-2">
            <label class="fw-bold" for="">Unidade:</label>
            <div class="border-2 border-bottom border-warning">
                {{ $avaliacoes->setor->unidade->nome }}
            </div>
        </div>
        <div class="col-3">
            <label class="fw-bold" for="">Setor:</label>
            <div class="border-2 border-bottom border-warning">
                {{ $avaliacoes->setor->nome }}
            </div>
        </div>
        <div class="col-2">
            <label class="fw-bold" for="">Nota:</label>
            <div class="border-2 border-bottom border-warning">
                {{ $avaliacoes->nota }}
            </div>
        </div>
        <div class="col-12 mt-3">
            <label class="fw-bold" for="">Comentario:</label>
            <div class="border-2 border border-warning p-2">
                {{ $avaliacoes->comentario }}
            </div>
        </div>
        <div class="text-center">
            <a href="{{ route('get-comentarios-avaliacoes-list') }}" class="mt-3 btn btn-warning" tabindex="-1"
            role="button" aria-disabled="true">
            <i class="fa-solid fa-chevron-left"></i>
            Voltar
        </a>
        </div>
    </div>

@endsection