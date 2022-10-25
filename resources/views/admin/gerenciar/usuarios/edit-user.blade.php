@extends('template.base')

@section('content')
<div class="d-flex justify-content-between">
    <h1 class="text-primary">Editar Usuário</h1>
    @can(permissionConstant()::GERENCIAR_USUARIOS_EDIT_PASSWORD)
    <div>
        <a class="btn btn-warning" href="{{ route('get-edit-user-password-view', $user) }}">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar Senha
        </a>
    </div>
    @endcan
</div>
<hr>
<form method="POST" action="{{ route('patch-update-user', $user) }}">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <div class="row">
        <div class="col-md-4">
            <label class="fw-bold" for="">Nome:</label>
            <input class="form-control" type="text" name="name" value="{{ $user->name }}">
        </div>
        <div class="col-md-4">
            <label class="fw-bold" for="">Email:</label>
            <input class="form-control" type="email" name="email" value="{{ $user->email }}">
        </div>
        <div class="col-md-4">
            <label class="fw-bold" for="">Tipo de Usuário:</label>
            <select class="form-select" name="tipo">
                @foreach ($roles as $role)
                <option @if ($role->id == $user->role_id) selected @endif value="{{ $role->id }}">
                    {{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="d-flex justify-content-end mt-2">
        <button class="btn btn-primary " type="submit">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar
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