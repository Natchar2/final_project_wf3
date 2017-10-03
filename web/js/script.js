window.jQuery || document.write('<script src="{{asset("assets/js/vendor/jquery.min.js")}}"><\/script>');

$(document).ready(function(){
	$('#myTable').DataTable({
		"language":{
			"url": "https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json"
		}
	});
});