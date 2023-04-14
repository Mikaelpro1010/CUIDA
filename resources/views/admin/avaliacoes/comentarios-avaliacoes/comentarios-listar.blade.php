@extends('template.base')

@section('titulo', 'Comentários das Avaliações')

@section('content')

    <form class="" action="{{ route('get-comentarios-avaliacoes-list') }}" method="GET">
        <div class="m-0 p-0 row">

            <div class="col-md-2">
                <label for="pesquisa">Unidade/Setor:</label>
                <input id="pesquisa_unidade_setor" class="form-control" type="text" name="pesquisa_unidade_setor"
                    placeholder="Unidade" value="{{ request()->pesquisa_unidade_setor }}">
            </div>

            <div class="col-md-3">
                <label for="secretaria_pesq">Secretaria:</label>
                <select id="secretaria_pesq" class="form-select" name="secretaria_pesq">
                    <option value="" @if (is_null(request()->secretaria_pesq)) selected @endif>Selecione</option>
                    @foreach ($secretariasSearchSelect as $secretaria)
                        <option value="{{ $secretaria->id }}" @if (request()->secretaria_pesq == $secretaria->id) selected @endif>
                            {{ $secretaria->sigla . ' - ' . $secretaria->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-3 dropdown d-flex align-items-end">
                <a class="btn btn-outline-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Notas
                </a>

                <ul class="dropdown-menu">
                    <li>
                        <button class="dropdown-item" id="nota_2">
                            <span class="text-danger">
                                <i class="fa-regular fa-face-angry"></i> - Muito Ruim
                            </span>
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" id="nota_4">
                            <span class="text-warning">
                                <i class="fa-regular fa-face-frown"></i> - Ruim
                            </span>
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" id="nota_6">
                            <span class="text-info">
                                <i class="fa-regular fa-face-meh"></i> - Neutro
                            </span>
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" id="nota_8">
                            <span class="text-primary">
                                <i class="fa-regular fa-face-smile"></i> - Bom
                            </span>
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" id="nota_10">
                            <span class="text-success">
                                <i class="fa-regular fa-face-laugh-beam"></i> - Muito Bom
                            </span>
                        </button>
                    </li>

                </ul>
            </div>

            <input id="pesq_nota" type="hidden" name="pesq_nota" value="{{ request()->pesq_nota }}">

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
        <table class="table table-sm table-striped mt-3 align-middle">
            <thead>
                <tr>
                    <th>Secretarias</th>
                    <th>Unidade</th>
                    <th>Setor</th>
                    <th>Data</th>
                    <th>Notas</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @forelse ($avaliacoes as $avaliacao)
                    <tr>
                        <td>
                            {{ $avaliacao->setor->unidade->secretaria->sigla . ' - ' . $avaliacao->setor->unidade->secretaria->nome }}
                        </td>
                        <td>
                            {{ $avaliacao->setor->unidade->nome }}
                        </td>
                        <td>
                            {{ $avaliacao->setor->nome }}
                        </td>
                        <td>
                            {{ formatarDataHora($avaliacao->created_at) }}
                        </td>
                        <td>
                            @switch($avaliacao->nota)
                                @case(2)
                                    <span class="text-danger">
                                        <i class="fa-regular fa-face-angry"></i> - Muito Ruim
                                    </span>
                                @break

                                @case(4)
                                    <span class="text-warning">
                                        <i class="fa-regular fa-face-frown"></i> - Ruim
                                    </span>
                                @break

                                @case(6)
                                    <span class="text-info">
                                        <i class="fa-regular fa-face-meh"></i> - Neutro
                                    </span>
                                @break

                                @case(8)
                                    <span class="text-primary">
                                        <i class="fa-regular fa-face-smile"></i> - Bom
                                    </span>
                                @break

                                @case(10)
                                    <span class="text-success">
                                        <i class="fa-regular fa-face-laugh-beam"></i> - Muito Bom
                                    </span>
                                @break
                            @endswitch
                        </td>
                        <td class="col-md-1">
                            <div class="d-flex justify-content-evenly">
                                <a class="btn text-primary"
                                    href="{{ route('get-comentarios-avaliacoes-view', ['id' => $avaliacao->id]) }}">
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
        </div>

        <div class='mx-auto'>
            {{ $avaliacoes->links('pagination::bootstrap-4') }}
        </div>

    @endsection

    @push('scripts')
        <script nonce="{{ app('csp-nonce') }}">
            $('#btnLimpaForm').click(function() {
                $('#pesquisa_unidade_setor').val('');
                $('#pesq_nota').val('');
                $('#secretaria_pesq').val('');
            });
            $('#nota_2').click(function(e){
                e.preventDefault();
                $('#pesq_nota').val('2')
            });
            $('#nota_4').click(function(e){
                e.preventDefault();
                $('#pesq_nota').val('4')
            });
            $('#nota_6').click(function(e){
                e.preventDefault();
                $('#pesq_nota').val('6')
            });
            $('#nota_8').click(function(e){
                e.preventDefault();
                $('#pesq_nota').val('8')
            });
            $('#nota_10').click(function(e){
                e.preventDefault();
                $('#pesq_nota').val('10')
            });
            </script>
    @endpush
