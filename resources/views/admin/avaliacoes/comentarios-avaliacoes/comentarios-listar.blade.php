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


            <div class="col-3 dropdown d-flex align-items-end mt-3">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Notas
                </a>

                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item"
                            href="{{ route('get-comentarios-avaliacoes-list', [
                                'pesquisa_unidade_setor' => request()->pesquisa_unidade_setor,
                                'secretaria_pesq' => request()->secretaria_pesq,
                                'pesq_nota' => 2,
                            ]) }}">
                            Muito Insatisfeito - <i class="text-danger fa-regular fa-face-angry"></i>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item"
                            href="{{ route('get-comentarios-avaliacoes-list', [
                                'pesquisa_unidade_setor' => request()->pesquisa_unidade_setor,
                                'secretaria_pesq' => request()->secretaria_pesq,
                                'pesq_nota' => 4,
                            ]) }}">
                            Insatisfeito - <i class="text-warning fa-regular fa-face-frown"></i>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item"
                            href="{{ route('get-comentarios-avaliacoes-list', [
                                'pesquisa_unidade_setor' => request()->pesquisa_unidade_setor,
                                'secretaria_pesq' => request()->secretaria_pesq,
                                'pesq_nota' => 6,
                            ]) }}">
                            Neutro - <i class="text-info fa-regular fa-face-meh"></i>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item"
                            href="{{ route('get-comentarios-avaliacoes-list', [
                                'pesquisa_unidade_setor' => request()->pesquisa_unidade_setor,
                                'secretaria_pesq' => request()->secretaria_pesq,
                                'pesq_nota' => 8,
                            ]) }}">
                            Satisfeito - <i class="text-primary fa-regular fa-face-smile"></i>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item"
                            href="{{ route('get-comentarios-avaliacoes-list', [
                                'pesquisa_unidade_setor' => request()->pesquisa_unidade_setor,
                                'secretaria_pesq' => request()->secretaria_pesq,
                                'pesq_nota' => 10,
                            ]) }}">
                            Muito Satisfeito - <i class="text-success fa-regular fa-face-laugh-beam"></i>
                        </a>
                    </li>

                </ul>
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
                                <i class="text-danger fa-regular fa-face-angry"></i>
                            @break

                            @case(4)
                                <i class="text-warning fa-regular fa-face-frown"></i>
                            @break

                            @case(6)
                                <i class="text-info fa-regular fa-face-meh"></i>
                            @break

                            @case(8)
                                <i class="text-primary fa-regular fa-face-smile"></i>
                            @break

                            @case(10)
                                <i class="text-success fa-regular fa-face-laugh-beam"></i>
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
        </script>
    @endpush
