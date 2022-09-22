@extends('template.simple_page')

@section('titulo', 'Avaliar - Unidade da Secretaria')

@section('main')
<div class="card shadow py-2">
    <h1 class="text-center px-3">{{ $unidade->nome }}</h1>

    <div class="text-center">
        <p>Como gostaria de nos avaliar hoje?</p>

        <form action="" method="POST">
            {{ csrf_field() }}
            <fieldset class="avaliar">
                <label for="muito-triste" onclick="javascript:avaliar(1)">
                    <input class="d-none" type="radio" name="avaliacao" id="muito-triste" value="1">
                    <i id="1" class="fa-4x text-danger fa-regular fa-face-angry"></i>
                </label>
                <label for="triste" onclick="javascript:avaliar(2)">
                    <input class="d-none" type="radio" name="avaliacao" id="triste" value="2">
                    <i id="2" class="fa-4x text-danger fa-regular fa-face-frown"></i>
                </label>
                <label for="neutro" onclick="javascript:avaliar(3)">
                    <input class="d-none" type="radio" name="avaliacao" id="neutro" value="3">
                    <i id="3" class="fa-4x text-primary fa-regular fa-face-meh"></i>
                </label>
                <label for="feliz" onclick="javascript:avaliar(4)">
                    <input class="d-none" type="radio" name="avaliacao" id="feliz" value="4">
                    <i id="4" class="fa-4x text-success fa-regular fa-face-smile"></i>
                </label>
                <label for="muito-feliz" onclick="javascript:avaliar(5)">
                    <input class="d-none" type="radio" name="avaliacao" id="muito-feliz" value="5">
                    <i id="5" class="fa-4x text-success fa-regular fa-face-laugh-beam"></i>
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

@section('scripts')
<script>
    function avaliar(valor){
        $('.avaliar i').each(function(index, element){
            $(element).addClass('opacity-50');
        });
        $('#'+valor).removeClass('opacity-50');
        $("#comentario").removeClass('d-none');
        $("#btn-avaliacao").removeClass('d-none');
        
        switch (valor) {
            case 1:
                $('#'+valor).addClass('text-danger');
                $('#avaliacao-text').html('<span class="text-danger">Muito Ruim</span>');        
                break;
            case 2:
                $('#'+valor).addClass('text-danger');
                $('#avaliacao-text').html('<span class="text-danger">Ruim</span>');        
                break;
            case 3:
                $('#'+valor).addClass('text-primary');
                $('#avaliacao-text').html('<span class="text-primary">Neutro</span>');        
                break;
            case 4:
                $('#'+valor).addClass('text-success');
                $('#avaliacao-text').html('<span class="text-success">Bom</span>');        
                break;                
            case 5:
                $('#'+valor).addClass('text-success');
                $('#avaliacao-text').html('<span class="text-success">Muito Bom</span>');        
                break;
        }
    }
</script>
@endsection