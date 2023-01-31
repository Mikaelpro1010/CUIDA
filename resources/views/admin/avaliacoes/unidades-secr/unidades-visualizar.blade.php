@extends('template.base')

@section('titulo', 'Unidades Secretaria')

@section('content')
    <div class="d-flex justify-content-between">
        <div>
            <h1 class="text-primary">
                {{ $unidadeObj->nome }}
                @if (!$unidadeObj->ativo)
                    <span class="text-danger"> (Inativo)</span>
                @endif
            </h1>
            <h5 class="text-secondary">
                {{ $unidadeObj->secretaria->nome }} - {{ $unidadeObj->secretaria->sigla }}
                @if (!$unidadeObj->secretaria->ativo)
                    <span class="text-danger"> (Inativo)</span>
                @endif
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
                @if (!$unidadeObj->secretaria->ativo || !$unidadeObj->ativo)
                    <span class="text-danger"> (Inativo)</span>
                @else
                    <a href="{{ route('get-qrcode-unidade-secr', $unidadeObj) }}" target="_blank">
                        Abrir
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                @endif
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

    <div class="mt-3">
        <div class="d-flex justify-content-between">
            <h4 class="text-primary">
                Setores
            </h4>
            <button id="btnCriarSetor" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        <hr>
    </div>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th class="fw-bold text-center col-md-1">Ativo</th>
                    <th class="fw-bold">Nome</th>
                    <th class="fw-bold text-end">Ações</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">

                @foreach ($unidadeObj->setores as $setor)
                    <tr id="setor-{{ $setor->id }}">
                        <td class="text-center">
                            <a href="{{ route('get-toggle-setor-status', $setor) }}">
                                @if ($setor->ativo)
                                    <i class="text-success fa-solid fa-circle-check"></i>
                                @else
                                    <i class="text-danger fa-solid fa-circle-xmark"></i>
                                @endif
                            </a>
                        </td>
                        <td id="setor-{{ $setor->id }}-nome">
                            {{ $setor->nome }}
                        </td>
                        <td class="text-end">
                            @if (!$setor->principal)
                                <button class="btnEditar btn btn-warning" data-id="{{ $setor->id }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Editar
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        <a class="btn btn-warning" href="{{ route('get-unidades-secr-list') }}">
            <i class="fa-solid fa-chevron-left"></i>
            Voltar
        </a>
    </div>

    <div class="modal fade" id="editaSetor" tabindex="-1" aria-labelledby="editaSetorLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editaSetorLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="CriarSetor" tabindex="-1" aria-labelledby="CriarSetorLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h1 class="modal-title text-white fs-5" id="CriarSetorLabel">Criar Novo Setor</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('post-store-setor', $unidadeObj) }}" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="col-md-12">
                            <label for="nome" class="form-label fw-bold">Nome:</label>
                            <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i>
                            Criar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


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

        $('#btnCriarSetor').click(function() {
            $('#CriarSetor').modal('show');
        });
    </script>
@endpush
