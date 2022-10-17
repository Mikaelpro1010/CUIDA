@extends('template.base')

@section('titulo', 'EscutaSol - Mensagens')
@section('content')
<div>
    <div class="d-sm-grid d-md-flex justify-content-between">
        <h3>
            <a href="" class="text-decoration-none text-dark" data-bs-toggle="modal" data-bs-target="#infoModal">
                Chat Manifestação: {{ $canalManifestacao->manifestacao->protocolo }} - {{
                canalMensagem()::STATUS_CANAL_MSG[$canalManifestacao->id_status] }}
                <i class="fa-solid fa-circle-info text-primary"></i>
            </a>
        </h3>
        @if ($canalManifestacao->id_status != canalMensagem()::STATUS_ENCERRADO)
        <a class="btn btn-danger" onclick="encerrar()"><i class="fa-solid fa-flag-checkered"></i> Encerrar Canal</a>
        @endif
    </div>

    <div class="my-3">
        <div class="bg-primary bg-opacity-10 border border-2 rounded-top  border-bottom-0 p-2 overflow-auto"
            style="height: @if ($canalManifestacao->id_status != canalMensagem()::STATUS_ENCERRADO) 40vh @else 65vh @endif">
            @foreach ( $mensagens as $mensagem)
            @if ($mensagem->msg_type == mensagem()::TIPO_APP_USER)
            <div class="mb-3 d-flex justify-content-start me-5">
                <div class="mw-50 bg-secondary bg-opacity-25 p-3">
                    <p class="m-0 text-start">
                        <b>
                            {{ $mensagem->autorMsgAppUser->name }}
                            -
                            {{ formatarDataHora($mensagem->created_at) }}
                        </b>
                    </p>
                    <hr class="m-1">
                    <p class="m-0">
                        {{ $mensagem->mensagem }}
                    </p>
                    @if ($mensagem->anexos->count() > 0)
                    <hr class="m-1">
                    <tr>
                        <th>
                            Anexos:
                        </th>
                        @foreach ($mensagem->anexos as $anexo)
                        <td>
                            <a href="{{$anexo->getUrl()}}" target='_blank'
                                download="{{$anexo->nome_original}}">{{$anexo->nome_original}}</a>
                        </td>
                        @endforeach
                    </tr>
                    @endif
                </div>
            </div>
            @endif
            @if ($mensagem->msg_type == mensagem()::TIPO_OUVIDOR)
            <div class="mb-3 d-flex justify-content-end ms-5">
                <div class="bg-success bg-opacity-25 p-3 rounded">
                    <p class="m-0 text-end">
                        <b>
                            {{ $mensagem->autorMsgUser->name }}
                            -
                            {{ formatarDataHora($mensagem->created_at) }}
                        </b>
                    </p>
                    <hr class="m-1">
                    <p class="m-0">
                        {{ $mensagem->mensagem }}
                    </p>
                    @if ($mensagem->anexos->count() > 0)
                    <hr class="m-1">
                    <tr>
                        <th>
                            Anexos:
                        </th>
                        @foreach ($mensagem->anexos as $anexo)
                        <td>
                            <a href="{{$anexo->getUrl()}}" target='_blank'
                                download="{{$anexo->nome_original}}">{{$anexo->nome_original}}</a>
                        </td>
                        @endforeach
                    </tr>
                    @endif
                </div>
            </div>
            @endif
            @if ($loop->last)
            <div id="final"></div>
            @endif
            @endforeach
        </div>
        @if ($canalManifestacao->id_status != canalMensagem()::STATUS_ENCERRADO)
        <form id="enviarMsg" class="bg-primary bg-opacity-10 border border-2 border-top-0 p-2 rounded-bottom"
            action="{{ route('enviarMsg', ['id' => $id]) }}" method="POST" enctype='multipart/form-data'>
            {{ csrf_field() }}
            <div class="form-floating">
                <textarea class="form-control" name="mensagem" id="mensagem" style="height:15vh; resize: none"
                    placeholder="Mensagem"></textarea>
                <label for="mensagem">Mensagem</label>
            </div>
            <div id="uploaded" class="mt-2 d-none">
                Enviados:
                <span id="uploaded-files" class="text-danger"></span>
            </div>
            <div class="d-flex flex-wrap justify-content-between">
                <a class='btn btn-primary mt-3' onclick="uploadFile()">
                    <i class="fa-solid fa-upload"></i>
                    Enviar Arquivo
                    <input id='upload-file' type='file' value='' name="anexo[]" multiple=true hidden />
                </a>
                <div class="d-flex flex-grow mt-3">
                    <label class="my-auto mx-2">Status Mensagem:</label>
                    <div>
                        <select class="form-select" name="status" id="status">
                            <option value="{{ canalMensagem()::STATUS_RESPONDIDO }}">Resposta</option>
                            <option value="{{ canalMensagem()::STATUS_ENCERRADO }}">Encerramento</option>
                        </select>
                    </div>
                </div>
                <div class="flex-wrap mt-3">
                    <a class="btn btn-warning mx-2" onclick="limpar()">
                        Limpar
                        <i class="fa-solid fa-eraser"></i>
                    </a>
                    <a class="btn btn-primary" onclick="enviarMsg()">
                        Enviar
                        <i class="fa-solid fa-paper-plane"></i>
                    </a>
                </div>
            </div>
        </form>
        @endif
    </div>
