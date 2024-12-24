window.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    function show(element) {
      gsap.to(element, {
        y: 0,
        opacity: 1,
        duration: 0.35,
      });
    }

    function hide(element) {
      gsap.to(element, {
        y: "100%",
        opacity: 0,
        duration: 0.35,
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
            duration: 0.4,
          },
          {
            y: 0,
            opacity: 1,
            duration: 0.4,
            scrollTrigger: {
              trigger: element,
              start: "0 75%",
              end: "+=250 75%",
            },
          }
        );
      });
    });

    gsap.matchMedia().add("(min-width: 992px)", () => {
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
      });

      ScrollTrigger.create({
        trigger: "#menu-title-container .title",
        start: "0 50%",
        end: "0 50%",
        onEnter: () => show("#menu-title-container .title"),
      });

      ScrollTrigger.create({
        trigger: "#reservation-container",
        start: "0 75%",
        end: "0 75%",
        onEnter: () => show("#reservation-container"),
      });
    });
  }, 500);
});
