@extends('template.simple_page')

@section('main')
    <main class="container mt-3">
        <div class="px-3 py-4 card bg-white shadow">
            @yield('content')
        </div>
    </main>
@endsection