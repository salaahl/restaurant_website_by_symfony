$(function () {
  $('#check_reservation-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      type: 'post',
      url: '../index.php',
      data: $(this).serialize(),
      success: function (data) {
        if (data != '') {
          console.log(data);
        } else {
          console.log('Aucune réservation à ce nom.');
        }
      },
      error: function () {
        console.log('Erreur.');
      },
    });
  });

  $('#new_reservation-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      type: 'post',
      url: '../index.php',
      data: $(this).serialize(),
      success: function (data) {
        if (data != '') {
          // Mettre dans l'objet "data" l'heure et le jour et les injecter dans le lien présent dans "hour"
          let availablity = data;
          availablity.forEach((hour) => {
            $('.availablity').append(
              '<div class="hour"><a href="./complete_reservation.html?">' +
                hour +
                '</a></div>'
            );
          });
        } else {
          $('.availablity').append(
            '<div>Aucune disponibilité sur cette date. Veuillez réessayer avec un autre jour.</div>'
          );
        }
      },
      error: function () {
        alert('Erreur');
      },
    });
  });
});