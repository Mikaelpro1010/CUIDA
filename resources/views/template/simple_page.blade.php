<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('titulo', 'EscutaSol')</title>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" nonce="{{ csp_nonce()}}">
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
                            <a class="nav-link text-white @if (Route::is('pagina-inicial')) border-bottom border-3 border-light @endif"
                                href="{{ route('pagina-inicial') }}">
                                Pagina Inicial
                            </a>
                        </li>
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
                        @guest
                        <li><a class="btn btn-outline-light" href="{{ route('login') }}">Login</a></li>
                        @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle text-white" data-bs-toggle="dropdown" role="button"
                                aria-expanded="false" aria-haspopup="true" v-pre>
                                {{ auth()->user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inicio') }}">Inicio</a>
                                    <a class="dropdown-item" href="{{ route('get-user-perfil') }}">Perfil de Usuário</a>
                                    {{-- @can(permissionConstant()::MANIFESTACAO_LIST)
                                    <a class="dropdown-item" href="{{ route('inicio') }}">Manifestações</a>
                                    @endcan
                                    @can(permissionConstant()::MANIFESTACAO_CHAT)
                                    <a class="dropdown-item" href="{{ route('mensagens') }}">Mensagens</a>
                                    @endcan --}}

                                    @if (auth()->user()->can(permissionConstant()::RELATORIO_AVALIACOES_GERAL_VIEW) ||
                                    auth()->user()->can(permissionConstant()::UNIDADE_SECRETARIA_LIST) )
                                    <a class="dropdown-item" href="
                                        @can(permissionConstant()::RELATORIO_AVALIACOES_GERAL_VIEW)
                                            {{ route('resumo-avaliacoes') }}
                                        @else
                                            {{ route('resumo-avaliacoes-secretaria-list') }} 
                                        @endcan
                                        ">
                                        Resumos das Avaliações
                                    </a>
                                    <hr>
                                    @endif
                                    <a id="logoutBtn" class="dropdown-item" href="{{ route('logout') }}">
                                        Sair
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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
                        <a class="nav-link @if (Route::is('get-list-manifestacoes')) border-bottom border-3 border-primary @endif"
                            href="{{ route('get-list-manifestacoes') }}">
                            Manifestações
                            <span class="badge rounded-pill bg-danger">
                                {{ manifestacoesNaoEncerradasNotification() }}
                            </span>
                        </a>
                        <a class="nav-link @if (Route::is('get-list-manifestacoes2')) border-bottom border-3 border-primary @endif"
                            href="{{ route('get-list-manifestacoes2') }}">
                            Manifestações 2
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

                        @if (auth()->user()->can(permissionConstant()::RELATORIO_AVALIACOES_GERAL_VIEW) ||
                        auth()->user()->can(permissionConstant()::UNIDADE_SECRETARIA_LIST) )
                        <li
                            class="nav-item dropdown @if (Route::current()->action['namespace'] == 'App\Http\Controllers\Avaliacoes') border-bottom border-3 border-primary @endif">
                            <a class=" nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Avaliações
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="
                                    @if (auth()->user()->can(permissionConstant()::RELATORIO_AVALIACOES_GERAL_VIEW)) 
                                    {{ route('resumo-avaliacoes') }}
                                    @else
                                    {{ route('resumo-avaliacoes-secretaria-list') }} 
                                    @endif
                                    ">Resumos</a>
                                </li>
                                @can(permissionConstant()::UNIDADE_SECRETARIA_LIST)
                                <li>
                                    <a class="dropdown-item" href="{{ route('unidades-secr-list') }}">
                                        Unidades da Secretaria
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif

                        @if(auth()->user()->can(permissionConstant()::GERENCIAR_USUARIOS_LIST) ||
                        auth()->user()->can(permissionConstant()::GERENCIAR_SECRETARIAS_LIST))
                        <li
                            class="nav-item dropdown @if (Route::current()->action['namespace'] == 'App\Http\Controllers\Gerenciar') border-bottom border-3 border-primary @endif">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Gerenciar
                            </a>
                            <ul class="dropdown-menu">
                                @can(permissionConstant()::GERENCIAR_USUARIOS_LIST)
                                <li>
                                    <a class="dropdown-item" href="{{ route('get-users-list') }}">Usuários</a>
                                </li>
                                @endcan

                                @can(permissionConstant()::GERENCIAR_SECRETARIAS_LIST)
                                <li>
                                    <a class="dropdown-item" href="{{ route('get-secretarias-list') }}">Secretarias</a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif

                        @if(auth()->user()->can(permissionConstant()::GERENCIAR_TIPOS_USUARIOS_LIST))
                        <li
                            class="nav-item dropdown @if (Route::current()->action['namespace'] == 'App\Http\Controllers\Configs') border-bottom border-3 border-primary @endif">
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

                                @can(permissionConstant()::GERENCIAR_FAQS_LIST)
                                <li>
                                    <a class="dropdown-item" href="{{ route('get-faq-list') }}">
                                        FAQs
                                    </a>
                                </li>
                                @endcan

                                @can(permissionConstant()::GERENCIAR_FAQS_LIST)
                                <li>
                                    <a class="dropdown-item" href="{{ route('get-tipo-avaliacao-list') }}">
                                        Tipos de Avaliação
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif
                    </div>
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
    <script src="{{ asset('js/scripts.js') }}" nonce="{{ app('csp-nonce') }}" data-auto-add-css="false">
    </script>
    @stack('scripts')
</body>

</html>