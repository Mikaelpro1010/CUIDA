@extends('template.base')

@section('content')
<!-- Inicio do conteudo do administrativo -->
<div class="row">
    <div class="top-list">
        @include('componentes/flash-message')
        <span class="title-content">Formulário</span>
        <div class="top-list-right">
            <a href="{{ route('listarAlunos') }}" class="btn btn-outline-info">Listar</a>
        </div>
    </div>
    @can(permissionConstant()::GERENCIAR_ALUNOS_CREATE)
    <div class="content-adm">
        <form class="form-adm" action="{{route('cadastrarAlunos')}}" method="post">
            {{ csrf_field() }}
            <div class="row-input">
                <div class="column">
                    <label class="title-input">Nome</label>
                    <input type="text" name="name" id="name" class="input-adm" placeholder="Nome completo">
                </div>
                <div class="column">
                    <label class="title-input">Nota</label>
                    <input type="number" name="nota" id="nota" class="input-adm">
                </div>
            </div>
    
            <div class="text-center">
                <button type="submit" class="btn btn-outline-success">Cadastrar</button>
            </div>
        </form>
    </div>
    @endcan
    <!-- Fim do conteudo do administrativo -->
</div>
@endsection