<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('titulo', 'EscutaSol')</title>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" nonce="{{ app('csp-nonce') }}">
    <style nonce="{{ app('csp-nonce') }}">
        @media print {

            .logo-container,
            .img-wrapper,
            img {
                height: 80px !important;
            }
        }
    </style>
</head>

<body class="text-center">
    <div class=" mx-auto">
        <div class="d-flex justify-content-between m-3">
            <div class="d-flex align-items-center">
                <img class="me-2 border border-3 rounded-circle border-primary"
                    src="{{ asset('imgs/adaptive-icon.png') }}" height="80px" alt="Logo EscutaSol">
                <span class="fs-1 text-primary"><b>EscutaSol</b></span>
            </div>
            <img class="col-md-4" src="{{asset('imgs/pms.png')}}" alt="" srcset="">
        </div>
        <h1>{{ $unidade->nome}} - {{$unidade->secretaria->sigla }}</h1>
        <h3>Faça sua Avaliação através do QRcode!</h3>

        <?= $qrcode ?>

        <footer class="d-flex justify-content-center mt-2 mb-3 mx-3">
            <div class="border-bottom text-center">
                EscutaSol - Controladoria e Ouvidoria Geral do Municipio de Sobral - CGM - {{ now()->year }}
            </div>
        </footer>
    </div>

    <script src="{{ asset('js/scripts.js') }}" nonce="{{ app('csp-nonce') }}" data-auto-add-css="false"></script>
    <script nonce="{{ app('csp-nonce') }}">
        $(document).ready(function(){
            print();
        });
    </script>
</body>

</html>