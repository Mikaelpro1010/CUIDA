@extends('template.base')

@section('titulo', 'EscutaSol - Mensagens')
@section('content')
<div>
    <h3 class="modal-title">
        Manifestação:
        {{ $manifestacao->protocolo }}
        /
        {{ manifest()::TIPO_MANIFESTACAO[$manifestacao->id_tipo_manifestacao]}}
        -
        {{ manifest()::SITUACAO[$manifestacao->id_situacao]}}
    </h3>
</div>
<hr>

<div class="row">
    <div class="col-md-4">
        <b>Usuário:</b>
        <p class="border-2 border-bottom border-warning">{{
            $manifestacao->autor->name }}</p>
    </div>
    <div class="col-md-3">
        <b>Email:</b>
        <p class="border-2 border-bottom border-warning">
            {{ $manifestacao->autor->email }}
        </p>
    </div>
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
            {{
            manifest()::MOTIVACAO[$manifestacao->id_motivacao]}}
        </p>
    </div>
    <div class="col-md-2">
        <b>Estado:</b>
        <p class="border-2 border-bottom border-warning">
            {{
            manifest()::ESTADO_PROCESSO[$manifestacao->id_estado_processo]}}
        </p>
    </div>
    <div class="col-md-3">
        <b>Tipo de Manifestação:</b>
        <p class="border-2 border-bottom border-warning">
            {{
            manifest()::TIPO_MANIFESTACAO[$manifestacao->id_tipo_manifestacao]}}
        </p>
    </div>
    <div class="col-md-2">
        <b>Situação:</b>
        <p class="border-2 border-bottom border-warning">
            {{
            manifest()::SITUACAO[$manifestacao->id_situacao]}}
        </p>
    </div>
    <div class="col-md-3">
        <b>Prazo:</b>
        <p class="border-2 border-bottom border-warning">
            {{
            manifest()::SITUACAO[$manifestacao->id_situacao]}}
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
        <button class="nav-link active" id="recursos-tab" data-bs-toggle="tab" data-bs-target="#recursos" type="button"
            role="tab" aria-controls="recursos" aria-selected="true">Recursos</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
            role="tab" aria-controls="profile" aria-selected="false">Profile</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button"
            role="tab" aria-controls="messages" aria-selected="false">Messages</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button"
            role="tab" aria-controls="settings" aria-selected="false">Settings</button>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="recursos" role="tabpanel" aria-labelledby="recursos-tab" tabindex="0">
        <div class="p-2 border border-1">
            <div class="accordion" id="accordion">
                @foreach ($manifestacao->recursos as $key => $recurso)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#r-{{$recurso->id}}" aria-expanded="true"
                            aria-controls="#r-{{$recurso->id}}">
                            {{ $key + 1 }}º Recurso
                        </button>
                    </h2>
                    <div id="r-{{$recurso->id}}" class="accordion-collapse collapse @if ($loop->last) show @endif"
                        data-bs-parent="#accordion">
                        <div class="accordion-body">
                            <div class="mb-3 d-flex justify-content-start me-5">
                                <div class="mw-50 bg-secondary bg-opacity-25 p-3">
                                    <p class="m-0 text-start">
                                        <b>
                                            {{ $manifestacao->autor->name }}
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
                            @if(!is_null($recurso->resposta))
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
                                <a class="btn btn-warning" onclick="responderRecurso({{$recurso->id}})">
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
    </div>
    <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">2.</div>
    <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab" tabindex="0">.3..</div>
    <div class="tab-pane" id="settings" role="tabpanel" aria-labelledby="settings-tab" tabindex="0">.4..</div>
</div>

{{-- Modal --}}
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
                        <textarea class="form-control" name="respostaRecurso" id="respostaRecurso"
                            style="height:20vh; resize: none" placeholder="respostaRecurso"></textarea>
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

@section('scripts')

<script>
    function limpar(){
        $('#mensagem').val('');
        $('#status').val(1);
        $('#upload-file').val('');
        $("#uploaded-files").empty();
        $("#uploaded").addClass('d-none');
    }

    function responderRecurso(id){
        $('#responderRecursoModal').modal('show');
    }

    function enviarMsg(){
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
                if($('#upload-file').val() == "" && $('#mensagem').val() == ""){
                    Swal.fire(
                        'Erro!',
                        'Nenhuma mensagem ou arquivo anexado.',
                        'error'
                    );
                }
                else{
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

    function uploadFile(){
        $('#upload-file')[0].click();
    }

    $("#upload-file").change(function() {
        var names = [];
        for (var i = 0; i < $(this).get(0).files.length; ++i) { 
            names.push($(this).get(0).files[i].name);   
        }
        uploaded(names);
    });

    function uploaded(nomes){
        $("#uploaded").removeClass('d-none');
        text = '';
        nomes.forEach(element => {
            text += element + ", ";
        });
        $("#uploaded-files").html(text);
    }



</script>

@endsection