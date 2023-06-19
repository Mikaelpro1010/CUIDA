<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $unidade->nome }}</title>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" nonce="{{ app('csp-nonce') }}">
    <style nonce="{{ app('csp-nonce') }}">
        @media print {
            .title {
                width: 600px;
            }

            .escutasol {
                position: absolute;
                top: 250px;
                left: 340px
            }

            .qr-code {
                position: absolute;
                top: 405px;
                left: 294px
            }

            .pms-logo {
                position: absolute;
                height: 75px;
                width: 265px;
                top: 480px;
                left: 0px
            }

            footer {
                width: 700px;
            }

            @page {
                size: A4;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>
    @foreach ($setorQrcode as $key => $atual)
        <div class="position-relative">
            <img class="bg-img h-100 w-100 position-relative" src="{{ asset('imgs/bg_low.png') }}">

            <div class="position-absolute top-0 start-50 translate-middle-x mt-5">
                <div class="text-center title">
                    <h1 class="fs-2">{{ $atual['setor']->nome }}</h1>
                    <h2 class="fs-4">
                        {{ $atual['setor']->unidade->nome }}{{ $atual['setor']->unidade->nome_oficial ? ' - ' . $atual['setor']->unidade->nome_oficial : '' }}
                    </h2>
                </div>
            </div>

            <div class="position-absolute top-0 left-0 h-100">
                <img class="pms-logo" src="{{ asset('imgs/pms.png') }}" alt="logo Prefeitura Municipal de Sobral">
                <div class="escutasol text-center">
                    <div class="d-flex justify-content-center">
                        <img class="me-2 border border-3 rounded-circle border-primary"
                            src="{{ asset('imgs/adaptive-icon.png') }}" height="60px" alt="Logo EscutaSol">
                        <b class="fs-1 text-primary align-self-center">EscutaSol</b>
                    </div>
                    <div class="mt-2 text-white">
                        <h4>Faça sua Avaliação através do QRcode!</h4>
                    </div>
                </div>
                <div class="qr-code">
                    <?= $atual['qrcode'] ?>
                </div>
            </div>

            <footer class="position-absolute bottom-0 start-50 translate-middle-x text-center">
                <span class="fs-2">
                    Aponte a câmera do seu celular e envie sua Avaliação!
                </span>
                <h4 class="fs-3">
                    {{ $atual['setor']->unidade->secretaria->nome }} -
                    {{ $atual['setor']->unidade->secretaria->sigla }}
                </h4>
                <span>
                    EscutaSol - Controladoria e Ouvidoria Geral do Municipio de Sobral - CGM - {{ now()->year }}
                </span>
            </footer>
        </div>
    @endforeach

</body>
<script src="{{ asset('js/scripts.js') }}" nonce="{{ app('csp-nonce') }}" data-auto-add-css="false"></script>
<script nonce="{{ app('csp-nonce') }}">
    $(document).ready(function() {
        print();
    });
</script>

</html>
