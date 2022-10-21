@extends('template.base')

@section('content')
<h1 class="text-primary">Editar Usuário</h1>
<hr>
<form method="POST" action="{{ route('patch-update-role', $role) }}">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <div class="row">
        <div class="col-md-6">
            <label class="fw-bold" for="">Nome:</label>
            <input class="form-control" type="text" name="name" value="{{ $role->name }}">
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
                <input class="form-check-input me-1" type="checkbox" name="permissions[]"
                    value="{{permission()::getPermission($permission)->id}}" @if ($role->hasPermission($permission))
                checked @endif>
                <label class=" form-check-label">{{ $permission }}</label>
            </li>
            @endforeach
        </ul>
        @endforeach
    </div>

    <div class="d-flex justify-content-end mt-2">
        <button class="btn btn-primary " type="submit">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar
        </button>
    </div>
</form>
<div class="d-flex justify-content-around">
    <a class="btn btn-warning" href="{{ route('get-roles-list') }}">
        <i class="fa-solid fa-chevron-left"></i>
        Voltar
    </a>
</div>
@endsection