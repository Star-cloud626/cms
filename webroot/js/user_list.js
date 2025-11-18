
function UserList(id, model){

	// the id of the element where the list will be
	this.id = id.replace('#','');

	if(model){
		this.model = model;
	}
	else{
		this.model = 'Client';
	}

	this.items = new Array();

	this.set = function (list){
		this.items = list;
	};

	this.add = function (id, name){
		var exists = false;
		for(var i=0; i<this.items.length; ++i){
			if(this.items[i]['id'] == id){
				exists = true;
			}
		}
		if(name.length>0 && !exists){
			this.items.push({
				"id":id,
				"name": name
			});
		}
		this.render();
		return false;
	};

	this.remove = function (obj, id){
		// array splice and unset doesnt work, remake array
		newList = new Array();
		for (var i = 0; i < obj.items.length; i++) {
			if(obj.items[i]['id'] != id){
				newList.push(obj.items[i]);
			}
		}
		obj.items = newList;
		obj.render();
	};

	this.render = function (){
		$('#' + this.id).html('');
		if(this.items.length<=0){
			$('#' + this.id).html( 'none.');
		}
		else {
			var html = '';
			var i = 0;
			for (i = 0; i < this.items.length; i++) {
				html += "<div class='usernode'>";
				html += "<input type='hidden' name='data["+this.model+"][id][]' value='"+this.items[i]['id']+"' />";
				html += "<p>" + this.items[i]['name'] + "</p>";
				html += "<a href='#'  id='"+this.id + this.items[i]['id'] +"' >remove</a>"
				html += "</div>";
			}
			$('#' + this.id).html(html);

			for (i = 0; i < this.items.length; i++) {
				var id = this.items[i]['id'];
				var func = this.remove;
				var obj = this;
				$('#'+this.id + this.items[i]['id']).bind('click', {id:id}, function(e){
					e.preventDefault();
					func(obj, e.data.id);
				});
			}
		}
	};

};