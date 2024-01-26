@extends('template.simple_page')

@section('main')
    <!-- Inicio do conteudo do administrativo -->
    <main class="wrapper dark:bg-black dark:border">
        <div class="card">
            <!-- Exibição de mensagens de erro -->
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @yield('content')
        </div>
    </main>
@endsection