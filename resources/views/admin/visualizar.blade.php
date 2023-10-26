@extends('componentes/layout')
@section('conteudo')
<!-- Inicio do conteudo do administrativo -->
<div class="wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Visualizar</span>
            <div class="top-list-right">
                <a href="listar.html" class="btn-info">Listar</a>
            </div>
        </div>

        <div class="content-adm">
            <div class="view-det-adm">
                <span class="view-adm-title">Nome: </span>
                <span class="view-adm-info">Cesar</span>
            </div>

            <div class="view-det-adm">
                <span class="view-adm-title">E-mail: </span>
                <span class="view-adm-info">cesar@celke.com.br</span>
            </div>

            <div class="view-det-adm">
                <span class="view-adm-title">Título 1: </span>
                <span class="view-adm-info">Informação 1</span>
            </div>

            <div class="view-det-adm">
                <span class="view-adm-title">Título 2: </span>
                <span class="view-adm-info">Informação 2</span>
            </div>

            <div class="view-det-adm">
                <span class="view-adm-title">Título 3: </span>
                <span class="view-adm-info">Informação 3</span>
            </div>

            <div class="view-det-adm">
                <span class="view-adm-title">Título 4: </span>
                <span class="view-adm-info">Informação 4</span>
            </div>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->
@endsection