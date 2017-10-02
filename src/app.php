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

	# -- SESSION
	$app->register(new Silex\Provider\SessionServiceProvider());

	if($app['session']->get('panier') == null)
	{
		$app['session']->set('panier', 0);
	}

	if($app['session']->get('total_price') == null)
	{
		$app['session']->set('total_price', 0);
	}

	if($app['session']->get('total_product') == null)
	{
		$app['session']->set('total_product', 0);
	}

	if($app['session']->get('total_product_by_id') == null)
	{
		$app['session']->set('total_product_by_id', array());
	}

	if($app['session']->get('token') == null)
	{
		$app['session']->set('token', null);
	}
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

	# -- PAYPAL
	$apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            'AbB8aZ2BYsMvDfrV9MFhOxkhnwgaUawUSka2QPVwNfOrU5CU7h6ubG9BRiSx3UQ8233Ip-XgxYGhvnkg',     // ClientID
            'EHdrwuNnzTp98c6UpIaVpAYD00wBKiUNXNQXmWHUXDrKQJWSIi-Jx4Kz13h7gWJsjmuLWrKrKm7yOfqV'      // ClientSecret
        )
	);
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