<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'EscutaSol')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/fontawesome.js') }}"></script>
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
                            <a class="nav-link text-white @if (Route::is('politicas')) border-bottom border-3 border-info @endif"
                                href="{{ route('politicas') }}">
                                Políticas de Privacidade
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white @if (Route::is('termos')) border-bottom border-3 border-info @endif"
                                href="{{ route('termos') }}">
                                Termos de Uso
                            </a>
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
                                {{ auth()->user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inicio') }}">Inicio</a>
                                    @can(permissionConstant()::MANIFESTACAO_LIST)
                                    <a class="dropdown-item" href="{{ route('inicio') }}">Manifestações</a>
                                    @endcan
                                    @can(permissionConstant()::MANIFESTACAO_CHAT)
                                    <a class="dropdown-item" href="{{ route('mensagens') }}">Mensagens</a>
                                    @endcan
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
                        <a class="nav-link @if (Route::is('inicio')) border-bottom border-3 border-primary @endif"
                            href="{{ route('inicio') }}">
                            Inicio
                        </a>
                        @can(permissionConstant()::MANIFESTACAO_LIST)
                        <a class="nav-link @if (Route::is('manifestacoes')) border-bottom border-3 border-primary @endif"
                            href="{{ route('manifestacoes') }}">
                            Manifestações
                            <span class="badge rounded-pill bg-danger">
                                {{ manifestacoesNaoEncerradasNotification() }}
                            </span>
                        </a>
                        @endcan
                        @can(permissionConstant()::MANIFESTACAO_CHAT)
                        <a class="nav-link @if (Route::is('mensagens')) border-bottom border-3 border-primary @endif"
                            href="{{ route('mensagens') }}">
                            Mensagens
                            <span class="badge rounded-pill bg-danger">
                                {{ canaisAguardandoRespostaNotification() }}
                            </span>
                        </a>
                        @endcan
                        <li
                            class="nav-item dropdown @if (Route::current()->action['namespace'] == 'App\Http\Controllers\Avaliacoes') border-bottom border-3 border-primary @endif">
                            <a class=" nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Avaliações
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="
                                    @if (auth()->user()->can(permissionConstant()::RELATORIO_AVALIACOES_GERAL_VIEW)) {{ route('resumo-avaliacoes') }}
                                    @else
                                    {{ route('resumo-avaliacoes-secretaria-list') }} @endif
                                    ">Resumos</a>
                                </li>
                                @can(permissionConstant()::UNIDADE_SECRETARIA_LIST)
                                <li><a class="dropdown-item" href="{{ route('unidades-secr-list') }}">Unidades</a></li>
                                @endcan
                            </ul>
                        </li>
                        @if (auth()->user()->can(permissionConstant()::GERENCIAR_USUARIOS_LIST) ||
                        auth()->user()->can(permissionConstant()::GERENCIAR_SECRETARIAS_LIST))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Gerenciar
                            </a>
                            <ul class="dropdown-menu">
                                @can(permissionConstant()::GERENCIAR_USUARIOS_LIST)
                                <li><a class="dropdown-item" href="{{ route('get-users-list') }}">Usuários</a>
                                </li>
                                @endcan
                                @can(permissionConstant()::GERENCIAR_SECRETARIAS_LIST)
                                <li><a class="dropdown-item" href="{{ route('get-secretarias-list') }}">Secretarias</a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif
                        @if (auth()->user()->can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_LIST))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Configurações
                            </a>
                            <ul class="dropdown-menu">
                                @can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_LIST)
                                <li>
                                    <a class="dropdown-item" href="{{ route('get-roles-list') }}">
                                        Tipos de Usuários
                                    </a>
                                </li>
                                @endcan
                                @can(permissionConstant()::GERENCIAR_TIPOS_MANIFESTACAO_LIST)
                                <li>
                                    <a class="dropdown-item" href="{{ route('get-tipo-manifestacao-list') }}">
                                        Tipos de Manifestacao
                                    </a>
                                </li>
                                @endcan
                                @can(permissionConstant()::GERENCIAR_ESTADOS_PROCESSO_LIST)
                                <li>
                                    <a class="dropdown-item" href="{{ route('get-estado-processo-list') }}">
                                        Estados do Processo
                                    </a>
                                </li>
                                @endcan
                                @can(permissionConstant()::GERENCIAR_MOTIVACOES_LIST)
                                <li>
                                    <a class="dropdown-item" href="{{ route('get-motivacao-list') }}">
                                        Motivações
                                    </a>
                                </li>
                                @endcan
                                
                                @can(permissionConstant()::GERENCIAR_SITUACOES_LIST)
                                <li>
                                    <a class="dropdown-item" href="{{ route('get-situacao-list') }}">
                                        Situações
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif
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
        @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @yield('main')
    </div>

    <footer class="d-flex justify-content-center mt-2 mb-3 mx-3">
        <div class="border-bottom text-center">
            EscutaSol - Controladoria e Ouvidoria Geral do Municipio de Sobral - CGM - 2022
        </div>
    </footer>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#liveToast").toast("show");
        });
    </script>
    @stack('scripts')
</body>

</html>