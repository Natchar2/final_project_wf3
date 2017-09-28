// -- PARAMETRE PAR DEFAUT DE NOTIFY
$.notify.defaults({
	autoHide: true,
	arrowShow: true,
	arrowSize: 50,
	autoHideDelay: 3000,
});
// ----------------------------------------------------------------------

// -- AJOUTER UN ITEM AU PANIER
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
			$.notify('Produit Ajouté', 'success');
			$('.get_total_price').text(data + ' EUR');
		},
		error: function(){
			$.notify('Une Erreur c\'est produite', 'error');
		},
	});
});
// ----------------------------------------------------------------------

// -- SUPPRIMER UN ITEM DU TABLEAU PAR ID
$('.remove_one_product').click(function(e){
	e.preventDefault();

	ID_product = $(this).attr('ID-product');

	$.ajax({
		url: 'removeOneItem',
		type: 'post',
		dataType: 'html',
		timeout: 4000,
		data: {
			id: ID_product
		},
		success: function(data){
			$.notify('Produit Supprimé', 'success');
			if(data == '')
			{
				data = '0';
			}
			$('.get_total_price').text(data + ' EUR');
		},
		error: function(){
			$.notify('Une Erreur c\'est produite', 'error');
		},
	});
});
// ----------------------------------------------------------------------

// -- SUPPRIMER TOUS LES ITEMS DU TABLEAU PAR ID
$('.remove_all_product').click(function(e){
	e.preventDefault();

	ID_product = $(this).attr('ID-product');

	$.ajax({
		url: 'removeAllItem',
		type: 'post',
		dataType: 'html',
		timeout: 4000,
		data: {
			id: ID_product
		},
		success: function(data){
			$.notify('Tous les produits ont été supprimé', 'success');
			if(data == '')
			{
				data = '0';
			}
			$('.get_total_price').text(data + ' EUR');
		},
		error: function(){
			$.notify('Une Erreur c\'est produite', 'error');
		},
	});
});
// ----------------------------------------------------------------------
