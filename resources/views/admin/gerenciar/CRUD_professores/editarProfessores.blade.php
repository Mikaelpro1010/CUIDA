@extends('componentes/layout')
@section('conteudo')
<!-- Inicio do conteudo do administrativo -->
<div class="top-list">
    <span class="title-content">Editar Professor</span>
    <div class="top-list-right">
        <a href="{{ route('listarProfessor') }}" class="btn-info">Listar</a>
    </div>
</div>
@can(permissionConstant()::GERENCIAR_PROFESSORES_EDIT)
<div class="content-adm">
    <form class="form-adm" action="{{route('atualizarProfessor', $professor)}}" method="POST">
        {{ csrf_field() }}
        <div class="row-input">
            <div class="column">
                <label class="title-input">Nome</label>
                <input type="text" name="name" id="name" class="input-adm" value="">
            </div>
            <div class="column">
                <label class="title-input">Disciplina</label>
                <input type="text" name="disciplina" id="disciplina" class="input-adm" value="">
            </div>
        </div>

        <button type="submit" class="btn-warning">Salvar</button>
    </form>
</div>
@endcan
<!-- Fim do conteudo do administrativo -->
@endsection