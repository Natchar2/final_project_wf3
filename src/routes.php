<?php
	# -- PROVIDER

	$app->mount('/', new Application\Provider\InterfaceCommerceControllerProvider());
	$app->mount('/admin', new Application\Provider\AdminControllerProvider());
	$app->mount('/forum', new Application\Provider\InterfaceForumControllerProvider());
	$app->mount('/agenda', new Application\Provider\InterfaceAgendaControllerProvider());

	#----------------------------------------------------------------------
?>