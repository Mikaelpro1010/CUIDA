@extends('template.simple_page')

@section('main')
    <!-- Inicio do conteudo do administrativo -->
    <main class="wrapper dark:bg-black dark:border">
        <div class="card">
            @yield('content')
        </div>
    </main>
@endsection