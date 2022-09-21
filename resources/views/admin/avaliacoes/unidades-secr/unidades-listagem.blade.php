@extends('template.base')

@section('titulo', 'Unidades Secretaria')

@section('content')
<div class="d-flex justify-content-between">
    <h3>Unidades da Secretaria</h3>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#novaUnidadeModal">
        <i class="fa-solid fa-plus"></i>
        Nova Unidade
    </button>
</div>
<hr>
<form class="" action="{{ route('unidades-secr-list') }}" method="GET">
    <div class="m-0 p-0 row">
        <div class="col-md-3">
            <label for="pesquisa">Nome:</label>
            <input id="pesquisa" class="form-control" type="text" name="pesquisa" placeholder="Pesquisar"
                value="{{ request()->pesquisa }}">
        </div>
        @if (true)
        <div class="col-md-3">
            <label for="secretaria_pesq">Secretaria:</label>
            <select id="secretaria_pesq" class="form-select" name="secretaria_pesq">
                <option value="" @if(is_null(request()->secretaria_pesq)) selected @endif >Selecione</option>
                @foreach ( $secretarias as $secretaria )
                <option value="{{ $secretaria->id }}" @if (request()->secretaria_pesq == $secretaria->id) selected
                    @endif>
                    {{ $secretaria->sigla . " - " . $secretaria->nome }}
                </option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-md-2">
            <label for="situacao">Situação:</label>
            <select id="situacao" class="form-select" name="situacao">
                <option value="" @if(is_null(request()->situacao)) selected @endif >Selecione</option>
                <option value="ativo" @if (request()->situacao == 'ativo') selected @endif> Ativo </option>
                <option value="inativo" @if (request()->situacao == 'inativo') selected @endif> Inativo </option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-success form-control mt-3" type="submit">
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
    <table class="table table-sm table-striped table align-middle">
        <thead>
            <tr>
                <th class="text-center">Ativo</th>
                <th>Nome</th>
                <th>Secretaria</th>
                <th>Última Atualização</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @if (isset($unidades) && count($unidades) > 0)
            @foreach ( $unidades as $unidade )
            <tr class="">
                <td>
                    <a class="btn" href="{{ route('ativar-unidade', $unidade) }}">
                        @if ($unidade->ativo)
                        <i class="text-success fa-solid fa-circle-check"></i>
                        @else
                        <i class="text-danger fa-solid fa-circle-xmark"></i>
                    </a>
                    @endif
                </td>
                <td>{{$unidade->nome}}</td>
                <td>{{ $unidade->secretaria->sigla . " - " . $unidade->secretaria->nome }}</td>
                <td>{{ formatarDataHora($unidade->updated_at) }}</td>
                <td class="align-middle text-center">
                    <a href="{{ route('visualizar-unidade', $unidade) }}" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </a>
                </td>
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




<!-- Modal -->
<div class="modal fade" id="novaUnidadeModal" tabindex="-1" aria-labelledby="novaUnidadeTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="novaUnidadeTitle">Nova Unidade</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('nova-unidade') }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div>
                        <label class="form-label" for="nome">Nome:</label>
                        <input class="form-control" type="text" name="nome" id="nome">
                    </div>
                    <div>
                        <label for="secretaria">Secretaria:</label>
                        <select id="secretaria" class="form-select" name="secretaria">
                            <option value="" @if(is_null(request()->secretaria)) selected @endif >Selecione</option>
                            @foreach ( $secretarias as $secretaria )
                            <option value="{{ $secretaria->id }}" @if (request()->secretaria == $secretaria->id)
                                selected @endif>
                                {{ $secretaria->sigla . " - " . $secretaria->nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="fomr-label" for="nome">Descrição</label>
                        <textarea class="form-control" name="descricao" rows="6" id="descricao"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" href="javascript:fecharModal()">Fechar</a>
                    <button type="submit" class="btn btn-primary">Criar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function limparForm(){
        $('#pesquisa').val('');
        $('#secretaria_pesq').val('');
        $('#situacao').val('');
    }
    function fecharModal(){
        $('#novaUnidadeModal').modal('hide');
        $('#nome').val('');
        $('#secretaria').val('');
        $('#descricao').val('');
    }
</script>
@endsection