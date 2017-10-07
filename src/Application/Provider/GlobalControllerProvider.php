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
	
    	

	    	return $controllers;
	    }
	}


 ?>