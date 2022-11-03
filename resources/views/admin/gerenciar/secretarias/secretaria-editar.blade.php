@extends('template.base')

@section('content')
<h1 class="text-primary">
    Editar Secretaria
</h1>

<hr>

<form class="row" action="{{ route('patch-update-secretaria', $secretaria) }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <div class="col-md-3">
        <label for="nome" class="form-label fw-bold">Sigla:</label>
        <input type="text" name="sigla" id="sigla" class="form-control" value="{{ $secretaria->sigla }}">
    </div>

    <div class="col-md-9">
        <label for="nome" class="form-label fw-bold">Nome:</label>
        <input type="text" name="nome" id="nome" class="form-control" value="{{ $secretaria->nome }}">
    </div>

    <div class="d-flex justify-content-end">
        <button class="btn btn-primary mt-4" type="submit">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar
        </button>
    </div>
</form>

<div class="text-center">
    <a href="{{ route('get-secretarias-list') }}" class="mt-3 btn btn-warning">
        <i class="fa-solid fa-chevron-left"></i>
        Voltar
    </a>
</div>
@endsection