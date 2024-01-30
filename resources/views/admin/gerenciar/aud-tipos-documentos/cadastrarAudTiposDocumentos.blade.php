@extends('template.base')

@section('content')
<!-- Inicio do conteudo do administrativo -->
<div class="row p-3">
    <div class="top-list">
        @include('componentes/flash-message')
        <span class="title-content">Formulário</span>
        <div class="top-list-right">
            <a href="{{ route('listarAudTiposDocumentos') }}" class="btn btn-outline-info">Listar</a>
        </div>
    </div>
    
    <div class="content-adm">
        <form class="form-adm" action="{{route('cadastrarAudTiposDocumentos')}}" method="post">
            {{ csrf_field() }}
            <div class="row-input">
                <div class="column">
                    <label class="title-input">Nome</label>
                    <input type="text" name="nome" id="nome" class="input-adm" placeholder="Nome">
                </div>
                <div class="column">
                    <label class="title-input">Interno</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="interno" id="exampleRadios1" value="1" checked>
                        <label class="form-check-label" for="exampleRadios1">
                          Sim
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="interno" id="exampleRadios2" value="2">
                        <label class="form-check-label" for="exampleRadios2">
                          Não
                        </label>
                      </div>
                    </div>
                    {{-- <input type="number" name="interno" id="interno" class="input-adm" placeholder="Interno"> --}}
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