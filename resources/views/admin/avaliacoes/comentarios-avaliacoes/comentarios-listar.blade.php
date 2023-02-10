@extends('template.base')

@section('titulo', 'Comentários das Avaliações')

@section('content')

    <form class="" action="{{ route('get-comentarios-avaliacoes-list') }}" method="GET">
        <div class="m-0 p-0 row">
            <div class="col-md-3">
                <label for="pesquisa">Secretaria:</label>
                <input id="pesquisa_secretaria" class="form-control" type="text" name="pesquisa_secretaria" placeholder="Secretaria"
                    value="{{ request()->pesquisa_secretaria }}">
            </div>
            
            <div class="col-md-2">
                <label for="pesquisa">Unidade:</label>
                <input id="pesquisa_unidade" class="form-control" type="text" name="pesquisa_unidade" placeholder="Unidade"
                    value="{{ request()->pesquisa_unidade }}">
            </div>

            <div class="col-md-2">
                <label for="pesquisa">Setor:</label>
                <input id="pesquisa_setor" class="form-control" type="text" name="pesquisa_setor" placeholder="Unidade"
                    value="{{ request()->pesquisa_setor }}">
            </div>

            <div class="col-md-1">
                <label for="pesq_nota">Nota:</label>
                <input id="pesq_nota" class="form-control" type="number" name="pesq_nota" placeholder="Nota"
                    value="{{ request()->pesq_nota }}">
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

    <table class="table table-sm table-striped">
        <thead>
            <tr>
                <th>Secretarias</th>
                <th>Unidade</th>
                <th>Setor</th>
                <th>Nota</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @forelse ($avaliacoes as $avaliacao)
                <tr>
                    <td>
                        {{ $avaliacao->setor->unidade->secretaria->sigla. ' - ' .$avaliacao->setor->unidade->secretaria->nome }}
                    </td>
                    <td>
                        {{ $avaliacao->setor->unidade->nome }}
                    </td>
                    <td>
                        {{ $avaliacao->setor->nome }}
                    </td>
                    <td>
                        {{ $avaliacao->nota }}
                    </td>
                    <td class="col-md-1">
                        <div class="d-flex justify-content-evenly">
                            <a class="btn text-primary" href="{{ route('get-comentarios-avaliacoes-view', ['id' => $avaliacao->id] ) }}">
                                <i class="fa-xl fa-solid fa-magnifying-glass"></i>
                            </a>
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
        {{ $avaliacoes->links('pagination::bootstrap-4') }}
    </div>

@endsection

@push('scripts')
    <script nonce="{{ app('csp-nonce') }}">
        $('#btnLimpaForm').click(function() {
            $('#pesquisa_secretaria').val('');
            $('#pesquisa_unidade').val('');
            $('#pesq_nota').val('');
            $('#pesquisa_setor').val('');
        });
    </script>
@endpush