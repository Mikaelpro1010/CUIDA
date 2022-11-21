@extends('template.base')

@section('content')
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-primary">Estados do Processo</h1>
        <a class="btn btn-primary" href="{{ route('get-create-estado-processo') }}">
            <i class="fa-solid fa-plus me-1"></i>
            Novo Estados do Processo
        </a>
    </div>
    <hr>

    <form class="" action="{{ route('get-estado-processo-list') }}" method="GET">
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
            @if (isset($estados_processo))
                @foreach ($estados_processo as $estado_processo)
                    <tr id="{{ $estado_processo->id }}">
                        <td>
                            {{ $estado_processo->id }}
                        </td>
                        <td>
                            @if ($estado_processo->ativo)
                                <a class="btn"
                                    href="{{ route('get-toggle-estado-processo-status', ['id' => $estado_processo->id]) }}">
                                    <i class="text-success fa-solid fa-circle-check"></i>
                                </a>
                            @else
                                <a class="btn"
                                    href="{{ route('get-toggle-estado-processo-status', ['id' => $estado_processo->id]) }}">
                                    <i class="text-danger fa-solid fa-circle-xmark"></i>
                                </a>
                            @endif
                        </td>
                        <td class="name">
                            {{ $estado_processo->nome }}
                        </td>
                        <td>
                            {{ $estado_processo->descricao }}
                        </td>
                        <td>
                            {{ Carbon\Carbon::parse($estado_processo->updated_at)->format('d/m/Y \à\s H:i\h') }}
                        </td>
                        <td class="col-md-1">
                            <div class="d-flex justify-content-evenly">
                                <a href="{{ route('get-estado-processo-view', ['id' => $estado_processo->id]) }}">
                                    <i class="fa-xl fa-solid fa-magnifying-glass text-primary"></i>
                                </a>
                                <a href="{{ route('get-edit-estado-processo-view', ['id' => $estado_processo->id]) }}">
                                    <i class="fa-xl fa-solid fa-pen-to-square text-warning"></i>
                                </a>
                                <a href="javascript:deletar({{ $estado_processo->id }})">
                                    <i class="fa-xl fa-solid fa-trash text-danger"></i>
                                </a>
                                <form class="d-none" id="deleteEstadoProcesso{{ $estado_processo->id }}"
                                    action="{{ route('delete-delete-estado-processo', $estado_processo) }}"
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
        {{ $estados_processo->links('pagination::bootstrap-4') }}
    </div>
    </div>
    <div id="deleteModal_1" name="id" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-light">Deletar Estado do Processo!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Deseja realmente deletar o Estado do Processo: <span id="deleteName" class="fw-bold"></span>
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
        var estadoProcessoID = 0;

        function deletar(id) {
            console.log($("#" + id + " .name").text());
            $("#deleteName").text($("#" + id + " .name").text());
            $('#deleteModal_1').modal('show');
            estadoProcessoID = id;
        }

        function close_modal() {
            $('#deleteModal_1').modal('hide');
            $('#deleteEstadoProcesso' + estadoProcessoID).submit();
        }
    </script>
@endpush