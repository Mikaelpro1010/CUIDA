<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('titulo', 'CUIDA')</title>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" nonce="{{ csp_nonce() }}">
</head>

<body class="dark:bg-black">
        <!-- Inicio Navbar -->
    <nav class="navbar fourth-bg-color dark:bg-black">
        <div class="navbar-content fourth-bg-color dark:bg-black">
            <div class="bars text-white">
                <i class="fa-solid fa-bars dark:border p-1"></i>
            </div>
            <img src="{{ asset('imgs/logo/logo.png') }}" nonce="{{ csp_nonce() }}" alt="Celke" class="logo">
        </div>
        
        <div class="navbar-content dark:bg-black">
            <img src="{{ asset('imgs/icon/moon.svg')}}" class="moon cursor-pointer mx-2" alt="moon" width="15px">
            <img src="{{ asset('imgs/icon/sun.svg')}}" class="sun cursor-pointer mx-2" alt="sun" witdh="15px">
            <div class="notification">
                <i class="fa-solid fa-bell text-white"></i>
                <span class="number">7</span>
                <div class="dropdown-menu dark:bg-black">
                    <div class="dropdown-content">
                        <li>
                            <img src="{{ asset('imgs/users/user.jpg') }}" nonce="{{ csp_nonce() }}" alt="Usuario" width="40">
                            <div class="text-white">
                                Fusce ut leo pretium, luctus elit id, vulputate lectus.
                            </div>
                        </li>
                        <li>
                            <img src="{{ asset('imgs/users/user.jpg') }}" nonce="{{ csp_nonce() }}" alt="Usuario" width="40">
                            <div class="text-white">
                                Fusce ut leo pretium, luctus elit id, vulputate lectus.
                            </div>
                        </li>
                    </div>
                </div>
            </div>
            
            <div class="avatar">
                <img src="{{ asset('imgs/users/user.jpg') }}" nonce="{{ csp_nonce() }}" alt="Usuario" width="40">
                <div class="dropdown-menu setting dark:bg-black">
                    <div class="item">
                        <a href="{{route('get-user-perfil')}}" class="text-white">
                            <span class="fa-solid fa-user"></span> Perfil
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{route('get-roles-list')}}" class="text-white">
                            <span class="fa-solid fa-gear"></span> Configuração
                        </a>
                    </div>
                    <div class="item">

                        <a class="text-white" href="{{ route('logout') }}" id="logout-link">
                            <span class="fa-solid fa-sign-out"></span> Sair
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Fim Navbar -->

    <div class="content dark:bg-black">

        <!-- Inicio da Sidebar -->
        <div class="sidebar second-bg-color dark:bg-black">

            @auth
            <a href="{{route('home')}}" class="sidebar-nav text-white dark:bg-black"><i class="icon fa-solid fa-house"></i><span>Dashboard</span></a>
            
            
            <button class="dropdown-btn text-white dark:bg-black">
                <i class="icon fa-solid fa-users"></i><span>Dropdown</span><i class="fa-solid fa-caret-down"></i>
            </button>
            <div class="dropdown-container text-white dark:bg-black">
                <a href="#" class="sidebar-nav text-white dark:bg-black"><i class="icon fa-solid fa-user-check"></i><span>Link 1</span></a>
                <a href="" class="sidebar-nav text-white dark:bg-black"><i class="icon fa-solid fa-user-gear"></i><span>Link 2</span></a>
                <a href="#" class="sidebar-nav text-white dark:bg-black"><i class="icon fa-solid fa-chalkboard-user"></i><span>Link 3</span></a>
            </div>

            <button class="dropdown-btn text-white dark:bg-black">
                <i class="icon fa-solid fa-gear"></i><span>Configurações</span><i class="fa-solid fa-caret-down"></i>
            </button>
            <div class="dropdown-container text-white dark:bg-black">
                <a href="{{ route('listarAudEtapasDocumentos') }}" class="sidebar-nav text-white dark:bg-black"><i class="icon fa-solid fa-file-lines"></i><span>Etapas de Documentos</span></a>
                <a href="{{ route('listarAudPrazosDocumentos') }}" class="sidebar-nav text-white dark:bg-black"><i class="icon fa-solid fa-file-lines"></i><span>Prazos de Documentos</span></a>
                <a href="{{ route('listarAudStatusDocumentos') }}" class="sidebar-nav text-white dark:bg-black"><i class="icon fa-solid fa-file-lines"></i><span>Status de Documentos</span></a>
                <a href="{{ route('listarAudTiposDocumentos') }}" class="sidebar-nav text-white dark:bg-black"><i class="icon fa-solid fa-file-lines"></i><span>Tipos de Documentos</span></a>
            </div>
            @endauth
            
            @can(permissionConstant()::GERENCIAR_ALUNOS_LIST)

            <a href="{{ route('listarAlunos') }}" class="sidebar-nav text-white dark:bg-black"><i class="icon fa-solid fa-eye"></i><span>Gerenciar Alunos</span></a>
            
            @endcan
            
            @can(permissionConstant()::GERENCIAR_PROFESSORES_LIST)
            
            <a href="{{ route('listarProfessores') }}" class="sidebar-nav text-white dark:bg-black"><i class="icon fa-solid fa-eye"></i><span>Gerenciar Professores</span></a>
            
            @endcan
            <!-- Fim da Sidebar -->
        </div>
        @yield('main')
            
    </div>

    <script src="{{ asset('js/scripts.js') }}" nonce="{{ csp_nonce() }}" data-auto-add-css="false"></script>
    @stack('scripts')
</body>

</html>