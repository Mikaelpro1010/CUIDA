@extends('template.base')

@section('titulo', 'EscutaSol - Editar Perfil de Usuário')

@section('content')
<div class="row">
    <div class="top-list">
        <span class="title-content">Editar Perfil de Usuário</span>
        <div class="top-list-right">
            <a class="btn btn-warning" href="{{ route('get-user-perfil-password')}}">
                <i class="fa-solid fa-pen-to-square"></i>
                Editar Senha
            </a>
        </div>
    </div>
    <form id="editForm" method="POST" action="{{ route('patch-update-user-perfil') }}">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
        <div class="row-input">
            <div class="column">
                <label for="">Nome:</label>
                <input class="form-control" type="text" name="name" value="{{ auth()->user()->name }}">
            </div>
            <div class="column">
                <label for="">Email:</label>
                <input class="form-control" type="email" name="email" value="{{ auth()->user()->email }}">
            </div>
        </div>

        <div class="text-center mt-3">
            <button type="submit" class="btn btn-outline-warning">Salvar</button>
        </div>
    </form>
</div>

@endsection