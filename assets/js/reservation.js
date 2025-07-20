// Gestion du DOM
document.addEventListener("DOMContentLoaded", () => {
  const $ = (selector) => document.querySelector(selector);
  const $$ = (selector) => document.querySelectorAll(selector);

  const toggleVisibility = (elementId, show) => {
    const el = $(elementId);
    if (el) el.classList.toggle("show", show);
  };

  const resetResponses = () => {
    $$(".response").forEach((el) => (el.innerHTML = ""));
  };

  // Gestion des boutons principaux
  $("#new-reservation-button").addEventListener("click", () => {
    toggleVisibility("#return-button", true);
    toggleVisibility("#new-reservation", true);
    toggleVisibility("#redirect-reservation", false);
  });

  $("#check-reservation-button").addEventListener("click", () => {
    toggleVisibility("#return-button", true);
    toggleVisibility("#check-reservation", true);
    toggleVisibility("#redirect-reservation", false);
  });

  $("#return-button").addEventListener("click", () => {
    toggleVisibility("#return-button", false);
    [
      "#check-reservation",
      "#new-reservation",
      "#complete-reservation",
      "#redirect-reservation",
    ].forEach((id) => toggleVisibility(id, false));
    toggleVisibility("#redirect-reservation", true);
    resetResponses();
  });

  // Gestion des formulaires
  const handleFormSubmit = async (formId, action, onSuccess) => {
    const form = $(formId);
    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const hourglass = $("#lds-hourglass");
      const responseElements = $$(".response");

      // Données du formulaire
      const formData = new URLSearchParams(new FormData(form)).toString();

      // Mise à jour de l'état visuel
      form.parentElement.classList.add("blur");
      hourglass.style.display = "flex";
      resetResponses();

      try {
        const response = await fetch("", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `action=${action}&${formData}`,
        });

        // Vérification du statut HTTP
        if (!response.ok) {
          // Si la réponse n'est pas OK, récupérez les données d'erreur du serveur
          const errorData = await response.json();

          // Vous pouvez aussi utiliser errorData pour afficher des informations spécifiques sur l'erreur
          onSuccess(errorData, responseElements, true); // On passe un flag "true" pour indiquer une erreur
        } else {
          // Si la réponse est OK, traitez les données normalement
          const data = await response.json();
          onSuccess(data, responseElements, false); // On passe "false" pour indiquer un succès
        }

        // Réinitialisation de l'état visuel après un délai
        setTimeout(() => {
          form.parentElement.classList.remove("blur");
          hourglass.style.display = "none";
        }, 1600);
      } catch (error) {
        // Si une erreur se produit (par exemple, erreur réseau), on la capture
        console.log(error);
        alert(error.message);

        // Optionnel : afficher l'erreur sur l'interface utilisateur
        const errorMessage = document.createElement("div");
        errorMessage.classList.add("error-message");
        errorMessage.textContent = `Une erreur est survenue : ${error.message}`;
        form.parentElement.appendChild(errorMessage);

        form.parentElement.classList.remove("blur");
        hourglass.style.display = "none";

        // Appel de onSuccess avec un flag d'erreur
        onSuccess({ message: error.message }, responseElements, true);
      }
    });
  };

  // Gestion de la vérification des réservations
  handleFormSubmit(
    "#check-reservation-form",
    "check_reservation",
    (data, responseElements, isError) => {
      if (isError) {
        alert(data.message);
        console.error(data.message);
      }

      if (data && data.length > 0) {
        const renderHtml = (html) =>
          responseElements.forEach((el) => (el.innerHTML += html));

        if (data.length === 1) {
          renderHtml(`
          <div class="check-reservation-response">
          <span class="fullname">${data[0].name} ${
            data[0].surname
          }</span>,<br><br>
          Une réservation pour ${data[0].seats} personne(s) est enregistrée pour le 
          ${new Date(data[0].date.date).toLocaleDateString()} à ${
            data[0].hour
          }h.<br><br>
          Cordialement,<br>l'équipe du Vingtième</div>
        `);
        } else {
          let html = `<div class="check-reservation-response">
          <span class="fullname">${data[0].name} ${data[0].surname}</span>,<br><br>Voici vos réservations :<br>`;
          data.forEach((res) => {
            html += `le ${new Date(res.date.date).toLocaleDateString()} à ${
              res.hour
            }h pour ${res.seats} personne(s),<br>`;
          });
          renderHtml(`${html}<br>Cordialement,<br>l'équipe du Vingtième</div>`);
        }
      }
    }
  );

  // Gestion de la création de nouvelles réservations
  handleFormSubmit(
    "#new-reservation-form",
    "new_reservation",
    (data, responseElements, isError) => {
      if (isError) {
        alert(data.message);
        console.error(data.message);
      }

      if (data && data.length > 0) {
        const buttonsHtml = data
          .map(
            (slot) => `
            <div class="hour-container">
              <label class="button-58">${slot.hour}h
                <span class="hour-text">${
                  slot.seats_available <= 4 ? " - dernières places" : ""
                }</span>
                <input type="button" class="hour" value="${slot.hour}" hidden />
              </label>
            </div>`
          )
          .join("");

        responseElements.forEach((el) => (el.innerHTML = buttonsHtml));

        // Ajouter des événements aux boutons
        $$(".hour").forEach((button) =>
          button.addEventListener("click", () => {
            const seats = $("#check-seats").value.trim();
            const date = $("#check-date").value.trim();
            const hour = button.value.trim();

            // Mise à jour de l'interface
            toggleVisibility("#new-reservation", false);
            toggleVisibility("#complete-reservation", true);
            $("#form-seats").value = seats;
            $("#form-date").value = date;
            $("#form-hour").value = hour;
          })
        );
      }
    }
  );

  // Gestion de la finalisation des réservations
  handleFormSubmit(
    "#complete-reservation-form",
    "complete_reservation",
    (data, responseElements, isError) => {
      if (isError) {
        alert(data.message);
        console.error(data.message);
      }

      if (data) {
        window.location.href = "/confirmation/" + data;
      }
    }
  );
});
