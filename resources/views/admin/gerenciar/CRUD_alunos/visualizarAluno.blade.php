@extends('template.base')

@section('content')
<div class="row">
    <!-- Inicio do conteudo do administrativo -->
    <div class="top-list">
        <span class="title-content">Visualizar</span>
        <div class="top-list-right">
            <a href="{{ route('listarAlunos') }}" class="btn btn-outline-info">Listar</a>
        </div>
    </div>
    @can(permissionConstant()::GERENCIAR_ALUNOS_VIEW)
    <div class="content-adm p-3">
        <div class="view-det-adm">
            <span class="view-adm-title">Nome: </span>
            <span class="view-adm-info">{{$aluno->name}}</span>
        </div>
    
        <div class="view-det-adm">
            <span class="view-adm-title">Nota: </span>
            <span class="view-adm-info">{{$aluno->nota}}</span>
        </div>
    </div>
    @endcan
    
    <!-- Fim do conteudo do administrativo -->
</div>
@endsection