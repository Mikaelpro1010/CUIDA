@extends('template.base')

@section('content')
<!-- Inicio do conteudo do administrativo -->
<div class="row p-3">
    <div class="top-list">
        @include('componentes/flash-message')
        <span class="title-content">Formulário</span>
        <div class="top-list-right">
            <a href="{{ route('listarAudStatusDocumentos') }}" class="btn btn-outline-info">Listar</a>
        </div>
    </div>
    
    <div class="content-adm">
        <form class="form-adm" action="{{route('cadastrarAudStatusDocumentos')}}" method="post">
            {{ csrf_field() }}
            <div class="row-input">
                <div class="column">
                    <label class="title-input">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome">
                </div>
            </div>
    
            <div>
                <button type="submit" class="btn btn-outline-success">Salvar</button>
            </div>
        </form>
    </div>
    <!-- Fim do conteudo do administrativo -->
</div>
@endsection