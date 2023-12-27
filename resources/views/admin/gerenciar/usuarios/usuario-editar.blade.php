@extends('template.base')

@section('content')
<div class="row">
    <div class="top-list">
        <span class="title-content">Editar Usuário</span>
        @can(permissionConstant()::GERENCIAR_USUARIOS_EDIT_PASSWORD)
        <div class="top-list-right">
            <a class="btn btn-warning" href="{{ route('get-edit-user-password-view', $user) }}">
                <i class="fa-solid fa-pen-to-square"></i>
                Editar Senha
            </a>
            <a href="{{ route('get-users-list') }}" class="btn-info">Listar</a>
        </div>
        @endcan
    </div>

    <div class="content-adm">
        <form class="form-adm" id="editForm" method="POST" action="{{ route('patch-update-user', $user) }}">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <div class="row-input">
                <div class="column">
                    <label class="fw-bold" for="">Nome:</label>
                    <input class="form-control" type="text" name="name" value="{{ $user->name }}">
                </div>
                <div class="column">
                    <label class="fw-bold" for="">Email:</label>
                    <input class="form-control" type="email" name="email" value="{{ $user->email }}">
                </div>
                <div class="column">
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
    </div>
    <div class="row-input">
        <div class="column">
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
    
    <div class="row-input">
        <div class="column">
            <ul id="secretarias_list" class="list-group">
                @foreach ($user->secretarias as $secretaria)
                <li id="list_{{$secretaria->id}}">
                    {{$secretaria->sigla}} - {{$secretaria->nome}}
                    <button class="btn deleteSecretaria" data-id="{{ $secretaria->id }}">
                        <i class="fa-xl text-danger fa-solid fa-trash"></i>
                    </button>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    
    
    <div class="text-center mt-3">
        <button id="btnEditForm" class="btn-warning">Salvar</button>
    </div>
    @endsection
</div>

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