</div>
{{-- Modal --}}
<div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <a href="{{ route('visualizarManifests', $canalManifestacao->id_manifestacao) }}"
                        class="text-decoration-none text-dark">
                        Manifestação:
                        {{ $canalManifestacao->manifestacao->protocolo }}
                        /
                        {{ manifest()::TIPO_MANIFESTACAO[$canalManifestacao->manifestacao->id_tipo_manifestacao]}}
                        -
                        {{ manifest()::SITUACAO[$canalManifestacao->manifestacao->id_situacao]}}
                    </a>
                    <a href="{{ route('visualizarManifests', $canalManifestacao->id_manifestacao) }}" target="_blank">
                        <i class="fa-solid fa-arrow-up-right-from-square text-primary"></i>
                    </a>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md">
                        <b>Usuário:</b>
                        <p class="border-2 border-bottom border-warning">{{
                            $canalManifestacao->manifestacao->autor->name }}</p>
                    </div>
                    <div class="col-md">
                        <b>Email:</b>
                        <p class="border-2 border-bottom border-warning">
                            {{ $canalManifestacao->manifestacao->autor->email }}
                        </p>
                    </div>
                    <div class="col-md-2">
                        <b>Protocolo:</b>
                        <p class="border-2 border-bottom border-warning">
                            {{ $canalManifestacao->manifestacao->protocolo }}
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <b>Data da Manifestação:</b>
                        <p class="border-2 border-bottom border-warning">
                            {{ formatarDataHora($canalManifestacao->manifestacao->data_abertura) }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <b>Situação:</b>
                        <p class="border-2 border-bottom border-warning">
                            {{
                            manifest()::SITUACAO[$canalManifestacao->manifestacao->id_situacao]}}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <b>Tipo de Manifestação:</b>
                        <p class="border-2 border-bottom border-warning">
                            {{
                            manifest()::TIPO_MANIFESTACAO[$canalManifestacao->manifestacao->id_tipo_manifestacao]}}
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <b>Manifestação:</b>
                        <p class="border-2 border border-warning p-2">
                            {{ $canalManifestacao->manifestacao->manifestacao }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
    $(document).ready(function(){ 
        $('#final')[0].scrollIntoView();
    });

@if ($canalManifestacao->id_status != canalMensagem()::STATUS_ENCERRADO)
    function limpar(){
        $('#mensagem').val('');
        $('#status').val(1);
        $('#upload-file').val('');
        $("#uploaded-files").empty();
        $("#uploaded").addClass('d-none');
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

    function encerrar(){
        Swal.fire({
        title: 'Deseja Encerrar o Chat desta Manifestação?',
        text: "Uma vez encerrado não será possivel enviar ou receber mensagens por este!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Sim, Encerrar!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                'Encerrado!',
                'Chat Encerrado.',
                'success'
                ).then((result) => {
                    $.post('{{ route("encerrarCanal", ["id" => $id]) }}', {'_token': '{{ csrf_token() }}' })
                    .done(()=>{location.reload();});
                });
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

@endif

</script>

@endpush