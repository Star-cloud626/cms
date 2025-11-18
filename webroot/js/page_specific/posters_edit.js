var tagList;
var clientList;
var commenterList;
$(document).ready(function(){

	// set up tag list, requires user_list.js
	tagList = new UserList('#tag_list', 'Tag');
	$('#tag_list_add').click(function(event){
		event.preventDefault();
		tagList.add($('#tag_id_select').val(), $('#tag_id_select :selected').text())
	});
	fillTags();
	tagList.render();


	// set up client list, requires user_list.js
	clientList = new UserList('#client_list');
	$('#client_list_add').click(function(event){
		event.preventDefault();
		clientList.add($('#client_id_select').val(), $('#client_id_select :selected').text())
	});
	fillClients();
	clientList.render();

	// set up commenter list, requires user_list.js
	commenterList = new UserList('#commenter_list', 'Commenter');
	$('#commenter_list_add').click(function(event){
		event.preventDefault();
		commenterList.add($('#commenter_id_select').val(), $('#commenter_id_select :selected').text())
	});
	fillCommenters();
	commenterList.render();
	
	// Allow select to be searchable
	$('#client_id_select').AddIncSearch({
        maxListSize   : 10000,
        maxMultiMatch : 10000
    });
	$('#tag_id_select').AddIncSearch({
        maxListSize   : 10000,
        maxMultiMatch : 10000
    });
	$('#commenter_id_select').AddIncSearch({
        maxListSize   : 10000,
        maxMultiMatch : 10000
    });
});