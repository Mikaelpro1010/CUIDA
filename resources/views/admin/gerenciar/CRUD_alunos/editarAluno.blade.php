@extends('template.base')

@section('content')
<!-- Inicio do conteudo do administrativo -->
<div class="row">
    <div class="top-list">
        <span class="title-content">Editar Aluno</span>
        <div class="top-list-right">
            <a href="{{ route('listarAlunos') }}" class="btn-info">Listar</a>
        </div>
    </div>
    @can(permissionConstant()::GERENCIAR_ALUNOS_EDIT)
    <div class="content-adm">
        <form class="form-adm" action="{{route('atualizarAluno', $aluno)}}" method="POST">
            {{ csrf_field() }}
            <div class="row-input">
                <div class="column">
                    <label class="title-input">Nome</label>
                    <input type="text" name="name" id="name" class="input-adm" value="{{$aluno->name}}">
                </div>
                <div class="column">
                    <label class="title-input">Nota</label>
                    <input type="number" name="nota" id="nota" class="input-adm" value="{{$aluno->nota}}">
                </div>
            </div>
    
            <button type="submit" class="btn-warning">Salvar</button>
        </form>
    </div>
    @endcan
    <!-- Fim do conteudo do administrativo -->
</div>
@endsection