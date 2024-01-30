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
                    <input type="text" name="nome" id="nome" class="input-adm" placeholder="Nome">
                </div>
                <div class="column">
                    <label class="title-input">Icone</label>
                    <input type="text" name="icone" id="icone" class="input-adm" placeholder="Icone">
                </div>
                <div class="column">
                    <label class="title-input">Lado Timeline</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="lado_timeline" id="exampleRadios1" value="left" checked>
                        <label class="form-check-label" for="exampleRadios1">
                          Left
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="lado_timeline" id="exampleRadios2" value="rigth">
                        <label class="form-check-label" for="exampleRadios2">
                          Rigth
                        </label>
                      </div>
                    </div>
                    {{-- <input type="text" name="lado_timeline" id="lado_timeline" class="input-adm" placeholder="Lado Timeline"> --}}
                </div>
                <div class="column">
                    <label class="title-input">Cadastrado Por</label>
                    <input type="number" name="cadastrado_por" id="cadastrado_por" class="input-adm" placeholder="Cadastrado Por">
                </div>
            </div>
    
            <div class="text-center">
                <button type="submit" class="btn btn-outline-success">Cadastrar</button>
            </div>
        </form>
    </div>
    <!-- Fim do conteudo do administrativo -->
</div>
@endsection