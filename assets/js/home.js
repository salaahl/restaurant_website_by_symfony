window.addEventListener("DOMContentLoaded", () => {
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
        width:
          window.innerWidth < 992 ? "calc(100% - 1.5rem)" : "calc(100% - 3rem)",
        borderRadius: 25,
      });

      gsap.to(".navbar-brand", {
        fontSize: "x-large",
      });
    }

    gsap.matchMedia().add("(max-width: 767px)", () => {
      ["#about-me-container", "#reservation-container"].forEach((element) => {
        gsap.to(element, {
          y: 0,
          opacity: 1,
          scrollTrigger: {
            trigger: element,
            start: "0 100%",
          },
        });
      });

      document
        .querySelectorAll(".flip-card:nth-of-type(n+2)")
        .forEach((element) => {
          gsap.to(element, {
            y: 0,
            scrollTrigger: {
              trigger: element,
              start: "0 100%",
            },
          });
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
        start: "0 100%",
        end: "0 100%",
        onEnter: () => show("#about-me-container"),
      });

      ScrollTrigger.create({
        trigger: "#menu-title-container .title",
        start: "0 100%",
        end: "0 100%",
        onEnter: () => show("#menu-title-container .title"),
      });

      ScrollTrigger.create({
        trigger: "#reservation-container",
        start: "0 100%",
        end: "0 100%",
        onEnter: () => show("#reservation-container"),
      });
    });
  }, 500);
});
