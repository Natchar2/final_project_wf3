<?php 

namespace Application\Provider;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class GlobalControllerProvider implements ControllerProviderInterface
	{

		 public function connect(Application $app)
	    {
	    	$controllers = $app['controllers_factory'];

	    	$controllers
	    		->get('/',  function() use($app){
	    			return $app->redirect('accueil');
	    		});

	    	$controllers
    		->get('/contact', 'Application\Controller\GlobalController::contactAction')
			->bind('contact_page');

			$controllers
    		->post('/contact', 'Application\Controller\GlobalController::contactPostAction')
			->bind('contactPost');

	    	return $controllers;
	    }
	}


 ?>