@extends('template.base')

@section('content')
<div class="row">
    <div class="top-list">
        <span class="title-content">Cadastrar Tipo de Usuário</span>
        <div class="top-list-right">
            <a href="{{ route('get-roles-list') }}" class="btn-info">Listar</a>
        </div>
    </div>
    <form method="POST" action="{{ route('post-store-role') }}">
        {{ csrf_field() }}
        <div class="row g-2">
            <div class="col-md-12">
                <label class="fw-bold" for="">Nome:</label>
                <input class="form-control" type="text" name="name" required>
            </div>
        </div>

        <hr>

        @component('admin.config.tipos-usuario.components.permissions', compact('permissionGroups'))
        @endcomponent

        <a type="submit" class="btn-success mt-3">Cadastrar</a>
    </form>
</div>
@endsection