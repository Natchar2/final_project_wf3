<?php


use Application\Extension\ApplicationTwigExtension;

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
	$twig->addExtension(new ApplicationTwigExtension());
	return $twig;
});

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

if($app['session']->get('total_price_by_id') == null)
{
	$app['session']->set('total_price_by_id', array());
}

if($app['session']->get('total_product_by_id') == null)
{
	$app['session']->set('total_product_by_id', array());
}

if($app['session']->get('token') == null)
{

   $string = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

	$token = "";

	for ($i = 0; $i < 20; $i++)

	{
		$token .= $string[mt_rand(0,mb_strlen($string) - 1)];
	}

	$app['session']->set('token', $token);
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


require_once RESSOURCES_ROOT . 'config/database.config.php';

require_once RESSOURCES_ROOT . 'config/firewall.config.php';

require_once 'routes.php';

return $app;
?>