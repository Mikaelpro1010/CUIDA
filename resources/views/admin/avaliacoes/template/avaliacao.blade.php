@extends('template.simple_page')

@section('titulo', 'Avaliações')

@section('main')
<main class="container mt-3">

    <ul class="nav nav-tabs">
        @can(permissionConstant()::RELATORIO_AVALIACOES_GERAL_VIEW)
        <li class="nav-item">
            <a class="nav-link @if(Route::is('resumo-avaliacoes')) active @endif"
                href=" {{ route('resumo-avaliacoes') }}">
                Geral
            </a>
        </li>
        @endcan
        <li class="nav-item">
            <a class="nav-link @if(Route::is('resumo-avaliacoes-secretaria-list') || Route::is('resumo-avaliacoes-secretaria')) active @endif"
                href="{{ route('resumo-avaliacoes-secretaria-list') }}">
                Avaliações por Secretaria
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if(Route::is('resumo-avaliacoes-unidade-list')|| Route::is('resumo-avaliacoes-unidade')) active @endif"
                href="{{ route('resumo-avaliacoes-unidade-list') }}">
                Avaliações por Unidade
            </a>
        </li>
    </ul>
    <div class="px-3 py-4  bg-white shadow border border-top-0 border-1 rounded-bottom-2">
        @yield('content')

    </div>
</main>

@endsection

@push('scripts')
<script src="{{ asset('js/chart.js') }}"></script>
@stack('scripts_resumo')
@endpush