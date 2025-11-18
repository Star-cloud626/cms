
var clientList;
$(document).ready(function(){

	// fix display on refresh
	if($('#add_new_client').is(':checked')){
		$('#client_form').fadeIn();
	}
	else{
		$('#client_form').fadeOut();
	}

	// set up client list, requires user_list.js
	clientList = new UserList('#client_list');
	$('#client_list_add').click(function(event){
		event.preventDefault();
		clientList.add($('#client_id_select').val(), $('#client_id_select :selected').text())
		});

	// events
	$('#add_new_client').click(function(){
		if($('#add_new_client').is(':checked')){
			$('#client_form').fadeIn();
		}
		else{
			$('#client_form').fadeOut();
		}
	});

	// Allow select to be searchable
	$('#client_id_select').AddIncSearch({
        maxListSize   : 10000,
        maxMultiMatch : 10000
    });
});

function verifyPasswords(id1, id2){
	var client = document.getElementById('add_new_client');
	if(!client.checked){
		return true;
	}
	var input1 = document.getElementById(id1);
	var input2 = document.getElementById(id2);
	if(input1.value == input2.value){
		return true;
	}
	alert('Password fields do not match.');
	return false;
}

