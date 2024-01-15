<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>@yield('titulo', 'CUIDA')</title>

        <link rel="stylesheet" href="{{ asset('css/styles.css') }}" nonce="{{ csp_nonce() }}">
        @yield('header')
    </head>
    <body>
        <!-- Inicio Navbar -->
        <nav class="navbar bg-second-color">
            <div class="navbar-content bg-second-color">
                <img src="{{ asset('imgs/logo/logo.png') }}" nonce="{{ csp_nonce() }}" alt="Celke" class="logo">
            </div>
            
            <div class="navbar-content">
                <a href="{{route('login')}}" class="text-white">Login</a>
            </div>
        </nav>
        <!-- Fim Navbar -->
        <div class="d-flex">
            @yield('conteudo')
        </div>
        <script src="{{ asset('js/scripts.js') }}" nonce="{{ csp_nonce() }}" data-auto-add-css="false"></script>
    </body>
</html>