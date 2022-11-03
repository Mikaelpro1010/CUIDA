@extends('template.base')

@section('content')
<h1 class="text-primary">
    Cadastrar Secretaria
</h1>

<hr>

<form class="row" action="{{ route('post-store-secretaria') }}" method="POST">
    {{ csrf_field() }}
    <div class="col-md-3">
        <label for="sigla" class="form-label fw-bold">Sigla:</label>
        <input type="text" name="sigla" id="sigla" class="form-control">
    </div>

    <div class="col-md-9">
        <label for="nome" class="form-label fw-bold">Nome:</label>
        <input type="text" name="nome" id="nome" class="form-control">
    </div>

    <div class="d-flex justify-content-end">
        <button class="btn btn-primary mt-4" type="submit">
            <i class="fa-solid fa-plus me-1"></i>
            Cadastrar
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

@section('scripts')
@endsection