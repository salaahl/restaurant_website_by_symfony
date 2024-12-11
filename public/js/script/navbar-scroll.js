window.addEventListener("load", () => {
  gsap.matchMedia().add("(min-width: 768px)", () => {
    gsap.fromTo(".navbar", 
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
    });
  });
});
