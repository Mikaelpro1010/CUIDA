@extends('template.base')

@section('content')
<div class="row">
    <div class="top-list">
        @can(permissionConstant()::GERENCIAR_USUARIOS_EDIT)
        <span class="title-content">Informações do usuário</span>
        <div class="top-list-right">
            <a class="btn btn-warning" href="{{ route('get-edit-user-view', $user) }}">
                <i class="fa-solid fa-pen-to-square me-1"></i>
                Editar Usuário
            </a>
            <a href="{{ route('get-users-list') }}" class="btn-info">Listar</a>
        </div>
        @endcan
    </div>

    <div class="content-adm">
        <div class="view-det-adm">
            <span class="view-adm-title">Id: </span>
            <span class="view-adm-info">Id</span>
        </div>
        
        <div class="view-det-adm">
            <span class="view-adm-title">Nome: </span>
            <span class="view-adm-info">{{ $user->name }}</span>
        </div>
        
        <div class="view-det-adm">
            <span class="view-adm-title">Email: </span>
            <span class="view-adm-info">{{ $user->email }}</span>
        </div>

        <div class="view-det-adm">
            <span class="view-adm-title">Tipo de Usuário: </span>
            <span class="view-adm-info">{{ $user->role->name }}</span>
        </div>
        
        <div class="view-det-adm">
            <span class="view-adm-title">Secretaria(s): </span>
            @foreach ($user->secretarias as $secretaria)
            <span class="view-adm-info">{{$secretaria->sigla}} - {{$secretaria->nome}}</span>
            @endforeach
        </div>

        <div class="view-det-adm">
            <span class="view-adm-title">Data de Cadastro: </span>
            <span class="view-adm-info">{{ formatarDataHora($user->created_at) }}</span>
        </div>

        <div class="view-det-adm">
            <span class="view-adm-title">Última atualização: </span>
            <span class="view-adm-info">{{ formatarDataHora($user->updated_at) }}</span>
        </div>
    </div>
</div>
@endsection