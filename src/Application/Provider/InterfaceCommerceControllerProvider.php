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
		->value('category_name','all')
		->bind('shop');


		$controllers
		->get('/categorie/{category_name}/page{page}', 'Application\Controller\InterfaceCommerceController::categoriePageAction')
		->assert('category_name','[^/]+')
		->value('category_name','all')
		->assert('page','[0-9]+')	
		->value('page','1')		
		->bind('shop_page');

		$controllers
		->get('/{category_name}/{slugproduct}_{ID_product}.html','Application\Controller\InterfaceCommerceController::articleAction')
		->assert('category_name','[^/]+')
		->assert('ID_product','[0-9]+')
		->bind('article');


		$controllers
		->post('/addItem', 'Application\Controller\InterfaceCommerceController::addItemAction')
		->bind('addItem');

		$controllers
		->get('/faq', 'Application\Controller\InterfaceCommerceController::faqAction')
		->bind('faq');

		$controllers
		->get('/about', 'Application\Controller\InterfaceCommerceController::aboutAction')
		->bind('about');


		$controllers
		->post('/removeOneItem', 'Application\Controller\InterfaceCommerceController::removeOneItemAction')
		->bind('removeOneItem');

		$controllers
		->post('/removeAllItem', 'Application\Controller\InterfaceCommerceController::removeAllItemAction')
		->bind('removeAllItem');


		$controllers

		->get('/forumAjoutPost', 'Application\Controller\InterfaceCommerceController::forumAjoutPostAction')
		->bind('forumAjoutPost');

		$controllers
		->get('/forumIndex', 'Application\Controller\InterfaceCommerceController::forumIndexAction')
		->bind('forumIndex');


		$controllers
		->get('/agendaIndex', 'Application\Controller\InterfaceCommerceController::agendaIndexAction')
		->bind('agenda');




		$controllers
		->get('/forumPostDetail', 'Application\Controller\InterfaceCommerceController::forumPostDetailAction')
		->bind('forumPostDetail');


		$controllers
		->post('/removeOneItem', 'Application\Controller\InterfaceCommerceController::removeOneItemAction')
		->bind('removeOneItem');

		$controllers
		->post('/removeAllItem', 'Application\Controller\InterfaceCommerceController::removeAllItemAction')
		->bind('removeAllItem');

		$controllers
		->get('/produit/ajouter/{ID_product}', 'Application\Controller\InterfaceCommerceController::newAdAction')
		->assert('ID_product', '\d+')
		->value('ID_product', '0')
		->bind('view_newAd');

		$controllers
		->post('/produit/ajouter/{ID_product}', 'Application\Controller\InterfaceCommerceController::newAdPostAction')
		->assert('ID_product', '\d+')
		->value('ID_product', '0')
		->bind('newAd');

		$controllers
		->get('/contact', 'Application\Controller\GlobalController::contactAction')
		->bind('contact_page');

		$controllers
		->post('/contact', 'Application\Controller\GlobalController::contactPostAction')
		->bind('contactPost');

		$controllers
		->get('/conditions_generales', 'Application\Controller\GlobalController::conditionsAction')
		->bind('conditions_page');

		$controllers
		->get('/produit/liste', 'Application\Controller\InterfaceCommerceController::listProducts')
		->bind('listProducts');

		$controllers
		->get('/produit/{ID_product}/{token}', 'Application\Controller\InterfaceCommerceController::deleteProduct')
		->assert('ID_product', '\d+')
		->bind('deleteProduct');

		$controllers
		->get('/shoppingCard', 'Application\Controller\InterfaceCommerceController::shoppingCardAction')
		->bind('shoppingCard');

		$controllers
		->get('/connexion', 'Application\Controller\InterfaceCommerceController::connexionAction')
		->bind('connexion');


		$controllers
		->get('/inscription', 'Application\Controller\InterfaceCommerceController::inscriptionAction')
		->bind('inscription');



		return $controllers;
	}
}
?>