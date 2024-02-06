@extends('template.base')

@section('content')
<!-- Inicio do conteudo do administrativo -->
<div class="row p-3">
    <div class="top-list">
        @include('componentes/flash-message')
        <span class="title-content">Formulário</span>
        <div class="top-list-right">
            <a href="{{ route('listarAudEtapasDocumentos') }}" class="btn btn-outline-info">Listar</a>
        </div>
    </div>
    
    <div class="content-adm">
        <form class="form-adm" action="{{route('cadastrarAudEtapasDocumentos')}}" method="post">
            {{ csrf_field() }}
            <div class="row-input">
                <div class="column">
                    <label class="title-input">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome">
                </div>
                <div class="column">
                    <label class="title-input">Icone</label>
                    <input type="text" name="icone" id="icone" class="form-control" placeholder="Icone">
                </div>
                <div class="column">
                    <label class="title-input">Lado Timeline</label>
                    <select name="lado_timeline" class="form-select" aria-label="Default select example">
                        <option selected>Menu de seleção</option>
                        <option value="left">Left</option>
                        <option value="rigth">Rigth</option>
                    </select>
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