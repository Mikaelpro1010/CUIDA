@extends('template.base')

@section('content')
<div class="row">
    @include('componentes/flash-message')
    <div class="d-flex justify-content-between">
        <h1 class="m-0 text-primary">Usuários</h1>
        @can(permissionConstant()::GERENCIAR_USUARIOS_CREATE)
            <div>
                <a class="btn btn-primary" href="{{ route('get-create-user') }}">
                    <i class="fa-solid fa-user-plus me-1"></i>
                    Novo Usuário
                </a>
            </div>
        @endcan
    </div>
    <hr>

    <form class="" action="{{ route('get-users-list') }}" method="GET">
        <div class="m-0 p-0 row">
            <div class="col-md-3">
                <label for="pesquisa">Nome/Email:</label>
                <input id="pesquisa" class="form-control" type="text" name="pesquisa" placeholder="Pesquisar"
                    value="{{ request()->pesquisa }}">
            </div>

            <div class="col-md-3">
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

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary form-control mt-3" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Buscar
                </button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a id="btnLimpaForm" class="btn btn-warning form-control mt-3">
                    Limpar
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </div>

    </form>

    <div class="table-responsive">
        <table class="table table-sm table-striped align-middle">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo de Usuário</th>
                    <th>Data de Registro</th>
                    @can(permissionConstant()::GERENCIAR_USUARIOS_VIEW_DELETED)
                        <th>Deletado em</th>
                    @endcan
                    @if (auth()->user()->can(permissionConstant()::GERENCIAR_USUARIOS_VIEW) ||
                            auth()->user()->can(permissionConstant()::GERENCIAR_USUARIOS_EDIT) ||
                            auth()->user()->can(permissionConstant()::GERENCIAR_USUARIOS_DELETE))
                        <th class="text-center">Ações</th>
                    @endif
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @forelse ($users as $user)
                    <tr id="{{ $user->id }}">
                        <th>{{ $user->id }}</th>
                        <td class="name">{{ $user->name }}</td>
                        <td class="email">{{ $user->email }}</td>
                        <td class="role">{{ $user->role->name }}</td>
                        <td>{{ formatarDataHora($user->created_at) }}</td>
                        @can(permissionConstant()::GERENCIAR_USUARIOS_VIEW_DELETED)
                            <td class="text-center">
                                @if (is_null($user->deleted_at))
                                    -
                                @else
                                    {{ formatarDataHora($user->deleted_at) }}
                                @endif
                            </td>
                        @endcan
                        <td class="col-md-1">
                            <div class="d-flex justify-content-around">
                                @can(permissionConstant()::GERENCIAR_USUARIOS_VIEW)
                                    <a class="btn btn-link" href="{{ route('get-user-view', $user) }}">
                                        <i class="fa-xl fa-solid fa-magnifying-glass"></i>
                                    </a>
                                @endcan
                                @can(permissionConstant()::GERENCIAR_USUARIOS_EDIT)
                                    <a class="btn btn-link" href="{{ route('get-edit-user-view', $user) }}">
                                        <i class="fa-xl text-warning fa-solid fa-pen-to-square"></i>
                                    </a>
                                @endcan
                                @can(permissionConstant()::GERENCIAR_USUARIOS_DELETE)
                                    <form class="d-none" id="deleteUser_{{ $user->id }}"
                                        action="{{ route('delete-delete-user', $user) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                    </form>
                                    <button class="btnDelete btn btn-link" data-id="{{ $user->id }}">
                                        <i class="fa-xl text-danger fa-solid fa-trash"></i>
                                    </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="table-warning text-center" colspan="6">Nenhum resultado Encontrado!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-evenly">
            {{ $users->links('pagination::bootstrap-4') }}
        </div>
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