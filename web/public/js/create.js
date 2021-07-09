function doSubmit(){
	var teamName = $('#teamName').val();
	var pokemon = $("#pokemonList").val();
	var update_id = $("#teamUpdate").val();
	
	if(teamName == "") {
		alert("Team name mandatory");
		return false;
	}
	if(update_id == undefined && pokemon == "") {
		alert("Please add at least one pokemon to your team");
		return false
	}
	
	var pokemon_list = "{\"pokemon_list\":["+pokemon+"]}";
	
	if(update_id == undefined){
		jQuery.get( "/team/new", {name: teamName, pokemon_list: pokemon_list}, function( data ) {
		  alert("New team \""+teamName+"\" created succesfully!");
		});
	} else {
		jQuery.get( "/team/update", {name: teamName, pokemon_list: pokemon_list, id: update_id}, function( data ) {
		  alert("New team \""+teamName+"\" updated succesfully!");
		});
	}
}

function getNewPokemon(){
	jQuery.get( "/pokemon/new", function( data ) {
		var card = '<div class="col">'
					+'<div class="card h-100" style="width: 17rem;">'
					  +'<img src="'+data.image+'" class="card-img-top" alt="'+data.name+'">'
					  +'<div class="card-body">'
					    +'<h5 class="card-title"><b>'+data.name+'</b></h5>'
					    +'<p class="card-text"><b>Base Experience</b> : '+data.baseExp+'</p>'		
						+'<p class="card-text"><b>Abilities</b> : <ul>';
		for(i=0;i<data.abilities.length;i++){
			card += '<li>'+data.abilities[i]+'</li>';
		}
			card +='</ul></p>'
					  +'</div>'
					  +'<div class="card-footer">'
			for(i=0;i<data.types.length;i++){
				card += '<img src="/images/types/'+data.types[i]+'.png" style="float:right; width:40px; height:40px;"/>';			
			}     			
			card +='</div>'
					+'</div>'
				   +'</div>'
		$("#pokemonCards").append(card);
		
		if($("#pokemonList").val() == ""){
			$("#pokemonList").val(JSON.stringify(data));
		} else {
			$("#pokemonList").val($("#pokemonList").val()+","+JSON.stringify(data));
		}
	  	console.log(data);
	});
}