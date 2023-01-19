@extends('template.base')

@section('titulo', 'EscutaSol - Editar Senha')
@section('content')
<div class="d-flex justify-content-between">
    <h2 class="text-primary">Alterar Senha</h2>
</div>
<hr>
<form method="POST" action="{{ route('patch-update-user-perfil-password') }}">
    {{ method_field('PATCH') }}
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-6">
            <label class="fw-bold" for="current_password">Senha atual:</label>
            <input class="form-control" type="password" name="current_password" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label class="fw-bold" for="password">Nova senha:</label>
            <input class="form-control" type="password" name="new_password" required>
        </div>
        <div class="col-md-6">
            <label class="fw-bold" for="confirm_password">Confirmar nova senha:</label>
            <input class="form-control" type="password" name="new_password_confirmation" required>
        </div>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <button class="btn btn-primary" type="submit">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar
        </button>
    </div>
</form>

<div class="d-flex justify-content-around">
    <a class="btn btn-warning" href="{{ route('get-user-perfil') }}">
        <i class="fa-solid fa-chevron-left"></i>
        Voltar
    </a>
</div>
@endsection