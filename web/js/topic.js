$('.plus_post').click(function(e){
	e.preventDefault();
	number_post = $('.old_post').length;

	$.ajax({
		url: '../topic_add_post',
		type: 'post',
		data: {
			number_post: number_post,
		},
		dataType: 'json',
		timeout: 4000,
		success: function(data){
			for(post in data){
				$('.block_post').append('<p class="card-text old_post">' + data[post].content + '</p>');
			}
		},
		error: function(data){
			console.log('Une erreur c\'est produite');
		}
	})
});




