let main = $('main');
let errorMargin = 2;

$('.global-container').scroll(function () {
  let currentScrollPos = $('#scroll-position').position();

  if (currentScrollPos.top + errorMargin > scrollPos.top) {
    navbar.removeClass('navbar-scroll');
    navbar.addClass('navbar-no-scroll');
  } else {
    navbar.removeClass('navbar-no-scroll');
    navbar.addClass('navbar-scroll');
  }
});

$('#nav-toggler-icon').click(function () {
  if ($('.navbar-collapse').hasClass('show')) {
    navbar.children('.navbar-collapse').removeClass('show');
    navbar.removeClass('black-navbar');
    main.removeClass('main-margin');
  } else {
    navbar.children('.navbar-collapse').addClass('show');
    navbar.addClass('black-navbar');
    main.addClass('main-margin');
  }
});