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
      form.style.filter = "blur(10px)";
      hourglass.style.display = "flex";
      resetResponses();

      try {
        const response = await fetch("", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `action=${action}&${formData}`,
        });
        const data = await response.json();

        setTimeout(() => {
          form.style.filter = "blur(0px)";
          hourglass.style.display = "none";
          onSuccess(data, responseElements);
        }, 1000);
      } catch (error) {
        console.error("Erreur : ", error);
        alert("Une erreur est survenue. Veuillez réessayer.");
        form.style.filter = "blur(0px)";
        hourglass.style.display = "none";
      }
    });
  };

  // Gestion de la vérification des réservations
  handleFormSubmit(
    "#check_reservation-form",
    "check_reservation",
    (data, responseElements) => {
      if (data && data.length > 0) {
        const renderHtml = (html) =>
          responseElements.forEach((el) => (el.innerHTML += html));

        if (data.length === 1) {
          renderHtml(`
          <div class="check-reservation-response">
          <span class="fullname">${data[0].name} ${
            data[0].surname
          }</span>,<br><br>
          Une réservation pour ${data[0].seats} personne(s) est enregistrée le 
          ${new Date(data[0].date.date).toLocaleDateString()} à ${
            data[0].hour
          }h.<br>
          Cordialement,<br>l'équipe du Vingtième</div>
        `);
        } else {
          let html = `<div class="check-reservation-response">
          <span class="fullname">${data[0].name} ${data[0].surname}</span>,<br><br>Voici vos réservations :<br>`;
          data.forEach((res) => {
            html += `le ${new Date(res.date.date).toLocaleDateString()} pour ${
              res.seats
            } personne(s) à ${res.hour}h,<br>`;
          });
          renderHtml(`${html}<br>Cordialement,<br>l'équipe du Vingtième</div>`);
        }
      } else {
        responseElements.forEach(
          (el) => (el.innerHTML = "Aucune réservation trouvée.")
        );
      }
    }
  );

  // Gestion de la création de nouvelles réservations
  handleFormSubmit(
    "#new_reservation-form",
    "new_reservation",
    (data, responseElements) => {
      if (data && data.length > 0) {
        const buttonsHtml = data
          .map(
            (slot) => `
            <div class="hour-container">
              <label class="button-58">${slot.hour}h - 
                <span class="hour-text">places restantes : ${slot.seats_available}</span>
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
      } else {
        responseElements.forEach(
          (el) => (el.innerHTML = "Aucune disponibilité pour cette date.")
        );
      }
    }
  );
});
