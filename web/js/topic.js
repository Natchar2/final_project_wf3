$('.plus_post').click(function(e){
	e.preventDefault();
	number_post = $('.old_post').length;

	$.ajax({
		url: '../topic_add_post',
		type: 'post',
		data: {
			number_post: number_post,
			ID_topic: $('.ID_topic').val(),
		},
		dataType: 'json',
		timeout: 4000,
		success: function(data){

			for(post in data){
				timestamp = data[post].post_date;
				date = new Date(timestamp * 1000);
				
					jour = date.getDate();
					mois = date.getMonth()+1;

					if(mois < 10 )
					{
						mois= "0"+mois;
					}

				  	annee = date.getFullYear();	  
				   	chaine = jour + "/"	+ mois + "/" + annee;  
							
				$('.block_post').append('<div class="col-xs-12 card-text old_post"><p class= "pull-right"> par <a href="{{ url("profilUser", {"ID_user" : '+ data[post].ID_user +'}) }}">'+ data[post].pseudo +'</a> le : ' + chaine +'</p><br>' + data[post].content + '<hr></div>');
			}
		},
		error: function(data){
			console.log('Une erreur c\'est produite');
		}
	})
});




