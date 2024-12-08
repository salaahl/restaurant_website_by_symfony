// Enregistrement des plugins
gsap.registerPlugin(ScrollTrigger);

// Configuration globale de GSAP
gsap.config({
  trialWarn: true, // Affiche un avertissement si un plug-in payant est utilisé
});
gsap.defaults({ ease: "power1.out" });

window.addEventListener("load", () => {
  gsap.matchMedia().add("(max-width: 767px)", () => {
    const textContainers = gsap.utils.toArray("article > .text-container");

    textContainers.forEach((textContainer) => {
      gsap.to(textContainer, {
        opacity: 1,
        scrollTrigger: {
          trigger: textContainer,
          start: "0% 50%",
        },
      });
    });
  });

  gsap.matchMedia().add("(min-width: 768px)", () => {
    gsap.fromTo(
      "section > article:nth-of-type(1) > .text-container",
      {
        x: "-35%",
        opacity: 0,
        duration: 0.8,
        delay: 0.5,
      },
      {
        x: "0%",
        opacity: 1,
        scrollTrigger: {
          trigger: "section > article:nth-of-type(1) > .text-container",
          start: "0% 50%",
        },
      }
    );

    gsap.fromTo(
      "section > article:nth-of-type(2) > .text-container",
      {
        y: "-35%",
        opacity: 0,
        duration: 0.8,
      },
      {
        y: "0%",
        opacity: 1,
        scrollTrigger: {
          trigger: "section > article:nth-of-type(2) > .text-container",
          start: "0% 50%",
        },
      }
    );

    gsap.fromTo(
      "section > article:nth-of-type(3) > .text-container",
      {
        x: "-35%",
        opacity: 0,
        duration: 0.8,
      },
      {
        x: "0%",
        opacity: 1,
        scrollTrigger: {
          trigger: "section > article:nth-of-type(3) > .text-container",
          start: "0% 50%",
        },
      }
    );
  });
});
