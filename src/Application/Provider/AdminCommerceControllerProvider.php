<?php
	namespace Application\Provider;
	
	use Silex\Api\ControllerProviderInterface;
	use Silex\Application;
	
	class AdminCommerceControllerProvider implements ControllerProviderInterface
	{
	    public function connect(Application $app)
	    {
	    	$controllers = $app['controllers_factory'];

	    	$controllers
	    		->get('/',  function() use($app){
	    			return $app->redirect('accueil');
	    		});

	    	$controllers
	    		->get('/accueil', 'Application\Controller\AdminController::accueilAdminAction')
	    		->bind('accueilAdmin');

	    	$controllers
				->get('/topic/liste', 'Application\Controller\AdminCommerceController::listTopics')
				->bind('list_topics_admin');

			$controllers
				->post('/topic/liste', 'Application\Controller\AdminCommerceController::deleteTopic')
				->bind('deleteTopic_admin');

	    	return $controllers;
	    }
	}
?>