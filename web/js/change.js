var change_type = function(feature_type, ID_user)
{
	$.ajax({
		url: 'changeTypeUser',
		type: 'POST',
		dataType: 'html',
		timeout: 4000,
		data: {
			feature_type: feature_type.toString(),
			ID_user: ID_user,
		},
		success: function(data){
			$('.result_change').html(data).addClass('alert alert-success');
		},
		error: function(data){
			console.log(data.status);
		}
	});
}

var remove_user = function(ID_user)
{
	$.ajax({
		url: 'removeUser',
		type: 'POST',
		dataType: 'html',
		timeout: 4000,
		data: {
			ID_user: ID_user,
		},
		success: function(data){
			$('.result_change').html(data).addClass('alert alert-success');
		},
		error: function(data){
			console.log(data.status);
		}
	});
}

var accept_modify = function(pseudo, ID_user, this_input)
{
	if(!confirm("Êtes vous sur de vouloir modifier l'email ?"))
	{
		this_input.blur();
	}
	else
	{
		this_input.focus();
	}

}


active = false;
$('.modify_mail').keyup(function(e){
	if(e.keyCode == 13 && active)
	{
		$.ajax({
			url: 'changeMailUser',
			type: 'POST',
			dataType: 'html',
			timeout: 4000,
			data: {
				ID_user: $(this).attr('ID-user').toString(),
				pseudo: $(this).attr('Pseudo-user').toString(),
				mail: $(this).val(),
			},
			success: function(data){
				text = data.split('Erreur');
				if(text.length == 1)
				{
					$('.result_change').html(data).addClass('alert-success').removeClass('alert-danger');
				}
				else
				{
					$('.result_change').html(data).addClass('alert-danger').removeClass('alert-success');
				}

				$('.modify_mail').blur();
			},
			error: function(data){
				$('.modify_mail').focus();
			}
		});	

		active = false;
	}
	else
	{
		active = true;
	}
})