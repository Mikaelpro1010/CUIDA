@extends('template.base')

@section('titulo', 'EscutaSol - Tipos de Usuário')
@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-primary">Tipos de Usuário</h1>
        @can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_CREATE)
            <div>
                <a class="btn btn-primary" href="{{ route('get-create-role') }}">
                    <i class="fa-solid fa-plus me-1"></i>
                    Novo Tipo de Usuário
                </a>
            </div>
        @endcan
    </div>
    <hr>

    <form class="" action="{{ route('get-roles-list') }}" method="GET">
        <div class="m-0 p-0 row">
            <div class="col-md-5">
                <label for="pesquisa">Nome:</label>
                <input id="pesquisa" class="form-control" type="text" name="pesquisa" placeholder="Pesquisar"
                    value="{{ request()->pesquisa }}">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary form-control mt-3" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Buscar
                </button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a class="btn btn-warning form-control mt-3" onclick="$('#pesquisa').val('')">
                    Limpar
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </div>

    </form>

    <div class="table-responsive">
        <table class="table table-striped text-middle">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Qtd Usuários</th>
                    <th>Data de Registro</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @forelse ($roles as $role)
                    <tr id="{{ $role->id }}">
                        <td>{{ $role->id }}</td>
                        <td class="name">{{ $role->name }}</td>
                        <td>{{ $role->users->count() }}</td>
                        <td>{{ formatarDataHora($role->created_at) }}</td>
                        <td class="col-md-1">
                            <div class="d-flex justify-content-around">
                                @can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_VIEW)
                                    <a href="{{ route('get-role-view', $role) }}">
                                        <i class="fa-xl fa-solid fa-magnifying-glass"></i>
                                    </a>
                                @endcan

                                @can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_EDIT)
                                    <a href="{{ route('get-edit-role-view', $role) }}">
                                        <i class="fa-xl text-warning fa-solid fa-pen-to-square"></i>
                                    </a>
                                @endcan

                                @can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_DELETE)
                                    <form class="d-none" id="deleteRole_{{ $role->id }}"
                                        action="{{ route('delete-delete-role', $role) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                    </form>
                                    <a href="javascript:deleteRole({{ $role->id }})">
                                        <i class="fa-xl text-danger fa-solid fa-trash"></i>
                                    </a>
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
            {{ $roles->links('pagination::bootstrap-4') }}
        </div>
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
                        <button type="button" class="btn btn-danger" onclick="javascript:deleteConfirm()">deletar</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection

@push('scripts')
    <script>
        @can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_DELETE)
            var userType;

            function deleteRole(id) {
                $("#deleteRoleName").text($("#" + id + " .name").text());
                $('#deleteModal').modal('show');
                userType = id;
            }

            function deleteConfirm() {
                $('#deleteModal').modal('hide');
                $('#deleteRole_' + userType).submit();
                userType = 0;
            }
        @endcan
    </script>
@endpush
