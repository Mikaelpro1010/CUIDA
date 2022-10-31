@extends('admin.avaliacoes.template.avaliacao')

@section('titulo', 'Resumo por Unidade')
@section('content')

<h3 class="text-primary">Unidades da Secretaria - ({{ $unidades->total() }})</h3>
<hr>

<form class="" action="{{ route('resumo-avaliacoes-unidade-list') }}" method="GET">
    <div class="m-0 p-0 row">
        <div class="col-md-3">
            <label for="pesquisa">Nome:</label>
            <input id="pesquisa" class="form-control" type="text" name="pesquisa" placeholder="Pesquisar"
                value="{{ request()->pesquisa }}">
        </div>

        <div class="col-md-3">
            <label for="secretaria_pesq">Secretaria:</label>
            <select id="secretaria_pesq" class="form-select" name="secretaria_pesq">
                <option value="" @if(is_null(request()->secretaria_pesq)) selected @endif >Selecione</option>
                @foreach ( $secretariasSearchSelect as $secretaria )
                <option value="{{ $secretaria->id }}" @if (request()->secretaria_pesq == $secretaria->id) selected
                    @endif>
                    {{ $secretaria->sigla . " - " . $secretaria->nome }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary form-control mt-3" type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
                Buscar
            </button>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a class="btn btn-warning form-control mt-3" onclick="limparForm()">
                Limpar
                <i class="fa-solid fa-eraser"></i>
            </a>
        </div>
    </div>
</form>

<div class="table-responsive mt-3">
    <table class="table table-sm table-striped align-middle">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Secretaria</th>
                <th class="text-end">Nota</th>
                <th class="text-end">Avaliações(qtd.)</th>
                <th class="text-center">Visualizar</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @forelse ( $unidades as $unidade )
            <tr class="">
                <td>{{$unidade->nome}}</td>
                <td>{{ $unidade->secretaria->sigla . " - " . $unidade->secretaria->nome }}</td>
                <td class="text-end">{{number_format($unidade->nota, 2,",",'')}}</td>
                <td class="text-end">{{ $unidade->getResumoFromCache()['qtd'] }}</td>
                <td class="align-middle text-center">
                    <a href="{{ route('resumo-avaliacoes-unidade', [$unidade->secretaria_id, $unidade]) }}">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td class="text-center" colspan="5">Não existem Resultados!</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-evenly">
    {{ $unidades->links('pagination::bootstrap-4') }}
</div>

@endsection

@push('scripts')
<script>
    function limparForm(){
        $('#pesquisa').val('');
        $('#secretaria_pesq').val('');
        $('#situacao').val('');
    }
</script>

@endpush