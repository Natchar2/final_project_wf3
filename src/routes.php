<?php
	# -- PROVIDER
$app->mount('/', new Application\Provider\InterfaceCommerceControllerProvider());
$app->mount('/admin', new Application\Provider\AdminCommerceControllerProvider());
$app->mount('/agenda', new Application\Provider\InterfaceAgendaControllerProvider());

	#----------------------------------------------------------------------
?>