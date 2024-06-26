let timer;

window.addEventListener('scroll', () => {
  if (timer) {
    clearTimeout(timer);
  }
  timer = setTimeout(() => {
    if (this.scrollY == 0) {
      navbar.classList.remove('navbar-scroll');
      navbar.classList.add('navbar-no-scroll');
    } else {
      navbar.classList.remove('navbar-no-scroll');
      navbar.classList.add('navbar-scroll');
    }
  }, 25);
});
