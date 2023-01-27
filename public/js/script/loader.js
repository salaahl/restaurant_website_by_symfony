var loader = document.getElementById("loader");
var globalContainer = document.getElementById("global-container");
var footerContainer = document.getElementById("footer");

globalContainer.style.opacity = '0';
footerContainer.style.opacity = '0';

function waithide() {
  window.setTimeout(
    function removethis() {
      loader.style.display = 'none';
      globalContainer.style.opacity = '1';
      footerContainer.style.opacity = '1';
      globalContainer.style.transition = 'opacity 1s';
      footerContainer.style.transition = 'opacity 1s';
    }, 3000);
}

$(document).ready(function() {
  var myVar = waithide();
});