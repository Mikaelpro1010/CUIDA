@extends('template.base')

@section('content')
<div class="row">
    <div class="top-list">
        <span class="title-content">Informações do Tipo de Usuário</span>
        <div class="top-list-right">
            <a class="btn btn-warning" href="{{ route('get-edit-role-view', $role) }}">
                <i class="fa-solid fa-pen-to-square me-1"></i>
                Editar Tipo de Usuário
            </a>
            <a href="{{ route('get-roles-list') }}" class="btn btn-info">Listar</a>
        </div>
    </div>

    <div class="content-adm">
        <div class="view-det-adm">
            <span class="view-adm-title">Id: </span>
            <span class="view-adm-info">{{ $role->id }}</span>
        </div>
        <div class="view-det-adm">
            <span class="view-adm-title">Nome: </span>
            <span class="view-adm-info">{{ $role->name }}</span>
        </div>
        <div class="view-det-adm">
            <span class="view-adm-title">Data de Cadastro: </span>
            <span class="view-adm-info">{{ formatarDataHora($role->created_at) }}</span>
        </div>
        <div class="view-det-adm">
            <span class="view-adm-title">Ultima Atualização: </span>
            <span class="view-adm-info">{{ formatarDataHora($role->updated_at) }}</span>
        </div>
        <hr>
    </div>

    <span class="title-content">Permissões</span>
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
</div>
@endsection