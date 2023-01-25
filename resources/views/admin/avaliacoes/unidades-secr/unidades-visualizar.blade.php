@extends('template.base')

@section('titulo', 'Unidades Secretaria')

@section('content')
<div class="d-flex justify-content-between">
    <div>
        <h1 class="text-primary">
            {{ $unidadeObj->nome }}
        </h1>
        <h5 class="text-secondary">
            {{$unidadeObj->secretaria->nome}} - {{$unidadeObj->secretaria->sigla}}
        </h5>
    </div>
    <hr>
    @can(permissionConstant()::UNIDADE_SECRETARIA_EDIT)
    <div class="d-flex align-items-center">
        <a href="{{ route('get-edit-unidade-view', ['id' => $unidadeObj->id]) }}" class="btn btn-warning">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar Unidade
        </a>
    </div>
    @endcan
</div>
<hr>

<div class="row">
    <div class="col-md-8">
        <b>Nome:</b>
        <p class="border-2 border-bottom border-warning">
            {{ $unidadeObj->nome }}
        </p>
    </div>

    <div class="col-md-4">
        <b>Emitir QRcode:</b>
        <p class="border-2 border-bottom border-warning">
            <a href="{{ route('get-qrcode-unidade-secr', $unidadeObj) }}" target="_blank">
                Abrir
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>
        </p>
    </div>

    <div class="col-md-8">
        <b>Secretaria:</b>
        <p class="border-2 border-bottom border-warning">
            {{ $unidadeObj->secretaria->sigla }} -
            {{ $unidadeObj->secretaria->nome }}
        </p>
    </div>
    <div class="col-md-4">
        <b>Situação:</b>
        <p class="border-2 border-bottom border-warning">
            {{ $unidadeObj->ativo ? 'Ativo' : 'Inativo' }}
            @if ($unidadeObj->ativo)
            <i class="text-success fa-solid fa-circle-check"></i>
            @else
            <i class="text-danger fa-solid fa-circle-xmark"></i>
            @endif
        </p>
    </div>
</div>
@if (!is_null($unidadeObj->descricao))
<div class="col-md-12">
    <b>Descrição:</b>
    <p class="border-2 border border-warning p-2">
        {{ $unidadeObj->descricao }}
    </p>
</div>
@endif

<div class="row">
    <div class="col-md-4">
        <b>Data de Criação:</b>
        <p class="border-2 border-bottom border-warning">
            {{ formatarDataHora($unidadeObj->created_at) }}
        </p>
    </div>
    <div class="col-md-4">
        <b>Ultima Atualização:</b>
        <p class="border-2 border-bottom border-warning">
            {{ formatarDataHora($unidadeObj->updated_at) }}
        </p>
    </div>
</div>

<div class="text-primary mt-3">
    <h4>
        Tipos de Avaliação
    </h4>
    <hr>
</div>

<ul class="list-group col-md-6">
    @foreach ($unidadeObj->tiposAvaliacao as $tipo)
    <li class="list-group-item border border-2 border-warning">
        {{ $tipo->nome }}
    </li>
    @endforeach
</ul>

<div class="d-flex justify-content-center mt-3">
    <a class="btn btn-warning" href="{{ route('get-unidades-secr-list') }}">
        <i class="fa-solid fa-chevron-left"></i>
        Voltar
    </a>
</div>


@can(permissionConstant()::UNIDADE_SECRETARIA_EDIT)
<!-- Modal -->
<div class="modal fade" id="novaUnidadeModal" tabindex="-1" aria-labelledby="novaUnidadeTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="novaUnidadeTitle">Editar</h5>
                <button type="button" class="btn-close btnFecharModal"></button>
            </div>
            <form action="{{ route('patch-update-unidade-secr', $unidadeObj) }}" method="POST">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                <div class="modal-body">
                    <div>
                        <label class="form-label" for="nome">Nome:</label>
                        <input class="form-control" type="text" name="nome" id="nome" value="{{ $unidadeObj->nome }}">
                    </div>
                    <div>
                        <label class="form-label" for="nome">Descrição</label>
                        <textarea class="form-control" name="descricao" rows="6"
                            id="descricao">{{ $unidadeObj->descricao }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger btnFecharModal">Fechar</a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Editar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan


@endsection

@push('scripts')
<script nonce="{{ app('csp-nonce') }}">
    let nome = '{{ $unidadeObj->nome }}';
        let descricao = '{{ $unidadeObj->descricao }}';

        $('.btnFecharModal').click(function() {
            $('#novaUnidadeModal').modal('hide');
            $('#nome').val(nome);
            $('#descricao').val(descricao);
        });
</script>
@endpush