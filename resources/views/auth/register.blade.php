@extends('template/layout_login')
@section('conteudo')
<body>
    <div class="d-flex">
        <div class="container-login">
            <div class="wrapper-login">
                <div class="title">
                    <span>Registro</span>
                </div>
                <form action="{{ route('register_submit') }}" class="form-login" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="name" placeholder="Name" name="name" value="{{ old('name') }}" required>
                    </div>
    
                    <div class="row">
                        <i class="fa-solid fa-user"></i>
                        <input type="email" id="email" placeholder="E-mail" name="email" value="{{ old('email') }}" required>
                    </div>
    
                    <div class="row">
                        <i class="fa-solid fa-lock"></i>
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
    </div>
</body>
@endsection