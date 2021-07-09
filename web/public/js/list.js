function deleteTeam(deletePath){
	jQuery.get( deletePath, function( data ) {
		 alert("Team deleted succesfully!");
	});
}

$( document ).ready(function() {
    $("input:checkbox").on('click', function() {
	  	var checked = [];
	  	$('input:checkbox:checked').each(function( index ) {
		  	var type = $( this ).attr('name');
		  	checked.push(type);
		});
		$('input[name="teams[]"]').each(function( index ) {
		  	var team_id = $( this ).val();
			var show = true;
			if (checked.length !== 0) {
				var team_types = [];
			  	$('input[name="type['+team_id+'][]"]').each(function( index ) {
					team_types.push($( this ).val());
				});
				
				for(i=0;i<checked.length;i++){
					if(jQuery.inArray(checked[i], team_types) === -1){
						show = false;
						break;
					}
				}				
			}
			var div = $('div[class="card"][id="'+team_id+'"]')
			if(show){
				div.show();
			} else {
				div.hide();
			}
		});
	});
	
	$('#confirm-delete').on('show.bs.modal', function(e) {
    	$(this).find('.btn-ok').attr('onclick', "deleteTeam(\""+$(e.relatedTarget).data('href')+"\")");
	});
});