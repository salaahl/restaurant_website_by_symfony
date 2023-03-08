$('#new-reservation-button').click(function () {
  $('#new-reservation').removeClass('hidden');
  $('#redirect-reservation').css({ display: 'none' });
});

$('#check-reservation-button').click(function () {
  $('#check-reservation').removeClass('hidden');
  $('#redirect-reservation').css({ display: 'none' });
});

$(document).on("click", ".hour", function(){
  let date = $('#check-date').val();
  let hour = $(this).text();
  $('#new-reservation').css({ display: 'none' });
  $('#complete-reservation').removeClass('hidden');
  $('#form-date').val(date);
  $('#form-hour').val(hour);
});