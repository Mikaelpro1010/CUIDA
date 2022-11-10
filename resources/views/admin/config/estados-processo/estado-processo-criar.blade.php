@extends('template.base')

@section('content')
    <div class="text-primary">
        <h2>
            Cadastrar Estado do Processo
        </h2>
        <hr>
    </div>
    <div>
        <form class="row" action="{{route('post-store-estado-processo')}}" method="POST">
            {{ csrf_field() }}
            <div class="col-12">
                <label for="nome" class="form-label mb-1 mt-3">Informe o nome:</label>
                <input type="text" name="nome" id="nome" class="form-control">
            </div>

            <div class="col-12">
                <label for="descricao" class="form-label mb-1 mt-3">Descreva:</label>
                <textarea class="form-control" name="descricao" id="descricao" rows="5"></textarea>
            </div>

            <div>
                <button class="btn btn-info mt-4" type="submit">Salvar cadastro</button>
            </div>
        </form>
    </div>
@endsection