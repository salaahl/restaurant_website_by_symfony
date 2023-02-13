let cReservationDisplay = $('#check-reservation').css('display');
let nReservationDisplay = $('#new-reservation').css('display');
let completeReservationDisplay = $('#new-reservation').css('display');

$('#check-reservation').css({ display: 'none' });
$('#new-reservation').css({ display: 'none' });
$('#complete-reservation').css({ display: 'none' });

$('#new-reservation-button').click(function () {
  $('#new-reservation').css({ display: nReservationDisplay });
  $('#redirect-reservation').css({ display: 'none' });
});

$('#check-reservation-button').click(function () {
  $('#check-reservation').css({ display: cReservationDisplay });
  $('#redirect-reservation').css({ display: 'none' });
});

$(document).on('click', '.hour', function () {
  let seats = $('#check-seats').val(),
    date = $('#check-date').val(),
    hour = $(this).text();

  $('#new-reservation').css({ display: 'none' });
  $('#complete-reservation').css({ display: completeReservationDisplay });

  $('#form-seats').val(seats);
  $('#form-date').val(date);
  $('#form-hour').val(hour);
});
