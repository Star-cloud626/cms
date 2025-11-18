
var full = false;

function checkPosition(){
	if($('#full_image').offset().left < 0){
		$('#full_image').css('left', 0);
	}
	if($('#full_image').offset().top < 0){
		$('#full_image').css('top', 0);
	}
}

var resizeHandler = function(){
	if(!full){
		//$('#full_image').width($(window).width() * 0.95);
		$('#full_image').height($(window).height() * 0.95);
		checkPosition();
	}
}

function toggleFull(){
	full = !full;
	if(!full){
		resizeHandler();
		$(window).bind('resize', resizeHandler);
		$('#resize_button').val('View Original Size');
		checkPosition();
	}
	else {
		$(window).unbind('resize', resizeHandler);
		//$('#full_image').css('width', 'auto');
		$('#full_image').css('height', 'auto');
		$('#resize_button').val('Fit to Window');
	}
}

$(document).ready(function() {
	$(window).bind('resize', resizeHandler);
	resizeHandler();
	$('#resize_button').show();
	$('#full_image').draggable();
});

