$( window ).scroll(function() {
  var currentScrollPos = window.pageYOffset;
  
  if (currentScrollPos == '0') {
    ('#navbar').removeClass("navbar-scroll");
    $('#navbar').addClass("navbar-non-scroll");
  }
  else if ('0' < currentScrollPos) {
    ('#navbar').removeClass("navbar-non-scroll");
    $('#navbar').addClass("navbar-scroll");
  }
});

$( '#nav-toggler-icon' ).click(function() {
  if($('.navbar-collapse').hasClass('show')) {
    $('.navbar-collapse').removeClass("show");
    $('.navbar').removeClass("black-navbar");
    $('main').removeClass("main-margin");
  } else {
    $('.navbar-collapse').addClass("show");
    $('.navbar').addClass("black-navbar");
    $('main').addClass("main-margin");
  }
});