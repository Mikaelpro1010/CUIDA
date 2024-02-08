@extends('template.base')

@section('content')
<!-- Inicio do conteudo do administrativo -->
<div class="row p-3">
    <div class="top-list">
        <span class="title-content">Editar Etapa de Documento</span>
        <div class="top-list-right">
            <a href="{{ route('listarAudEtapasDocumentos') }}" class="btn btn-outline-info">Listar</a>
        </div>
    </div>
    
    <div class="content-adm">
        <form class="form-adm" action="{{route('atualizarAudEtapaDocumento', $AudEtapaDocumento)}}" method="POST">
            {{ csrf_field() }}
            <div class="row-input">
                <div class="column">
                    <label class="title-input">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" value="{{$AudEtapaDocumento->nome}}">
                </div>
                <div class="column">
                    <label class="title-input">Icone</label>
                    <input type="text" name="icone" id="icone" class="form-control" value="{{$AudEtapaDocumento->icone}}">
                </div>
                <div class="column">
                    <label class="title-input">Lado Timeline</label>
                    <select name="lado_timeline" class="form-select" aria-label="Default select example">
                        <option selected>Menu de seleção</option>
                        <option value="left">Left</option>
                        <option value="rigth">Rigth</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-outline-warning">Salvar</button>
        </form>
    </div>
    <!-- Fim do conteudo do administrativo -->
</div>
@endsection