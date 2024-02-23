@extends('template.base')

@section('content')
<!-- Inicio do conteudo do administrativo -->
<div class="row p-3">
    <div class="top-list">
        <span class="title-content">Cadastrar Ofício</span>
        <div class="top-list-right">
            <a href="{{ route('listarAudPrazosDocumentos') }}" class="btn btn-outline-info">Listar</a>
        </div>
    </div>
    
    <div class="content-adm">
        <form class="form-adm" action="" method="post">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-4">
                    <label class="title-input">Número do Ofício</label>
                    <input type="text" name="nome" id="nome" class="form-control" placeholder="Digite o número do ofício">
                </div>
                <div class="col-4">
                    <label class="title-input">Data de criação do relatório</label>
                    <input type="date" name="data_criacao" id="data_criacao" class="form-control" placeholder="Nome">
                </div>
                <div class="col-4">
                    <label class="title-input">Data de envio do relatório</label>
                    <input type="date" name="data_envio" id="data_envio" class="form-control" placeholder="Nome">
                </div>
                <div class="col-12 mt-3">
                    <label class="title-input">Descrição do ofício</label>
                    <textarea name="descricao_oficio" id="descricao_oficio" class="form-control" placeholder="Digite a descrição do ofício"></textarea>
                </div>
                <div class="col-12 mt-3">
                    <label class="title-input">Observações sobre o ofício</label>
                    <textarea name="observacao_oficio" id="observacao_oficio" class="form-control" placeholder="Digite aqui as observações sobre o ofício"></textarea>
                </div>
                <div class="col-12 mt-3">
                    <label class="title-input">Secretaria</label>
                    <select name="secretaria" class="form-select" aria-label="Default select example">
                        <option selected>Selecione uma opção</option>
                        <option value="1">Sim</option>
                        <option value="2">Não</option>
                    </select>
                </div>
                <div class="col-5 mt-2">
                    <label class="title-input">Possui PROAD?</label>
                    <select name="lado_timeline" class="form-select" aria-label="Default select example">
                        <option selected>Selecione</option>
                        <option value="1">Sim</option>
                        <option value="2">Não</option>
                    </select>
                </div>
                <div class="col-5 mt-2">
                    <label class="title-input">Prazo para resposta?</label>
                    <select name="prazo_resposta" class="form-select" aria-label="Default select example">
                        <option selected>Selecione</option>
                        <option value="left">Left</option>
                        <option value="rigth">Rigth</option>
                    </select>
                </div>
                <div class="col mt-3">
                    <label class="title-input">Documentos</label>
                    <input type="file" id="fileInput" name="Escolher Arquivo">
                    <br>
                    <div class="alert alert-danger">
                        <span>Atenção: Só é permitido anexos abaixo de 8MB. Caso o tamanho do arquivo exceda esse limite, entre em contato com o Admnistrador do sistema.</span>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <label class="title-input">O processo foi encerrado?</label>
                    <select name="processo_encerrado" class="form-select" aria-label="Default select example">
                        <option selected>Selecione</option>
                        <option value="1">Sim</option>
                        <option value="2">Não</option>
                    </select>
                </div>
            </div>
    
            <div class="mt-3">
                <button type="submit" class="btn btn-outline-success">Salvar</button>
            </div>
        </form>
    </div>
    <!-- Fim do conteudo do administrativo -->
</div>
@endsection