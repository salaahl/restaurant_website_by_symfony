document
  .getElementById('check_reservation-form')
  .addEventListener('submit', (e) => {
    e.preventDefault();

    let $ = (id) => {
      document.getElementById(id);
    };

    let checkReservation = document.getElementById('check-reservation');
    let hourglass = document.getElementById('lds-hourglass');
    let response = document.getElementsByClassName('response');
    let mail = document.getElementById('mail').value;
    let surname = document.getElementById('surname').value;

    checkReservation.style.filter = 'blur(10px)';
    hourglass.style.display = 'flex';
    for (let i = 0; i < response.length; i++) {
      response[i].innerHTML = '';
    }

    postData('', {
      check_reservation: 'initialize',
      mail: mail,
      surname: surname,
    })
      .then((data) => {
        checkReservation.style.filter = 'blur(0px)';
        hourglass.style.display = 'none';
        let html;
        if (data != '') {
          if (data.hour.length === 1) {
            html =
              '<div class="check-reservation-response">' +
              data.name +
              ' ' +
              data.surname +
              ',<br>' +
              'Une réservation à votre nom pour ' +
              data.seat_reserved +
              ' personne(s) est bien enregistrée pour le ' +
              new Date(data.date).toDateString('fr-FR', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
              }) +
              ' à ' +
              data.hour +
              '<br>' +
              'Cordialement,<br>' +
              "L'équipe du Vingtième" +
              '</div>';

            for (let i = 0; i < response.length; i++) {
              response[i].innerHTML += html;
            }
          } else {
            html =
              '<div class="check-reservation-response">' +
              data.name +
              ' ' +
              data.surname +
              ',<br>' +
              'Voici pour vos réservations :' +
              '<br>';

            for (let i = 0; i < response.length; i++) {
              response[i].innerHTML += html;
            }

            let checkReservation = document.getElementsByClassName(
              'check-reservation-response'
            );
            for (let index = 0; index < data.hour.length; index++) {
              html = data.date[index] + ' à ' + data.hour[index] + ',<br>';

              for (let i = 0; i < response.length; i++) {
                checkReservation[i].innerHTML += html;
              }
            }

            html = 'Cordialement,<br>' + "L'équipe du Vingtième" + '</div>';

            for (let i = 0; i < response.length; i++) {
              checkReservation[i].innerHTML += html;
            }
          }
        } else {
          for (let i = 0; i < response.length; i++) {
            response[i].innerHTML +=
              '<div class="check-reservation-response">Aucune réservation à ce nom.</div>';
          }
        }
      })
      .catch((error) => {
        alert(
          'Impossible de récupérer la liste de vos réservations. Veuillez prendre contact avec le restaurant.'
        );

        checkReservation.style.filter = 'blur(0px)';
        hourglass.style.display = 'none';
      });
  });

document
  .getElementById('new_reservation-form')
  .addEventListener('submit', (e) => {
    e.preventDefault();

    let $ = (id) => {
      document.getElementById(id);
    };

    let newReservation = document.getElementById('new-reservation');
    let hourglass = document.getElementById('lds-hourglass');
    let response = document.getElementsByClassName('response');
    let seats = document.getElementById('check-seats').value;
    let date = document.getElementById('check-date').value;

    newReservation.style.filter = 'blur(10px)';
    hourglass.style.display = 'flex';
    for (let i = 0; i < response.length; i++) {
      response[i].innerHTML = '';
    }

    postData('', {
      new_reservation: 'initialize',
      seats: seats,
      date: date,
    })
      .then((data) => {
        newReservation.style.filter = 'blur(0px)';
        hourglass.style.display = 'none';

        if (data != '') {
          for (let index = 0; index < data.length; index++) {
            html =
              '<button class="hour button-58">' +
              data[index].hour +
              '</button>';
            for (let i = 0; i < response.length; i++) {
              response[i].innerHTML += html;
            }
          }

          let hours = document.getElementsByClassName('hour');
          for (let index = 0; index < hours.length; index++) {
            hours[index].onclick = () => {
              let seats = document.getElementById('check-seats').value;
              let date = document.getElementById('check-date').value;
              let hour = hours[index].innerHTML;
              document.getElementById('new-reservation').style.display = 'none';
              document
                .getElementById('complete-reservation')
                .classList.remove('hidden');
              document.getElementById('form-seats').value = seats;
              document.getElementById('form-date').value = date;
              document.getElementById('form-hour').value = hour;
            };
          }
        } else {
          html =
            '<div>Aucune disponibilité sur cette date. Veuillez réessayer avec un autre jour.</div>';
          for (let i = 0; i < response.length; i++) {
            response[i].innerHTML += html;
          }
        }
      })
      .catch((error) => {
        alert(
          'Impossible de faire une nouvelle réservation pour le moment. Veuillez prendre contact avec le restaurant.'
        );
        newReservation.style.filter = 'blur(0px)';
        hourglass.style.display = 'none';
      });
  });
