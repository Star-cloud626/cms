
$(document).ready(function() {

	// For showing default text in a text input:
	$('.show_default').addClass("idle_field");
	$('.show_default').focus(function() {
		$(this).removeClass("idle_field").addClass("focus_field");
		if (this.value == this.defaultValue){
			this.value = '';
		}
		if(this.value != this.defaultValue){
			this.select();
		}
	});
	$('.show_default').blur(function() {
		if ($.trim(this.value) == ''){
			this.value = (this.defaultValue ? this.defaultValue : '');
			$(this).removeClass("focus_field").addClass("idle_field");
		}
	});
	
});

function verifyPasswords(id1, id2){
	var input1 = document.getElementById(id1);
	var input2 = document.getElementById(id2);
	if(input1.value == input2.value){
		return true;
	}
	alert('Password fields do not match.');
	return false;
}

