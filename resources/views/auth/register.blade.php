@extends('template/layout_login')
@section('conteudo')
<div class="container-login">
    <div class="wrapper-login">
        <div class="title">
            <span>Registro</span>
        </div>
        <form action="{{ route('register_submit') }}" class="form-login" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <img class="icon" src="{{ asset('imgs/icon/user.svg')}}" alt="user">
                <input type="text" id="name" placeholder="Name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="row">
                <img class="icon" src="{{ asset('imgs/icon/email.svg')}}" alt="email">
                <input type="email" id="email" placeholder="E-mail" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="row">
                <img class="icon" src="{{ asset('imgs/icon/lock.svg')}}" alt="lock">
                <input type="password" id="password" placeholder="Senha" name="password" value="{{ old('password') }}" required>
            </div>

            <!-- <div class="row">
                <i class="fa-solid fa-lock"></i>
                <input type="password" placeholder="Confirmar Senha" name="confirm_password" required>
            </div> -->

            <div class="row button">
                <input type="submit" value="Registrar">
            </div>
        </form>
    </div>
</div>
@endsection