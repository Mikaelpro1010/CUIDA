@extends('template.simple_page')

@section('main')
<div class="container col-md-5 mt-5">
    <div class="card shadow p-3">
        <h3 class="mx-auto my-2">Login</h3>
        <form class="d-grid gap-2" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="email" class=" ">E-mail</label>
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                    name="email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </div>
                @endif
            </div>

            <div class="form-group">
                <label for="password" class="control-label">Senha</label>
                <input id="password" type="password"
                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                @if ($errors->has('password'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </div>
                @endif
            </div>

            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Mantenha-me Conectado
                    </label>
                </div>
            </div>

            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary py-2 px-4">
                    Login
                </button>
                {{-- <a class="btn btn-link" href="{{ route('password.request') }}">
                    Esqueceu a Senha?
                </a> --}}
            </div>
        </form>
    </div>
</div>
@endsection