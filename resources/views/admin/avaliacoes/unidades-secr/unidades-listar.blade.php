@extends('template.base')

@section('titulo', 'Unidades Secretaria')

@section('content')
<div class="d-flex justify-content-between">
    <h3 class="text-primary">Unidades da Secretaria - ({{ $unidades->total() }})</h3>
    @can(permissionConstant()::UNIDADE_SECRETARIA_CREATE)
    <a class="btn btn-primary" href="{{ route('get-create-unidade') }}">
        <i class="fa-solid fa-plus me-1"></i>
        Nova Unidade
    </a>
    @endcan
</div>
<hr>
<form class="" action="{{ route('get-unidades-secr-list') }}" method="GET">
    <div class="m-0 p-0 row">
        <div class="col-md-3">
            <label for="pesquisa">Nome:</label>
            <input id="pesquisa" class="form-control" type="text" name="pesquisa" placeholder="Pesquisar"
                value="{{ request()->pesquisa }}">
        </div>

        <div class="col-md-2">
            <label for="situacao">Situação:</label>
            <select id="situacao" class="form-select" name="situacao">
                <option value="" @if(is_null(request()->situacao)) selected @endif >Selecione</option>
                <option value="ativo" @if (request()->situacao == 'ativo') selected @endif> Ativo </option>
                <option value="inativo" @if (request()->situacao == 'inativo') selected @endif> Inativo </option>
            </select>
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
            <a id="btnLimpaForm" class="btn btn-warning form-control mt-3">
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
                {{-- @can(permissionConstant()::UNIDADE_SECRETARIA_TOGGLE_ATIVO) --}}
                <th class="text-center">Ativo</th>
                {{-- @endcan --}}
                <th>Nome</th>
                <th>Secretaria</th>
                <th>Última Atualização</th>
                @can(permissionConstant()::UNIDADE_SECRETARIA_VIEW)
                <th class="text-center">Ações</th>
                @endcan
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @if (isset($unidades) && count($unidades) > 0)
            @foreach ( $unidades as $unidade )
            <tr class="">
                <td class="text-center">
                    @if (auth()->user()->can(permissionConstant()::UNIDADE_SECRETARIA_TOGGLE_ATIVO))
                    <a class="btn" href="{{ route('get-ativar-unidade-secr', $unidade) }}">
                        @if ($unidade->ativo)
                        <i class="text-success fa-solid fa-circle-check"></i>
                        @else
                        <i class="text-danger fa-solid fa-circle-xmark"></i>
                        @endif
                    </a>
                    @else
                    @if ($unidade->ativo)
                    <i class="text-success fa-solid fa-circle-check"></i>
                    @else
                    <i class="text-danger fa-solid fa-circle-xmark"></i>
                    @endif
                    @endif
                </td>
                <td>{{$unidade->nome}}</td>
                <td>{{ $unidade->secretaria->sigla . " - " . $unidade->secretaria->nome }}</td>
                <td>{{ formatarDataHora($unidade->updated_at) }}</td>
                @can(permissionConstant()::UNIDADE_SECRETARIA_VIEW)
                <td class="align-middle text-center">
                    <a class="btn text-primary" href="{{ route('get-unidades-secr-view', $unidade) }}">
                        <i class="fa-xl fa-solid fa-magnifying-glass"></i>
                    </a>
                    <a class="btn text-warning"
                        href="{{ route('resumo-avaliacoes-unidade', ['unidade' => $unidade, 'secretaria' => $unidade->secretaria_id]) }}">
                        <i class=" fa-xl fa-solid fa-chart-area"></i>
                    </a>
                </td>
                @endcan
            </tr>
            @endforeach
            @else
            <tr>
                <td class="text-center" colspan="5">Não existem Resultados!</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-evenly">
    {{ $unidades->links('pagination::bootstrap-4') }}
</div>

@can(permissionConstant()::UNIDADE_SECRETARIA_CREATE)
<!-- Modal -->
{{-- <div class="modal fade" id="novaUnidadeModal" tabindex="-1" aria-labelledby="novaUnidadeTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light" id="novaUnidadeTitle">Nova Unidade</h5>
                <button type="button" class="btn-close btnfecharModal"></button>
            </div>
            <form action="{{ route('nova-unidade') }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row g-1">
                        <div class="col-md-12">
                            <label class="form-label fw-bold" for="nome">Nome:</label>
                            <input class="form-control" type="text" name="nome" id="nome" placeholder="Nome">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold" for="secretaria">Secretaria:</label>
                            <select id="secretaria" class="form-select" name="secretaria">
                                <option value="" @if(is_null(request()->secretaria)) selected @endif >Selecione</option>
                                @foreach ( $secretariasCreateSelect as $secretaria )
                                <option value="{{ $secretaria->id }}" @if (request()->secretaria == $secretaria->id)
                                    selected @endif>
                                    {{ $secretaria->sigla . " - " . $secretaria->nome }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold" for="descricao">Descrição:</label>
                            <textarea class="form-control" name="descricao" rows="6" id="descricao"
                                placeholder="Descrição"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger btnfecharModal">Fechar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        Criar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
@endcan

@endsection

@push('scripts')
<script nonce="{{ app('csp-nonce') }}">
    $('#btnLimpaForm').click(function(){
        $('#pesquisa').val('');
        $('#secretaria_pesq').val('');
        $('#situacao').val('');
    });

    $('.btnfecharModal').click(function(){
        $('#novaUnidadeModal').modal('hide');
        $('#nome').val('');
        $('#secretaria').val('');
        $('#descricao').val('');
    });
</script>
@endpush