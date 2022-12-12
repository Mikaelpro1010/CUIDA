@extends('template.base')

@section('titulo', 'EscutaSol - Tipos de Manifestação')
@section('content')
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-primary">Tipos de Manifestação</h1>
        <a class="btn btn-primary" href="{{ route('get-create-tipo-manifestacao') }}">
            <i class="fa-solid fa-plus me-1"></i>
            Novo Tipo de Manifestação
        </a>
    </div>
    <hr>

    <form class="" action="{{ route('get-tipo-manifestacao-list') }}" method="GET">
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

    <table class="table table-striped">
        <thead>
            <th>Id</th>
            <th>Ativo</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Última alteração</th>
            <th class="text-center">Ações</th>
        </thead>
        <tbody>
            @forelse ($tipo_manifestacoes as $tipo_manifestacao)
                <tr id="{{ $tipo_manifestacao->id }}">
                    <td>
                        {{ $tipo_manifestacao->id }}
                    </td>
                    <td>
                        @if ($tipo_manifestacao->ativo)
                            <a class="btn"
                                href="{{ route('get-toggle-tipo-manifestacao-status', ['id' => $tipo_manifestacao->id]) }}">
                                <i class="text-success fa-solid fa-circle-check"></i>
                            </a>
                        @else
                            <a class="btn"
                                href="{{ route('get-toggle-tipo-manifestacao-status', ['id' => $tipo_manifestacao->id]) }}">
                                <i class="text-danger fa-solid fa-circle-xmark"></i>
                            </a>
                        @endif
                    </td>
                    <td class="name">
                        {{ $tipo_manifestacao->nome }}
                    </td>
                    <td>
                        {{ $tipo_manifestacao->descricao }}
                    </td>
                    <td>
                        {{ Carbon\Carbon::parse($tipo_manifestacao->updated_at)->format('d/m/Y \à\s H:i\h') }}
                    </td>
                    <td class="col-md-1">
                        <div class="d-flex justify-content-evenly">
                            <a href="{{ route('get-tipo-manifestacao-view', ['id' => $tipo_manifestacao->id]) }}">
                                <i class="fa-xl fa-solid fa-magnifying-glass text-primary"></i>
                            </a>
                            <a href="{{ route('get-edit-tipo-manifestacao-view', ['id' => $tipo_manifestacao->id]) }}">
                                <i class="fa-xl fa-solid fa-pen-to-square text-warning"></i>
                            </a>
                            <a href="javascript:deletar({{ $tipo_manifestacao->id }})">
                                <i class="fa-xl fa-solid fa-trash text-danger"></i>
                            </a>
                            <form class="d-none" id="deleteTipoManifestacao{{ $tipo_manifestacao->id }}"
                                action="{{ route('delete-delete-tipo-manifestacao', $tipo_manifestacao) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center table-warning">
                        Nenhum resultado encontrado!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class='mx-auto'>
        {{ $tipo_manifestacoes->links('pagination::bootstrap-4') }}
    </div>
    </div>
    <div id="deleteModal" name="id" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-light">Deletar Tipo de Manifestação!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Deseja realmente deletar o Tipo de Manifestação: <span id="deleteName" class="fw-bold"></span>
                    </p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" onclick="close_modal()">Deletar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script nonce="{{ app('csp-nonce') }}">
    var tipoManifestacaoID = 0;

        function deletar(id) {
            $("#deleteName").text($("#" + id + " .name").text());
            $('#deleteModal').modal('show');
            tipoManifestacaoID = id;
        }

        function close_modal() {
            $('#deleteModal').modal('hide');
            $('#deleteTipoManifestacao' + tipoManifestacaoID).submit();
        }
</script>
@endpush