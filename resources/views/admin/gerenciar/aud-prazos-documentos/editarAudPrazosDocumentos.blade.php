@extends('template.base')

@section('content')
<!-- Inicio do conteudo do administrativo -->
<div class="row p-3">
    <div class="top-list">
        <span class="title-content">Editar Prazo de Documento</span>
        <div class="top-list-right">
            <a href="{{ route('listarAudPrazosDocumentos') }}" class="btn btn-outline-info">Listar</a>
        </div>
    </div>
    
    <div class="content-adm">
        <form class="form-adm" action="{{route('atualizarAudPrazoDocumento', $AudPrazoDocumento)}}" method="POST">
            {{ csrf_field() }}
            <div class="row-input">
                <div class="column">
                    <label class="title-input">Nome</label>
                    <input type="text" name="nome" id="nome" class="input-adm" value="{{$AudPrazoDocumento->nome}}">
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-outline-warning">Salvar</button>
            </div>
        </form>
    </div>
    <!-- Fim do conteudo do administrativo -->
</div>
@endsection