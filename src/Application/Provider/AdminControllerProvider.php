<?php
	namespace Application\Provider;
	
	use Silex\Api\ControllerProviderInterface;
	use Silex\Application;
	
	class AdminControllerProvider implements ControllerProviderInterface
	{
	    public function connect(Application $app)
	    {
	    	$controllers = $app['controllers_factory'];

	    	$controllers
	    		->get('/',  function() use($app){
	    			return $app->redirect('accueilAdmin');
	    		});

	    	$controllers
	    		->get('/accueil', 'Application\Controller\AdminController::accueilAdminAction')
	    		->bind('accueilAdmin');



//--------------------------- Produits --------------------------------------

				$controllers
				->get('/produit/liste', 'Application\Controller\AdminController::listProductsAdminAction')
				->bind('listProductsAdmin');



//--------------------------- Agenda --------------------------------------

				$controllers
				->get('/event/liste', 'Application\Controller\AdminController::listEventsAdminAction')
				->bind('listEventsAdmin');





//--------------------------- Topics --------------------------------------


	    	$controllers
				->get('/topic/liste', 'Application\Controller\AdminController::listTopicsAdminAction')
				->bind('listTopicsAdmin');


//--------------------------- Users --------------------------------------


	    	$controllers
				->get('/user/liste', 'Application\Controller\AdminController::listUsersAdminAction')
				->bind('listUsersAdmin');






	    	return $controllers;
	    }
	}
?>