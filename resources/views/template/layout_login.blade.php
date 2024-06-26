<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>@yield('titulo', 'CUIDA')</title>

        <link rel="stylesheet" href="{{ asset('css/styles.css') }}" nonce="{{ csp_nonce() }}">
        @yield('header')
    </head>
    <body class="dark:bg-black">
        <!-- Inicio Navbar -->
        <nav class="navbar second-bg-color dark:bg-black dark:border">
            <div class="navbar-content second-bg-color mx-3 dark:bg-black">
                <img src="{{ asset('imgs/logo/logo.png') }}" nonce="{{ csp_nonce() }}" alt="Celke" class="logo">
            </div>
            
            <div class="navbar-content mx-3">
                <img src="{{ asset('imgs/icon/moon.svg')}}" class="moon cursor-pointer mx-2" alt="moon" width="15px">
                <img src="{{ asset('imgs/icon/sun.svg')}}" class="sun cursor-pointer mx-2" alt="sun" witdh="15px">
                <a href="{{route('login')}}" class="nav-link text-white mx-2">Login</a>
                <a href="{{route('register')}}" class="nav-link bg-gray-900 text-white block rounded-md px-3 py-2 text-base font-medium">Cadastrar</a>
            </div>
        </nav>
        <!-- Fim Navbar -->
        <div class="d-flex">
            @yield('conteudo')
        </div>
        <script src="{{ asset('js/scripts.js') }}" nonce="{{ csp_nonce() }}" data-auto-add-css="false"></script>
    </body>
</html>