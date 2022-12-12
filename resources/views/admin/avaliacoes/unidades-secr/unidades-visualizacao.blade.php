@extends('template.base')

@section('titulo', 'Unidades Secretaria')

@section('content')
<div class="d-flex justify-content-between">
    <h3 class="text-primary">{{$unidade->nome}} - {{ $unidade->secretaria->sigla }}</h3>

    @can(permissionConstant()::UNIDADE_SECRETARIA_UPDATE)
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#novaUnidadeModal">
        <i class="fa-solid fa-pen-to-square"></i>
        Editar Unidade
    </button>
    @endcan
</div>
<hr>

<div class="row">
    <div class="col-md-8">
        <b>Nome:</b>
        <p class="border-2 border-bottom border-warning">
            {{$unidade->nome }}
        </p>
    </div>

    <div class="col-md-4">
        <b>Emitir QRcode:</b>
        <p class="border-2 border-bottom border-warning">
            <a href="{{ route('gerar-qrcode-unidade', $unidade) }}" target="_blank">
                Abrir
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>
        </p>
    </div>

    <div class="col-md-8">
        <b>Secretaria:</b>
        <p class="border-2 border-bottom border-warning">
            {{$unidade->secretaria->sigla }} -
            {{$unidade->secretaria->nome }}
        </p>
    </div>
    <div class="col-md-4">
        <b>Situação:</b>
        <p class="border-2 border-bottom border-warning">
            {{ $unidade->ativo ? 'Ativo' : 'Inativo' }}
            @if ($unidade->ativo)
            <i class="text-success fa-solid fa-circle-check"></i>
            @else
            <i class="text-danger fa-solid fa-circle-xmark"></i>
            @endif
        </p>
    </div>
</div>
@if(!is_null($unidade->descricao))
<div class="col-md-12">
    <b>Descrição:</b>
    <p class="border-2 border border-warning p-2">
        {{ $unidade->descricao }}
    </p>
</div>
@endif

<div class="row">
    <div class="col-md-4">
        <b>Data de Criação:</b>
        <p class="border-2 border-bottom border-warning">
            {{ formatarDataHora($unidade->created_at) }}
        </p>
    </div>
    <div class="col-md-4">
        <b>Ultima Atualização:</b>
        <p class="border-2 border-bottom border-warning">
            {{ formatarDataHora($unidade->updated_at) }}
        </p>
    </div>
</div>

<div class="d-flex justify-content-center">
    <a class="btn btn-warning" href="{{ route('unidades-secr-list') }}">
        <i class="fa-solid fa-chevron-left"></i>
        Voltar
    </a>
</div>


@can(permissionConstant()::UNIDADE_SECRETARIA_UPDATE)
<!-- Modal -->
<div class="modal fade" id="novaUnidadeModal" tabindex="-1" aria-labelledby="novaUnidadeTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="novaUnidadeTitle">Editar</h5>
                <button type="button" class="btn-close btnFecharModal"></button>
            </div>
            <form action="{{ route('atualizar-unidade', $unidade) }}" method="POST">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                <div class="modal-body">
                    <div>
                        <label class="form-label" for="nome">Nome:</label>
                        <input class="form-control" type="text" name="nome" id="nome" value="{{ $unidade->nome }}">
                    </div>
                    <div>
                        <label class="form-label" for="nome">Descrição</label>
                        <textarea class="form-control" name="descricao" rows="6"
                            id="descricao">{{$unidade->descricao}}</textarea>
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
    let nome = '{{$unidade->nome}}';
    let descricao  = '{{$unidade->descricao}}';

    $('.btnFecharModal').click(function (){
        $('#novaUnidadeModal').modal('hide');
        $('#nome').val(nome);
        $('#descricao').val(descricao);
    });
</script>
@endpush