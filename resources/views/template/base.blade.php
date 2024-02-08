@extends('template.simple_page')

@section('main')
    <!-- Inicio do conteudo do administrativo -->
    <main class="wrapper dark:bg-black dark:border">
        <div class="card p-1">
            @include('componentes/flash-message')
            <!-- Exibição de mensagens de erro -->
            @if($errors->any())
                @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show p-3">
                    <span>{{ $error }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endforeach
            @endif
            @yield('content')
        </div>
    </main>
@endsection