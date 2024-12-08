// Enregistrement des plugins
gsap.registerPlugin(ScrollTrigger);

// Configuration globale de GSAP
gsap.config({
  trialWarn: true, // Affiche un avertissement si un plug-in payant est utilisé
});
gsap.defaults({ ease: "power1.out" });

window.addEventListener("load", () => {
  gsap.matchMedia().add("(max-width: 767px)", () => {
    gsap.to(".navbar", {
      backgroundColor: "white",
      scrollTrigger: {
        trigger: "html",
        start: "0 0",
        end: "15% 0",
        scrub: true,
      },
    });
  });

  gsap.matchMedia().add("(min-width: 768px)", () => {
    gsap.fromTo(
      ".navbar",
      {
        width: "calc(100% - 1.5rem)",
        color: "white",
        backgroundColor: "black",
      },
      {
        width: "70%",
        maxWidth: 800,
        borderRadius: 40,
        color: "white",
        backgroundColor: "black",
        scrollTrigger: {
          trigger: "html",
          start: "0 0",
          end: "15% 0",
          scrub: true,
        },
      }
    );

    gsap.to(".navbar-brand", {
      fontSize: "large",
      scrollTrigger: {
        trigger: "html",
        start: "0 0",
        end: "15% 0",
        scrub: true,
      },
    });

    gsap.from(".straight-lines.mirror > .straight", {
      x: "25%",
      opacity: 0,
      duration: 0.5,
      delay: 0.25,
      stagger: 0.1,
      scrollTrigger: {
        trigger: "#menu-title-container .title",
        start: "50% 50%",
      },
    });

    gsap.from(".straight-lines:first-of-type > .straight", {
      x: "-25%",
      opacity: 0,
      duration: 0.5,
      delay: 0.25,
      stagger: 0.1,
      scrollTrigger: {
        trigger: "#menu-title-container .title",
        start: "50% 50%",
      },
    });
  });
});
