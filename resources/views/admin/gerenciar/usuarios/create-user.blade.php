@extends('template.base')

@section('content')
<h1 class="text-primary">Cadastrar Usuário</h1>
<hr>
<form method="POST" action="{{ route('post-store-user') }}">
    {{ csrf_field() }}
    <div class="row g-2">
        <div class="col-md-6">
            <label class="fw-bold" for="">Nome:</label>
            <input class="form-control" type="text" name="name">
        </div>
        <div class="col-md-6">
            <label class="fw-bold" for="">Email:</label>
            <input class="form-control" type="email" name="email">
        </div>
        <div class="col-md-12">
            <label class="fw-bold" for="">Tipo de Usuário:</label>
            <select class="form-select" name="tipo">
                @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="fw-bold" for="">Senha:</label>
            <input class="form-control" type="password" name="senha">
        </div>
        <div class="col-md-4">
            <label class="fw-bold" for="">Confirmar Senha:</label>
            <input class="form-control" type="password" name="senha_confirmation">
        </div>
    </div>
    <div class="d-flex justify-content-end mt-2">
        <button class="btn btn-primary " type="submit">
            <i class="fa-solid fa-user-plus me-1"></i>
            Criar
        </button>
    </div>
</form>

<div class="d-flex justify-content-around">
    <a class="btn btn-warning" href="{{ route('get-users-list') }}">
        <i class="fa-solid fa-chevron-left"></i>
        Voltar
    </a>
</div>
@endsection