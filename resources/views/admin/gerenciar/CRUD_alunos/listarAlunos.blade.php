@extends('template.base')

@section('content')

<!-- Inicio do conteudo do administrativo -->
<div class="top-list">
    @include('componentes/flash-message')
    @can(permissionConstant()::GERENCIAR_ALUNOS_CREATE)
        <span class="title-content">Listar</span>
        <div class="top-list-right">
            <a href="{{route('visualizarCadastroAluno')}}" class="btn-success">Cadastrar</a>
            <!--<button type="button" class="btn-success"><i class="fa-solid fa-square-plus"></i></button>-->
        </div>
    @endcan
</div>
@can(permissionConstant()::GERENCIAR_ALUNOS_LIST)
<table class="table-list">
    <thead class="list-head">
        <tr>
            <th class="list-head-content">ID</th>
            <th class="list-head-content">Nome</th>
            <th class="list-head-content table-sm-none">Nota</th>
            <th class="list-head-content">Ações</th>
        </tr>
    </thead>
    <tbody class="list-body">
        @foreach($alunos as $aluno)
        <tr>
            <td class="list-body-content"> {{$aluno->id}} </td>
            <td class="list-body-content">{{$aluno->name}}</td>
            <td class="list-body-content table-sm-none">{{$aluno->nota}}</td>
            <td class="list-body-content">
                <a class="btn btn-outline-primary" href="{{ route('visualizarAluno', $aluno) }}">Visualizar</a>
                <a class="btn btn-outline-warning" href="{{ route('editarAluno', $aluno) }}">Editar</a>
                <a class="btn btn-outline-danger" href="javascript:deleteItem({{ $aluno->id }})">Deletar</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endcan

<div class="d-flex justify-content-evenly">
    {{ $alunos->links('pagination::bootstrap-4') }}
</div>
@can(permissionConstant()::GERENCIAR_ALUNOS_DELETE)
<div id="modalDelete" name="id" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deletar elemento</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="" action="{{ route('deletarAluno') }}" method="POST">
                    <p>Tem certeza que deseja excluir esses dados?</p>
                    {{ csrf_field() }}
                    <input type="hidden" id="deletar" name="id" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
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