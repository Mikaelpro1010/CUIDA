function confirmDelete(itemId) {
    document.getElementById('deletar').value = itemId;
    $('#modalDelete').modal('show');
}

document.addEventListener('DOMContentLoaded', function () {
    var deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var itemId = this.getAttribute('data-item-id');
            confirmDelete(itemId);
        });
    });
});