@extends('template.base')

@section('titulo', 'EscutaSol - Editar Senha')
@section('content')
<div class="row p-3">
    <div class="top-list">
        <span class="title-content">Alterar Senha</span>
    </div>
    <form method="POST" class="form-adm" action="{{ route('patch-update-user-perfil-password') }}">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}
        <div class="row-input">
            <div class="column">
                <span class="view-adm-title" for="current_password">Senha atual: </span>
                <input class="input-adm" type="password" name="current_password" required>
            </div>
        </div>
        <div class="row-input">
            <div class="column">
                <span class="view-adm-title" for="password">Nova Senha: </span>
                <input class="input-adm" type="password" name="new_password" required>
            </div>
            <div class="column">
                <span class="view-adm-title" for="confirm_password">Confirmar Nova Senha: </span>
                <input class="input-adm" type="password" name="new_password_confirmation" required>
            </div>
        </div>
        <div class="text-center mt-3">
            <button type="submit" class="btn btn-outline-warning">Salvar</button>
        </div>
    </form>
</div>
@endsection