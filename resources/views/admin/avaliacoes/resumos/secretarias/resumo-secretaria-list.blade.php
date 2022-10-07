@extends('admin.avaliacoes.template.avaliacao')

@section('titulo', 'Resumo por Secretaria')
@section('content')
<div class="d-flex justify-content-between">
    <h3 class="">
        Resumo por Secretaria
    </h3>
    {{-- <form id="secretaria" class="row" action="">
        <label class="col-md-2 col-form-label" for="secretaria">Secretaria:</label>
        <div class="col-md-10">
            <input id="anoSelect" type="hidden" name="ano" value="{{formatarDataHora(null, 'Y')}}">
            <select id="secretaria" class="form-select" name="secretaria">
                <option value="" @if(is_null(request()->secretaria)) selected @endif >Selecione</option>
                @foreach ( $secretarias as $secretaria )
                <option value="{{ $secretaria->id }}" @if (request()->secretaria == $secretaria->id) selected @endif>
                    {{ $secretaria->sigla . " - " . $secretaria->nome }}
                </option>
                @endforeach
            </select>
        </div>
    </form> --}}
</div>
<hr>
<div class="alert alert-info">
    <ul>
        <li>Selecione uma Secretaria!</li>
    </ul>
</div>

@endsection

@section('scripts')
<script>
    $("#secretaria").change(function(){
        // $('#secretaria').submit();
    });
    
</script>
@endsection