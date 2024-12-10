window.addEventListener("load", () => {
  gsap.matchMedia().add("(max-width: 767px)", () => {
    [
      "#about-me-container",
      "#menu-container",
      "#reservation-container",
    ].forEach((element) => {
      gsap.fromTo(
        element,
        {
          y: 250,
          opacity: 0,
        },
        {
          y: 0,
          opacity: 1,
          scrollTrigger: {
            trigger: element,
            start: "0 100%",
            end: "+=250 100%",
            scrub: true,
          },
        }
      );
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
        maxWidth: 750,
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

    gsap.from("#menu-title-container .title", {
      y: "100%",
      opacity: 0,
      duration: 0.5,
      scrollTrigger: {
        trigger: "#menu-title-container .title",
        start: "-100% 100%",
        end: "100% 100%",
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
        start: "0% 100%",
        end: "+=50%",
        scrub: true,
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
        start: "0% 100%",
        end: "+=50%",
        scrub: true,
      },
    });
  });
});
