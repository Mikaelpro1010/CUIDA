@extends('template.base')

@section('titulo', 'EscutaSol - Inicio')
@section('content')
<div>
    <div class="d-sm-grid d-md-flex justify-content-between d-flex align-items-center">
        <h1 class="d-flex align-self-middle text-primary">Bem Vindo!</h1>
    </div>
    <hr>

    @if (auth()->user()->can(permissionConstant()::RELATORIO_AVALIACOES_GERAL_VIEW) ||
    auth()->user()->can(permissionConstant()::UNIDADE_SECRETARIA_LIST) )
    <h3>Relatórios:</h3>
    <div class="row gap-2">
        @can(permissionConstant()::RELATORIO_AVALIACOES_GERAL_VIEW)
        <div class="col-md-3">
            <a href="{{ route('resumo-avaliacoes') }}" class="btn btn-primary w-100">
                <i class="fa-5x fa-solid fa-chart-line"></i> <br>
                <b>Avaliações Gerais</b>
            </a>
        </div>
        @endcan
        @can(permissionConstant()::RELATORIO_AVALIACOES_SECRETARIA_VIEW)
        <div class="col-md-3">
            <a href="{{ route('resumo-avaliacoes-secretaria-list') }}" class="btn btn-primary w-100">
                <i class="fa-5x fa-solid fa-chart-column"></i> <br>
                <b>Avaliações por Secretaria</b>
            </a>
        </div>
        @endcan
        @can(permissionConstant()::RELATORIO_AVALIACOES_UNIDADE_VIEW)
        <div class="col-md-3">
            <a href="{{ route('resumo-avaliacoes-unidade-list') }}" class="btn btn-primary w-100">
                <i class="fa-5x fa-solid fa-chart-area"></i> <br>
                <b>Avaliações por Unidade</b>
            </a>
        </div>
        @endcan
    </div>
    @endif

    @if(auth()->user()->can(permissionConstant()::GERENCIAR_USUARIOS_LIST) ||
    auth()->user()->can(permissionConstant()::GERENCIAR_SECRETARIAS_LIST) ||
    auth()->user()->can(permissionConstant()::UNIDADE_SECRETARIA_LIST))
    <h3 class="mt-3">Gerenciar:</h3>
    <div class="row gap-2">
        @can(permissionConstant()::UNIDADE_SECRETARIA_LIST)
        <div class="col-md-3">
            <a href="{{ route('get-unidades-secr-list') }}" class="btn btn-success w-100">
                <i class="fa-5x fa-solid fa-users"></i> <br>
                <b>Unidades da Secretaria</b>
            </a>
        </div>
        @endcan
        @can(permissionConstant()::GERENCIAR_USUARIOS_LIST)
        <div class="col-md-3">
            <a href="{{ route('get-users-list') }}" class="btn btn-success w-100">
                <i class="fa-5x fa-solid fa-users"></i> <br>
                <b>Usuários</b>
            </a>
        </div>
        @endcan
        @can(permissionConstant()::GERENCIAR_SECRETARIAS_LIST)
        <div class="col-md-3">
            <a href="{{ route('get-secretarias-list') }}" class="btn btn-success w-100">
                <i class="fa-5x fa-solid fa-building-columns"></i> <br>
                <b>Secretarias</b>
            </a>
        </div>
        @endcan
    </div>
    @endif

    @if(auth()->user()->can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_LIST))
    <h3 class="mt-3">Configurações:</h3>
    <div class="row gap-2">
        @can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_LIST)
        <div class="col-md-3">
            <a href="{{ route('get-roles-list') }}" class="btn btn-secondary w-100">
                <i class="fa-5x fa-solid fa-users-gear"></i> <br>
                <b>Tipos de Usuários</b>
            </a>
        </div>
        @endcan
    </div>
    @endif
</div>
@endsection