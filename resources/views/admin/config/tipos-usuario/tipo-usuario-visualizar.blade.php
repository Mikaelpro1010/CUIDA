@extends('template.base')

@section('content')
<div class="row">
    <div class="d-flex justify-content-between">
        <h1 class="m-0 text-primary">Informações do Tipo de Usuário</h1>
        <div>
            <a class="btn btn-warning" href="{{ route('get-edit-role-view', $role) }}">
                <i class="fa-solid fa-pen-to-square me-1"></i>
                Editar Tipo de Usuário
            </a>
        </div>
    </div>
    <hr>

    <div class="row">
        <div class="col-md-1">
            <label class="fw-bold" for="">Id</label>
            <p class="border-bottom border-2 border-warning px-2 ">{{ $role->id }}</p>
        </div>
        <div class="col-md-4">
            <label class="fw-bold" for="">Nome</label>
            <p class="border-bottom border-2 border-warning px-2 ">{{ $role->name }}</p>
        </div>

        <div class="col-md-3">
            <label class="fw-bold" for="">Data de Cadastro</label>
            <p class="border-bottom border-2 border-warning px-2 ">{{ formatarDataHora($role->created_at) }}</p>
        </div>
        <div class="col-md-3">
            <label class="fw-bold" for="">Ultima Atualização</label>
            <p class="border-bottom border-2 border-warning px-2 ">{{ formatarDataHora($role->updated_at) }}</p>
        </div>
    </div>

    <hr>
    <h4 class="text-primary">Permissões</h4>
    <div class="row mx-0">
        @foreach ($permissionGroups as $key => $permissionGroup )
        <ul class="col-md-4 list-group mb-2">
            <li class="list-group-item active">
                {{$key}}
            </li>
            @foreach ($permissionGroup as $permission)
            <li class="list-group-item">
                @if($role->hasPermission($permission))
                <i class="text-primary fa-solid fa-square-check"></i>
                @else
                <i class="text-secondary fa-regular fa-square"></i>
                @endif
                {{ $permission }}
            </li>
            @endforeach
        </ul>
        @endforeach
    </div>

    <div class="mt-3 d-flex justify-content-around">
        <a class="btn btn-warning" href="{{ route('get-roles-list') }}">
            <i class="fa-solid fa-chevron-left"></i>
            Voltar
        </a>
    </div>
</div>
@endsection