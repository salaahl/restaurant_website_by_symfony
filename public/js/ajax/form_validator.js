$(function () {
  $('#form-menu').validate({
    rules: {
      type: {
        required: true,
        minlength: 2,
        equalTo: '#confirm'
      },
      confirm: {
        required: true
      }
    },
    messages: {
      type: {
        required: 'Veuillez remplir ce champ',
        minlength: 'Le nom du menu doit faire plus de deux caractères',
        equalTo: 'Le nom du plat ne correspond pas',
      },
      confirm: {
        required: 'Veuillez remplir ce champ'
      }
    },
    submitHandler: function (form) {
      // default form submit. Sinon remplacer par le bloc $.ajax...
      form.submit();
    },
  });

  $('#form-dish').validate({
    // in 'rules' user have to specify all the constraints for respective fields
    rules: {
      new_dish_name: {
        required: true,
        minlength: 2,
      },
      new_dish_description: {
        required: true,
        maxlength: 100,
      },
      new_dish_price: {
        required: true,
      },
      new_dish_menu: {
        required: true,
      },
    },
    messages: {
      new_dish_name: {
        required: 'Veuillez remplir ce champ.',
        minlength: 'Le nom du menu doit faire plus de deux caractères',
      },
      new_dish_description: {
        required: 'Veuillez remplir ce champ.',
        maxlength: 'Vous avez depassé le nombre de caractères autorisé.',
      },
      new_dish_price: {
        required: 'Veuillez remplir ce champ.',
      },
      new_dish_menu: {
        required: 'Veuillez selectionner un menu.',
      },
    },
    submitHandler: function (form) {
      // default form submit. Sinon remplacer par le bloc $.ajax...
      form.submit();
    },
  });
});