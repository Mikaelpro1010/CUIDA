@extends('template.base')

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
            <div class="col-6">
                <label for="nome" class="form-label mb-1 mt-3">Campo para editar a pergunta:</label>
                <input type="text" name="pergunta" id="pergunta" class="form-control" value="{{ $FAQ->pergunta }}">
            </div>
            <div class="col-6">
                <label for="resposta" class="form-label mb-1 mt-3">Campo para editar a resposta:</label>
                <input type="text" name="resposta" id="resposta" class="form-control" value="{{ $FAQ->resposta }}">
            </div>
            <div class="col-6">
                <button class="btn btn-info mt-4" type="submit">Salvar edição</button>
            </div>
    </form>
@endsection