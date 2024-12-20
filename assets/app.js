/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// Enregistrement des plugins GSAP et configuration globale
gsap.registerPlugin(ScrollTrigger);
gsap.config({
  trialWarn: true,
});
gsap.defaults({ ease: "power1.out" });

// Constantes
const $ = (id) => {
  document.getElementById(id);
};
const loader = document.getElementById("loader");
const navbar = document.getElementById("navbar");
const globalContainer = document.getElementById("global-container");
const footer = document.getElementsByTagName("footer");

window.addEventListener("load", () => {
  // Loader
  loader.style.display = "none";
  globalContainer.style.opacity = "1";

  // Navbar
  // Rétracter la navbar en cas de clic sur le menu
  if (window.innerWidth < 991) {
    document.getElementById("menu-button").addEventListener("click", () => {
      document.querySelector(".navbar-collapse").classList.remove("show");
    });
  }

  // L'animation est gérée autrement sur la page d'accueil
  if (window.location.pathname !== "/") {
    gsap.matchMedia().add("(min-width: 992px)", () => {
      gsap.fromTo(
        ".navbar",
        {
          backgroundColor: "transparent",
        },
        {
          backgroundColor: "white",
          scrollTrigger: {
            trigger: "html",
            start: "0 0",
            end: "15% 0",
            scrub: true,
          },
        }
      );
    });
  }
});
