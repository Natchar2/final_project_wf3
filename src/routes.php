<?php
	# -- PROVIDER
	$app->mount('/', new Application\Provider\InterfaceCommerceControllerProvider());
	$app->mount('/admin', new Application\Provider\AdminCommerceControllerProvider());
	$app->mount('/forum', new Application\Provider\InterfaceForumControllerProvider());
	#----------------------------------------------------------------------
?>