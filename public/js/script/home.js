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

    document.querySelectorAll(".flip-card").forEach((card) => {
      gsap.to(card, {
        opacity: 1,
        scrollTrigger: {
          trigger: card,
          start: "0 85%",
          end: "0 85%",
        },
      });
    });
  });

  gsap.matchMedia().add("(min-width: 768px)", () => {
    gsap.fromTo(
      ".navbar",
      {
        minHeight: "7vh",
        width: "calc(100% - 1.5rem)",
        color: "white",
        backgroundColor: "black",
      },
      {
        minHeight: "6vh",
        width: "70%",
        maxWidth: 700,
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
      x: "100%",
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
      x: "-100%",
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
