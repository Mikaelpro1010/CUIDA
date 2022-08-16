@extends('template.base')

@section('titulo', 'EscutaSol - Inicio')
@section('content')
<div>
    <div class="d-sm-grid d-md-flex justify-content-between d-flex align-items-center">
        <h3 class="d-flex align-self-middle">Manifestações</h3>
        <div class="m-0 p-0 row gap-2">
            <a href="{{ route('mensagens') }}" class="btn btn-info col-md-auto">
                <p class="m-0 p-0">
                    Chats Aberto(s):
                </p>
            </a>
            <a href="{{ route('mensagens', 
            [
                'protocolo' => request()->protocolo, 
                'data_inicio' => request()->data_inicio,  
                'data_fim' => request()->data_fim, 
                'status' => 1
            ]) }}" class="btn btn-warning col-md-auto">
                <p class="m-0 p-0">
                    Respondido(s):
                </p>
            </a>

            <a href="{{ route('mensagens', 
            [
                'protocolo' => request()->protocolo, 
                'data_inicio' => request()->data_inicio,  
                'data_fim' => request()->data_fim, 
                'status' => 2
            ]) }}" class="btn btn-danger col-md-auto">
                <p class="m-0 p-0">
                    Aguardando Resposta(s):
                </p>
            </a>
            <a href="{{ route('mensagens', 
            [
                'protocolo' => request()->protocolo, 
                'data_inicio' => request()->data_inicio,  
                'data_fim' => request()->data_fim, 
                'status' => 3
            ]) }}" class="btn btn-success col-md-auto">
                <p class="m-0 p-0">
                    Encerrado(s):
                </p>
            </a>
        </div>
    </div>
    <hr>


</div>
@endsection

@section('scripts')
<script>

</script>
@endsection