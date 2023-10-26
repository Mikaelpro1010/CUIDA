@extends('componentes/layout_login')
@section('header')
<body class="d-flex">
    <div class="container-login">
        <div class="wrapper-login">
            <div class="title">
                <span>Login</span>
            </div>
            <form action="{{route('login_submit')}}" class="form-login" method="POST">
                {{ csrf_field() }}
                <div class="row">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" placeholder="E-mail" name="email" required>
                </div>
                <div class="row">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" placeholder="Senha" name="password" required>
                </div>

                <div class="row button">
                    <input type="submit" value="Acessar">
                </div>

                <div class="signup-link">
                    <a href="{{route('register')}}">Cadastrar</a> - <a href="#">Esqueceu a senha?</a>
                </div>

            </form>
        </div>
    </div>

</body>
@endsection