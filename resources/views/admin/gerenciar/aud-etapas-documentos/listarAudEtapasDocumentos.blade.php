@extends('template.base')

@section('content')

<!-- Inicio do conteudo do administrativo -->
<div class="row p-3">
    <div class="top-list">
        @include('componentes/flash-message')
        
            <span class="title-content">Listar</span>
            <div class="top-list-right">
                <a href="{{route('visualizarAudEtapaDocumento')}}" class="btn btn-outline-success">Cadastrar</a>
                <!--<button type="button" class="btn-success"><i class="fa-solid fa-square-plus"></i></button>-->
            </div>
        @endcan
    </div>
    
    <div class="table-responsive">
        <table class="table-list">
            <thead class="list-head">
                <tr>
                    <th class="list-head-content">ID</th>
                    <th class="list-head-content">Nome</th>
                    <th class="list-head-content table-sm-none">Icone</th>
                    <th class="list-head-content table-sm-none">Lado Timeline</th>
                    <th class="list-head-content table-sm-none">Cadastrado Por</th>
                    <th class="list-head-content">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                @foreach($AudEtapasDocumentos as $AudEtapaDocumento)
                <tr>
                    <td class="list-body-content"> {{$AudEtapaDocumento->id}} </td>
                    <td class="list-body-content">{{$AudEtapaDocumento->nome}}</td>
                    <td class="list-body-content">{{$AudEtapaDocumento->icone}}</td>
                    <td class="list-body-content">{{$AudEtapaDocumento->lado_timeline}}</td>
                    <td class="list-body-content table-sm-none">{{$AudEtapaDocumento->cadastrado_por}}</td>
                    <td class="list-body-content">
                        <a class="btn btn-outline-primary" href="{{ route('visualizarAudEtapasDocumentos', $AudEtapaDocumento) }}">Visualizar</a>
                        <a class="btn btn-outline-warning" href="{{ route('editarAudEtapasDocumentos', $AudEtapaDocumento) }}">Editar</a>
                        <a class="btn btn-outline-danger" href="javascript:deleteItem({{ $AudEtapaDocumento->id }})">Deletar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @endcan
    
    <div class="justify-content-evenly">
        {{ $AudEtapasDocumentos->links('pagination::bootstrap-4') }}
    </div>
    
    <div id="modalDelete" name="id" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Deletar elemento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="" action="{{ route('deletarAluno') }}" method="POST">
                        <p>Tem certeza que deseja excluir esses dados?</p>
                        {{ csrf_field() }}
                        <input type="hidden" id="deletar" name="id" value="">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success" onclick="close_modal()">Deletar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcan
    <!-- Fim do conteudo do administrativo -->
    @endsection
</div>


@push('scripts')
<script>
    function deleteItem(id) {
        $('#deletar').val(id);
        $('#modalDelete').modal('show');
    }

    function close_modal() {
        $('#modalDelete').modal('hide');
    }
</script>
@endpush