@extends('template.base')

@section('titulo', 'EscutaSol - Faq')
@section('content')
    @if (session('mensagem'))
        <div class="alert alert-success" role="alert">
            {{ session('mensagem') }}
        </div>
    @endif
        <h2 class="text-primary">
            Editar FAQ
        </h2>
        <hr>
    <form class="row" action="{{ route('patch-update-faq', $FAQ->id) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
            <div class="col-12">
                <label for="nome" class="form-label mb-1 mt-3">Campo para editar a pergunta:</label>
                <textarea class="form-control" name="pergunta" id="pergunta" rows="5">{{ $FAQ->pergunta }}</textarea>
            </div>
            <div class="col-12">
                <label for="resposta" class="form-label mb-1 mt-3">Campo para editar a resposta:</label>
                <textarea class="form-control" name="resposta" id="resposta" rows="5">{{ $FAQ->resposta }}</textarea>
            </div>
            <div class="text-center">
                <a href="{{ route('get-faq-list') }}" class="mt-3 btn btn-warning" tabindex="-1"
                role="button" aria-disabled="true">
                <i class="fa-solid fa-chevron-left"></i>
                Voltar
                </a>
            </div>
    </form>
@endsection