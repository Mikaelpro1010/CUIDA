@extends('template.simple_page')

@section('main')
    <!-- Inicio do conteudo do administrativo -->
    <main class="wrapper p-2 py-2 px-4">
        <div class="card bg-white shadow">
            @yield('content')
        </div>
    </main>
@endsection