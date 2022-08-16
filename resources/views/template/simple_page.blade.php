<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'EscutaSol')</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script src="{{ asset('js/fontawesome.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
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
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('politicas') }}">Políticas de Privacidade</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('termos') }}">Termos de Uso</a>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right text-white gap-2">
                        <!-- Authentication Links -->
                        @guest
                        <li><a class="btn btn-outline-light" href="{{ route('login') }}">Login</a></li>
                        <li><a class="btn btn-outline-light" href="{{ route('register') }}">Register</a></li>
                        @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle text-white" data-bs-toggle="dropdown" role="button"
                                aria-expanded="false" aria-haspopup="true" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inicio') }}">inicio</a>
                                    <a class="dropdown-item" href="{{ route('inicio') }}">Manifestações</a>
                                    <a class="dropdown-item" href="{{ route('mensagens') }}">Mensagens</a>
                                    <hr>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>

        </nav>
        @auth
        <div class="navbar navbar-expand-lg bg-warning py-0">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon "></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link @if(routeClass()::currentRouteName() == 'inicio') border-bottom border-3 border-primary @endif"
                            href="{{ route('inicio') }}">
                            Inicio
                        </a>
                        <a class="nav-link @if(routeClass()::currentRouteName() == 'manifestacoes') border-bottom border-3 border-primary @endif"
                            href="{{ route('manifestacoes') }}">
                            Manifestações
                            <span class="badge rounded-pill bg-danger">
                                {{ manifestacoesNaoEncerradasNotification() }}
                            </span>
                        </a>
                        <a class="nav-link @if(routeClass()::currentRouteName() == 'mensagens') border-bottom border-3 border-primary @endif"
                            href="{{ route('mensagens') }}">
                            Mensagens
                            <span class="badge rounded-pill bg-danger">
                                {{ canaisAguardandoRespostaNotification() }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            @endauth
    </header>

    <div class="container mt-3">
        @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <li>{{ session('warning') }}</li>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <li>{{ session('success') }}</li>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <li>{{ $error }}</li>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endforeach
        @endif
        @yield('main')
    </div>

    <footer class="d-flex justify-content-center mt-2 mb-3 mx-3">
        <div class="border-bottom text-center">EscutaSol - Controladoria e Ouvidoria Geral do Municipio de
            Sobral - CGM - 2022</div>
    </footer>
    <script src="{{ asset('js/jquery-3.6.0.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    @yield('scripts')
    <script>
        $(document).ready(function(){
            $("#liveToast").toast("show");
    });
    </script>
</body>

</html>