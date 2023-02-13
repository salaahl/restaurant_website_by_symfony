let globalContainerDisplay = globalContainer.css('display');

globalContainer.css({ display: 'none' });

function waithide() {
  window.setTimeout(function removethis() {
    loader.css({ display: 'none' });
    globalContainer.css({ display: globalContainerDisplay });
  }, 3000);
}

$(document).ready(function () {
  let removeLoader = waithide();
});
