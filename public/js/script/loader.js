let navbarDisplay = navbar.css('display');
let globalContainerDisplay = globalContainer.css('display');

navbar.css({ display: 'none' });
globalContainer.css({ display: 'none' });

function waithide() {
  window.setTimeout(function removethis() {
    loader.css({ display: 'none' });
    navbar.css({ display: navbarDisplay });
    globalContainer.css({ display: globalContainerDisplay });
    navbar.css({ transition: 'all 1s' });
    globalContainer.css({ transition: 'all 1s' });
  }, 3000);
}

$(document).ready(function () {
  let removeLoader = waithide();
});
