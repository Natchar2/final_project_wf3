$('.add_product').click(function(e){
	e.preventDefault();

	ID_product = $(this).attr('ID-product');

	$.ajax({
		url: 'addItem',
		type: 'post',
		dataType: 'html',
		timeout: 4000,
		data: {
			id: ID_product
		},
		success: function(data){
			$.notify.defaults({
				arrowShow: true,
				arrowSize: 50,
				autoHideDelay: 3000,
			});
			$.notify('Produit ' + data + ' Ajouté', 'success');
		},
		error: function(){
			console.log("error");
		},
	});
	
});