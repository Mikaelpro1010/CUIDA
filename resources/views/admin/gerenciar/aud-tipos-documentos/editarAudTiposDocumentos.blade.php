@extends('template.base')

@section('content')
<!-- Inicio do conteudo do administrativo -->
<div class="row p-3">
    <div class="top-list">
        <span class="title-content">Editar Tipo de Documento</span>
        <div class="top-list-right">
            <a href="{{ route('listarAudTiposDocumentos') }}" class="btn btn-outline-info">Listar</a>
        </div>
    </div>
    
    <div class="content-adm">
        <form class="form-adm" action="{{route('atualizarAudTipoDocumento', $AudTipoDocumento)}}" method="POST">
            {{ csrf_field() }}
            <div class="row-input">
                <div class="column">
                    <label class="title-input">Nome</label>
                    <input type="text" name="nome" id="nome" class="input-adm" value="{{$AudTipoDocumento->nome}}">
                </div>
                <div class="column">
                    <label class="title-input">Interno</label>
                    <input type="number" name="interno" id="interno" class="input-adm" value="{{$AudTipoDocumento->interno}}">
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