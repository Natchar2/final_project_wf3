<?php
	# -- CONFIG APP
	$app['debug'] = true;

	$app['controllers']
		->value('id', '1')
		->assert('id', '\d+');
	#----------------------------------------------------------------------

	# -- TWIG
	$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.path' => array(
			RESSOURCES_ROOT . 'views',
			RESSOURCES_ROOT . 'layout',
		),
	));

	$app->register(new Silex\Provider\HttpFragmentServiceProvider());

	$app->extend('twig', function($twig, $app){
		$twig->addExtension(new \Application\Extension\ApplicationTwigExtension());
		return $twig;
	});
	#----------------------------------------------------------------------

	# -- ASSET
	$app->register(new Silex\Provider\AssetServiceProvider());
	#----------------------------------------------------------------------

	# -- SWIFT MAILER
	$app->register(new Silex\Provider\SwiftmailerServiceProvider(), array(
		'swiftmailer.use_spool' => false,
		'swiftmailer.options' => array(
			'host' => 'localhost',
			'port' => 25,
			'username' => 'root',
			'password' => '',
		),
	));
	#----------------------------------------------------------------------

	# -- CSRF
	$app->register(new Silex\Provider\CsrfServiceProvider());
	#----------------------------------------------------------------------

	# -- FORM
	$app->register(new Silex\Provider\FormServiceProvider());
	$app->register(new Silex\Provider\LocaleServiceProvider());
	$app->register(new Silex\Provider\ValidatorServiceProvider());
	$app->register(new Silex\Provider\TranslationServiceProvider(), array(
	    'translator.domains' => array(),
	));
	#----------------------------------------------------------------------

	# -- ERROR
	// $app->error(function(\Exception $e, Request $request, $code) use($app){
	// 	if($code == 404){
	// 		return $app['twig']->render('404.html.twig');
	// 	}
	// });
	#----------------------------------------------------------------------

	require_once RESSOURCES_ROOT . 'config/database.config.php';

	require_once RESSOURCES_ROOT . 'config/firewall.config.php';

	require_once 'routes.php';

	return $app;
?>