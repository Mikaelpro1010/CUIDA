<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('titulo', 'EscutaSol')</title>
    
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" nonce="{{ csp_nonce() }}">
</head>

<body class="bg-light">
    <header class="sticky-top">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow py-1">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <div class="d-flex">
                        <img class="me-2" src="{{ asset('imgs/adaptive-icon.png') }}" height="50px"
                            alt="Logo EscutaSol">
                        <span class="fs-3 align-self-center"><b>EscutaSol</b></span>
                    </div>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto pb-2 pb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link text-white @if (Route::is('politicas')) border-bottom border-3 border-light @endif"
                                href="{{ route('politicas') }}">
                                Políticas de Privacidade
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white @if (Route::is('termos')) border-bottom border-3 border-light @endif"
                                href="{{ route('termos') }}">
                                Termos de Uso
                            </a>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right text-white gap-2">
                        <li><a class="btn btn-outline-light" href="{{ route('login') }}">Login</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container mt-3">
        @yield('main')
    </div>

    <footer class="d-flex justify-content-center mt-2 mb-3 mx-3">
        <div class="border-bottom text-center">
            EscutaSol - Controladoria e Ouvidoria Geral do Municipio de Sobral - CGM - {{ now()->format('Y') }}
        </div>
    </footer>
    <script src="{{ asset('js/scripts.js') }}" nonce="{{ app('csp-nonce') }}" data-auto-add-css="false"></script>
    @stack('scripts')
</body>

</html>
