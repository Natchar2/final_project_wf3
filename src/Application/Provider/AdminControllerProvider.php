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
    			return $app->redirect('accueil');
    		});

    		$controllers
    		->get('/accueil', 'Application\Controller\AdminController::accueilAdminAction')
    		->bind('accueilAdmin');



//--------------------------- Produits --------------------------------------

			$controllers
			->get('/produit/liste', 'Application\Controller\AdminController::listProductsAdminAction')
			->bind('listProductsAdmin');

			$controllers
			->post('/produit/liste', 'Application\Controller\AdminController::deleteProductAdminAction')
			->assert('ID_product', '\d+')
			->bind('deleteProductAdmin');

			$controllers
			->get('/produit/ajouter/{ID_product}/{token}', 'Application\Controller\AdminController::addProductAdminAction')
			->assert('ID_product', '\d+')
			->value('ID_product', '0')
			->assert('token', '\w+')
			->value('token', '0')
			->bind('addProductAdmin');

			$controllers
			->post('/produit/ajouter', 'Application\Controller\AdminController::addProductPostAdminAction')
			->bind('addProductPostAdmin');







//--------------------------- Agenda --------------------------------------

			$controllers
			->get('/event/liste', 'Application\Controller\AdminController::listEventsAdminAction')
			->bind('listEventsAdmin');


			$controllers
			->post('/event/liste', 'Application\Controller\AdminController::deleteEventAdminAction')
			->bind('deleteEventAdmin');



			$controllers
			->get('/event/ajouter/{ID_event}/{token}','Application\Controller\AdminController::addEventAdminAction')
			->assert('ID_event', '\d+')
			->value('ID_event', '0')
			->assert('token', '\w+')
			->value('token', '0')
			->bind('addEventAdmin');

			$controllers
			->post('/event/ajouter','Application\Controller\AdminController::addEventPostAdminAction')
			->bind('addEventPostAdmin');



//--------------------------- Topics --------------------------------------


	    	$controllers
			->get('/topic/liste', 'Application\Controller\AdminController::listTopicsAdminAction')
			->bind('listTopicsAdmin');

			$controllers
			->post('/topic/liste', 'Application\Controller\AdminController::deleteTopicAdminAction')
			->bind('deleteTopicAdmin');

			$controllers
			->get('/topic/ajouter', 'Application\Controller\AdminController::addTopicAdminAction')
			->bind('addTopicAdmin');

			$controllers
			->post('/topic/ajouter', 'Application\Controller\AdminController::addTopicPostAdminAction')
			->bind('addTopicPostAdmin');









//--------------------------- Users --------------------------------------


	    	$controllers
			->get('/user/liste', 'Application\Controller\AdminController::listUsersAdminAction')
			->bind('listUsersAdmin');



	    	return $controllers;
	    }
	}
?>