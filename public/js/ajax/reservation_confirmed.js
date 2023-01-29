$(function() {
	$('form').on('submit', function(e) {
		e.preventDefault();
		$.ajax({
			type: 'post',
			data: $(this).serialize(),
			success: function(data) {
				alert('Réservation confirmée !');
			},
			error: function() {
				alert('Erreur');
			}
		});
	});
});