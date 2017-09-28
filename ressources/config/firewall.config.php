<?php
	# -- SECURITY
	// $app->register(new Silex\Provider\SessionServiceProvider());

	// $app->register(new Silex\Provider\SecurityServiceProvider(), array(
 //    	'security.firewalls' => array(
 //    		'main' => array(
 //    			'pattern' => '^/',
 //    			'http' => true,
 //    			'anonymous' => true,
 //    			'form' => array(
 //    				'login_path' => '/connexion',
 //    				'check_path' => '/admin/login_check'
 //    			),
 //                'logout' => array(
 //                    'logout_path' => "/deconnexion",
 //                ),
 //                'users' => function() use($app){
 //                    return new PROVIDER_A_RENSEIGNER_POUR_MAIN_FIREWALL($app['idiorm.db']);
 //                }
 //    		),
 //    	),

 //    	'security.access_rules' => array(
 //    		array('^/admin', 'ROLE_ADMIN', 'http'),
 //    	),

 //    	// 'security.role_hierarchy' => array(),
	// ));

	$app['security.default_encoder'] = function ($app) {
        return $app['security.encoder.bcrypt'];
    };
    
	$app['security.default_encoder'] = function() use($app){
		return $app['security.encoder.digest'];
	};
    #----------------------------------------------------------------------
?>