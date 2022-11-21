@extends('template.base')

@section('content')
    <div class="text-primary">
        <h2>
            Cadastrar FAQ
        </h2>
    </div>
    <hr>
    <div>
        <form class="row" action="{{ route('post-store-faq') }}" method="POST">
            {{ csrf_field() }}
            <div class="col-12">
                <label for="resposta" class="form-label mb-1 mt-3">Informe a pergunta:</label>
                <textarea class="form-control" name="pergunta" id="pergunta" rows="5"></textarea>
            </div>

            <div class="col-12">
                <label for="resposta" class="form-label mb-1 mt-3">Digite a resposta:</label>
                <textarea class="form-control" name="resposta" id="resposta" rows="5"></textarea>
            </div>

            <div class="d-flex">
                <button class="btn btn-info mt-4" type="submit">Salvar cadastro</button>
            </div>

        </form>
    </div>
    </div>
@endsection

@section('scripts')
@endsection