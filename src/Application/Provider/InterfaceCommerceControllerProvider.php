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
		->get('/categorie/{category_name}', 'Application\Controller\InterfaceCommerceController::categorieAction')
		->assert('category_name','[^/]+')
		->value('category_name','skate')
		->bind('categorie');



		$controllers
		->get('/categorie/{category_name}/page{page}', 'Application\Controller\InterfaceCommerceController::categoriePageAction')
		->assert('category_name','[^/]+')
		->value('category_name','skate')
		->assert('page','[0-9]+')	
		->value('page','1')		
		->bind('categorie_page');





		$controllers
		->get('/{category_name}/{slugproduct}_{ID_product}.html','Application\Controller\InterfaceCommerceController::articleAction')
		->assert('category_name','[^/]+')
		->assert('ID_product','[0-9]+')

		->bind('article');



		$controllers
		->post('/addItem', 'Application\Controller\InterfaceCommerceController::addItemAction')
		->bind('addItem');

		return $controllers;
	}




}
?>