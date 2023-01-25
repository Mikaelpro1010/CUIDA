@extends('template.base')

@section('content')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="text-primary">Secretarias</h1>
    @can(permissionConstant()::GERENCIAR_SECRETARIAS_CREATE)
    <a href="{{ route('get-create-secretaria') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i>
        Nova Secretaria
    </a>
    @endcan
</div>

<hr>

<form action="{{ route('get-secretarias-list') }}" method="GET">
    <div class="m-0 p-0 row">
        <div class="col-md-5">
            <label for="pesquisa">Secretaria:</label>
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
            <a id="btnLimpaForm" class="btn btn-warning form-control mt-3">
                Limpar
                <i class="fa-solid fa-eraser"></i>
            </a>
        </div>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-sm table-striped align-middle ">
        <thead>
            <th class="text-center">Id</th>
            <th class="text-center">Status</th>
            <th>Sigla</th>
            <th>Nome</th>
            <th>Última alteração</th>
            <th class="text-center">Ações</th>
        </thead>
        <tbody class="table-group-divider">
            @forelse ($secretarias as $secretaria)
            <tr>
                <th class="text-end">
                    {{ $secretaria->id }}
                </th>
                <td class="text-center">
                    @can(permissionConstant()::GERENCIAR_SECRETARIAS_ACTIVE_TOGGLE)
                    <a href="{{ route('get-toggle-secretaria-status', $secretaria) }}">
                        @if ($secretaria->ativo)
                        <i class="text-success fa-solid fa-circle-check"></i>
                        @else
                        <i class="text-danger fa-solid fa-circle-xmark"></i>
                        @endif
                    </a>
                    @else
                    @if ($secretaria->ativo)
                    <i class="text-success fa-solid fa-circle-check"></i>
                    @else
                    <i class="text-danger fa-solid fa-circle-xmark"></i>
                    @endif
                    @endcan
                </td>
                <td>
                    {{ $secretaria->sigla }}
                </td>
                <td>
                    {{ $secretaria->nome }}
                </td>
                <td>
                    {{ formatarDataHora($secretaria->updated_at)}}
                </td>
                <td class="col-md-1">
                    <div class="d-flex justify-content-evenly">
                        @can(permissionConstant()::GERENCIAR_SECRETARIAS_VIEW)
                        <a class="btn" href="{{ route('get-secretaria-view', $secretaria) }}">
                            <i class="fa-xl fa-solid fa-magnifying-glass text-primary"></i>
                        </a>
                        @endcan
                        @can(permissionConstant()::GERENCIAR_SECRETARIAS_EDIT)
                        <a class="btn" href="{{ route('get-edit-secretaria', $secretaria->id) }}">
                            <i class="fa-xl fa-solid fa-pen-to-square text-warning"></i>
                        </a>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td class="text-center fw-bold" colspan="6">
                    Não existem registros!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-evenly">
        {{ $secretarias->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection

@push('scripts')
<script nonce="{{ app('csp-nonce') }}">
    $('#btnLimpaForm').click(function(){
        $('#pesquisa').val('');
    });
</script>
@endpush