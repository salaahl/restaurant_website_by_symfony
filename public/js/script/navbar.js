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
  }, 50);
});

$('#nav-toggler-icon').click(function () {
  if ($('.navbar-collapse').hasClass('show')) {
    navbar.children('.navbar-collapse').removeClass('show');
    navbar.removeClass('black-navbar');
  } else {
    navbar.children('.navbar-collapse').addClass('show');
    navbar.addClass('black-navbar');
  }
});
