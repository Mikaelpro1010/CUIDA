@extends('template.base')

@section('titulo', 'EscutaSol - Editar Perfil de Usuário')
@section('content')
<div class="d-flex justify-content-between">
    <h2 class="text-primary">Perfil de Usuário</h2>
    <div>
        <a class="btn btn-warning" href="{{ route('get-edit-user-perfil-view')}}">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar Perfil
        </a>
    </div>
</div>
<hr>
<div class="row g-3">
    <div class="col-md-6">
        <label class="fw-bold" for="">Nome:</label>
        <div class="border-2 border-bottom border-warning">
            {{ auth()->user()->name }}
        </div>
    </div>
    <div class="col-md-6">
        <label class="fw-bold" for="">E-mail:</label>
        <div class="border-2 border-bottom border-warning">
            {{ auth()->user()->email }}
        </div>
    </div>
    <div class="col-md-4">
        <label class="fw-bold" for="">Tipo de Usuário:</label>
        <div class="border-2 border-bottom border-warning">
            {{ auth()->user()->role->name }}
        </div>
    </div>
    <div class="col-md-4">
        <label class="fw-bold" for="">Última Atualização:</label>
        <div class="border-2 border-bottom border-warning">
            {{ formatarDataHora(auth()->user()->updated_at)}}
        </div>
    </div>

</div>

@endsection