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
                    <input type="text" name="lado_timeline" id="lado_timeline" class="form-control" value="{{$AudEtapaDocumento->lado_timeline}}">
                </div>
                <div class="column">
                    <label class="title-input">Cadastrado Por</label>
                    <input type="number" name="cadastrado_por" id="cadastrado_por" class="form-control" value="{{$AudEtapaDocumento->cadastrado_por}}">
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