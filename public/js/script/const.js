// Enregistrement des plugins
gsap.registerPlugin(ScrollTrigger);

// Configuration globale de GSAP
gsap.config({
  trialWarn: true, // Affiche un avertissement si un plug-in payant est utilisé
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
