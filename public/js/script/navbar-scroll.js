// Enregistrement des plugins
gsap.registerPlugin(ScrollTrigger);

// Configuration globale de GSAP
gsap.config({
  trialWarn: true, // Affiche un avertissement si un plug-in payant est utilisé
});
gsap.defaults({ ease: "power1.out" });

window.addEventListener("load", () => {
  gsap.matchMedia().add("(min-width: 768px)", () => {
    gsap.to(".navbar", {
      color: "black",
      backgroundColor: "white",
      scrollTrigger: {
        trigger: "html",
        start: "0 0",
        end: "15% 0",
        scrub: true,
      },
    });
  });
});
