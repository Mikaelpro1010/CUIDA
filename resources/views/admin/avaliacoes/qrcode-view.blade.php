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
    <div class="d-flex justify-content-between m-3">
        <div class="d-flex align-items-center">
            <img class="me-2 border border-3 rounded-circle border-primary" src="{{ asset('imgs/adaptive-icon.png') }}"
                height="80px" alt="Logo EscutaSol">
            <span class="fs-1 text-primary"><b>EscutaSol</b></span>
        </div>
        <img class="col-md-4" src="{{ asset('imgs/pms.png') }}" alt="" srcset="">
    </div>

    @if ($setor)
        <h1>{{ $setor->nome }}</h1>
        <br>
        <h4>{{ $unidade->nome }} - {{ $unidade->nome_oficial }}</h4>
        <br>
    @else
        <h1>{{ $unidade->nome }}</h1>
        <h3>{{ $unidade->nome_oficial }}</h3>
        <br>
    @endif
    <h4>Faça sua Avaliação através do QRcode!</h4>

    <?= $qrcode ?>

    <h4>{{ $unidade->secretaria->nome }} - {{ $unidade->secretaria->sigla }}</h4>
    <footer class="d-flex justify-content-center my-3">
        <div class="border-bottom text-center">
            EscutaSol - Controladoria e Ouvidoria Geral do Municipio de Sobral - CGM - {{ now()->year }}
        </div>
    </footer>
</body>
<script src="{{ asset('js/scripts.js') }}" nonce="{{ app('csp-nonce') }}" data-auto-add-css="false"></script>
<script nonce="{{ app('csp-nonce') }}">
    $(document).ready(function() {
        print();
    });
</script>

</html>
