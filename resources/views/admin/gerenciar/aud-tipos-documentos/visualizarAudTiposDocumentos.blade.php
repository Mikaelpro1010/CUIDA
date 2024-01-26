@extends('template.base')

@section('content')
<div class="row p-3">
    <!-- Inicio do conteudo do administrativo -->
    <div class="top-list">
        <span class="title-content">Visualizar</span>
        <div class="top-list-right">
            <a href="{{ route('listarAudTiposDocumentos') }}" class="btn btn-outline-info">Listar</a>
        </div>
    </div>
   
    <div class="content-adm p-3">
        <div class="view-det-adm">
            <span class="view-adm-title">Nome: </span>
            <span class="view-adm-info">{{$AudTipoDocumento->nome}}</span>
        </div>
        <div class="view-det-adm">
            <span class="view-adm-title">Interno: </span>
            <span class="view-adm-info">{{$AudTipoDocumento->interno}}</span>
        </div>
    </div>
    
    <!-- Fim do conteudo do administrativo -->
</div>
@endsection