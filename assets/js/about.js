window.addEventListener("load", () => {
  gsap.matchMedia().add("(min-width: 992px)", () => {
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
