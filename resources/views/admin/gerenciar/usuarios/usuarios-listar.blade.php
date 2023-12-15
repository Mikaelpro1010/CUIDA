@extends('template.base')

@section('content')
<div class="row">
    <div class="top-list">
        @include('componentes/flash-message')
        @can(permissionConstant()::GERENCIAR_USUARIOS_CREATE)
            <span class="title-content">Usuários</span>
            <div class="top-list-right">
                <a href="{{ route('get-create-user') }}" class="btn-success">Cadastrar</a>
            </div>
        @endcan
    </div>

    <form class="" action="{{ route('get-users-list') }}" method="GET">
         <div class="row-input">
            <div class="column">
                <label for="pesquisa">Nome/Email:</label>
                <input id="pesquisa" class="form-control" type="text" name="pesquisa" placeholder="Pesquisar"
                    value="{{ request()->pesquisa }}">
            </div>

            <div class="column">
                <label for="tipo_usuario">Tipo de Usuário:</label>
                <select id="tipo_usuario" class="form-select" name="tipo_usuario">
                    <option value="" @if (is_null(request()->tipo_usuario)) selected @endif>Selecione</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @if (request()->tipo_usuario == $role->id) selected @endif>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="column">
                <button class="btn btn-primary form-control mt-4" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Buscar
                </button>
            </div>
            <div class="column">
                <a id="btnLimpaForm" class="btn btn-warning form-control mt-4">
                    Limpar
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </div>

    </form>

    <table class="table-list">
        <thead class="list-head">
            <tr>
                <th class="list-head-content">Id</th>
                <th class="list-head-content">Nome</th>
                <th class="list-head-content">Email</th>
                <th class="list-head-content">Tipo de Usuário</th>
                <th class="list-head-content">Data de Registro</th>
                @can(permissionConstant()::GERENCIAR_USUARIOS_VIEW_DELETED)
                    <th class="list-head-content">Deletado em</th>
                @endcan
                @if (auth()->user()->can(permissionConstant()::GERENCIAR_USUARIOS_VIEW) ||
                        auth()->user()->can(permissionConstant()::GERENCIAR_USUARIOS_EDIT) ||
                        auth()->user()->can(permissionConstant()::GERENCIAR_USUARIOS_DELETE))
                    <th class="list-head-content">Ações</th>
                @endif
            </tr>
        </thead>
        <tbody class="list-body">
            @forelse ($users as $user)
                <tr id="{{ $user->id }}">
                    <td class="list-body-content">{{ $user->id }}</td>
                    <td class="name list-body-content">{{ $user->name }}</td>
                    <td class="email list-body-content">{{ $user->email }}</td>
                    <td class="role list-body-content">{{ $user->role->name }}</td>
                    <td class="list-body-content">{{ formatarDataHora($user->created_at) }}</td>
                    @can(permissionConstant()::GERENCIAR_USUARIOS_VIEW_DELETED)
                        <td class="text-center">
                            @if (is_null($user->deleted_at))
                                -
                            @else
                                {{ formatarDataHora($user->deleted_at) }}
                            @endif
                        </td>
                    @endcan
                    <td class="list-body-content">
                        @can(permissionConstant()::GERENCIAR_USUARIOS_VIEW)
                            <a class="btn btn-outline-primary" href="{{ route('get-user-view', $user) }}">Visualizar</a>
                        @endcan
                        @can(permissionConstant()::GERENCIAR_USUARIOS_EDIT)
                            <a class="btn btn-outline-warning" href="{{ route('get-edit-user-view', $user) }}">Editar</a>
                        @endcan
                        @can(permissionConstant()::GERENCIAR_USUARIOS_DELETE)
                            <form class="d-none" id="deleteUser_{{ $user->id }}"
                                action="{{ route('delete-delete-user', $user) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                            <button class="btnDelete btn-outline-danger" data-id="{{ $user->id }}">Deletar</button>
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
    <div class="justify-content-evenly">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>

    @can(permissionConstant()::GERENCIAR_USUARIOS_DELETE)
        <div id="deleteModal" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-light">Deletar Usuário!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Deseja realmente deletar o usuário:</p>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Nome:</th>
                                    <th>Email:</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="deleteUserRole"></td>
                                    <td id="deleteUserName"></td>
                                    <td id="deleteUserEmail"></td>
                                </tr>
                            </tbody>
                        </table>
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
        $('#btnLimpaForm').click(function() {
            $('#pesquisa').val('');
            $('#tipo_usuario').val('');
        });

        @can(permissionConstant()::GERENCIAR_USUARIOS_DELETE)
            var user;
            $('.btnDelete').click(function() {
                deleteUser($(this).data('id'));
            });

            function deleteUser(id) {
                $("#deleteUserName").text($("#" + id + " .name").text());
                $("#deleteUserEmail").text($("#" + id + " .email").text());
                $("#deleteUserRole").text($("#" + id + " .role").text());

                $('#deleteModal').modal('show');
                user = id;
            }

            $("#btnDeleteConfirm").click(function() {
                $('#deleteModal').modal('hide');
                $('#deleteUser_' + user).submit();
                user = 0;
            });
        @endcan
    </script>
@endpush