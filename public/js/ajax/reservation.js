$('#check_reservation-form').on('submit', function (e) {
  e.preventDefault();
  $.ajax({
    type: 'post',
    data: $(this).serialize(),
    success: function (data) {
      if (data != '') {
        console.log(data);
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
          '<div class="check-reservation-response">Aucune réservation à ce nom.</div>'
        );
      }
    },
    error: function () {},
  });
});

$('#new_reservation-form').on('submit', function (e) {
  e.preventDefault();
  $.ajax({
    type: 'post',
    data: $(this).serialize(),
    success: function (data) {
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
    error: function () {
      alert('Erreur');
    },
  });
});
