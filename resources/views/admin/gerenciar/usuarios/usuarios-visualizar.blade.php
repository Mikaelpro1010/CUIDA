@extends('template.base')

@section('content')
<div class="row">
    <div class="d-flex justify-content-between">
        <h1 class="m-0 text-primary">Informações do Usuário</h1>
        @can(permissionConstant()::GERENCIAR_USUARIOS_EDIT)
        <div>
            <a class="btn btn-warning" href="{{ route('get-edit-user-view', $user) }}">
                <i class="fa-solid fa-pen-to-square me-1"></i>
                Editar Usuário
            </a>
        </div>
        @endcan
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
        <div class="col-md-3">
            <label class="fw-bold" for="">Tipo de Usuário</label>
            <p class="border-bottom border-2 border-warning px-2 ">{{ $user->role->name }}</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <label class="fw-bold">Secretaria(s)</label>
            <ul class="list-group list-group-flush">
                @foreach ($user->secretarias as $secretaria)
                <li class="list-group-item border-bottom border-2 border-warning pb-0">
                    {{$secretaria->sigla}} - {{$secretaria->nome}}
                </li>
                @endforeach
            </ul>
        </div>
    
        <div class="col-md-3">
            <label class="fw-bold" for="">Data de Cadastro</label>
            <p class="border-bottom border-2 border-warning px-2 ">{{ formatarDataHora($user->created_at) }}</p>
        </div>
        <div class="col-md-3">
            <label class="fw-bold" for="">Ultima Atualização</label>
            <p class="border-bottom border-2 border-warning px-2 ">{{ formatarDataHora($user->updated_at) }}</p>
        </div>
    </div>
    
    <div class="d-flex justify-content-around mt-3">
        <a class="btn btn-warning" href="{{ route('get-users-list') }}">
            <i class="fa-solid fa-chevron-left"></i>
            Voltar
        </a>
    </div>
</div>
@endsection