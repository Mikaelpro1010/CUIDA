@extends('template.base')

@section('titulo', 'EscutaSol - Tipos de Avaliação')
@section('content')
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-primary">Tipos de Avaliação</h1>
        <a class="btn btn-primary" href="{{ route('get-create-tipo-avaliacao') }}">
            <i class="fa-solid fa-plus me-1"></i>
            Novo Tipo de Avaliação
        </a>
    </div>
    <hr>

    <form class="" action="" method="GET">
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
            <th>Última alteração</th>
            <th class="text-center">Ações</th>
        </thead>
        <tbody>
            @forelse ($tipo_avaliacoes as $tipo_avaliacao)
                <tr id="{{ $tipo_avaliacao->id }}">
                    <td>
                        {{ $tipo_avaliacao->id }}
                    </td>
                    <td>
                        @if ($tipo_avaliacao->ativo)
                            <a class="btn"
                                href="{{ route('get-toggle-tipo-avaliacao-status', ['id' => $tipo_avaliacao->id]) }}">
                                <i class="text-success fa-solid fa-circle-check"></i>
                            </a>
                        @else
                            <a class="btn"
                                href="{{ route('get-toggle-tipo-avaliacao-status', ['id' => $tipo_avaliacao->id]) }}">
                                <i class="text-danger fa-solid fa-circle-xmark"></i>
                            </a>
                        @endif
                    </td>
                    <td class="name">
                        {{ $tipo_avaliacao->nome }}
                    </td>
                    <td>
                        {{ Carbon\Carbon::parse($tipo_avaliacao->updated_at)->format('d/m/Y \à\s H:i\h') }}
                    </td>
                    <td class="col-md-1">
                        <div class="d-flex justify-content-evenly">
                            <a href="{{ route('get-tipo-avaliacao-view', ['id' => $tipo_avaliacao->id]) }}">
                                <i class="fa-xl fa-solid fa-magnifying-glass text-primary"></i>
                            </a>
                            <a href="{{ route('get-edit-tipo-avaliacao-view', ['id' => $tipo_avaliacao->id]) }}">
                                <i class="fa-xl fa-solid fa-pen-to-square text-warning"></i>
                            </a>
                            <a href="javascript:deletar({{ $tipo_avaliacao->id }})">
                                <i class="fa-xl fa-solid fa-trash text-danger"></i>
                            </a>
                            <form class="d-none" id="deleteTipoAvaliacao{{ $tipo_avaliacao->id }}"
                                action="{{ route('delete-delete-tipo-avaliacao', $tipo_avaliacao) }}" method="POST">
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
        {{ $tipo_avaliacoes->links('pagination::bootstrap-4') }}
    </div>
    </div>
    <div id="deleteModal" name="id" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-light">Deletar Tipo de Avaliação!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Deseja realmente deletar o Tipo de Avaliação: <span id="deleteName" class="fw-bold"></span>
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
    var tipoAvaliacaoID = 0;

        function deletar(id) {
            $("#deleteName").text($("#" + id + " .name").text());
            $('#deleteModal').modal('show');
            tipoAvaliacaoID = id;
        }

        function close_modal() {
            $('#deleteModal').modal('hide');
            $('#deleteTipoAvaliacao' + tipoAvaliacaoID).submit();
        }
</script>
@endpush