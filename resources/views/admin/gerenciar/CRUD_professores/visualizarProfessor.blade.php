@extends('template.base')

@section('content')
<!-- Inicio do conteudo do administrativo -->
<div class="row">
    <div class="top-list">
        <span class="title-content">Visualizar</span>
        <div class="top-list-right">
            <a href="{{ route('listarProfessores') }}" class="btn btn-outline-info">Listar</a>
        </div>
    </div>
    @can(permissionConstant()::GERENCIAR_PROFESSORES_VIEW)
    <div class="content-adm p-3">
        <div class="view-det-adm">
            <span class="view-adm-title">Nome: </span>
            <span class="view-adm-info">{{$professor->name}}</span>
        </div>

        <div class="view-det-adm">
            <span class="view-adm-title">Nota: </span>
            <span class="view-adm-info">{{$professor->disciplina}}</span>
        </div>
    </div>
    @endcan

    <!-- Fim do conteudo do administrativo -->
</div>
@endsection