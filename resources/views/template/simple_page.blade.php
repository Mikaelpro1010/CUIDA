<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('titulo', 'CUIDA')</title>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" nonce="{{ csp_nonce() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" nonce="{{ csp_nonce() }}">
</head>

<body class="dark:bg-black">
        <!-- Inicio Navbar -->
    <nav class="navbar bg-blue-950 dark:bg-black border border-white">
        <div class="navbar-content bg-blue-950 dark:bg-black">

            <div class="bars text-white dark:border border-white p-1 dark:border-2">
                <i class="fa-solid fa-bars"></i>
            </div>
            <img src="{{ asset('imgs/logo/logo.png') }}" nonce="{{ csp_nonce() }}" alt="Celke" class="logo">
        </div>
        
        <div class="navbar-content dark:bg-black">
            <i class="moon cursor-pointer bi bi-moon-fill mx-2 text-white"></i>
            <i class="sun cursor-pointer  bi bi-brightness-high-fill mx-2 text-white"></i>
            <div class="notification">
                <i class="fa-solid fa-bell text-white"></i>
                <span class="number">7</span>
                <div class="dropdown-menu">
                    <div class="dropdown-content">
                        <li>
                            <img src="{{ asset('images/users/user.jpg') }}" nonce="{{ csp_nonce() }}" alt="Usuario" width="40">
                            <div class="text">
                                Fusce ut leo pretium, luctus elit id, vulputate lectus.
                            </div>
                        </li>
                        <li>
                            <img src="{{ asset('images/users/user.jpg') }}" nonce="{{ csp_nonce() }}" alt="Usuario" width="40">
                            <div class="text">
                                Fusce ut leo pretium, luctus elit id, vulputate lectus.
                            </div>
                        </li>
                    </div>
                </div>
            </div>
            
            <div class="avatar">
                <img src="{{ asset('imgs/users/user.jpg') }}" nonce="{{ csp_nonce() }}" alt="Usuario" width="40">
                <div class="dropdown-menu setting">
                    <div class="item">
                        <a href="" class="text-white">
                            <span class="fa-solid fa-user"></span> Perfil
                        </a>
                    </div>
                    <div class="item">
                        <a href="" class="text-white">
                            <span class="fa-solid fa-gear"></span> Configuração
                        </a>
                    </div>
                    <div class="item">
                        <a class="text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <span class="fa-solid fa-arrow-right-from-bracket text-white"></span> Sair
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
        <div class="sidebar bg-blue-950 dark:bg-black border border-white">
            <a href="{{route('home')}}" class="sidebar-nav dark:bg-black text-white"><i class="icon fa-solid fa-house"></i><span>Dashboard</span></a>
            
            <button class="dropdown-btn dark:bg-black text-white">
                <i class="icon fa-solid fa-users"></i><span>Dropdown</span><i class="fa-solid fa-caret-down"></i>
            </button>
            <div class="dropdown-container dark:bg-black text-white">
                <a href="#" class="sidebar-nav dark:bg-black text-white"><i class="icon fa-solid fa-user-check"></i><span>Link 1</span></a>
                <a href="" class="sidebar-nav dark:bg-black text-white"><i class="icon fa-solid fa-user-gear"></i><span>Link 2</span></a>
                <a href="#" class="sidebar-nav dark:bg-black text-white"><i class="icon fa-solid fa-chalkboard-user"></i><span>Link 3</span></a>
            </div>
            
            @can(permissionConstant()::GERENCIAR_ALUNOS_LIST)

            <a href="{{ route('listarAlunos') }}" class="sidebar-nav dark:bg-black text-white"><i class="icon fa-solid fa-eye"></i><span>Gerenciar Alunos</span></a>
            
            @endcan
            
            @can(permissionConstant()::GERENCIAR_PROFESSORES_LIST)
            
            <a href="{{ route('listarProfessores') }}" class="sidebar-nav dark:bg-black text-white"><i class="icon fa-solid fa-eye"></i><span>Gerenciar Professores</span></a>
            
            @endcan
            <!-- @if (session('warning'))
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
                @endif -->
            <!-- Fim da Sidebar -->
        </div>
        @yield('main')
            
    </div>

    <script src="{{ asset('js/scripts.js') }}" nonce="{{ csp_nonce() }}" data-auto-add-css="false"></script>
    @stack('scripts')
</body>

</html>