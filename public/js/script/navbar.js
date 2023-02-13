let timer;

$(window).scroll(() => {
  if (timer) {
    clearTimeout(timer);
  }
  timer = setTimeout(() => {
    if ($(this).scrollTop() == 0) {
      navbar.removeClass('navbar-scroll');
      navbar.addClass('navbar-no-scroll');
    } else {
      navbar.removeClass('navbar-no-scroll');
      navbar.addClass('navbar-scroll');
    }
  }, 100);
});