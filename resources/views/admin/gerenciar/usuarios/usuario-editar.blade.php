@extends('template.base')

@section('content')
<div class="d-flex justify-content-between">
    <h1 class="text-primary">Editar Usuário</h1>
    @can(permissionConstant()::GERENCIAR_USUARIOS_EDIT_PASSWORD)
    <div>
        <a class="btn btn-warning" href="{{ route('get-edit-user-password-view', $user) }}">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar Senha
        </a>
    </div>
    @endcan
</div>
<hr>
<form id="editForm" method="POST" action="{{ route('patch-update-user', $user) }}">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <div class="row">
        <div class="col-md-4">
            <label class="fw-bold" for="">Nome:</label>
            <input class="form-control" type="text" name="name" value="{{ $user->name }}">
        </div>
        <div class="col-md-4">
            <label class="fw-bold" for="">Email:</label>
            <input class="form-control" type="email" name="email" value="{{ $user->email }}">
        </div>
        <div class="col-md-4">
            <label class="fw-bold" for="">Tipo de Usuário:</label>
            <select class="form-select" name="tipo">
                @foreach ($roles as $role)
                <option @if ($role->id == $user->role_id) selected @endif value="{{ $role->id }}">
                    {{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="secretarias">
        @foreach ($user->secretarias as $secretaria)
        <input id="secretaria_{{$secretaria->id}}" type="hidden" name="secretaria[]" value="{{$secretaria->id}}">
        @endforeach
    </div>

</form>
<div class="row">
    <div class="col-md-8">
        <label class="fw-bold" for="">Secretaria(s):</label>
        <select id="secretariaSelect" class="form-select">
            <option value="">Selecione</option>
            @foreach ($secretarias as $secretaria)
            <option value="{{ $secretaria->id }}" @if (old('secretaria')==$secretaria->id) selected @endif >
                {{ $secretaria->sigla }} - {{ $secretaria->nome }}
            </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-8">
        <ul id="secretarias_list" class="list-group">
            @foreach ($user->secretarias as $secretaria)
            <li id="list_{{$secretaria->id}}" class="list-group-item d-flex justify-content-between align-items-center">
                {{$secretaria->sigla}} - {{$secretaria->nome}}
                <button class="btn deleteSecretaria" data-id="{{ $secretaria->id }}">
                    <i class="fa-xl text-danger fa-solid fa-trash"></i>
                </button>
            </li>
            @endforeach
        </ul>
    </div>
</div>


<div class="d-flex justify-content-end mt-2">
    <button id="btnEditForm" class="btn btn-primary">
        <i class="fa-solid fa-pen-to-square"></i>
        Editar
    </button>
</div>
<div class="d-flex justify-content-around">
    <a class="btn btn-warning" href="{{ route('get-users-list') }}">
        <i class="fa-solid fa-chevron-left"></i>
        Voltar
    </a>
</div>
@endsection

@push('scripts')
<script nonce="{{ app('csp-nonce') }}">
    $("#btnEditForm").click(function() {
        $("#editForm").submit();
    });

    $("#secretarias_list").on('click', '.deleteSecretaria', function() {
        removerItem($(this).data('id'));
    });

    $("#secretariaSelect").change(function (){
        if($('#secretaria_' + $("#secretariaSelect").val()).length == 0){
            $('#secretarias').append(`
                <input id="secretaria_`+ $("#secretariaSelect").val() +`" type="hidden" name="secretaria[]" value="` + $("#secretariaSelect").val() + `">
            `);
            $("#secretarias_list").append(`
                <li id="list_` + $("#secretariaSelect").val() + `" class="list-group-item d-flex justify-content-between align-items-center">
                   `+ $("#secretariaSelect option:selected").text() +`
                    <button class="btn deleteSecretaria" data-id="` + $("#secretariaSelect").val() + `">
                        <i class="fa-xl text-danger fa-solid fa-trash"></i>
                    </button>
                </li>
            `);
            $("#secretarias_list").removeClass('d-none');
        }
        $("#secretariaSelect").val(''); 
    });

    function removerItem(id){
        $('#secretaria_'+id).remove();
        $('#list_'+id).remove();
    }

</script>
@endpush