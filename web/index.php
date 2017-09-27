<?php

	# -- REQUIRE
	require_once 'constante.php';
	require_once VENDOR_ROOT . 'autoload.php';

	# -- INITIALIZE
	$app = new \Silex\Application();

	require SRC_ROOT . 'app.php';

	$app->run();
?>