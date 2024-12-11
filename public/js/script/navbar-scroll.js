window.addEventListener("load", () => {
  // L'animation est gérée autrement sur la page d'accueil
  if (window.location.pathname !== "/") {
    setTimeout(() => {
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
    }, 1000);
  }
});
