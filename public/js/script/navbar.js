const nav = document.getElementById("navbar");

// Apply black background in navbar on scroll
var prevScrollpos = window.pageYOffset;
window.onscroll = function() {
  var currentScrollPos = window.pageYOffset;
  if (currentScrollPos == '0') {
    nav.style.top = "1%";
    nav.style.background = "transparent";
  }
  else if (prevScrollpos < currentScrollPos) {
    nav.style.top = "1%";
    nav.style.background = "black";
  }
  prevScrollpos = currentScrollPos;
}