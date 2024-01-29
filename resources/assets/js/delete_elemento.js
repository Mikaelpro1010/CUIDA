function deleteItem(id) {
    $('#deletar').val(id);
    $('#modalDelete').modal('show');
}

function close_modal() {
    $('#modalDelete').modal('hide');
}