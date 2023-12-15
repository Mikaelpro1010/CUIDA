@extends('template.base')

@section('content')
<div class="row">
    <div class="top-list">
        <span class="title-content">Cadastrar Usuário</span>
        <div class="top-list-right">
            <a href="{{ route('get-users-list') }}" class="btn-info">Listar</a>
        </div>
    </div>
    <div class="content-adm">
        <form class="form-adm" id="cadastrar" method="POST" action="{{ route('post-store-user') }}">
            {{ csrf_field() }}
            <div class="row-input">
                <div class="column">
                    <label class="fw-bold" for="">Nome:</label>
                    <input class="form-control" type="text" name="name" value="{{old('name')}}">
                </div>
                <div class="column">
                    <label class="fw-bold" for="">Email:</label>
                    <input class="form-control" type="email" name="email" value="{{old('email')}}">
                </div>
        
                <div class="column">
                    <label class="fw-bold" for="">Tipo de Usuário:</label>
                    <select class="form-select" name="tipo_usuario">
                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @if (old('tipo_usuario')==$role->id) selected @endif >{{ $role->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="column">
                    <label class="fw-bold" for="">Senha:</label>
                    <input class="form-control" type="password" name="senha">
                </div>
                <div class="column">
                    <label class="fw-bold" for="">Confirmar Senha:</label>
                    <input class="form-control" type="password" name="senha_confirmation">
                </div>
        
                <div id="secretarias"></div>
            </div>
            
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

            <div class="column mb-3">
                <ul id="secretarias_list" class="list-group d-none">
                </ul>
            </div>

            <a id="btnCadastrar" class="btn-success mt-3">Cadastrar</a>
        </form>
        
    </div>
</div>
@endsection

@push('scripts')
<script nonce="{{ app('csp-nonce') }}">
    $("#secretariaSelect").change(function (){
        if($('#secretaria_' + $("#secretariaSelect").val()).length == 0){
            $('#secretarias').append(`
                <input id="secretaria_`+ $("#secretariaSelect").val() +`" type="hidden" name="secretaria[]" value="` + $("#secretariaSelect").val() + `">
            `);
            $("#secretarias_list").append(`
                <li id="list_` + $("#secretariaSelect").val() + `" class="list-group-item d-flex justify-content-between">`+ $("#secretariaSelect option:selected").text() +`
                    <a href="javascript:removerItem(` + $("#secretariaSelect").val() + `)">
                        <i class="fa-xl text-danger fa-solid fa-trash"></i>
                    </a>
                </li>
            `);
            $("#secretarias_list").removeClass('d-none');
        }
        $("#secretariaSelect").val(''); 
    });

    $('#btnCadastrar').click(function(){
        $('#cadastrar').submit();
    })

    function removerItem(id){
        $('#secretaria_'+id).remove();
        $('#list_'+id).remove();
    }

</script>
@endpush