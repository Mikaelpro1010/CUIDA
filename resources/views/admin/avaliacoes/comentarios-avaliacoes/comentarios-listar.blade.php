@extends('template.base')

@section('titulo', 'Comentários das Avaliações')

@section('content')
    <div class="d-flex justify-content-between">
        <h1 class="text-primary fs-3">Comentarios das Avaliações</h1>
    </div>
    <hr>
    <form class="" action="{{ route('get-comentarios-avaliacoes-list') }}" method="GET">
        <div class="m-0 p-0 row">

            <div class="col-md-3">
                <label class="fw-bold" for="pesquisa">Unidade/Setor:</label>
                <input id="pesquisa_unidade_setor" class="form-control" type="text" name="pesquisa_unidade_setor"
                    placeholder="Unidade" value="{{ request()->pesquisa_unidade_setor }}">
            </div>

            <div class="col-md-3">
                <label class="fw-bold" for="secretaria_pesq">Secretaria:</label>
                <select id="secretaria_pesq" class="form-select" name="secretaria_pesq">
                    <option value="" @if (is_null(request()->secretaria_pesq)) selected @endif>Selecione</option>
                    @foreach ($secretariasSearchSelect as $secretaria)
                        <option value="{{ $secretaria->id }}" @if (request()->secretaria_pesq == $secretaria->id) selected @endif>
                            {{ $secretaria->sigla . ' - ' . $secretaria->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="fw-bold" for="notas_select">Notas:</label>
                <div class="">
                    <button id="notas_select" class="form-select text-decoration-none text-start" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        @switch(request()->pesq_nota)
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

                            @default
                                Todas as Notas
                        @endswitch
                    </button>

                    <ul class="dropdown-menu">
                        <li>
                            <button class="dropdown-item nota" data-nota=''>
                                <span>
                                    Todas as Notas
                                </span>
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item nota" data-nota='2'>
                                <span class="text-danger">
                                    <i class="fa-regular fa-face-angry"></i> - Muito Ruim
                                </span>
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item nota" data-nota='4'>
                                <span class="text-warning">
                                    <i class="fa-regular fa-face-frown"></i> - Ruim
                                </span>
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item nota" data-nota='6'>
                                <span class="text-info">
                                    <i class="fa-regular fa-face-meh"></i> - Neutro
                                </span>
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item nota" data-nota='8'>
                                <span class="text-primary">
                                    <i class="fa-regular fa-face-smile"></i> - Bom
                                </span>
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item nota" data-nota='10'>
                                <span class="text-success">
                                    <i class="fa-regular fa-face-laugh-beam"></i> - Muito Bom
                                </span>
                            </button>
                        </li>
                    </ul>
                </div>
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
                    <th>Notas</th>
                    <th>Data</th>
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
                        <td>
                            {{ formatarDataHora($avaliacao->created_at) }}
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
            $('.nota').click(function(e) {
                e.preventDefault();
                $('#pesq_nota').val($(this).data('nota'));

                switch ($(this).data('nota')) {
                    case (''):
                        $("#notas_select").html(`Todas as Notas`);
                        break;
                    case (2):
                        $("#notas_select").html(
                            `<span class="text-danger">
                                <i class="fa-regular fa-face-angry"></i> - Muito Ruim
                            </span>`
                        );
                        break;
                    case (4):
                        $("#notas_select").html(
                            `<span class="text-warning">
                                <i class="fa-regular fa-face-frown"></i> - Ruim
                            </span>`
                        );
                        break;
                    case (6):
                        $("#notas_select").html(
                            `<span class="text-info">
                                <i class="fa-regular fa-face-meh"></i> - Neutro
                            </span>`
                        );
                        break;
                    case (8):
                        $("#notas_select").html(
                            `<span class="text-primary">
                                <i class="fa-regular fa-face-smile"></i> - Bom
                            </span>`
                        );
                        break;
                    case (10):
                        $("#notas_select").html(
                            `<span class="text-success">
                                <i class="fa-regular fa-face-laugh-beam"></i> - Muito Bom
                            </span>`
                        );
                        break;
                }

            });
        </script>
    @endpush
