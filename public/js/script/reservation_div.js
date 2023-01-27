const redirectContainer = document.getElementById("redirect-reservation-container");
const checkContainer = document.getElementById("check-reservation-container");
const reservationContainer = document.getElementById("new-reservation-container");

const newReservation = document.getElementById("new-reservation");
const checkReservation = document.getElementById("check-reservation");

newReservation.addEventListener("focus", function() {
  reservationContainer.style.display = "block";
  redirectContainer.style.display = "none";
});

checkReservation.addEventListener("focus", function() {
  checkContainer.style.display = "block";
  redirectContainer.style.display = "none";
});