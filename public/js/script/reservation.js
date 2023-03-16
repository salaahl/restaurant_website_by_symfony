document
  .getElementById('new-reservation-button')
  .addEventListener('click', () => {
    document.getElementById('new-reservation').classList.remove('hidden');
    document.getElementById('redirect-reservation').style.display = 'none';
  });

document
  .getElementById('check-reservation-button')
  .addEventListener('click', () => {
    document.getElementById('check-reservation').classList.remove('hidden');
    document.getElementById('redirect-reservation').style.display = 'none';
  });


