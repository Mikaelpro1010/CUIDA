@extends('template.base')

@section('titulo', 'Unidades Secretaria')

@section('content')
<div class="d-flex justify-content-between">
    <h3>{{$unidade->nome}} - {{ $unidade->secretaria->sigla }}</h3>
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#novaUnidadeModal">
        <i class="fa-solid fa-pen-to-square"></i>
        Editar Unidade
    </button>
</div>
<hr>

<div class="row">
    <div class="col-md-6 row">
        <div class="col-md-8">
            <b>Nome:</b>
            <p class="border-2 border-bottom border-warning">
                {{$unidade->nome }}
            </p>
        </div>

        <div class="col-md-4">
            <b>Situação:</b>
            <p class="border-2 border-bottom border-warning">
                {{ $unidade->ativo ? 'Ativo' : 'Inativo' }}
            </p>
        </div>
        <div class="col-md-12">
            <b>Secretaria:</b>
            <p class="border-2 border-bottom border-warning">
                {{$unidade->secretaria->sigla }} -
                {{$unidade->secretaria->nome }}
            </p>
        </div>
    </div>
    <div class="col-md-6 d-flex justify-content-end">
        <a href="{{ route('get-avaliar-unidade', $unidade->token) }}"> link avaliaçao</a>
        {{-- <b>Qr-Code:</b> --}}
        <?php echo $qrcode ?>
    </div>
</div>

<div class="col-md-12">
    <b>Descrição:</b>
    <p class="border-2 border border-warning p-2">
        {{ $unidade->descricao }}
    </p>
</div>

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
                </td>
            </tr>
        </tbody>
    </table>

</div>
<!-- Modal -->
<div class="modal fade" id="novaUnidadeModal" tabindex="-1" aria-labelledby="novaUnidadeTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="novaUnidadeTitle">Editar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <label class="fomr-label" for="nome">Descrição</label>
                        <textarea class="form-control" name="descricao" rows="6"
                            id="descricao">{{$unidade->descricao}}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" href="javascript:fecharModal()">Fechar</a>
                    <button type="submit" class="btn btn-primary">Editar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function fecharModal(){
        $('#novaUnidadeModal').modal('hide');
    }
</script>
@endsection