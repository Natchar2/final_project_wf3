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
    		->get('/produit/ajouter', 'Application\Controller\InterfaceCommerceController::newAdAction')
			->bind('view_newAd');

    	$controllers
    		->post('/produit/ajouter', 'Application\Controller\InterfaceCommerceController::newAdPostAction')
			->bind('newAd');

		$controllers
    		->get('/contact', 'Application\Controller\GlobalController::contactAction')
			->bind('contact_page');

		$controllers
    		->post('/contact', 'Application\Controller\GlobalController::contactPostAction')
			->bind('contactPost');

		$controllers
			->get('/faq', 'Application\Controller\GlobalController::FAQAction')
			->bind('FAQ_page');

		$controllers
			->get('/conditions_generales', 'Application\Controller\GlobalController::conditionsAction')
			->bind('conditions_page');

		return $controllers;
	}
}
?>