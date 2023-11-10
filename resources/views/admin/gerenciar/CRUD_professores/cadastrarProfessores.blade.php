@extends('template.base')

@section('content')
<!-- Inicio do conteudo do administrativo -->
<div class="row">
    <div class="top-list">
        @include('componentes/flash-message')
        <span class="title-content">Formulário</span>
        <div class="top-list-right">
            <a href="{{ route('listarProfessores') }}" class="btn-info">Listar</a>
        </div>
    </div>
    @can(permissionConstant()::GERENCIAR_PROFESSORES_CREATE)
    <div class="content-adm">
        <form class="form-adm" action="{{route('cadastrarProfessores')}}" method="post">
            {{ csrf_field() }}
            <div class="row-input">
                <div class="column">
                    <label class="title-input">Nome</label>
                    <input type="text" name="name" id="name" class="input-adm" placeholder="Nome completo">
                </div>
                <div class="column">
                    <label class="title-input">Disciplina</label>
                    <input type="text" name="disciplina" id="disciplina" class="input-adm">
                </div>
            </div>
    
            <button type="submit" class="btn-success">Cadastrar</button>
        </form>
    </div>
    @endcan
    <!-- Fim do conteudo do administrativo -->
</div>
@endsection