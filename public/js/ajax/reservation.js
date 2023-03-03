$('#check_reservation-form').on('submit', function (e) {
  e.preventDefault();

  $('#check-reservation').css('filter', 'blur(10px)');
  $('.lds-hourglass').css('display', 'flex');

  $.ajax({
    type: 'post',
    data: $(this).serialize(),
    success: function (data) {
      $('#check-reservation').css('filter', 'blur(0px)');
      $('.lds-hourglass').css('display', 'none');

      if (data != '') {
        if ((data.hour.length === 1)) {
          $('.response').append(
            '<div class="check-reservation-response">' +
              data.name +
              ' ' +
              data.surname +
              ',<br>' +
              'Une réservation à votre nom est bien enregistrée pour le ' +
              data.date +
              ' à ' +
              data.hour +
              '<br>' +
              'Cordialement,<br>' +
              "L'équipe du Vingtième" +
              '</div>'
          );
        } else {
          $('.response').append(
            '<div class="check-reservation-response">' +
              data.name +
              ' ' +
              data.surname +
              ',<br>' +
              'Voici pour vos réservations :' +
              '<br>'
          );
          for (let index = 0; index < data.hour.length; index++) {
            $('.check-reservation-response').append(
              data.date[index] + ' à ' + data.hour[index] + ',<br>'
            );
          }
          $('.check-reservation-response').append(
            'Cordialement,<br>' + "L'équipe du Vingtième" + '</div>'
          );
        }
      } else {
        $('.response').append(
          '<div class="check-reservation-response">Aucune réservation à ce nom.</div>'
        );
      }
    },
    error: function () {
      alert('Erreur. Veuillez contacter un administrateur.')
    },
  });
});

$('#new_reservation-form').on('submit', function (e) {
  e.preventDefault();

  $('#new-reservation').css('filter', 'blur(10px)');
  $('.lds-hourglass').css('display', 'flex');

  $.ajax({
    type: 'post',
    data: $(this).serialize(),
    success: function (data) {
      $('#new-reservation').css('filter', 'blur(0px)');
      $('.lds-hourglass').css('display', 'none');

      if (data != '') {
        for (let index = 0; index < data.length; index++) {
          $('.response').append(
            '<button class="hour button-58">' + data[index].hour + '</button>'
          );
        }
      } else {
        $('.response').append(
          '<div>Aucune disponibilité sur cette date. Veuillez réessayer avec un autre jour.</div>'
        );
      }
    },
    error: function (xhr) {
      console.log(xhr.responseText)
      alert('Erreur. Veuillez contacter un administrateur.');
    },
  });
});
