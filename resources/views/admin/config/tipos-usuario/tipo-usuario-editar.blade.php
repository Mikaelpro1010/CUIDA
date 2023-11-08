@extends('componentes/layout')

@section('conteudo')
<h1 class="text-primary">Editar Tipo de Usuário</h1>
<hr>
<form method="POST" action="{{ route('patch-update-role', $role) }}">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <div class="row">
        <div class="col-md-6">
            <label class="fw-bold" for="">Nome:</label>
            <input class="form-control" type="text" name="name" value="{{ $role->name }}">
        </div>
    </div>

    <hr>

    @component('admin.config.tipos-usuario.components.permissions', compact('permissionGroups', 'role'))
    @endcomponent

    <div class="d-flex justify-content-end mt-2">
        <button class="btn btn-primary " type="submit">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar
        </button>
    </div>
</form>
<div class="d-flex justify-content-around">
    <a class="btn btn-warning" href="{{ route('get-roles-list') }}">
        <i class="fa-solid fa-chevron-left"></i>
        Voltar
    </a>
</div>
@endsection