@extends('template.simple_page')

@section('main')
    <!-- Inicio do conteudo do administrativo -->
    <main class="wrapper p-2 py-2 px-2 dark:bg-black">
        <div class="card dark:bg-gray-700">
            @yield('content')
        </div>
    </main>
@endsection