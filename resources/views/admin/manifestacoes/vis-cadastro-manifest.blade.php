@extends('template.base')

@section('content')
    <h3 class="text-primary">Formulário de Manifestação</h3>
    <hr>
    <div class="p-4">
        <form class="row" action="{{ route('post-store-manifest2') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <label class="mb-1 mt-3" for="">Nome:</label>
            <div class="col-10">
                <input type="text" name="nome" id="nome" placeholder="Nome" class="form-control" value="{{old('nome')}}">
            </div>

            <div class="col-2">
                <p class="mb-1">Anônimo?*</p>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="anonimo" id="select_sim" value="1">
                    <label class="form-check-label" for="select_sim">
                        Sim
                    </label>
                </div>
                <div class="mb-2 form-check">
                    <input class="form-check-input" type="radio" name="anonimo" id="select_nao" value="0" checked>
                    <label class="form-check-label" for="select_nao">
                        Não
                    </label>
                </div>
            </div>

            <div class="col-5">
                <label class="" for="">E-mail:</label>
                <input type="email" name="email" id="email" placeholder="E-mail" class="form-control" value="{{old('email')}}">
            </div>

            <div class="col-5">
                <label for="">Número:</label>
                <input type="text" name="numero_telefone" id="numero" placeholder="(XX) XXXXXXXXX" class="form-control" value="{{old('numero_telefone')}}">
            </div>

            <div class="col-2">
                <p class="mb-1">Tipo de telefone?*</p>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo_telefone" value="1" id="tipoCel" checked>
                    <label class="form-check-label" for="tipoCel">
                        Cel
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo_telefone" value="2" id="tipoFixo">
                    <label class="form-check-label" for="tipoFixo">
                        Fixo
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo_telefone" value="3" id="tipoWhats">
                    <label class="form-check-label" for="tipoWhats">
                        Whatsapp
                    </label>
                </div>
            </div>

            <div class="col-4 mb-3">
                <label class="mb-1 mt-3" for="">Endereço:</label>
                <input type="text" name="endereco" id="endereco" placeholder="Endereço" class="form-control" value="{{old('endereco')}}">
            </div>

            <div class="col-4 mb-3">
                <label class="mb-1 mt-3" for="">Bairro:</label>
                <input type="text" name="bairro" id="endereco" placeholder="Bairro" class="form-control" value="{{old('bairro')}}">
            </div>

            <div class="col-4 mb-3">
                <label class="mb-1 mt-3" for="">Tipo de assunto:</label>
                <select class="form-select" name="tipo_manifestacao">
                    <option value="">Selecione</option>
                    @foreach ($tipos_manifestacao as $tipo_manifestacao)
                        <option value="{{ $tipo_manifestacao->id }}">{{ $tipo_manifestacao->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-4 mt-3">
                <label class="mb-1 mt-3" for="">Situação:</label>
                <select class="form-select" name="situacao">
                    <option value="">Selecione</option>
                    @foreach ($situacoes as $situacao)
                        <option value="{{ $situacao->id }}">{{ $situacao->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-4 mt-3">
                <label class="mb-1 mt-3" for="">Estado-processo:</label>
                <select class="form-select" name="estado_processo">
                    <option value="">Selecione</option>
                    @foreach ($estados_processo as $estado_processo)
                        <option name="estados_processo" value="{{ $estado_processo->id }}">{{ $estado_processo->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-4 mt-3">
                <label class="mb-1 mt-3" for="">Motivação:</label>
                <select class="form-select" name="motivacao">
                    <option value="">Selecione</option>
                    @foreach ($motivacoes as $motivacao)
                        <option name="motivacoes" value="{{ $motivacao->id }}">{{ $motivacao->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 mt-3">
                <label class="mb-1 mt-3" for="">Descrição:</label>
                <textarea class="form-control" name="manifestacao" placeholder="Mensagem*" id="descricao" rows="5"></textarea>
            </div>

            <div class="d-flex mt-3 form-group">
                <input name="anexos" class="form-control" type="file" id="formFile">
            </div>

            <div class="mb-1 mt-3 mb-3">
                <div class="form-check">
                    <input for="checkbox" name="checkbox" id="checkbox" required="required" class="form-check-input"
                        type="checkbox" value="" aria-label="Checkbox for following text input">

                    <label for="checkbox" class="form-check-label">Declaro que as informações acima são verdadeiras e
                        estou ciente de estar sujeito às penas da legislação pertinente caso tenha afirmado falsamente os
                        dados preenchidos.</label>
                </div>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-primary mt-4" type="submit">Enviar Manifestação</button>
                </div>
            </div>
        </form>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#numero').mask('(00) 00000-0000')
        });
        $("#select_sim").change(function(){
            $("#nome").prop("disabled", true)
            $("#nome").val("");
            $("#email").prop("disabled", true)
            $("#email").val("");
            $("#numero").prop("disabled", true)
            $("#numero").val("");
            $("#tipoCel").prop("disabled", true)
            $("#tipoCel").val("");
            $("#tipoFixo").prop("disabled", true)
            $("#tipoFixo").val("");
            $("#tipoWhats").prop("disabled", true)
            $("#tipoWhats").val("");
        })
        $("#select_nao").change(function(){
            $("#nome").prop("disabled", false)
            $("#email").prop("disabled", false)
            $("#numero").prop("disabled", false)
            $("#tipoCel").prop("disabled", false)
            $("#tipoFixo").prop("disabled", false)
            $("#tipoWhats").prop("disabled", false)
        })

    </script>
@endpush
