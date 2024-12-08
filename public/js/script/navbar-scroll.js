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
