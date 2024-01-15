@extends('template/layout_login')
@section('conteudo')
<div class="container-login text-center">
    <div class="wrapper-login">
        <div class="title">
            <span>Login</span>
        </div>
        <form action="{{route('login_submit')}}" class="form-login" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <img class="icon" src="{{ asset('imgs/icon/email.svg')}}" alt="email">
                <input type="text" placeholder="E-mail" name="email" required>
            </div>
            <div class="row">
                <img class="icon" src="{{ asset('imgs/icon/lock.svg')}}" alt="lock">
                <input type="password" placeholder="Senha" name="password" required>
            </div>

            <div class="row button">
                <input type="submit" value="Acessar">
            </div>

            <div class="signup-link">
                <a href="{{route('register')}}">Cadastrar</a> - <a href="#">Esqueceu a senha?</a> - <a href="{{route('politicas')}}">Política de Privacidade</a>
            </div>

        </form>
    </div>
</div>
@endsection