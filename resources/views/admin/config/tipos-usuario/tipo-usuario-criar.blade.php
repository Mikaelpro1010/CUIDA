@extends('template.base')

@section('content')
<h1 class="text-primary">Cadastrar Tipo de Usuário</h1>
<hr>
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

    <div class="d-flex justify-content-end mt-2">
        <button class="btn btn-primary " type="submit">
            <i class="fa-solid fa-plus me-1"></i>
            Criar
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