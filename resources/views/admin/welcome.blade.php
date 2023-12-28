@extends('template.base')

@section('content')
<!-- Inicio do conteudo do administrativo -->
<div class="row mx-1">
        <div class="box box-first">
            <span class="fa-solid fa-users text-white"></span>
            <span class="text-white">397</span>
            <span class="text-white">Usuários</span>
        </div>
    
        <div class="box box-second">
            <span class="fa-solid fa-truck-ramp-box text-white"></span>
            <span class="text-white">43</span>
            <span class="text-white">Entregas</span>
        </div>
    
        <div class="box box-third">
            <span class="fa-solid fa-circle-check text-white"></span>
            <span class="text-white">12</span>
            <span class="text-white">Completas</span>
        </div>
    
        <div class="box box-fourth">
            <span class="fa-solid fa-triangle-exclamation text-white"></span>
            <span class="text-white">3</span>
            <span class="text-white">Alertas</span>
        </div>
</div>
<!-- Fim do conteudo do administrativo -->
@endsection