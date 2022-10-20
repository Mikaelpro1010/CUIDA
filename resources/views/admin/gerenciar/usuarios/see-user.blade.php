@extends('template.base')

@section('content')
<div class="d-flex justify-content-between">
    <h1 class="m-0 text-primary">Informações do Usuário</h1>
    <div>
        <a class="btn btn-warning" href="{{ route('get-edit-user-view', $user) }}">
            <i class="fa-solid fa-pen-to-square me-1"></i>
            Editar Usuário
        </a>
    </div>
</div>
<hr>

<div class="row">
    <div class="col-md-1">
        <label class="fw-bold" for="">Id</label>
        <p class="border-bottom border-2 border-warning px-2 ">{{ $user->id }}</p>
    </div>
    <div class="col-md-4">
        <label class="fw-bold" for="">Nome</label>
        <p class="border-bottom border-2 border-warning px-2 ">{{ $user->name }}</p>
    </div>
    <div class="col-md-4">
        <label class="fw-bold" for="">Email</label>
        <p class="border-bottom border-2 border-warning px-2 ">{{ $user->email }}</p>
    </div>
    <div class="col-md-2">
        <label class="fw-bold" for="">Tipo de Usuário</label>
        <p class="border-bottom border-2 border-warning px-2 ">{{ $user->role->name }}</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <label class="fw-bold" for="">Data de Cadastro</label>
        <p class="border-bottom border-2 border-warning px-2 ">{{ formatarDataHora($user->created_at) }}</p>
    </div>
    <div class="col-md-4">
        <label class="fw-bold" for="">Ultima Atualização</label>
        <p class="border-bottom border-2 border-warning px-2 ">{{ formatarDataHora($user->updated_at) }}</p>
    </div>
</div>

<div class="d-flex justify-content-around">
    <a class="btn btn-warning" href="{{ route('get-users-list') }}">
        <i class="fa-solid fa-chevron-left"></i>
        Voltar
    </a>
</div>
@endsection