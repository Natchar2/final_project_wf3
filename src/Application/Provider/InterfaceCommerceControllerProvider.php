<?php
namespace Application\Provider;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class InterfaceCommerceControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers
		->get('/',  function() use($app){
			return $app->redirect('accueil');
		});

		$controllers
			->get('/accueil', 'Application\Controller\InterfaceCommerceController::accueilAction')
			->bind('accueil');

		$controllers
			->get('/panier', 'Application\Controller\InterfaceCommerceController::panierAction')
			->bind('panier');

		$controllers
			->get('/categorie', 'Application\Controller\InterfaceCommerceController::categorieAction')
			->bind('categorie');


    	$controllers
    		->post('/addItem', 'Application\Controller\InterfaceCommerceController::addItemAction')
    		->bind('addItem');

    	$controllers
    		->post('/removeOneItem', 'Application\Controller\InterfaceCommerceController::removeOneItemAction')
    		->bind('removeOneItem');

    	$controllers
    		->post('/removeAllItem', 'Application\Controller\InterfaceCommerceController::removeAllItemAction')
    		->bind('removeAllItem');

		return $controllers;
	}
}
?>