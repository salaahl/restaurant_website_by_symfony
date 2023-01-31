navbar.css({ opacity: "0" });
globalContainer.css({ opacity: "0" });
footer.css({ opacity: "0" });

function waithide() {
  window.setTimeout(
    function removethis() {
      loader.css({ display: "none" });
      navbar.css({ opacity: "1" });
      globalContainer.css({ opacity: "1" });
      footer.css({ opacity: "1" });
      navbar.css({ transition: "all 1s" });
      globalContainer.css({ transition: "all 1s" });
      footer.css({ transition: "all 1s" });
    }, 3000);
}

$(document).ready(function() {
  let removeLoader = waithide();
});