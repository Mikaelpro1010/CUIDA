@extends('template.base')

@section('content')
<div class="row p-3">
    <!-- Inicio do conteudo do administrativo -->
    <div class="top-list">
        <span class="title-content">Visualizar</span>
        <div class="top-list-right">
            <a href="{{ route('listarAudPrazosDocumentos') }}" class="btn btn-outline-info">Listar</a>
        </div>
    </div>
   
    <div class="content-adm p-3">
        <div class="view-det-adm">
            <span class="view-adm-title">Nome: </span>
            <span class="view-adm-info">{{$AudPrazoDocumento->nome}}</span>
        </div>
    </div>
    
    <!-- Fim do conteudo do administrativo -->
</div>
@endsection