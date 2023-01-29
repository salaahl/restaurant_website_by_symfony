$("#new-reservation-button").focus(function() {
  $("#new-reservation").css({ display: "block" });
  $("#redirect-reservation").css({ display: "none" });
});

$("#check-reservation-button").focus(function() {
  $("#check-reservation").css({ display: "block" });
  $("#redirect-reservation").css({ display: "none" });
});

$('.hour').click(function() {
  let date = $('#check-date').val();
  let hour = $(this).text();
  $("#new-reservation").css({ display: "none" });
  $("#complete-reservation").css({ display: "block" });
  $('#form-date').val(date);
  $('#form-hour').val(hour);
});