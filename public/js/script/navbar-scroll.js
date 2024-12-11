window.addEventListener("load", () => {
  // L'animation est gérée autrement sur la page d'accueil
  if (window.location.pathname !== "/") {
    gsap.matchMedia().add("(min-width: 768px)", () => {
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
