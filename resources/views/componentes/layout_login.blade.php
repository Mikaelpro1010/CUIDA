<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/icon/favicon.ico">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" nonce="{{ csp_nonce() }}">
    <title>Document</title>
    @yield('header')
</head>
<body>
    <!-- Inicio Navbar -->
    <nav class="navbar bg-blue-950 dark:bg-black">
        <div class="navbar-content bg-blue-950 dark:bg-black">
            <img src="{{ asset('imgs/logo/logo.png') }}" nonce="{{ csp_nonce() }}" alt="Celke" class="logo">
        </div>
        
        <div class="navbar-content dark:bg-black">
            <a href="{{route('login')}}" class="text-white">Login</a>
        </div>
    </nav>
    <!-- Fim Navbar -->
    @yield('conteudo')
    <script src="{{ asset('js/scripts.js') }}" nonce="{{ csp_nonce() }}" data-auto-add-css="false"></script>
</body>
</html>