@extends('template.base')

@section('content')
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-primary">Situações</h1>
        <a class="btn btn-primary" href="{{ route('get-create-situacao') }}">
            <i class="fa-solid fa-plus me-1"></i>
            Novas Situações
        </a>
    </div>
    <hr>

    <form class="" action="{{ route('get-situacao-list') }}" method="GET">
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
            @if (isset($situacoes))
                @foreach ($situacoes as $situacao)
                    <tr id="{{ $situacao->id }}">
                        <td>
                            {{ $situacao->id }}
                        </td>
                        <td>
                            @if ($situacao->ativo)
                                <a class="btn"
                                    href="{{ route('get-toggle-situacao-status', ['id' => $situacao->id]) }}">
                                    <i class="text-success fa-solid fa-circle-check"></i>
                                </a>
                            @else
                                <a class="btn"
                                    href="{{ route('get-toggle-situacao-status', ['id' => $situacao->id]) }}">
                                    <i class="text-danger fa-solid fa-circle-xmark"></i>
                                </a>
                            @endif
                        </td>
                        <td class="name">
                            {{ $situacao->nome }}
                        </td>
                        <td>
                            {{ $situacao->descricao }}
                        </td>
                        <td>
                            {{ Carbon\Carbon::parse($situacao->updated_at)->format('d/m/Y \à\s H:i\h') }}
                        </td>
                        <td class="col-md-1">
                            <div class="d-flex justify-content-evenly">
                                <a href="{{ route('get-situacao-view', ['id' => $situacao->id]) }}">
                                    <i class="fa-xl fa-solid fa-magnifying-glass text-primary"></i>
                                </a>
                                <a href="{{ route('get-edit-situacao-view', ['id' => $situacao->id]) }}">
                                    <i class="fa-xl fa-solid fa-pen-to-square text-warning"></i>
                                </a>
                                <a href="javascript:deletar({{ $situacao->id }})">
                                    <i class="fa-xl fa-solid fa-trash text-danger"></i>
                                </a>
                                <form class="d-none" id="deleteSituacao{{ $situacao->id }}"
                                    action="{{ route('delete-delete-situacao', $situacao) }}"
                                    method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <p>não existem registros</p>
            @endif
        </tbody>
    </table>
    <div class='mx-auto'>
        {{ $situacoes->links('pagination::bootstrap-4') }}
    </div>
    </div>
    <div id="deleteModal_2" name="id" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-light">Deletar Situação!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Deseja realmente deletar a Situação: <span id="deleteName" class="fw-bold"></span>
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
    <script>
        var SituacaoID = 0;

        function deletar(id) {
            console.log($("#" + id + " .name").text());
            $("#deleteName").text($("#" + id + " .name").text());
            $('#deleteModal_2').modal('show');
            SituacaoID = id;
        }

        function close_modal() {
            $('#deleteModal_1').modal('hide');
            $('#deleteSituacao' + SituacaoID).submit();
        }
    </script>
@endpush