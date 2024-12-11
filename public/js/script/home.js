window.addEventListener("load", () => {
  setTimeout(() => {
    function show(element) {
      gsap.to(element, {
        y: 0,
        opacity: 1,
      });
    }

    function hide(element) {
      gsap.to(element, {
        y: "100%",
        opacity: 0,
      });
    }

    function reduceNavbar() {
      gsap.to(".navbar", {
        minHeight: "6vh",
        width: window.innerWidth < 1024 ? 700 : 750,
        borderRadius: 40,
      });

      gsap.to(".navbar-brand", {
        fontSize: "large",
      });
    }

    function resetNavbar() {
      gsap.to(".navbar", {
        minHeight: "7vh",
        width: "96%",
        borderRadius: 25,
      });

      gsap.to(".navbar-brand", {
        fontSize: "x-large",
      });
    }

    gsap.matchMedia().add("(min-width: 768px)", () => {
      ScrollTrigger.create({
        trigger: "#navbar",
        start: "0 0",
        end: "0 0",
        onEnter: () => reduceNavbar(),
        onEnterBack: () => resetNavbar(),
      });

      ScrollTrigger.create({
        trigger: "#about-me-container",
        start: "0 75%",
        end: "0 75%",
        onEnter: () => show("#about-me-container"),
        onEnterBack: () => hide("#about-me-container"),
      });

      ScrollTrigger.create({
        trigger: "#menu-title-container .title",
        start: "0 50%",
        end: "0 50%",
        onEnter: () => show("#menu-title-container .title"),
        onEnterBack: () => hide("#menu-title-container .title"),
      });

      gsap.from(".straight-lines.mirror > .straight", {
        x: "100%",
        opacity: 0,
        duration: 0.4,
        delay: 0.25,
        stagger: 0.1,
        scrollTrigger: {
          trigger: "#menu-title-container .title",
          start: "0 50%",
          end: "+=25%",
          scrub: true,
        },
      });

      gsap.from(".straight-lines:first-of-type > .straight", {
        x: "-100%",
        opacity: 0,
        duration: 0.4,
        delay: 0.25,
        stagger: 0.1,
        scrollTrigger: {
          trigger: "#menu-title-container .title",
          start: "0 50%",
          end: "+=25%",
          scrub: true,
        },
      });

      ScrollTrigger.create({
        trigger: "#reservation-container",
        start: "0 75%",
        end: "0 75%",
        onEnter: () => show("#reservation-container"),
        onEnterBack: () => hide("#reservation-container"),
      });
    });
  }, 500);
});
