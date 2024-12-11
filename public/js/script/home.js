window.addEventListener("load", () => {
  setTimeout(() => {
    function reduceNavbar() {
      gsap.to(
        ".navbar",
        {
          minHeight: "6vh",
          width: "70%",
          maxWidth: 750,
          borderRadius: 40,
        });

      gsap.to(
        ".navbar-brand",
        {
          fontSize: "large"
        });
    }
    
    function resetNavbar() {
      gsap.to(
        ".navbar",
        {
          minHeight: "7vh",
          width: "calc(100% - 1.5rem)"
        });

      gsap.to(
        ".navbar-brand",
        {
          fontSize: "x-large"
        });
    }
    
    gsap.matchMedia().add("(min-width: 768px)", () => {
      ScrollTrigger.create({
        trigger: "html",
        start: "0 10vh",
        end: "0 10vh",
        onEnter: () => reduceNavbar(),
        onEnterBack: () => resetNavbar(),
      });
  
      gsap.from("#about-me-container", {
        y: "25%",
        opacity: 0,
        scrollTrigger: {
          trigger: "#about-me-container",
          start: "0% 100%",
          end: "50% 100%",
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
  }, 500);
});
