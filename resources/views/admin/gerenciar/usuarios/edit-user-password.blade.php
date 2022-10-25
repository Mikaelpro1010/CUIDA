@extends('template.base')

@section('content')
<h1 class="text-primary">Editar Senha</h1>
<hr>
<form method="POST" action="{{ route('patch-update-user-password', $user) }}">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <div class="row">
        <div class="col-md-4">
            <label class="fw-bold" for="">Senha:</label>
            <input class="form-control" type="password" name="senha">
        </div>
        <div class="col-md-4">
            <label class="fw-bold" for="">Confirmar Senha:</label>
            <input class="form-control" type="password" name="senha_confirmation">
        </div>
    </div>
    <div class="d-flex justify-content-end mt-2">
        <button class="btn btn-primary " type="submit">
            <i class="fa-solid fa-pen-to-square"></i>
            Alterar Senha
        </button>
    </div>
</form>
<div class="d-flex justify-content-around">
    <a class="btn btn-warning" href="{{ route('get-users-list') }}">
        <i class="fa-solid fa-chevron-left"></i>
        Voltar
    </a>
</div>
@endsection