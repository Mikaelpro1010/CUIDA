$(document).ready(function () {
  $("#logoutBtn").click(function (e) {
    e.preventDefault();
    $("#logout-form").submit();
  });
});
