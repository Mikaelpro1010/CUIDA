@extends('template.initial')

@section('titulo', 'Avaliar - Setor da Secretaria')

@section('main')
    <div class="card shadow py-2">
        <h1 class="text-center px-3">{{ $setor->nome }}</h1>
        <h4 class="text-center text-secondary px-3">{{ $setor->unidade->nome }}</h4>

        <div class="text-center">
            @foreach ($setor->tiposAvaliacao as $key => $tipoAvaliacao)
                <div id="title-{{ $key }}" class="@if (!$loop->first) d-none @endif">
                    <p class="align-items-center">
                        {{ $key + 1 }} - {{ $tipoAvaliacao->pergunta }}
                        <span id="check-{{ $key }}" class="d-none">
                            <i class="fa-2x fa-solid fa-check text-success"></i>
                        </span>
                    </p>
                </div>
                <div id="body-{{ $key }}" class="@if (!$loop->first) d-none @endif">
                    <fieldset class="avaliar-{{ $key }} mb-2">
                        <label id="label-muito-triste-{{ $key }}" data-item='{{ $key }}' data-nota='1'>
                            <i class="fa-4x text-danger fa-regular fa-face-angry"></i>
                        </label>

                        <label id="label-triste-{{ $key }}" data-item='{{ $key }}' data-nota='2'>
                            <i class="fa-4x text-warning fa-regular fa-face-frown"></i>
                        </label>

                        <label id="label-neutro-{{ $key }}" data-item='{{ $key }}' data-nota='3'>
                            <i class="fa-4x text-info fa-regular fa-face-meh"></i>
                        </label>

                        <label id="label-feliz-{{ $key }}" data-item='{{ $key }}' data-nota='4'>
                            <i class="fa-4x text-primary fa-regular fa-face-smile"></i>
                        </label>

                        <label id="label-muito-feliz-{{ $key }}" data-item='{{ $key }}' data-nota='5'>
                            <i class="fa-4x text-success fa-regular fa-face-laugh-beam"></i>
                        </label>
                    </fieldset>

                    <span id="avaliacao-text-{{ $key }}"></span>
                    <div id="comentario-{{ $key }}" class=" px-3 d-none">
                        <label class="d-flex aling-itens-start my-2" for="textArea-{{ $key }}">
                            Comentário(opcional):
                        </label>
                        <textarea id="textArea-{{ $key }}" class="form-control"></textarea>
                        <input id="avaliacao-{{ $key }}" type="hidden">
                        <input id="tipoAvaliacao-{{ $key }}" type="hidden" value="{{ $tipoAvaliacao->id }}">
                        <button class="btnAvaliacao btn btn-success mt-3" data-id="{{ $key }}">
                            Enviar Avaliação
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style nonce="{{ app('csp-nonce') }}">
        textarea {
            height: 15vh;
            resize: none;
        }
    </style>
@endsection

