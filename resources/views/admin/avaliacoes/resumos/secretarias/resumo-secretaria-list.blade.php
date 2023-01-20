@extends('admin.avaliacoes.template.avaliacao')

@section('titulo', 'Resumo por Secretaria')
@section('content')

<h3 class="text-primary">Resumo por Secretaria</h3>
<hr>

<form action="{{ route('get-list-resumo-avaliacoes-secretaria') }}" method="GET">
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
    <table class="table table-sm table-striped align-middle">
        <thead>
            <tr>
                <th>Ativo</th>
                <th>Sigla</th>
                <th>Secretaria</th>
                <th>Unidades(qtd.)</th>
                <th>Nota</th>
                <th class="text-end">Avaliações(qtd.)</th>
                <th class="col-1">Visualizar</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @forelse ($secretarias as $secretaria)
            <tr>
                <td class="text-center">
                    @if ($secretaria->ativo)
                    <i class="text-success fa-solid fa-circle-check"></i>
                    @else
                    <i class="text-danger fa-solid fa-circle-xmark"></i>
                    @endif
                </td>
                <td>{{ $secretaria->sigla }}</td>
                <td>{{ $secretaria->nome }}</td>
                <td>{{ $secretaria->unidades->count() }}</td>
                <td>{{ number_format($secretaria->nota, 2, ',', '') }}</td>
                <td class="text-end">{{ $secretaria->getResumo()['qtd'] }}</td>
                <td class="text-center">
                    <a href="{{route('get-resumo-avaliacoes-secretaria', $secretaria)}}">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td class="text-center table-danger" colspan="7">
                    Não existem Secretarias!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-evenly">
    {{ $secretarias->links('pagination::bootstrap-4') }}
</div>

@endsection

@push('scripts')
<script nonce="{{ app('csp-nonce') }}">
    $('#btnLimpaForm').click(function(){
        $('#pesquisa').val('');
    });
</script>
@endpush