@extends('template.simple_page')

@section('main')
<div class="container col-md-5 mt-5">
    <div class="card shadow p-3">
        <h3 class="mx-auto p-3">Registre-se</h3>
        <form class="d-grid gap-2" method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="name" class="col-md-4 control-label">Nome</label>
                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                    name="name" value="{{ old('name') }}" required autofocus>
                @if ($errors->has('name'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </div>
                @endif
            </div>
            <div class="form-group">
                <label for="email" class="col-md-4 control-label">E-mail</label>
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                    name="email" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </div>
                @endif
            </div>
            <div class="form-group">
                <label for="password" class="col-md-4 control-label">Senha</label>
                <input id="password" type="password"
                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                @if ($errors->has('password'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </div>
                @endif
            </div>
            <div class="form-group">
                <label for="password-confirm" class="col-md-4 control-label">Confirmar Senha</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
            <div class="form-group mx-auto">
                <button type="submit" class="btn btn-primary px-3 py-2">
                    Registrar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection