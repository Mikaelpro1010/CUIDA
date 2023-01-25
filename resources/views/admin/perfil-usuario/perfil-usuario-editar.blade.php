@extends('template.base')

@section('titulo', 'EscutaSol - Editar Perfil de Usuário')

@section('content')
<div class="d-flex justify-content-between">
    <h2 class="text-primary">Editar Perfil de Usuário</h2>
    <div>
        <a class="btn btn-warning" href="{{ route('get-user-perfil-password')}}">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar Senha
        </a>
    </div>
</div>
<hr>
<form id="editForm" method="POST" action="{{ route('patch-update-user-perfil') }}">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <div class="row">
        <div class="col-md">
            <label class="fw-bold" for="">Nome:</label>
            <input class="form-control" type="text" name="name" value="{{ auth()->user()->name }}">
        </div>
        <div class="col-md">
            <label class="fw-bold" for="">Email:</label>
            <input class="form-control" type="email" name="email" value="{{ auth()->user()->email }}">
        </div>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <button class="btn btn-primary" type="submit">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar
        </button>
    </div>
</form>

<div class="d-flex justify-content-around">
    <a class="btn btn-warning" href="{{ route('get-user-perfil') }}">
        <i class="fa-solid fa-chevron-left"></i>
        Voltar
    </a>
</div>

@endsection