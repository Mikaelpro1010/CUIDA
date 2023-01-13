@extends('template.base')

@section('titulo', 'EscutaSol - Editar Senha')
@section('content')
    <div class="d-flex justify-content-between">
        <h2 class="text-primary">Alterar Senha</h2>
    </div>
    <hr>
    <form id="editForm" method="POST" action="{{ route('update-password') }}" class="needs-validation" novalidate>
        {{ csrf_field() }}
        <div class="col-md">
            <label for="current_password">Senha atual:</label>
            <input class="form-control" type="password" name="current_password">
        </div>
        <div class="col-md mt-3">
            <label for="password">Nova senha:</label>
            <input class="form-control" type="password" name="password">
        </div>
        <div class="col-md mt-3">
            <label for="confirm_password">Confirmar nova senha:</label>
            <input class="form-control" type="password" name="confirm_password">
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
