@extends('template.base')

@section('content')

<!-- Inicio do conteudo do administrativo -->
<div class="row p-3">
    <div class="top-list">
        <span class="title-content">Listar</span>
        <div class="top-list-right">
            <a href="{{route('visualizarCadastroAudPrazosDocumentos')}}" class="btn btn-outline-success">Cadastrar</a>
            <!--<button type="button" class="btn-success"><i class="fa-solid fa-square-plus"></i></button>-->
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table-list">
            <thead class="list-head">
                <tr>
                    <th class="list-head-content">ID</th>
                    <th class="list-head-content">Nome</th>
                    <th class="list-head-content table-sm-none">Cadastrado Por</th>
                    <th class="list-head-content">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                @foreach($AudPrazosDocumentos as $AudPrazoDocumento)
                <tr>
                    <td class="list-body-content"> {{$AudPrazoDocumento->id}} </td>
                    <td class="list-body-content">{{$AudPrazoDocumento->nome}}</td>
                    <td class="list-body-content table-sm-none">{{$AudPrazoDocumento->usuario->name}}</td>
                    <td class="list-body-content">
                        <a class="btn btn-outline-primary" href="{{ route('visualizarAudPrazoDocumento', $AudPrazoDocumento) }}">Visualizar</a>
                        <a class="btn btn-outline-warning" href="{{ route('editarAudPrazoDocumento', $AudPrazoDocumento) }}">Editar</a>
                        <a id="deleteButton{{ $AudPrazoDocumento->id }}" class="btn btn-outline-danger delete-btn" data-item-id="{{ $AudPrazoDocumento->id }}">Deletar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    
    <div class="justify-content-evenly">
        {{ $AudPrazosDocumentos->links('pagination::bootstrap-4') }}
    </div>
    
    <div id="modalDelete" name="id" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Deletar elemento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="" action="{{ route('deletarAudPrazoDocumento') }}" method="POST">
                        <p>Tem certeza que deseja excluir esses dados?</p>
                        {{ csrf_field() }}
                        <input type="hidden" id="deletar" name="id" value="">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-outline-success" onclick="close_modal()">Deletar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Fim do conteudo do administrativo -->
</div>
@endsection