@extends('template.base')

@section('content')
<!-- Inicio do conteudo do administrativo -->
<div class="row">
    <div class="box box-first">
        <span class="fa-solid fa-users"></span>
        <span>397</span>
        <span>Usuários</span>
    </div>

    <div class="box box-second">
        <span class="fa-solid fa-truck-ramp-box"></span>
        <span>43</span>
        <span>Entregas</span>
    </div>

    <div class="box box-third">
        <span class="fa-solid fa-circle-check"></span>
        <span>12</span>
        <span>Completas</span>
    </div>

    <div class="box box-fourth">
        <span class="fa-solid fa-triangle-exclamation"></span>
        <span>3</span>
        <span>Alertas</span>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->
@endsection