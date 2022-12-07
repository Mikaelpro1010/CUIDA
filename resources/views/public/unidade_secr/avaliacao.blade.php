@extends('template.simple_page')

@section('titulo', 'Avaliar - Unidade da Secretaria')

@section('main')
<div class="card shadow py-2">
    <h1 class="text-center px-3">{{ $unidade->nome }}</h1>

    <div class="text-center">
        <p>Como gostaria de nos avaliar hoje?</p>

        <form action="{{ route('post-store-avaliacao', $unidade->token) }}" method="POST">
            {{ csrf_field() }}
            <fieldset class="avaliar">
                <label id="label-muito-feliz" for="muito-feliz">
                    <input class="d-none" type="radio" name="avaliacao" id="muito-feliz" value="10">
                    <i id="5" class="fa-4x text-success fa-regular fa-face-laugh-beam"></i>
                </label>

                <label id="label-feliz" for="feliz">
                    <input class="d-none" type="radio" name="avaliacao" id="feliz" value="8">
                    <i id="4" class="fa-4x text-primary fa-regular fa-face-smile"></i>
                </label>

                <label id="label-neutro" for="neutro">
                    <input class="d-none" type="radio" name="avaliacao" id="neutro" value="6">
                    <i id="3" class="fa-4x text-info fa-regular fa-face-meh"></i>
                </label>

                <label id="label-triste" for="triste">
                    <input class="d-none" type="radio" name="avaliacao" id="triste" value="4">
                    <i id="2" class="fa-4x text-warning fa-regular fa-face-frown"></i>
                </label>

                <label id="label-muito-triste" for="muito-triste">
                    <input class="d-none" type="radio" name="avaliacao" id="muito-triste" value="2">
                    <i id="1" class="fa-4x text-danger fa-regular fa-face-angry"></i>
                </label>
            </fieldset>
            <div class="px-3">
                <div id="avaliacao-text" class="my-3">
                </div>
                <div id="comentario" class="form-floating mt-2 d-none">
                    <textarea class="form-control " name="comentario" style="height:15vh; resize: none"></textarea>
                    <label for="comentario">Deseja fazer um comentario? (opcional)</label>
                </div>
                <button id="btn-avaliacao" class="btn btn-success mt-2 d-none" type="submit">
                    Enviar Avaliação
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script nonce="{{ app('csp-nonce') }}">
    $("#label-muito-feliz").click(fn => {avaliar(5)});
    $("#label-feliz").click(fn => {avaliar(4)});
    $("#label-neutro").click(fn => {avaliar(3)});
    $("#label-triste").click(fn => {avaliar(2)});
    $("#label-muito-triste").click(fn => {avaliar(1)});

    function avaliar(valor){
        $('.avaliar label').each(function(index, element){
            $(element).addClass('opacity-50');
        });
        // $('#'+valor).removeClass('opacity-50');
        $("#comentario").removeClass('d-none');
        $("#btn-avaliacao").removeClass('d-none');
        
        switch (valor) {
            case 1:
                $("#label-muito-triste").removeClass('opacity-50');
                $('#'+valor).addClass('text-danger');
                $('#avaliacao-text').html('<span class="text-danger">Muito Ruim</span>');        
                break;
            case 2:
                $("#label-triste").removeClass('opacity-50');
                $('#'+valor).addClass('text-warning');
                $('#avaliacao-text').html('<span class="text-warning">Ruim</span>');        
                break;
            case 3:
                $("#label-neutro").removeClass('opacity-50');
                $('#'+valor).addClass('text-info');
                $('#avaliacao-text').html('<span class="text-info">Neutro</span>');        
                break;
            case 4:
                $("#label-feliz").removeClass('opacity-50');
                $('#'+valor).addClass('text-primary');
                $('#avaliacao-text').html('<span class="text-primary">Bom</span>');        
                break;                
            case 5:
                $("#label-muito-feliz").removeClass('opacity-50');
                $('#'+valor).addClass('text-success');
                $('#avaliacao-text').html('<span class="text-success">Muito Bom</span>');        
                break;
        }
    }
</script>
@endpush