@push('scripts')
    <script nonce="{{ app('csp-nonce') }}">
        let maxItens = {{ $setor->tiposAvaliacao->count() }};
        let local = null;
        let storeIndex = -1;

        $(document).ready(function() {
            local = JSON.parse(window.localStorage.getItem('avaliacao'));
            verifyStorage();
        });

        $("label").click(function() {
            avaliar($(this).data('nota'), $(this).data('item'));
        });

        $('.btnAvaliacao').click(function() {
            enviarAvaliacao($(this).data('id'));
            $('.btnAvaliacao').addClass('d-none');
        });

        function diferentDates(date1, date2) {
            return date1.getFullYear() != date2.getFullYear() ||
                date1.getMonth() != date2.getMonth() ||
                date1.getDate() != date2.getDate();
        }

        function verifyStorage() {
            if (local) {
                if (diferentDates(new Date(local.date), new Date())) {
                    window.localStorage.removeItem('avaliacao');
                } else {
                    for (let [index, avaliacao] of local.avaliacao.entries()) {
                        if (avaliacao.url == window.location.href) {
                            storeIndex = index;
                            if (avaliacao.itens >= maxItens) {
                                window.location.href = '{{ route('agradecimento-avaliacao') }}';
                            } else {
                                for (i = 0; i < avaliacao.itens; i++) {
                                    $("#check-" + i).removeClass('d-none');
                                    $("#body-" + i).addClass('d-none');
                                    $("#title-" + (i + 1)).removeClass('d-none');
                                    $("#body-" + (i + 1)).removeClass('d-none');
                                }
                            }
                            break;
                        }
                    }
                }
            }
        }

        function avaliar(nota, nPergunta) {
            $('.avaliar-' + nPergunta + ' label').each(function(index, element) {
                $(element).addClass('opacity-50');
            });
            $("#comentario-" + nPergunta).removeClass('d-none');
            $(".btnAvaliacao").removeClass('d-none');

            switch (nota) {
                case 1:
                    $("#label-muito-triste-" + nPergunta).removeClass('opacity-50');
                    $('#avaliacao-text-' + nPergunta).html('<span class="text-danger">Muito Ruim</span>');
                    $('#avaliacao-' + nPergunta).val(2);
                    break;
                case 2:
                    $("#label-triste-" + nPergunta).removeClass('opacity-50');
                    $('#avaliacao-text-' + nPergunta).html('<span class="text-warning">Ruim</span>');
                    $('#avaliacao-' + nPergunta).val(4);
                    break;
                case 3:
                    $("#label-neutro-" + nPergunta).removeClass('opacity-50');
                    $('#avaliacao-text-' + nPergunta).html('<span class="text-info">Neutro</span>');
                    $('#avaliacao-' + nPergunta).val(6);
                    break;
                case 4:
                    $("#label-feliz-" + nPergunta).removeClass('opacity-50');
                    $('#avaliacao-text-' + nPergunta).html('<span class="text-primary">Bom</span>');
                    $('#avaliacao-' + nPergunta).val(8);
                    break;
                case 5:
                    $("#label-muito-feliz-" + nPergunta).removeClass('opacity-50');
                    $('#avaliacao-text-' + nPergunta).html('<span class="text-success">Muito Bom</span>');
                    $('#avaliacao-' + nPergunta).val(10);
                    break;
            }
        }

        function storage(item) {
            if (local) {
                avaliacao = [...local.avaliacao];
                if (storeIndex == -1) {
                    avaliacao.push({
                        url: window.location.href,
                        itens: item + 1,
                    });
                } else {
                    avaliacao[storeIndex].itens = item + 1;
                }
            } else {
                avaliacao = [{
                    url: window.location.href,
                    itens: item + 1,
                }]
            }
            window.localStorage.setItem('avaliacao', JSON.stringify({
                avaliacao: avaliacao,
                date: '{{ now() }}'
            }));
        }

        function enviarAvaliacao(item) {
            $.ajax({
                url: "{{ route('post-store-avaliacao', $setor->token) }}",
                type: "post",
                dataType: 'json',
                data: {
                    'avaliacao': $('#avaliacao-' + item).val(),
                    'comentario': $('#textArea-' + item).val(),
                    'tipo': $('#tipoAvaliacao-' + item).val(),
                    "_token": "{{ csrf_token() }}",
                },
            }).done(function(response) {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Avaliação Enviada!',
                    showConfirmButton: false,
                    timer: 1500,
                }).then(function() {
                    storage(item);
                    $('#check-' + item).removeClass('d-none');
                    if (item + 1 < maxItens) {
                        $("#body-" + item).addClass('d-none');
                        $("#title-" + (item + 1)).removeClass('d-none');
                        $("#body-" + (item + 1)).removeClass('d-none');
                    } else {
                        window.location.href = '{{ route('agradecimento-avaliacao') }}';
                    }
                });
            });
        }
    </script>
@endpush
