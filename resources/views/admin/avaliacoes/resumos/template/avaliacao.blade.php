@extends('template.simple_page')

@section('titulo', 'Avaliações')

@section('main')
<main class="container mt-3">

    <ul class="nav nav-tabs">
        @can(permissionConstant()::RELATORIO_AVALIACOES_GERAL_VIEW)
        <li class="nav-item">
            <a class="nav-link @if(Route::is('get-avaliacao-geral')) active @endif"
                href=" {{ route('get-avaliacao-geral') }}">
                Geral
            </a>
        </li>
        @endcan
        @can(permissionConstant()::RELATORIO_AVALIACOES_SECRETARIA_VIEW)
        <li class="nav-item">
            <a class="nav-link @if(Route::is('get-list-resumo-avaliacoes-secretaria') || Route::is('get-resumo-avaliacoes-secretaria')) active @endif"
                href="{{ route('get-list-resumo-avaliacoes-secretaria') }}">
                Avaliações por Secretaria
            </a>
        </li>
        @endcan
        @can(permissionConstant()::RELATORIO_AVALIACOES_UNIDADE_VIEW)
        <li class="nav-item">
            <a class="nav-link @if(Route::is('get-list-resumo-avaliacoes-unidade')|| Route::is('get-resumo-avaliacoes-unidade')) active @endif"
                href="{{ route('get-list-resumo-avaliacoes-unidade') }}">
                Avaliações por Unidade
            </a>
        </li>
        @endcan
    </ul>
    <div class="px-3 py-4  bg-white shadow border border-top-0 border-1 rounded-bottom-2">
        @yield('content')

    </div>
</main>

@endsection

@push('scripts')
@stack('scripts_resumo')
@endpush