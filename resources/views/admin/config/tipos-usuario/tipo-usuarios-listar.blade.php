@extends('template.base')

@section('content')
<div class="row">
    <div class="top-list">
        @include('componentes/flash-message')
        @can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_CREATE)
        <span class="title-content">Tipos de Usuários</span>
        <div class="top-list-right">
            <a href="{{ route('get-create-role') }}" class="btn btn-outline-success">Cadastrar</a>
        </div>
        @endcan
    </div>

    <form action="{{ route('get-roles-list') }}" method="GET">
        <div class="row-input">
            <div class="column">
                <label for="pesquisa">Nome:</label>
                <input id="pesquisa" class="form-control" type="text" name="pesquisa" placeholder="Pesquisar"
                    value="{{ request()->pesquisa }}">
            </div>

            <div class="column align-items-end">
                <button class="btn btn-primary form-control mt-4" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Buscar
                </button>
            </div>
            <div class="column align-items-end">
                <a id="btnLimpaForm" class="btn btn-warning form-control mt-4">
                    Limpar
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </div>

    </form>

    <div class="table-responsive">
        <table class="table-list">
            <thead class="list-head">
                <tr>
                    <th class="list-head-content">Id</th>
                    <th class="list-head-content">Nome</th>
                    <th class="list-head-content">Qtd Usuários</th>
                    <th class="list-head-content">Data de Registro</th>
                    <th class="list-head-content">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                @forelse ($roles as $role)
                <tr id="{{ $role->id }}">
                    <td class="list-body-content">{{ $role->id }}</td>
                    <td class="name list-body-content">{{ $role->name }}</td>
                    <td class="list-body-content">{{ $role->users->count() }}</td>
                    <td class="list-body-content">{{ formatarDataHora($role->created_at) }}</td>
                    <td class="list-body-content">
                            @can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_VIEW)
                            <a class="btn btn-outline-primary" href="{{ route('get-role-view', $role) }}">Visualizar</a>
                            @endcan
    
                            @can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_EDIT)
                            <a class="btn btn-outline-warning" href="{{ route('get-edit-role-view', $role) }}">Editar</a>
                            @endcan
    
                            @can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_DELETE)
                            <form class="d-none" id="deleteRole_{{ $role->id }}"
                                action="{{ route('delete-delete-role', $role) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                            <button class="btnDelete btn-outline-danger" data-id="{{ $role->id }}">Deletar</button>
                            @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="table-warning text-center" colspan="6">Nenhum resultado Encontrado!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="justify-content-evenly">
        {{ $roles->links('pagination::bootstrap-4') }}
    </div>

    @can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_DELETE)
    <div id="deleteModal" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-light">Deletar Usuário!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Deseja realmente deletar o Tipo de Usuário: <span id="deleteRoleName" class="fw-bold"></span></p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">cancelar</button>
                    <button id="btnDeleteConfirm" type="button" class="btn btn-danger">deletar</button>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection

@push('scripts')
<script nonce="{{ app('csp-nonce') }}">
    $('#btnLimpaForm').click(function(){
        $('#pesquisa').val('');
    });

    @can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_DELETE)
    var userType;

    $('.btnDelete').click(function() {
        deleteRole($(this).data('id'));
    });

    function deleteRole(id) {
        $("#deleteRoleName").text($("#" + id + " .name").text());
        $('#deleteModal').modal('show');
        userType = id;
    }

    $("#btnDeleteConfirm").click(function() {
        $('#deleteModal').modal('hide');
        $('#deleteRole_' + userType).submit();
        userType = 0;
    });

    @endcan
</script>
@endpush