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

	ID_product = $(this).attr('ID-product').toString();

	$.ajax({
		url: 'addItem',
		type: 'post',
		dataType: 'json',
		timeout: 4000,
		data: {
			id: ID_product
		},
		success: function(data){
			$.notify('Produit Ajouté', 'success');
			$('.total_price').text(data.total_price);
			$('.total_product').text(data.total_product);
			$('.total_by_id').attr('ID-product', function(id){
				$(this).text(data.total_product_by_id[$(this).attr('ID-product')]);
			});
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

	ID_product = $(this).attr('ID-product').toString();

	$this = $(this);

	$.ajax({
		url: 'removeOneItem',
		type: 'post',
		dataType: 'json',
		timeout: 4000,
		data: {
			id: ID_product
		},
		success: function(data){

			if(data.total_product_by_id[$this.attr('ID-product')] >= 0) $.notify('Produit Supprimé', 'success');

			$('.total_price').text(data.total_price);
			$('.total_product').text(data.total_product);
			$('.total_by_id').attr('ID-product', function(id){
				if(data.total_product_by_id[$(this).attr('ID-product')])
				{
					$(this).text(data.total_product_by_id[$(this).attr('ID-product')]);
				}
				else
				{
					$(this).text("0");
				}
			});
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

	ID_product = $(this).attr('ID-product').toString();

	$this = $(this);

	$.ajax({
		url: 'removeAllItem',
		type: 'post',
		dataType: 'json',
		timeout: 4000,
		data: {
			id: ID_product
		},
		success: function(data){

			if(data.total_product_by_id[$($this).attr('ID-product')] >= 0) $.notify('Tous les produits ont été supprimé', 'success');

			if(data.total_price == null)
			{
				$('.total_price').text("0");
			}
			else
			{
				$('.total_price').text(data.total_price);
			}
			$('.total_product').text(data.total_product);
			$('.total_by_id').attr('ID-product', function(id){
				if(data.total_product_by_id[$(this).attr('ID-product')])
				{
					$(this).text(data.total_product_by_id[$(this).attr('ID-product')]);
				}
				else
				{
					$(this).text("0");
				}
			});
		},
		error: function(){
			$.notify('Une Erreur c\'est produite', 'error');
		},
	});
});
// ----------------------------------------------------------------------
