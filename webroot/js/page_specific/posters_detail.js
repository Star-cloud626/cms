
$(document).ready(function(){

	$('#images').sortable();

	$('#savePositions').click(function(event){
		event.preventDefault();

		// save the ids to form
		var ids = $('#images').sortable('toArray');
		if(ids){
			$('#positionFormContent').html('');
			for(var i = 0; i<ids.length; ++i){
				$('#positionFormContent').append('<input type="hidden" name="data[positions][]" value="'+ids[i]+'" />');
			}

			// submit the form
			$(this).parents("form:first").ajaxSubmit({
				success: function(responseText, responseCode) {
					$('#positionFormContent').hide().html(responseText).fadeIn();
					setTimeout(function(){
						$('#positionFormContent').fadeOut();
					}, 5000);
				}
			});
		}

		return false;
	});

});