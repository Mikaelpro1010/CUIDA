@extends('admin.avaliacoes.template.avaliacao')

@section('titulo', 'Resumo por Secretaria')
@section('content')

<h3>Resumo por Secretaria</h3>
<hr>

<div class="table-responsive">
    <table class="table table-sm table-striped align-middle">
        <thead>
            <tr>
                <th>Sigla</th>
                <th>Secretaria</th>
                <th>Unidades(qtd.)</th>
                <th>Nota</th>
                <th class="text-end">Avaliações(qtd.)</th>
                <th class="col-1">Visualizar</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @if (isset($secretarias))
            @foreach ($secretarias as $secretaria)
            <tr>
                <td>{{ $secretaria->sigla }}</td>
                <td>{{ $secretaria->nome }}</td>
                <td>{{ $secretaria->unidades->count() }}</td>
                <td>{{ $secretaria->media }}</td>
                <td class="text-end">{{ $secretaria->avaliacoes->count() }}</td>
                <td class="text-center">
                    <a href="{{route('resumo-avaliacoes-secretaria', $secretaria)}}">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </a>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td class="text-center table-danger" colspan="4">
                    Não existem Secretarias!
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

@endsection