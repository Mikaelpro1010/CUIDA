@extends('template.simple_page')

@section('main')
    <!-- Inicio do conteudo do administrativo -->
    <main class="wrapper dark:bg-black dark:border">
        <div class="card p-3">
            <!-- Exibição de mensagens de erro -->
            @if($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
            @endif
            @yield('content')
        </div>
    </main>
@endsection