// Gestion du DOM
window.addEventListener("DOMContentLoaded", () => {
  document
    .getElementById("new-reservation-button")
    .addEventListener("click", () => {
      document.getElementById("new-reservation").classList.remove("hidden");
      document.getElementById("redirect-reservation").classList.add("hidden");
    });

  document
    .getElementById("check-reservation-button")
    .addEventListener("click", () => {
      document.getElementById("check-reservation").classList.remove("hidden");
      document.getElementById("redirect-reservation").classList.add("hidden");
    });

  document.getElementById("return-button").addEventListener("click", () => {
    document.getElementById("check-reservation").classList.add("hidden");
    document.getElementById("new-reservation").classList.add("hidden");
    document.getElementById("complete-reservation").classList.add("hidden");
    document.getElementById("redirect-reservation").classList.remove("hidden");
  });

  // Gestion des formulaires
  document
    .getElementById("check_reservation-form")
    .addEventListener("submit", (e) => {
      e.preventDefault();

      const checkReservation = document.getElementById("check-reservation");
      const hourglass = document.getElementById("lds-hourglass");
      const responseElements = document.getElementsByClassName("response");
      const email = document.getElementById("email").value;
      const surname = document.getElementById("surname").value;

      // Désactive le formulaire et affiche l'indicateur de chargement
      checkReservation.style.filter = "blur(10px)";
      hourglass.style.display = "flex";
      Array.from(responseElements).forEach((el) => (el.innerHTML = ""));

      // Données de la requête
      const reservationData = new URLSearchParams({
        action: "check_reservation",
        email,
        surname,
      }).toString();

      // Envoi de la requête fetch
      fetch("", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: reservationData,
      })
        .then((response) => response.json())
        .then((data) => {
          // Réinitialiser l'interface
          checkReservation.style.filter = "blur(0px)";
          hourglass.style.display = "none";

          if (data) {
            const renderResponse = (html) => {
              Array.from(responseElements).forEach(
                (el) => (el.innerHTML += html)
              );
            };

            if (data.length === 1) {
              var html = `
                <div class="check-reservation-response">${data[0].name} ${
                data[0].surname
              },<br>
                Une réservation à votre nom pour ${
                  data[0].seats
                } personne(s) est bien enregistrée 
                pour le ${new Date(data[0].date).toLocaleDateString()} à ${
                data[0].hour
              }h.<br><br>
                Cordialement,<br>L'équipe du Vingtième</div>
              `;
              renderResponse(html);
            } else {
              html = `<div class="check-reservation-response">${data[0].name} ${data[0].surname},<br>Voici pour vos réservations :<br><br>`;

              data.forEach((reservation, index) => {
                html += `le ${new Date(
                  reservation.date.date
                ).toLocaleDateString()} pour ${reservation.seats} personne(s) 
                    à ${reservation.hour}h,<br>`;
              });

              html += "<br>Cordialement,<br>L'équipe du Vingtième</div>";
              renderResponse(html);
            }
          } else {
            let html = `<div class="check-reservation-response">Aucune réservation à ce nom.</div>`;
            renderResponse(html);
          }
        })
        .catch((error) => {
          alert(
            "Impossible de récupérer la liste de vos réservations. Veuillez prendre contact avec le restaurant."
          );
          console.error(error);
          // Réinitialisation de l'interface en cas d'erreur
          checkReservation.style.filter = "blur(0px)";
          hourglass.style.display = "none";
        });
    });

  document
    .getElementById("new_reservation-form")
    .addEventListener("submit", async (e) => {
      e.preventDefault();

      const $ = (id) => document.getElementById(id);
      const newReservation = $("new-reservation");
      const hourglass = $("lds-hourglass");
      const responseElements = Array.from(
        document.getElementsByClassName("response")
      );
      const seats = $("check-seats").value.trim();
      const date = $("check-date").value.trim();

      // Fonction d'affichage des réponses
      const displayResponse = (html) => {
        responseElements.forEach((element) => (element.innerHTML = html));
      };

      // Mise à jour de l'état visuel
      const toggleLoadingState = (isLoading) => {
        newReservation.style.filter = isLoading ? "blur(10px)" : "blur(0px)";
        hourglass.style.display = isLoading ? "flex" : "none";
      };

      // Validation des données utilisateur
      if (!seats || !date) {
        displayResponse(
          '<div class="error">Veuillez remplir tous les champs.</div>'
        );
        return;
      }

      toggleLoadingState(true);

      try {
        const reservationData = new URLSearchParams({
          action: "new_reservation",
          seats,
          date,
        });

        const response = await fetch("", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: reservationData,
        });

        const data = await response.json();
        toggleLoadingState(false);

        if (data && data.length > 0) {
          // Construire les boutons pour chaque horaire disponible
          const buttonsHtml = data
            .map(
              (slot) =>
                `<input type="button" class="hour button-58" value="${slot.hour}" />`
            )
            .join("");

          displayResponse(buttonsHtml);

          // Ajout d'événements aux boutons générés
          document.querySelectorAll(".hour").forEach((button) => {
            button.addEventListener("click", () => handleHourClick(button));
          });
        } else {
          displayResponse(
            '<div class="no-availability">Aucune disponibilité sur cette date. Veuillez réessayer avec une autre date.</div>'
          );
        }
      } catch (error) {
        console.error("Erreur lors de la requête : ", error);
        alert(
          "Impossible de faire une nouvelle réservation pour le moment. Veuillez contacter le restaurant."
        );
        toggleLoadingState(false);
      }
    });

  // Fonction pour gérer le clic sur un horaire
  const handleHourClick = (button) => {
    const seats = document.getElementById("check-seats").value.trim();
    const date = document.getElementById("check-date").value.trim();
    const hour = button.value.trim();

    // Mise à jour de l'interface pour la réservation complète
    document.getElementById("new-reservation").style.display = "none";
    document.getElementById("complete-reservation").classList.remove("hidden");
    document.getElementById("form-seats").value = seats;
    document.getElementById("form-date").value = date;
    document.getElementById("form-hour").value = hour;
  };
});
