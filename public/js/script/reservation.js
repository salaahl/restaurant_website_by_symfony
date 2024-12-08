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
