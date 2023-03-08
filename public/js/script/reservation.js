$('#new-reservation-button').click(function () {
  $('#new-reservation').removeClass('hidden');
  $('#redirect-reservation').css({ display: 'none' });
});

$('#check-reservation-button').click(function () {
  $('#check-reservation').removeClass('hidden');
  $('#redirect-reservation').css({ display: 'none' });
});

$(document).on("click", ".hour", function () {
  let seats = $('#check-seats').val();
  let date = $('#check-date').val();
  let hour = $(this).text();
  $('#new-reservation').css({ display: 'none' });
  $('#complete-reservation').removeClass('hidden');
  $('#form-seats').val(seats);
  $('#form-date').val(date);
  $('#form-hour').val(hour);
});