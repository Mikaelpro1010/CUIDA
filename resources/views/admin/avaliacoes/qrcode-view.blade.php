<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<div class="container">

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
                    height: 60px !important;
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

    <body class="text-center">
        <img class="h-100 w-100 position-relative" src="{{ asset('imgs/Escutasol_2.png') }}">

        <div class="mt-5 position-absolute top-0">
            <div class="d-flex justify-content-between m-3">
                <div class="d-flex align-items-center col row mt-2">
                    <h1>Avaliação geral</h1>
                    <h2>Unidade de Apoio Barragem</h2>
                </div>
            </div>

            <div class="my-5 row align-items-center col-12 mb-5">
                <div class="mx-4 col-3">
                    <img class="col-md-4" src="{{ asset('imgs/pms.png') }}" alt="" srcset="">
                </div>
                <div class="mx-5 col-6 mt-4">
                    <div class="mb-3">
                        <img class="me-2 border border-3 rounded-circle border-primary"
                            src="{{ asset('imgs/adaptive-icon.png') }}" height="20px" alt="Logo EscutaSol">
                        <span class="fs-1 text-primary"><b>EscutaSol</b></span>
                    </div>
                    {{-- @if ($setor)
                        <h1>{{ $setor->nome }}</h1>
                        
                        <h4>{{ $unidade->nome }} - {{ $unidade->nome_oficial }}</h4>
                       
                    @else
                        <h1>{{ $unidade->nome }}</h1>
                        <h3>{{ $unidade->nome_oficial }}</h3>
                    
                    @endif --}}
                    <div class="mb-4">
                        <h4>Faça sua Avaliação através do QRcode!</h4>
                    </div>
                    <div class="pe-1 pb-1">
                        <?= $qrcode ?>
                    </div>
                </div>
            </div>
        </div>
        <footer class="d-flex justify-content-center row bottom-3 ">
            <div class="">
                <h4>{{ $unidade->secretaria->nome }} - {{ $unidade->secretaria->sigla }}</h4>
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
</div>
