@extends('template.base')

@section('content')
<div class="row">
    <div class="top-list">
        <span class="title-content">Editar Tipo de Usuário</span>
        <div class="top-list-right">
            <a href="{{ route('get-roles-list') }}" class="btn btn-outline-info">Listar</a>
        </div>
    </div>
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

        <div class="text-center">
            <button type="submit" id="btnEditForm" class="btn btn-outline-warning">Salvar</button>
        </div>
    </form>
</div>
@endsection