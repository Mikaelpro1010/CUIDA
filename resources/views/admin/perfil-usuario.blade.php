@extends('template.base')

@section('titulo', 'EscutaSol - Perfil de Usuário')
@section('content')
    <div class="d-flex justify-content-between">
        <h2 class="text-primary">Perfil de Usuário</h2>
        <div>
            <a class="btn btn-warning" href="{{ route('pagina-password')}}">
                <i class="fa-solid fa-pen-to-square"></i>
                Editar Senha
            </a>
        </div>
    </div>
    <hr>
    <form id="editForm" method="POST" action="{{ route('update-user') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md">
                <label class="fw-bold" for="">Nome:</label>
                <input class="form-control" type="text" name="name" value="{{ Auth::user()->name }}">
            </div>
            <div class="col-md">
                <label class="fw-bold" for="">Email:</label>
                <input class="form-control" type="email" name="email" value="{{ Auth::user()->email }}">
            </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <button id="btnEditForm" class="btn btn-primary">
                <i class="fa-solid fa-pen-to-square"></i>
                Editar
            </button>
        </div>
    </form>

@endsection

@push('scripts')
<script nonce="{{ app('csp-nonce') }}">
    $("#btnEditForm").click(function() {
        $("#editForm").submit();
    });
</script>
@endpush