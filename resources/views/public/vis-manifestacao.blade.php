@extends('template.base')

@section('content')
    <div class="accordion accordion-flush mb-3">
        <div class="accordion-item mb-3">
            <h3 class="accordion-header">
                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
                    Manifestação:
                </button>
            </h3>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="row align-items-start p-3">
                        <div class="col">
                            <label class="fw-bold" for="">Nome</label>
                            <p class="border-2 border-bottom border-warning">{{ $manifestacao->nome }}</p>
                        </div>
                        <div class="col">
                            <label class="fw-bold" for="">Tipo de Manifestação</label>
                            <p class="border-2 border-bottom border-warning">{{ $manifestacao->tipoManifestacao->nome }}</p>
                        </div>
                        <div class="col">
                            <label class="fw-bold" for="">Situação:</label>
                            <p class="border-2 border-bottom border-warning">{{ $manifestacao->situacao->nome }}</p>
                        </div>
                        <div class="col">
                            <label class="fw-bold" for="">Data da Manifestação:</label>
                            <p class="border-2 border-bottom border-warning">
                                {{ Carbon\Carbon::parse($manifestacao->updated_at)->format('d/m/Y \à\s H:i\h') }}</p>
                        </div>
                        <label class="fw-bold" for="">Manifestação:</label>
                        <p class="border-2 border-bottom border-warning">{{ $manifestacao->manifestacao }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-item mb-3">
            <h3 class="accordion-header">
                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    Resposta:
                </button>
            </h3>
            <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <label for="" class="fw-bold">Contextualização:</label>
                    <p class="border-2 border-bottom border-warning">{{ $manifestacao->contextualizacao }}</p>
                    <label for="" class="fw-bold">Providência adotada:</label>
                    <p class="border-2 border-bottom border-warning">{{ $manifestacao->providencia_adotada }}</p>
                    <label for="" class="fw-bold">Conclusão:</label>
                    <p class="border-2 border-bottom border-warning">{{ $manifestacao->conclusao }}</p>
                </div>
            </div>
        </div>

        <div class="border accordion-item mb-3">
            <h3 class="accordion-header">
                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseOne">
                    Histórico:
                </button>
            </h3>
            <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <table class="border table table-striped">
                        <thead>
                            <th>Etapas</th>
                            <th>Data de criação</th>
                        </thead>
                        <tbody>
                            @forelse ($manifestacao->historico as $etapa)
                                <tr id="{{ $etapa->id }}">
                                    <td class="name">
                                        {{ $etapa->etapas }}
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
            </div>
        </div>

        {{-- @if ($manifestacao->situacao->nome == 'Pré-Concluída' || $manifestacao->situacao->nome == 'Recurso') --}}
            <div class="border accordion-item mb-3">
                <h3 class="accordion-header">
                    <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                        Recurso:
                    </button>
                </h3>
                <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        @foreach ($manifestacao->recursos as $key=>$recurso)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <div>
                                        <button class="border accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#recurso_{{ $recurso->id }}" aria-expanded="false"
                                            aria-controls="recurso_{{ $recurso->id }}">
                                            Recurso - {{ $key + 1 }}
                                        </button>
                                    </div>
                                </h2>

                                <div id="recurso_{{ $recurso->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#recurso">
                                    <div class="m-2">
                                        <div class="accordion-body">
                                            <div align="left" class="mt-3 border-1 border border-secondary p-2 accordion-body">
                                                <p class="fw-bold border-2 border-bottom border-warning">
                                                    Recurso-
                                                    {{formatarDataHora($recurso->created_at)}}
                                                </p>
                                                <p>
                                                    {{$recurso->recurso}}
                                                </p>
                                            </div>
                                            @if (!is_null($recurso->resposta))
                                                <div align="left" class="mt-2 border-1 border border-secondary p-2 accordion-body">
                                                    <p class="fw-bold border-2 border-bottom border-warning">
                                                        Resposta ao recurso-
                                                        {{formatarDataHora($recurso->data_resposta)}}
                                                    </p>
                                                    <p>
                                                        {{$recurso->resposta}}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                                
                        {{-- @if ($podeCriarRescurso) --}}
                            <div class='p-3 text-center'>
                                <button class="btn btn-primary mt-4" id="button_open_recurso">
                                    Entrar com recurso
                                </button>
                            </div>
                            <div name="id" id="Modal_recurso" class="modal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Informar recurso</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="" action="{{ route('criar-recurso') }}" method="POST">
                                                <p>Digite um texto relacionado ao seu recurso:</p>
                                                <textarea class="form-control" name="recurso" id="recurso" rows="5"></textarea>
                                                <input type="hidden" name="protocolo"
                                                value="{{ $manifestacao->protocolo }}">
                                                <input type="hidden" name="senha" value="{{ $manifestacao->senha }}">
                                                {{ csrf_field() }}
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" id="button_enviar_recurso" class="btn btn-success">Enviar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {{-- @endif  --}}
                    </div>
                </div>
            </div>
        {{-- @endif --}}


    </div>
@endsection

@push('scripts')
    <script nonce="{{ app('csp-nonce') }}">
        $('#button_open_recurso').click(function() {
            $('#Modal_recurso').modal('show');
        });

        $('#button_enivar_recurso').click(function() {
            $('#Modal_recurso').modal('hide');
        });
    </script>
@endpush
