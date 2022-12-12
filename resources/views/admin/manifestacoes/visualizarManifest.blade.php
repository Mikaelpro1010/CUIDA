@extends('template.base')

@section('titulo', 'EscutaSol - Mensagens')
@section('content')
    <div class="d-flex justify-content-between">
        <h3 class="modal-title">
            Manifestação:
            {{ $manifestacao->protocolo }}
            /
            {{ $manifestacao->tipoManifestacao->nome }}
            -
            {{ $manifestacao->situacao->nome }}
        </h3>
        <div>
            <a class="btn btn-primary" href="">
                <i class="fa-solid fa-comments"></i>
                Mensagens Chat
            </a>
        </div>
    </div>
    <hr>

    <div class="row">
        <div class="col-md-3">
            <b>Data da Manifestação:</b>
            <p class="border-2 border-bottom border-warning">
                {{ formatarDataHora($manifestacao->data_abertura) }}
            </p>
        </div>
        <div class="col-md-2">
            <b>Protocolo:</b>
            <p class="border-2 border-bottom border-warning">
                {{ $manifestacao->protocolo }}
            </p>
        </div>

        <div class="col-md-2">
            <b>Motivação:</b>
            <p class="border-2 border-bottom border-warning">
                {{ $manifestacao->motivacao->nome }}
            </p>
        </div>

        <div class="col-md-2">
            <b>Estado:</b>
            <p class="border-2 border-bottom border-warning">
                {{ $manifestacao->estadoProcesso->nome }}
            </p>
        </div>

        <div class="col-md-3">
            <b>Tipo de Manifestação:</b>
            <p class="border-2 border-bottom border-warning">
                {{ $manifestacao->tipoManifestacao->nome }}
            </p>
        </div>

        <div class="col-md-2">
            <b>Situação:</b>
            <p class="border-2 border-bottom border-warning">
                {{ $manifestacao->situacao->nome }}
            </p>
        </div>

        <div class="col-md-3">
            <b>Prazo:</b>
            <p class="border-2 border-bottom border-warning text-danger">
                Inserir Prazo
            </p>
        </div>

        <div class="col-md-12">
            <b>Manifestação:</b>
            <p class="border-2 border border-warning p-2">
                {{ $manifestacao->manifestacao }}
            </p>
        </div>
    </div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="inicial-tab" data-bs-toggle="tab" data-bs-target="#inicial" type="button"
                role="tab" aria-controls="inicial" aria-selected="true">
                Inicial
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="anexos-tab" data-bs-toggle="tab" data-bs-target="#anexos" type="button"
                role="tab" aria-controls="anexos" aria-selected="true">
                Anexos
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="respostas-tab" data-bs-toggle="tab" data-bs-target="#respostas" type="button"
                role="tab" aria-controls="respostas" aria-selected="true">
                Respostas
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="compartilhamentos-tab" data-bs-toggle="tab" data-bs-target="#compartilhamentos"
                type="button" role="tab" aria-controls="compartilhamentos" aria-selected="true">
                Compartilhamentos
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="prorrogacao-tab" data-bs-toggle="tab" data-bs-target="#prorrogacao" type="button"
                role="tab" aria-controls="prorrogacao" aria-selected="true">
                Prorrogação
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="historico-tab" data-bs-toggle="tab" data-bs-target="#historico" type="button"
                role="tab" aria-controls="historico" aria-selected="true">
                Histórico
            </button>

        </li>
        <li class="nav-item">
            <button class="nav-link" id="invalidar-tab" data-bs-toggle="tab" data-bs-target="#invalidar" type="button"
                role="tab" aria-controls="invalidar" aria-selected="true">
                Invalidar
            </button>
        </li>
        @if ($manifestacao->recursos->count() > 0)
            <li class="nav-item">
                <button class="nav-link" id="recursos-tab" data-bs-toggle="tab" data-bs-target="#recursos" type="button"
                    role="tab" aria-controls="recursos" aria-selected="true">
                    Recursos
                </button>
            </li>
        @endif
    </ul>

    <div class="tab-content card px-2 py-3 border border-top-0 rounded-0 rounded-bottom">
        <div class="tab-pane active" id="inicial" role="tabpanel" aria-labelledby="inicial-tab" tabindex="0">
            Inicial
        </div>

        <div class="tab-pane" id="anexos" role="tabpanel" aria-labelledby="anexos-tab" tabindex="0">
            Anexos
        </div>

        <div class="tab-pane" id="respostas" role="tabpanel" aria-labelledby="respostas-tab" tabindex="0">
            Respostas
        </div>

        <div class="tab-pane" id="compartilhamentos" role="tabpanel" aria-labelledby="compartilhamentos-tab"
            tabindex="0">
            compartilhamento
        </div>

        <div class="tab-pane" id="prorrogacao" role="tabpanel" aria-labelledby="prorrogacao-tab" tabindex="0">
            @if ($manifestacao->prorrogacao->count() > 0)
                <table class="table table-striped">
                    <thead>
                        <th>ID</th>
                        <th>Data de Solicitação</th>
                        <th>Motivo da Solicitação</th>
                        <th>Solicitante</th>
                        <th>Resposta</th>
                        <th>Situação</th>
                        <th>Data da Resposta</th>
                    </thead>
                    <tbody>
                        @foreach ($manifestacao->prorrogacao as $prorrogacao)
                            <tr>
                                <td>{{ $prorrogacao->id }}</td>
                                <td>{{ formatarDataHora($prorrogacao->created_at) }}</td>
                                <td>{{ $prorrogacao->motivo_solicitacao }}</td>
                                <td>{{ $prorrogacao->user->name }}</td>
                                <td>{{ $prorrogacao->resposta }}</td>
                                @if ($prorrogacao->situacao == App\Models\Prorrogacao::SITUACAO_APROVADO )
                                    <td>{{ App\Models\Prorrogacao::SITUACAO_NOME[$prorrogacao->situacao] }} <i
                                            class="text-success fa-solid fa-circle-check"></i></td>
                                @elseif($prorrogacao->situacao == App\Models\Prorrogacao::SITUACAO_REPROVADO )
                                    <td>{{ App\Models\Prorrogacao::SITUACAO_NOME[$prorrogacao->situacao] }} <i
                                            class="text-danger fa-solid fa-circle-xmark"></i></td>
                                @else
                                    <td>{{ App\Models\Prorrogacao::SITUACAO_NOME[$prorrogacao->situacao] }}</td>
                                @endif
                                <td>{{ formatarDataHora($prorrogacao->updated_at) }}</td>
                                {{-- @if ($manifestacao->situacao_id != $situacaoAguardandoProrrogacao) --}}
                                @if ($prorrogacao->situacao == App\Models\Prorrogacao::SITUACAO_ESPERA )
                                    {
                                    <td> <button id="modal" class="btn btn-primary"
                                            onclick="$('#prorrogacao_{{ $prorrogacao->id }}').modal('show');"
                                            type="submit">Responder</button></td>
                                    }
                                    <div id="prorrogacao_{{ $prorrogacao->id }}" name="id" class="modal" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Resposta</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class=""
                                                        action="{{ route('criar-resposta', ['manifestacao' => $manifestacao, 'prorrogacao' => $prorrogacao]) }}"
                                                        method="POST">
                                                        <p>Deseja aceitar esta prorrogação?</p>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="situacao"
                                                                value="{{ App\Models\Prorrogacao::SITUACAO_APROVADO }}">
                                                            <label class="form-check-label" for="flexRadioDefault1">
                                                                {{ App\Models\Prorrogacao::SITUACAO_NOME[App\Models\Prorrogacao::SITUACAO_APROVADO] }}
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="situacao"
                                                                value="{{ App\Models\Prorrogacao::SITUACAO_REPROVADO }}" checked>
                                                            <label class="form-check-label" for="flexRadioDefault2">
                                                                {{ App\Models\Prorrogacao::SITUACAO_NOME[App\Models\Prorrogacao::SITUACAO_REPROVADO] }}
                                                            </label>
                                                        </div>
        
                                                        <label class="mt-3" for="">Justifique o motivo da sua
                                                            escolha:</label>
                                                        <textarea class="mt-3 mb-3 form-control" name="resposta" placeholder="Mensagem*" id="descricao" rows="5"></textarea>
                                                        {{ csrf_field() }}
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success"
                                                                onclick="close_modal()">Solicitar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- @endif --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            @if ($manifestacao->situacao_id != $situacaoAguardandoProrrogacao)
                <div>
                    <button id="modal" class="btn btn-primary mt-4" onclick="solicitacao_1()"
                        type="submit">Solicitar
                        Prorrogação</button>
                </div>
                <div id="myModal1" name="id" class="modal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Prorrogação</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="" action="{{ route('criar-prorrogacao', $manifestacao->id) }}"
                                    method="POST">
                                    <p>Justifique a prorrogação dessa manifestação:</p>
                                    <textarea name="motivo_solicitacao" class="mb-3 form-control" name="mensagem" placeholder="Mensagem*"
                                        id="descricao" rows="5"></textarea>
                                    {{ csrf_field() }}
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success"
                                            onclick="close_modal()">Solicitar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <div class="tab-pane" id="historico" role="tabpanel" aria-labelledby="historico-tab" tabindex="0">
            <table class="table table-striped">
                <thead>
                    <th>Etapas</th>
                    <th>Data de criação</th>
                </thead>
                <tbody>
                    @forelse ($manifestacao->historico as $etapa)
                        <tr id="{{ $etapa->id }}">
                            <td class="name">
                                {{ $etapa->alternativo }}
                            </td>
                            <td>
                                {{ formatarDataHora($etapa->created_at) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center table-warning">
                                Nenhum resultado encontrado!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="tab-pane" id="invalidar" role="tabpanel" aria-labelledby="invalidar-tab" tabindex="0">
            Invalidar
        </div>

        @if ($manifestacao->recursos->count() > 0)
            <div class="tab-pane" id="recursos" role="tabpanel" aria-labelledby="recursos-tab" tabindex="0">
                <div class="accordion" id="accordion">
                    @foreach ($manifestacao->recursos as $key => $recurso)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#r-{{ $recurso->id }}" aria-expanded="true"
                                    aria-controls="#r-{{ $recurso->id }}">
                                    {{ $key + 1 }}º Recurso
                                </button>
                            </h2>
                            <div id="r-{{ $recurso->id }}"
                                class="accordion-collapse collapse @if ($loop->last) show @endif"
                                data-bs-parent="#accordion">
                                <div class="accordion-body">
                                    <div class="mb-3 d-flex justify-content-start me-5">
                                        <div class="mw-50 bg-secondary bg-opacity-25 p-3">
                                            <p class="m-0 text-start">
                                                <b>
                                                    {{-- {{ $manifestacoes->autor->name }} --}}
                                                    -
                                                    {{ formatarDataHora($recurso->created_at) }}
                                                </b>
                                            </p>
                                            <hr class="m-1">
                                            <p class="m-0">
                                                {{ $recurso->recurso }}
                                            </p>
                                        </div>
                                    </div>
                                    @if (!is_null($recurso->resposta))
                                        <div class="mb-3 d-flex justify-content-end ms-5">
                                            <div class="bg-success bg-opacity-25 p-3 rounded">
                                                <p class="m-0 text-end">
                                                    <b>
                                                        {{ auth()->user()->name }}
                                                        -
                                                        {{ formatarDataHora($recurso->data_resposta) }}
                                                    </b>
                                                </p>
                                                <hr class="m-1">
                                                <p class="m-0">
                                                    {{ $recurso->resposta }}
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-end">
                                            <a class="btn btn-warning" onclick="responderRecurso({{ $recurso->id }})">
                                                <i class="fa-solid fa-reply"></i>
                                                Responder
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Modal --}}
    {{-- @if ($prorrogacao->situacao != 3)
        <div id="myModal1" name="id" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Prorrogação</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="" action="{{ route('criar-prorrogacao', $manifestacao->id) }}" method="POST">
                            <p>Justifique a prorrogação dessa manifestação:</p>
                            <textarea name="motivo_solicitacao" class="mb-3 form-control" name="mensagem" placeholder="Mensagem*"
                                id="descricao" rows="5"></textarea>
                            {{ csrf_field() }}
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success" onclick="close_modal()">Solicitar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>   
        </div>
    @endif --}}

    <div class="modal fade" id="responderRecursoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resposta ao recurso:</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        {{ csrf_field() }}
                        <div class="form-floating">
                            <textarea class="form-control" name="respostaRecurso" id="respostaRecurso" style="height:20vh; resize: none"
                                placeholder="respostaRecurso"></textarea>
                            <label for="respostaRecurso">Resposta</label>
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            <a class="btn btn-danger" onclick="$('#responderRecursoModal').modal('hide')">Cancelar</a>
                            <button class="ms-2 btn btn-primary" type="submit">
                                <i class="fa-solid fa-reply"></i>
                                Responder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection

