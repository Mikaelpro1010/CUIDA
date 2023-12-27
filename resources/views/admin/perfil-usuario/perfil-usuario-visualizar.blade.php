@extends('template.base')

@section('titulo', 'EscutaSol - Editar Perfil de Usuário')
@section('content')
<div class="row">
    <div class="top-list">
        <h2 class="title-content">Perfil de Usuário</h2>
        <div class="top-list-right">
            <a class="btn btn-warning" href="{{ route('get-edit-user-perfil-view')}}">
                <i class="fa-solid fa-pen-to-square"></i>
                Editar Perfil
            </a>
        </div>
    </div>
    <div class="content-adm">
        <div class="view-det-adm">
            <span class="view-adm-title">Nome: </span>
            <span class="view-adm-info">
                {{ auth()->user()->name }}
            </span>
        </div>
        <div class="view-det-adm">
            <span class="view-adm-title">E-mail: </span>
            <span class="view-adm-info">
                {{ auth()->user()->email }}
            </span>
        </div>
        <div class="view-det-adm">
            <span class="view-adm-title">Tipo de Usuário: </span>
            <span class="view-adm-info">
                {{ auth()->user()->role->name }}
            </span>
        </div>
        <div class="view-det-adm">
            <span class="view-adm-title">Última Atualização: </span>
            <span class="view-adm-info">
                {{ formatarDataHora(auth()->user()->updated_at)}}
            </span>
        </div>
    
    </div>
</div>

@endsection