<?php
// -- SECURITY

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
	'security.firewalls' => array(
		'main' => array(
			'pattern' => '^/',
			'http' => true,
			'anonymous' => true,
			'form' => array(
				'login_path' => '/connexion',
				'check_path' => '/admin/login_check'
			),
                'logout' => array(
                    'logout_path' => "/deconnexion",
                ),
                'users' => function() use($app){
                	return new Application\Provider\UsersControllerProvider($app['idiorm.db']);
                }
            ),
	),

	'security.access_rules' => array(
		array('^/admin', 'ROLE_ADMIN', 'http'),

		array('^/commerce/paiement', 'ROLE_USER', 'http'),
		array('^/commerce/produit/^', 'ROLE_USER', 'http'),
		array('^/commerce/profil/^', 'ROLE_USER', 'http'),
		array('^/commerce/suivi', 'ROLE_USER', 'http'),

		array('^/agenda/event/^', 'ROLE_USER', 'http'),

		array('^/forum/topic_add_post', 'ROLE_USER', 'http'),
		array('^/forum/topic/ajouter', 'ROLE_USER', 'http'),
		array('^/forum/topic/liste', 'ROLE_USER', 'http'),
	),

	'security.role_hierarchy' => array(
		'ROLE_ADMIN' => array(
			'ROLE_USER',
		),
	),
));

$app['security.default_encoder'] = function ($app) {
	return $app['security.encoder.bcrypt'];
};

$app['security.default_encoder'] = function() use($app){
	return $app['security.encoder.digest'];
};
#----------------------------------------------------------------------
?>