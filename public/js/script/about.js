const section1 = document.getElementById("section-one");
const section2 = document.getElementById("section-two");
const section3 = document.getElementById("section-three");


window.onscroll = function() {
  var currentScrollPos = window.pageYOffset;
  if (currentScrollPos > (section1.offsetTop - 700)) {
    section1.classList.add("visible");
    section2.classList.remove("visible");
    section3.classList.remove("visible");
  }
  
  if (currentScrollPos > (section2.offsetTop - 700)) {
    section2.classList.add("visible");
    section1.classList.remove("visible");
    section3.classList.remove("visible");
  }
  
  if (currentScrollPos > (section3.offsetTop - 700)) {
    section3.classList.add("visible");
    section1.classList.remove("visible");
    section2.classList.remove("visible");
  }
}