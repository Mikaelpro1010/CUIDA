@extends('template.base')

@section('content')

<div class="d-flex justify-content-between align-items-center">
    <h1 class="text-primary">Informações da Secretaria</h1>
    @can(permissionConstant()::GERENCIAR_SECRETARIAS_EDIT)
    <a class="btn btn-warning" href="{{ route('get-edit-secretaria', $secretaria->id) }}">
        <i class="fa-solid fa-pen-to-square me-1"></i>
        Editar Secretaria
    </a>
    @endcan
</div>

<hr>

<div class="row align-items-start">
    <div class="col-md-1">
        <label class="fw-bold">Status:</label>
        <div class="border-2 border-bottom border-warning text-center">
            @if ($secretaria->ativo)
            Ativo
            <i class="text-success fa-solid fa-circle-check"></i>
            @else
            Inativo
            <i class="text-danger fa-solid fa-circle-xmark"></i>
            @endif
        </div>
    </div>

    <div class="col-md-2">
        <label class="fw-bold" for="">Sigla:</label>
        <div class="border-2 border-bottom border-warning">
            {{ $secretaria->sigla }}
        </div>
    </div>

    <div class="col-md-5">
        <label class="fw-bold" for="">Nome:</label>
        <div class="border-2 border-bottom border-warning">
            {{ $secretaria->nome }}
        </div>
    </div>

    <div class="col-md-3">
        <label class="fw-bold" for="">Última Alteração:</label>
        <div class="border-2 border-bottom border-warning">
            {{ formatarDataHora($secretaria->updated_at) }}
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('get-secretarias-list') }}" class="mt-3 btn btn-warning">
            <i class="fa-solid fa-chevron-left"></i>
            Voltar
        </a>
    </div>
</div>
@endsection