@push('scripts')
    <script>
        function limpar() {
            $('#mensagem').val('');
            $('#status').val(1);
            $('#upload-file').val('');
            $("#uploaded-files").empty();
            $("#uploaded").addClass('d-none');
        }

        function responderRecurso(id) {
            $('#responderRecursoModal').modal('show');
        }

        function enviarMsg() {
            Swal.fire({
                title: 'Deseja Enviar essa Mensagem?',
                text: "Não será possivel editar essa mensagem após enviada!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Sim, Enviar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    if ($('#upload-file').val() == "" && $('#mensagem').val() == "") {
                        Swal.fire(
                            'Erro!',
                            'Nenhuma mensagem ou arquivo anexado.',
                            'error'
                        );
                    } else {
                        Swal.fire(
                            'Enviado!',
                            'Mensagem Enviada.',
                            'success'
                        ).then((result) => {
                            $('#enviarMsg').submit();
                        });
                    }
                }
            });
        }

        function uploadFile() {
            $('#upload-file')[0].click();
        }

        $("#upload-file").change(function() {
            var names = [];
            for (var i = 0; i < $(this).get(0).files.length; ++i) {
                names.push($(this).get(0).files[i].name);
            }
            uploaded(names);
        });

        function uploaded(nomes) {
            $("#uploaded").removeClass('d-none');
            text = '';
            nomes.forEach(element => {
                text += element + ", ";
            });
            $("#uploaded-files").html(text);
        }

        function solicitacao_1() {
            $('#myModal1').modal('show');
        }

        function close_modal() {
            $('#myModal1').modal('hide');
        }


        function close_modal() {
            $('#myModal2').modal('hide');
        }
    </script>
@endpush
