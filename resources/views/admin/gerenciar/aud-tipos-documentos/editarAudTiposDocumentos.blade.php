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
                    <input type="text" name="nome" id="nome" class="form-control" value="{{$AudTipoDocumento->nome}}">
                </div>
                <div class="column">
                    <label class="title-input">Interno</label>
                    <select name="interno" class="form-select" aria-label="Default select example">
                        <option selected>Menu de seleção</option>
                        <option value="1">Sim</option>
                        <option value="2">Não</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-outline-warning">Salvar</button>
        </form>
    </div>
    <!-- Fim do conteudo do administrativo -->
</div>
@endsection