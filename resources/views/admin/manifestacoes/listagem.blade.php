@extends('template.base')

@section('titulo', 'EscutaSol - Manifestações')
@section('content')
<div>
    {{-- @if (session('mensagem'))
        <div class="alert alert-success" role="alert">
            {{ session('mensagem') }}
        </div>
    @endif --}}
    <div class="d-sm-grid d-md-flex justify-content-between d-flex align-items-center">
        <h3 class="d-flex align-self-middle">Manifestações</h3>
        <a class="btn btn-primary" href="{{ route('get-create-manifest2') }}">
            <i class="fa-solid fa-plus me-1"></i>
            Nova Manifestação
        </a>
    </div>
    <hr>

    <div class="table-responsive mt-3">
        <table class="table table-sm table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="text-center">Protocolo</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                    <th>Data Abertura</th>
                    <th>Manifestante</th>
                    <th>Situação</th>
                    <th>Tipo de Manifestação</th>
                    <th>Data</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @if (isset($manifestacoes) && count($manifestacoes) > 0)
                @foreach ( $manifestacoes as $manifestacao )
                <tr>
                    <td>
                        {{$manifestacao->id}}
                    </td>
                    <th class="text-center">
                        {{ $manifestacao->protocolo }}
                        /
                        {{ count($manifestacao->canalMensagem) }}
                    </th>
                    <td class="">
                        {{ $manifestacao->motivacao->nome }}
                    </td>
                    <td class="">
                        {{ $manifestacao->estadoProcesso->nome }}
                    </td>
                    <td class="">
                        {{ formatarDataHora($manifestacao->created_at) }}
                    </td>
                    <td class="">
                        {{-- {{ $manifestacao->autor->name }} --}}
                    </td>
                    <td class="">
                        {{ $manifestacao->situacao->nome }}
                    </td>
                    <td class="">
                        {{ $manifestacao->tipoManifestacao->nome }}
                    </td>

                    <td class="">
                        {{$manifestacao->created_at}}
                    </td>
                    <td class=" text-center">
                        <a href="{{ route('visualizarManifest', $manifestacao->id) }}" class="btn btn-primary">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td class="text-center" colspan="9">Não existem Resultados!</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-evenly">
        {{ $manifestacoes->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    function limparForm(){
        $('#protocolo').val('');
        $('#data-inicio').val('');
        $('#data-fim').val('');
        $('#motivacao').val(0);
        $('#tipo').val(0);
        $('#situacao').val(0);
        $('#estado_processo').val(0);
    }
</script>
@endpush