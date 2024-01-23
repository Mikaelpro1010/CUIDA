@extends('template.base')

@section('content')
<div class="row p-3">
    <!-- Inicio do conteudo do administrativo -->
    <div class="top-list">
        <span class="title-content">Visualizar</span>
        <div class="top-list-right">
            <a href="{{ route('listarAudEtapasDocumentos') }}" class="btn btn-outline-info">Listar</a>
        </div>
    </div>
   
    <div class="content-adm p-3">
        <div class="view-det-adm">
            <span class="view-adm-title">Nome: </span>
            <span class="view-adm-info">{{$AudEtapaDocumento->nome}}</span>
        </div>
        
        <div class="view-det-adm">
            <span class="view-adm-title">Icone: </span>
            <span class="view-adm-info">{{$AudEtapaDocumento->icone}}</span>
        </div>
        
        <div class="view-det-adm">
            <span class="view-adm-title">Lado Timeline: </span>
            <span class="view-adm-info">{{$AudEtapaDocumento->lado_timeline}}</span>
        </div>

        <div class="view-det-adm">
            <span class="view-adm-title">Cadastrado Por: </span>
            <span class="view-adm-info">{{$AudEtapaDocumento->cadastrado_por}}</span>
        </div>
    </div>
    
    <!-- Fim do conteudo do administrativo -->
</div>
@endsection