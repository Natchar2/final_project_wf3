<?php

	# -- REQUIRE
	require_once dirname(__DIR__) . '/vendor/autoload.php';

	# -- USE

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response; 
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
	use Symfony\Component\HttpKernel\HttpKernelInterface;
	use Application\Provider\InterfaceControllerProvider;
	#----------------------------------------------------------------------

	# -- INITIALIZE
	$app = new \Silex\Application();

	$app['debug'] = true;

	$app['controllers']
		->value('id', '1')
		->assert('id', '\d+');
	#----------------------------------------------------------------------

	# -- TWIG
	$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.path' => array(
			dirname(__DIR__) . '/ressources/views',
			dirname(__DIR__) . '/ressources/layout',
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

	# -- DOCTRINE DBAL
	$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
		'db.options' => array(
			'driver' => 'pdo_mysql',
            'dbname' => 'base_de_donnee',
            'host' => 'localhost',
            'user' => 'root',
            'password' => null,
		),
	));
	#----------------------------------------------------------------------

	# -- IDIORM
	$app->register(new \Idiorm\Silex\Provider\IdiormServiceProvider(), array(
        'idiorm.db.options' => array(
            'connection_string' => 'mysql:host=localhost;dbname=base_de_donnee',
            'username' => 'root',
            'password' => '',
			'id_column_overrides' => array(
				'articles' =>  'id',
			),
        ),
	));
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

	# -- ERROR
	$app->error(function(\Exception $e, Request $request, $code) use($app){
		if($code == 404){
			return $app['twig']->render('404.html.twig');
		}
	});
	#----------------------------------------------------------------------

	# -- PROVIDER
	$app->mount('/', new InterfaceControllerProvider());
	#----------------------------------------------------------------------

	$app->run();
?>